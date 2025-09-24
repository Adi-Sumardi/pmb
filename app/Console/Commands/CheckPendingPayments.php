<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\StudentBill;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckPendingPayments extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'payments:check-pending
                            {--hours=2 : Number of hours to check for pending payments}
                            {--auto-expire : Automatically expire payments older than specified hours}
                            {--notify : Send notifications about pending payments}';

    /**
     * The console command description.
     */
    protected $description = 'Check and manage pending payments that may need attention';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $autoExpire = $this->option('auto-expire');
        $notify = $this->option('notify');

        $this->info("🔍 Checking Pending Payments");
        $this->info("⏰ Looking for payments pending more than {$hours} hour(s)");

        $cutoffTime = now()->subHours($hours);

        // Get payments that are still pending after cutoff time
        $pendingPayments = Payment::where('status', 'PENDING')
            ->where('created_at', '<', $cutoffTime)
            ->with('pendaftar')
            ->get();

        if ($pendingPayments->isEmpty()) {
            $this->info("✅ No pending payments found older than {$hours} hour(s)");
            return 0;
        }

        $this->warn("⚠️  Found {$pendingPayments->count()} pending payment(s) older than {$hours} hour(s):");

        $table = [];
        foreach ($pendingPayments as $payment) {
            $hoursAgo = $payment->created_at->diffInHours(now());
            $table[] = [
                'ID' => $payment->id,
                'Student' => $payment->pendaftar->nama_murid,
                'Amount' => 'Rp ' . number_format((float) $payment->amount, 0, ',', '.'),
                'Created' => $payment->created_at->format('Y-m-d H:i:s'),
                'Hours Ago' => $hoursAgo,
                'External ID' => $payment->external_id ?? 'Not Set'
            ];
        }

        $this->table([
            'ID', 'Student', 'Amount', 'Created', 'Hours Ago', 'External ID'
        ], $table);

        // Auto-expire if requested
        if ($autoExpire) {
            $this->warn("⚡ Auto-expiring pending payments...");

            $expiredCount = 0;
            foreach ($pendingPayments as $payment) {
                // Only expire if external_id exists (invoice was created) but payment is still pending
                if ($payment->external_id) {
                    $payment->update([
                        'status' => 'EXPIRED',
                        'expired_at' => now()
                    ]);
                    $expiredCount++;

                    Log::info('Auto-expired pending payment', [
                        'payment_id' => $payment->id,
                        'external_id' => $payment->external_id,
                        'student' => $payment->pendaftar->nama_murid,
                        'hours_pending' => $payment->created_at->diffInHours(now())
                    ]);
                }
            }

            $this->info("✅ Expired {$expiredCount} payment(s)");
        }

        // Check StudentBills without external payments
        $this->info("\n📋 Checking StudentBills status...");

        $orphanBills = StudentBill::where('payment_status', 'pending')
            ->where('created_at', '<', $cutoffTime)
            ->with('pendaftar')
            ->get();

        if ($orphanBills->isNotEmpty()) {
            $this->warn("⚠️  Found {$orphanBills->count()} pending StudentBill(s) without payments:");

            $billTable = [];
            foreach ($orphanBills as $bill) {
                $billTable[] = [
                    'ID' => $bill->id,
                    'Student' => $bill->pendaftar->nama_murid,
                    'Type' => $bill->bill_type,
                    'Amount' => 'Rp ' . number_format((float) $bill->total_amount, 0, ',', '.'),
                    'Status' => $bill->payment_status,
                    'Created' => $bill->created_at->format('Y-m-d H:i:s')
                ];
            }

            $this->table([
                'ID', 'Student', 'Type', 'Amount', 'Status', 'Created'
            ], $billTable);
        }

        // Summary
        $this->info("\n📊 Summary:");
        $this->info("• Pending Payments: {$pendingPayments->count()}");
        $this->info("• Pending Bills: {$orphanBills->count()}");

        if ($autoExpire) {
            $this->info("• Expired Payments: {$expiredCount}");
        }

        return 0;
    }
}
