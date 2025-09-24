<?php

namespace App\Services;

use App\Models\BillPayment;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class RevenueCalculationService
{
    // Cache keys and TTL constants
    const CACHE_KEY_TOTAL_REVENUE = 'revenue_total';
    const CACHE_KEY_REGISTRATION_REVENUE = 'revenue_registration';
    const CACHE_KEY_BILL_REVENUE = 'revenue_bills';
    const CACHE_TTL = 300; // 5 minutes

    /**
     * Get total revenue from all sources
     *
     * @param bool $useCache Whether to use cache
     * @return float
     */
    public function getTotalRevenue(bool $useCache = true): float
    {
        if ($useCache) {
            return Cache::remember(self::CACHE_KEY_TOTAL_REVENUE, self::CACHE_TTL, function () {
                return $this->calculateTotalRevenue();
            });
        }

        return $this->calculateTotalRevenue();
    }

    /**
     * Get revenue from registration payments only
     *
     * @param bool $useCache Whether to use cache
     * @return float
     */
    public function getRegistrationRevenue(bool $useCache = true): float
    {
        if ($useCache) {
            return Cache::remember(self::CACHE_KEY_REGISTRATION_REVENUE, self::CACHE_TTL, function () {
                return $this->calculateRegistrationRevenue();
            });
        }

        return $this->calculateRegistrationRevenue();
    }

    /**
     * Get revenue from bill payments only
     *
     * @param bool $useCache Whether to use cache
     * @return float
     */
    public function getBillRevenue(bool $useCache = true): float
    {
        if ($useCache) {
            return Cache::remember(self::CACHE_KEY_BILL_REVENUE, self::CACHE_TTL, function () {
                return $this->calculateBillRevenue();
            });
        }

        return $this->calculateBillRevenue();
    }

    /**
     * Get separate revenue streams (not combined)
     * Fixed: Registration revenue only from Payment table to avoid duplication
     *
     * @param bool $useCache Whether to use cache
     * @return array
     */
    public function getRevenueStreams(bool $useCache = true): array
    {
        $registrationRevenue = $this->getRegistrationRevenue($useCache); // Only from Payment table
        $billRevenue = $this->getBillRevenue($useCache); // Now excludes registration_fee
        $uangPangkalRevenue = $this->getUangPangkalRevenue($useCache);

        return [
            [
                'source' => 'Registration',
                'amount' => $registrationRevenue,
                'description' => 'Pendapatan dari Formulir Pendaftaran (Payment only - no duplication)',
                'type' => 'registration'
            ],
            [
                'source' => 'Bills/SPP',
                'amount' => $billRevenue,
                'description' => 'Pendapatan dari SPP/Tagihan (Non-Registration)',
                'type' => 'bills'
            ],
            [
                'source' => 'Uang Pangkal',
                'amount' => $uangPangkalRevenue,
                'description' => 'Pendapatan dari Uang Pangkal',
                'type' => 'uang_pangkal'
            ],
        ];
    }

    /**
     * Get detailed revenue breakdown - DEPRECATED
     * Use getRevenueStreams() instead for proper separation
     *
     * @param bool $useCache Whether to use cache
     * @return array
     */
    public function getRevenueBreakdown(bool $useCache = true): array
    {
        $registrationRevenue = $this->getRegistrationRevenue($useCache);
        $billRevenue = $this->getBillRevenue($useCache);
        $totalRevenue = $registrationRevenue + $billRevenue;

        return [
            'registration_revenue' => $registrationRevenue,
            'bill_revenue' => $billRevenue,
            'total_revenue' => $totalRevenue,
            'registration_percentage' => $totalRevenue > 0 ? ($registrationRevenue / $totalRevenue) * 100 : 0,
            'bill_percentage' => $totalRevenue > 0 ? ($billRevenue / $totalRevenue) * 100 : 0,
            'note' => 'DEPRECATED: Use getRevenueStreams() for proper revenue separation'
        ];
    }    /**
     * Get revenue statistics for a specific period
     *
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @return array
     */
    public function getRevenueByPeriod(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $registrationQuery = Payment::where('status', 'PAID');
        $billQuery = BillPayment::where('status', 'completed');

        if ($startDate) {
            $registrationQuery->whereDate('paid_at', '>=', $startDate);
            $billQuery->whereDate('confirmed_at', '>=', $startDate);
        }

        if ($endDate) {
            $registrationQuery->whereDate('paid_at', '<=', $endDate);
            $billQuery->whereDate('confirmed_at', '<=', $endDate);
        }

        $registrationRevenue = $registrationQuery->sum('amount');
        $billRevenue = $billQuery->sum('amount');

        return [
            'registration_revenue' => $registrationRevenue,
            'bill_revenue' => $billRevenue,
            'total_revenue' => $registrationRevenue + $billRevenue,
            'period' => [
                'start_date' => $startDate?->format('Y-m-d'),
                'end_date' => $endDate?->format('Y-m-d'),
            ]
        ];
    }

    /**
     * Get monthly revenue trends
     *
     * @param int $months Number of months to get
     * @return array
     */
    public function getMonthlyRevenueTrends(int $months = 12): array
    {
        $trends = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $monthlyData = $this->getRevenueByPeriod($startOfMonth, $endOfMonth);
            $monthlyData['month'] = $date->format('Y-m');
            $monthlyData['month_name'] = $date->format('F Y');

            $trends[] = $monthlyData;
        }

        return $trends;
    }

    /**
     * Get revenue statistics with transaction counts
     *
     * @return array
     */
    public function getRevenueStats(): array
    {
        return [
            'total_revenue' => $this->getTotalRevenue(),
            'registration_revenue' => $this->getRegistrationRevenue(),
            'bill_revenue' => $this->getBillRevenue(),
            'registration_transactions' => Payment::where('status', 'PAID')->count(),
            'bill_transactions' => BillPayment::where('status', 'completed')->count(),
            'pending_registration_payments' => Payment::where('status', 'PENDING')->count(),
            'pending_bill_payments' => BillPayment::where('status', 'pending')->count(),
        ];
    }

    /**
     * Get registration-specific statistics (for Payment/Transactions page)
     *
     * @return array
     */
    public function getRegistrationStats(): array
    {
        $paidCount = Payment::where('status', 'PAID')->count();
        $pendingCount = Payment::where('status', 'PENDING')->count();
        $expiredCount = Payment::where('status', 'EXPIRED')->count();
        $failedCount = Payment::whereIn('status', ['FAILED', 'CANCELLED'])->count();

        $paidAmount = (float) Payment::where('status', 'PAID')->sum('amount');
        $pendingAmount = (float) Payment::where('status', 'PENDING')->sum('amount');
        $expiredAmount = (float) Payment::where('status', 'EXPIRED')->sum('amount');
        $failedAmount = (float) Payment::whereIn('status', ['FAILED', 'CANCELLED'])->sum('amount');

        return [
            'paid_count' => $paidCount,
            'pending_count' => $pendingCount,
            'expired_count' => $expiredCount,
            'failed_count' => $failedCount,
            'total_count' => $paidCount + $pendingCount + $expiredCount + $failedCount,
            'total_transactions' => $paidCount + $pendingCount + $expiredCount + $failedCount, // Alias for view compatibility
            'paid_transactions' => $paidCount, // Alias for view compatibility
            'pending_transactions' => $pendingCount, // Alias for view compatibility
            'total_revenue' => $paidAmount, // Alias for view compatibility (only paid amount as actual revenue)
            'paid_amount' => $paidAmount,
            'pending_amount' => $pendingAmount,
            'expired_amount' => $expiredAmount,
            'failed_amount' => $failedAmount,
            'total_amount' => $paidAmount + $pendingAmount + $expiredAmount + $failedAmount,
            'revenue_description' => 'Pendapatan dari Formulir Pendaftaran'
        ];
    }

    /**
     * Get registration-specific statistics with date filter support
     *
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $status
     * @return array
     */
    public function getRegistrationStatsWithFilter(?string $dateFrom = null, ?string $dateTo = null, ?string $status = null): array
    {
        $query = Payment::query();

        // Apply date filters
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Calculate counts with filters applied
        $paidCount = (clone $query)->where('status', 'PAID')->count();
        $pendingCount = (clone $query)->where('status', 'PENDING')->count();
        $expiredCount = (clone $query)->where('status', 'EXPIRED')->count();
        $failedCount = (clone $query)->whereIn('status', ['FAILED', 'CANCELLED'])->count();

        // Calculate amounts with filters applied
        $paidAmount = (float) (clone $query)->where('status', 'PAID')->sum('amount');
        $pendingAmount = (float) (clone $query)->where('status', 'PENDING')->sum('amount');
        $expiredAmount = (float) (clone $query)->where('status', 'EXPIRED')->sum('amount');
        $failedAmount = (float) (clone $query)->whereIn('status', ['FAILED', 'CANCELLED'])->sum('amount');

        return [
            'paid_count' => $paidCount,
            'pending_count' => $pendingCount,
            'expired_count' => $expiredCount,
            'failed_count' => $failedCount,
            'total_count' => $paidCount + $pendingCount + $expiredCount + $failedCount,
            'total_transactions' => $paidCount + $pendingCount + $expiredCount + $failedCount, // Alias for view compatibility
            'paid_transactions' => $paidCount, // Alias for view compatibility
            'pending_transactions' => $pendingCount, // Alias for view compatibility
            'total_revenue' => $paidAmount, // Alias for view compatibility (only paid amount as actual revenue)
            'paid_amount' => $paidAmount,
            'pending_amount' => $pendingAmount,
            'expired_amount' => $expiredAmount,
            'failed_amount' => $failedAmount,
            'total_amount' => $paidAmount + $pendingAmount + $expiredAmount + $failedAmount,
            'revenue_description' => 'Pendapatan dari Formulir Pendaftaran' . ($dateFrom || $dateTo ? ' (Periode: ' . ($dateFrom ?: 'Awal') . ' - ' . ($dateTo ?: 'Sekarang') . ')' : ''),
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ];
    }

    /**
     * Get bill-specific statistics (excluding registration fees)
     *
     * @return array
     */
    public function getBillStats(): array
    {
        $billTypes = ['spp', 'uang_pangkal', 'uniform', 'books', 'activity'];
        $stats = [];

        foreach ($billTypes as $type) {
            $paidCount = \App\Models\StudentBill::where('bill_type', $type)
                ->where('payment_status', 'paid')->count();
            $pendingCount = \App\Models\StudentBill::where('bill_type', $type)
                ->where('payment_status', 'pending')->count();

            $paidAmount = (float) \App\Models\StudentBill::where('bill_type', $type)
                ->where('payment_status', 'paid')->sum('paid_amount');
            $pendingAmount = (float) \App\Models\StudentBill::where('bill_type', $type)
                ->where('payment_status', 'pending')->sum('total_amount');

            $stats[$type] = [
                'paid_count' => $paidCount,
                'pending_count' => $pendingCount,
                'paid_amount' => $paidAmount,
                'pending_amount' => $pendingAmount,
                'total_count' => $paidCount + $pendingCount,
                'total_amount' => $paidAmount + $pendingAmount
            ];
        }

        return $stats;
    }

    /**
     * Clear revenue cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY_TOTAL_REVENUE);
        Cache::forget(self::CACHE_KEY_REGISTRATION_REVENUE);
        Cache::forget(self::CACHE_KEY_BILL_REVENUE);
        Cache::forget('revenue_uang_pangkal');
        Cache::forget('revenue_total_registration');
    }

    /**
     * Calculate total revenue from all sources
     * Fixed: Use only Payment table for registration to avoid duplication
     *
     * @return float
     */
    private function calculateTotalRevenue(): float
    {
        return $this->calculateRegistrationRevenue() + $this->calculateBillRevenue();
    }

    /**
     * Calculate revenue from registration payments
     *
     * @return float
     */
    private function calculateRegistrationRevenue(): float
    {
        return (float) Payment::where('status', 'PAID')->sum('amount');
    }

    /**
     * Calculate revenue from bill payments (exclude registration fees)
     *
     * @return float
     */
    private function calculateBillRevenue(): float
    {
        return (float) BillPayment::where('status', 'completed')
            ->whereHas('studentBill', function($q) {
                $q->where('bill_type', '!=', 'registration_fee');
            })->sum('amount');
    }

    /**
     * DEPRECATED: Use getRegistrationRevenue() instead
     * This method was causing duplication by counting Payment + BillPayment
     *
     * @param bool $useCache Whether to use cache
     * @return float
     */
    public function getTotalRegistrationRevenue(bool $useCache = true): float
    {
        // Return only Payment revenue to avoid duplication
        return $this->getRegistrationRevenue($useCache);
    }

    /**
     * DEPRECATED: Was causing duplication
     * Calculate registration revenue from Payment table only
     *
     * @return float
     */
    private function calculateTotalRegistrationRevenue(): float
    {
        // Only count from Payment table to avoid duplication
        return $this->calculateRegistrationRevenue();
    }    /**
     * Get revenue from uang pangkal payments only
     *
     * @param bool $useCache Whether to use cache
     * @return float
     */
    public function getUangPangkalRevenue(bool $useCache = true): float
    {
        if ($useCache) {
            return Cache::remember('revenue_uang_pangkal', self::CACHE_TTL, function () {
                return $this->calculateUangPangkalRevenue();
            });
        }

        return $this->calculateUangPangkalRevenue();
    }

    /**
     * Calculate revenue from uang pangkal payments
     *
     * @return float
     */
    private function calculateUangPangkalRevenue(): float
    {
        // Method 1: From BillPayment for uang_pangkal bills
        $billPaymentRevenue = BillPayment::whereHas('studentBill', function($q) {
            $q->where('bill_type', 'uang_pangkal');
        })->where('status', 'completed')->sum('amount');

        // Method 2: From StudentBill paid_amount for uang_pangkal
        $studentBillRevenue = \App\Models\StudentBill::where('bill_type', 'uang_pangkal')
            ->sum('paid_amount');

        // Use the higher value (in case of data inconsistency)
        return (float) max($billPaymentRevenue, $studentBillRevenue);
    }

    /**
     * Get uang pangkal-specific statistics
     *
     * @return array
     */
    public function getUangPangkalStats(): array
    {
        $totalBills = \App\Models\StudentBill::where('bill_type', 'uang_pangkal')->count();
        $paidBills = \App\Models\StudentBill::where('bill_type', 'uang_pangkal')
            ->where('remaining_amount', '<=', 0)->count();
        $pendingBills = \App\Models\StudentBill::where('bill_type', 'uang_pangkal')
            ->where('remaining_amount', '>', 0)->count();

        return [
            'total_transactions' => $totalBills,
            'paid_transactions' => $paidBills,
            'pending_transactions' => $pendingBills,
            'failed_transactions' => 0, // No failed status in StudentBill
            'total_revenue' => $this->getUangPangkalRevenue(),
            'revenue_description' => 'Pendapatan dari Uang Pangkal'
        ];
    }

    /**
     * Clear uang pangkal cache
     *
     * @return void
     */
    public function clearUangPangkalCache(): void
    {
        Cache::forget('revenue_uang_pangkal');
    }
}
