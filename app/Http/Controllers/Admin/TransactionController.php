<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Laravel\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use App\Models\Payment;
use App\Services\RevenueCalculationService;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;
use PDF;

class TransactionController extends Controller
{
    protected $revenueService;

    public function __construct(RevenueCalculationService $revenueService)
    {
        $this->revenueService = $revenueService;
    }
    // For Excel Export
    public function export(Request $request)
    {
        // Validate input parameters for security
        $validatedData = $request->validate([
            'status' => 'nullable|in:PENDING,PAID,EXPIRED,FAILED,CANCELLED',
            'jenjang' => 'nullable|string|max:50',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'search' => 'nullable|string|max:255',
            'payment_type' => 'nullable|in:formulir,spp,uang_pangkal,uniform,books,activity'
        ]);

        // Apply the same filters used in the index method using validated data
        $query = Payment::with('pendaftar');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $validatedData['status']);
        }

        // Filter by jenjang
        if ($request->filled('jenjang')) {
            $query->whereHas('pendaftar', function($q) use ($validatedData) {
                $q->where('jenjang', $validatedData['jenjang']);
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $validatedData['date_from']);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $validatedData['date_to']);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $validatedData['search'];
            $query->whereHas('pendaftar', function($q) use ($search) {
                $q->where('nama_murid', 'like', '%' . $search . '%')
                  ->orWhere('no_pendaftaran', 'like', '%' . $search . '%');
            });
        }

        // Generate filename with current date
        $filename = 'transaksi_pembayaran_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return FacadesExcel::download(new TransactionsExport($query), $filename);
    }

    // For PDF Export
    public function exportPdf(Request $request)
    {
        // Validate input parameters for security
        $validatedData = $request->validate([
            'status' => 'nullable|in:PENDING,PAID,EXPIRED,FAILED,CANCELLED',
            'jenjang' => 'nullable|string|max:50',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'search' => 'nullable|string|max:255',
            'payment_type' => 'nullable|in:formulir,spp,uang_pangkal,uniform,books,activity'
        ]);

        // Apply the same filters used in the index method using validated data
        $query = Payment::with('pendaftar');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $validatedData['status']);
        }

        // Filter by jenjang
        if ($request->filled('jenjang')) {
            $query->whereHas('pendaftar', function($q) use ($validatedData) {
                $q->where('jenjang', $validatedData['jenjang']);
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $validatedData['date_from']);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $validatedData['date_to']);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $validatedData['search'];
            $query->whereHas('pendaftar', function($q) use ($search) {
                $q->where('nama_murid', 'like', '%' . $search . '%')
                  ->orWhere('no_pendaftaran', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy('created_at', 'desc');

        $payments = $query->get();

        // Get statistics with the same filters applied
        $dateFrom = $request->filled('date_from') ? $validatedData['date_from'] : null;
        $dateTo = $request->filled('date_to') ? $validatedData['date_to'] : null;
        $stats = $this->revenueService->getRegistrationStatsWithFilter($dateFrom, $dateTo);

        $pdf = FacadePdf::loadView('admin.transactions.pdf', compact('payments', 'stats', 'request'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('transaksi_pembayaran_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    // For Print View
    public function printView(Request $request)
    {
        // Validate input parameters for security
        $validatedData = $request->validate([
            'status' => 'nullable|in:PENDING,PAID,EXPIRED,FAILED,CANCELLED',
            'jenjang' => 'nullable|string|max:50',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'search' => 'nullable|string|max:255',
            'payment_type' => 'nullable|in:formulir,spp,uang_pangkal,seragam,buku,kegiatan'
        ]);

        // Apply the same filters used in the index method using validated data
        $query = Payment::with('pendaftar');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $validatedData['status']);
        }

        // Filter by jenjang
        if ($request->filled('jenjang')) {
            $query->whereHas('pendaftar', function($q) use ($validatedData) {
                $q->where('jenjang', $validatedData['jenjang']);
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $validatedData['date_from']);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $validatedData['date_to']);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $validatedData['search'];
            $query->whereHas('pendaftar', function($q) use ($search) {
                $q->where('nama_murid', 'like', '%' . $search . '%')
                  ->orWhere('no_pendaftaran', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy('created_at', 'desc');

        $payments = $query->get();

        // Get statistics with the same filters applied
        $dateFrom = $request->filled('date_from') ? $validatedData['date_from'] : null;
        $dateTo = $request->filled('date_to') ? $validatedData['date_to'] : null;
        $stats = $this->revenueService->getRegistrationStatsWithFilter($dateFrom, $dateTo);

        return view('admin.transactions.print', compact('payments', 'stats', 'request'));
    }


}
