<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        return [
            'No.',
            'Transaction ID',
            'Nama Siswa',
            'No. Pendaftaran',
            'Unit',
            'Jenjang',
            'Amount',
            'Metode Pembayaran',
            'Status',
            'Tanggal Dibuat',
            'Tanggal Dibayar'
        ];
    }

    public function map($payment): array
    {
        // Extract payment method from xendit_response
        $paymentMethod = '';
        $paymentChannel = '';

        if (isset($payment->xendit_response['payment_method'])) {
            $paymentMethod = $payment->xendit_response['payment_method'];
            $paymentChannel = $payment->xendit_response['payment_channel'] ?? '';

            // Display friendly name
            if ($paymentMethod == 'EWALLET') {
                $displayMethod = $paymentChannel ?: 'E-Wallet';
            } elseif ($paymentMethod == 'BANK_TRANSFER' || $paymentMethod == 'VIRTUAL_ACCOUNT') {
                $displayMethod = ($paymentChannel ?: 'VA') . ' Virtual Account';
            } elseif ($paymentMethod == 'QR_CODE' || $paymentMethod == 'QRIS') {
                $displayMethod = 'QRIS';
            } elseif ($paymentMethod == 'CREDIT_CARD') {
                $displayMethod = 'Kartu Kredit/Debit';
            } elseif ($paymentMethod == 'RETAIL_OUTLET') {
                $displayMethod = $paymentChannel ?: 'Retail Store';
            } else {
                $displayMethod = $paymentMethod;
            }
        } else {
            $displayMethod = $payment->status == 'PENDING' ? 'Menunggu pembayaran' : 'N/A';
        }

        // Map status to friendly names
        $statusLabels = [
            'PAID' => 'Lunas',
            'PENDING' => 'Menunggu',
            'FAILED' => 'Gagal'
        ];

        return [
            $payment->id,
            $payment->external_id,
            $payment->pendaftar->nama_murid,
            $payment->pendaftar->no_pendaftaran,
            $payment->pendaftar->unit,
            strtoupper($payment->pendaftar->jenjang),
            $payment->amount,
            $displayMethod,
            $statusLabels[$payment->status] ?? $payment->status,
            $payment->created_at->format('d/m/Y H:i'),
            $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
