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
     * Upload & process Excel/CSV
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

            DB::transaction(function () use ($rows) {
                foreach ($rows as $index => $row) {
                    if ($index === 1) continue; // Skip header row

                    $ticket = trim($row['A']);
                    if (!$ticket) continue; // Ticket is required

                    Enrollment::updateOrCreate(
                        ['TICKET_NUMBER' => $ticket],
                        [
                            'id'                   => Enrollment::nextId(), // only used if new row
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
                        ]
                    );
                }
            });

            return back()->with('success', 'File uploaded and processed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }
}
