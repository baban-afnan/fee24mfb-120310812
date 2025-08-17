<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EnrollmentController extends Controller
{
    /**
     * Show enrollment list with search and status
     */
    public function index(Request $request)
    {
        $query = Enrollment::query();

        // Search by ticket, BVN, or agent
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%$search%")
                  ->orWhere('bvn', 'like', "%$search%")
                  ->orWhere('agent_name', 'like', "%$search%");
            });
        }

        $data = $query->latest()->paginate(10);

        // Collect status status
        $status = [
            'total'      => Enrollment::count(),
            'successful' => Enrollment::where('validation_status', 'successful')->count(),
            'rejected'   => Enrollment::where('validation_status', 'rejected')->count(),
            'failed'     => Enrollment::where('validation_status', 'failed')->count(),
            'pending'    => Enrollment::where('validation_status', 'pending')->count(),
            'ongoing'    => Enrollment::where('validation_status', 'ongoing')->count(),
        ];

        return view('enrollments', compact('data', 'status'));
    }

    /**
     * Upload & process Excel/CSV with strict status update rules
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120', // 5MB max
        ]);

        try {
            $path = $request->file('file')->getRealPath();
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            // Counters
            $created = 0;
            $updated = 0;
            $skippedDuplicates = 0;
            $skippedEmpty = 0;
            $processedTickets = [];

            DB::transaction(function () use ($rows, &$created, &$updated, &$skippedDuplicates, &$skippedEmpty, &$processedTickets) {
                foreach ($rows as $index => $row) {
                    if ($index === 1) continue; // Skip header row

                    $ticket = isset($row['A']) ? trim($row['A']) : null;
                    
                    // Skip if ticket number is empty
                    if (empty($ticket)) {
                        $skippedEmpty++;
                        continue;
                    }

                    // Skip if ticket is duplicated in the current file
                    if (isset($processedTickets[$ticket])) {
                        $skippedDuplicates++;
                        continue;
                    }
                    $processedTickets[$ticket] = true;

                    // Prepare all fields including status
                    $updateData = [
                        'BVN'                  => $row['B'] ?? null,
                        'AGT_MGT_INST_NAME'    => $row['C'] ?? null,
                        'AGT_MGT_INST_CODE'    => $row['D'] ?? null,
                        'AGENT_NAME'           => $row['E'] ?? null,
                        'AGENT_CODE'           => $row['F'] ?? null,
                        'ENROLLER_CODE'        => $row['G'] ?? null,
                        'LATITUDE'             => $row['H'] ?? null,
                        'LONGITUDE'            => $row['I'] ?? null,
                        'FINGER_PRINT_SCANNER' => $row['J'] ?? null,
                        'BMS_IMPORT_ID'        => $row['K'] ?? null,
                        'validation_status'    => $row['L'] ?? 'pending',
                        'VALIDATION_MESSAGE'   => $row['M'] ?? null,
                        'AMOUNT'               => isset($row['N']) ? (float)$row['N'] : null,
                        'CAPTURE_DATE'         => $row['O'] ?? null,
                        'SYNC_DATE'            => $row['P'] ?? null,
                        'VALIDATION_DATE'      => $row['Q'] ?? null,
                        'AGENT_STATE'          => $row['R'] ?? null,
                    ];

                    // Check if ticket exists
                    $existing = Enrollment::where('TICKET_NUMBER', $ticket)->first();

                    if ($existing) {
                        // Update ALL fields including status
                        $existing->update($updateData);
                        $updated++;
                    } else {
                        // Create new record
                        Enrollment::create(array_merge(['TICKET_NUMBER' => $ticket], $updateData));
                        $created++;
                    }
                }
            });

            $message = sprintf(
                "Process completed: %d created, %d updated",
                $created,
                $updated
            );

            if ($skippedDuplicates > 0 || $skippedEmpty > 0) {
                $message .= sprintf(
                    " | Skipped: %d duplicate tickets, %d empty tickets",
                    $skippedDuplicates,
                    $skippedEmpty
                );
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Processing failed: ' . $e->getMessage());
        }
    }
}