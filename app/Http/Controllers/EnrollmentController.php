<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EnrollmentController extends Controller
{
    // Define allowed statuses as a constant
    const ALLOWED_STATUSES = [
        'pending',
        'ongoing',
        'failed',
        'rejected',
        'successful'
    ];

    /**
     * Display enrollment list with search and status filters
     */
    public function index(Request $request)
    {
        $query = Enrollment::query();

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('TICKET_NUMBER', 'like', "%$search%")
                  ->orWhere('BVN', 'like', "%$search%")
                  ->orWhere('AGENT_NAME', 'like', "%$search%");
            });
        }

        // Status filter if provided
        if ($request->status && in_array($request->status, self::ALLOWED_STATUSES)) {
            $query->where('validation_status', $request->status);
        }

        $data = $query->latest()->paginate(10);

        // Status counters for the dashboard
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
     * Process file upload with strict status validation
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

            // Counters for reporting
            $created = 0;
            $updated = 0;
            $skippedDuplicates = 0;
            $skippedEmpty = 0;
            $invalidStatus = 0;
            $processedTickets = [];

            DB::transaction(function () use ($rows, &$created, &$updated, &$skippedDuplicates, &$skippedEmpty, &$invalidStatus, &$processedTickets) {
                foreach ($rows as $index => $row) {
                    if ($index === 1) continue; // Skip header row

                    $ticket = isset($row['A']) ? trim($row['A']) : null;

                    // Skip empty ticket numbers
                    if (empty($ticket)) {
                        $skippedEmpty++;
                        continue;
                    }

                    // Prevent duplicates in the same file
                    if (isset($processedTickets[$ticket])) {
                        $skippedDuplicates++;
                        continue;
                    }
                    $processedTickets[$ticket] = true;

                    // Column mapping from Excel to DB fields
                    $map = [
                        'B' => 'BVN',
                        'C' => 'AGT_MGT_INST_NAME',
                        'D' => 'AGT_MGT_INST_CODE',
                        'E' => 'AGENT_NAME',
                        'F' => 'AGENT_CODE',
                        'G' => 'ENROLLER_CODE',
                        'H' => 'LATITUDE',
                        'I' => 'LONGITUDE',
                        'J' => 'FINGER_PRINT_SCANNER',
                        'K' => 'BMS_IMPORT_ID',
                        // 'L' => status handled separately
                        'M' => 'VALIDATION_MESSAGE',
                        'N' => 'AMOUNT',
                        'O' => 'CAPTURE_DATE',
                        'P' => 'SYNC_DATE',
                        'Q' => 'VALIDATION_DATE',

                    ];

                    // Find existing by TICKET_NUMBER or create new instance
                    $enrollment = Enrollment::firstOrNew(['TICKET_NUMBER' => $ticket]);
                    $isNew = !$enrollment->exists;

                    // Assign ticket number on create only; never change it on update
                    if ($isNew) {
                        $enrollment->TICKET_NUMBER = $ticket;
                    }

                    // Apply non-empty values; do not overwrite existing data with blanks
                    foreach ($map as $col => $field) {
                        $value = $row[$col] ?? null;
                        if ($field === 'AMOUNT' && $value !== null && $value !== '') {
                            $value = (float) $value;
                        }

                        if ($isNew) {
                            $enrollment->{$field} = ($value === '' ? null : $value);
                        } else {
                            if ($value !== null && $value !== '') {
                                $enrollment->{$field} = $value;
                            }
                        }
                    }

                    // Validate and normalize status. Only set if provided; default to pending on create
                    $statusCell = $row['L'] ?? null;
                    if ($statusCell !== null && trim($statusCell) !== '') {
                        $status = strtolower(trim($statusCell));
                        if (!in_array($status, self::ALLOWED_STATUSES)) {
                            $status = 'pending';
                            $invalidStatus++;
                        }
                        $enrollment->validation_status = $status;
                    } else if ($isNew && empty($enrollment->validation_status)) {
                        $enrollment->validation_status = 'pending';
                    }

                    if ($isNew) {
                        // If model uses manual IDs, generate next id
                        if ($enrollment->incrementing === false) {
                            $enrollment->id = Enrollment::nextId();
                        }
                        $enrollment->save();
                        $created++;
                    } else {
                        $enrollment->save();
                        $updated++;
                    }
                }
            });

            // Prepare response message
            $message = "File processed: {$created} created, {$updated} updated";

            $warnings = [];
            if ($skippedDuplicates > 0) $warnings[] = "{$skippedDuplicates} duplicate tickets skipped";
            if ($skippedEmpty > 0) $warnings[] = "{$skippedEmpty} empty tickets skipped";
            if ($invalidStatus > 0) $warnings[] = "{$invalidStatus} invalid statuses corrected";

            if (!empty($warnings)) {
                $message .= " (" . implode(', ', $warnings) . ")";
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Update enrollment status and validation message
     */
    public function updateStatus(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'validation_status' => 'required|in:pending,ongoing,failed,rejected,successful',
            'VALIDATION_MESSAGE' => 'nullable|string|max:255'
        ]);

        $enrollment->update($validated);

        return back()->with('success', 'Enrollment status updated successfully');
    }

    /**
     * Display single enrollment record
     */
    public function show(Enrollment $enrollment)
    {
        return view('enrollments.show', compact('enrollment'));
    }
}