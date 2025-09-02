<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Laravel\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;
use PDF;

class AdminTransactionController extends Controller
{
    // For Excel Export
    public function export(Request $request)
    {
        // Apply the same filters used in the index method
        $query = Payment::with('pendaftar')
            ->when($request->status, function($q, $status) {
                return $q->where('status', $status);
            })
            ->when($request->jenjang, function($q, $jenjang) {
                return $q->whereHas('pendaftar', function($query) use ($jenjang) {
                    $query->where('jenjang', $jenjang);
                });
            })
            ->when($request->search, function($q, $search) {
                return $q->whereHas('pendaftar', function($query) use ($search) {
                    $query->where('nama_murid', 'like', "%{$search}%")
                        ->orWhere('no_pendaftaran', 'like', "%{$search}%");
                });
            })
            ->when($request->date_from, function($q, $date) {
                return $q->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function($q, $date) {
                return $q->whereDate('created_at', '<=', $date);
            });

        // Generate filename with current date
        $filename = 'transaksi_pembayaran_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return FacadesExcel::download(new TransactionsExport($query), $filename);
    }

    // For PDF Export
    public function exportPdf(Request $request)
    {
        // Apply the same filters as above
        $query = Payment::with('pendaftar')
            ->when($request->status, function($q, $status) {
                return $q->where('status', $status);
            })
            ->when($request->jenjang, function($q, $jenjang) {
                return $q->whereHas('pendaftar', function($query) use ($jenjang) {
                    $query->where('jenjang', $jenjang);
                });
            })
            ->when($request->search, function($q, $search) {
                return $q->whereHas('pendaftar', function($query) use ($search) {
                    $query->where('nama_murid', 'like', "%{$search}%")
                        ->orWhere('no_pendaftaran', 'like', "%{$search}%");
                });
            })
            ->when($request->date_from, function($q, $date) {
                return $q->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function($q, $date) {
                return $q->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc');

        $payments = $query->get();
        $stats = $this->getPaymentStats($query);

        $pdf = FacadePdf::loadView('transactions.admin.pdf', compact('payments', 'stats', 'request'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('transaksi_pembayaran_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    // For Print View
    public function printView(Request $request)
    {
        // Apply the same filters as above
        $query = Payment::with('pendaftar')
            ->when($request->status, function($q, $status) {
                return $q->where('status', $status);
            })
            ->when($request->jenjang, function($q, $jenjang) {
                return $q->whereHas('pendaftar', function($query) use ($jenjang) {
                    $query->where('jenjang', $jenjang);
                });
            })
            ->when($request->search, function($q, $search) {
                return $q->whereHas('pendaftar', function($query) use ($search) {
                    $query->where('nama_murid', 'like', "%{$search}%")
                        ->orWhere('no_pendaftaran', 'like', "%{$search}%");
                });
            })
            ->when($request->date_from, function($q, $date) {
                return $q->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function($q, $date) {
                return $q->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc');

        $payments = $query->get();
        $stats = $this->getPaymentStats($query);

        return view('transactions.admin.print', compact('payments', 'stats', 'request'));
    }

    // Helper method to get payment statistics
    private function getPaymentStats($query)
    {
        $totalTransactions = clone $query;
        $paidTransactions = clone $query;
        $pendingTransactions = clone $query;
        $totalRevenue = clone $query;

        return [
            'total_transactions' => $totalTransactions->count(),
            'paid_transactions' => $paidTransactions->where('status', 'PAID')->count(),
            'pending_transactions' => $pendingTransactions->where('status', 'PENDING')->count(),
            'total_revenue' => $totalRevenue->where('status', 'PAID')->sum('amount')
        ];
    }
}
