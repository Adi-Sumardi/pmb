# Revenue Calculation Service Documentation

## Overview

`RevenueCalculationService` adalah service class yang dibuat untuk memastikan konsistensi perhitungan revenue di seluruh aplikasi PPDB. Service ini mengatasi masalah perbedaan perhitungan revenue antara halaman Dashboard dan Transactions yang sebelumnya menggunakan formula yang berbeda.

## Problem Statement

### Sebelum Implementasi Service:

**Dashboard (`/admin/dashboard`):**
- Formula: `BillPayment::completed + Payment::PAID`
- Total: Rp 3.512.250 (menggabungkan 2 sumber pemasukan)

**Transactions (`/admin/transactions`):**
- Formula: `Payment::PAID` saja  
- Total: Rp 1.762.250 (hanya formulir pendaftaran)

**Masalah:** Inkonsistensi perhitungan dan penjumlahan yang membingungkan

### Setelah Implementasi Service:

Sekarang menampilkan **revenue streams terpisah** sesuai konteks bisnis:

**Dashboard:**
- **Pendapatan Formulir Pendaftaran:** Rp 1.762.250
- **Pendapatan SPP/Tagihan:** Rp 1.750.000
- **Ditampilkan terpisah** sebagai 2 sumber pemasukan yang berbeda

**Transactions:**
- **Pendapatan Formulir Pendaftaran:** Rp 1.762.250 (sesuai konteks halaman)

## Features

### 1. Revenue Calculation Methods

```php
// Get separate revenue streams (RECOMMENDED)
$service->getRevenueStreams($useCache = true);

// Get registration-specific statistics (for transactions page)
$service->getRegistrationStats();

// Get bill-specific statistics (for SPP/billing page)
$service->getBillStats();

// Individual revenue calculations
$service->getRegistrationRevenue($useCache = true);
$service->getBillRevenue($useCache = true);

// DEPRECATED: Use getRevenueStreams() instead
$service->getRevenueBreakdown($useCache = true);
$service->getTotalRevenue($useCache = true);
```

### 2. Period-based Revenue Analysis

```php
// Get revenue for specific period
$service->getRevenueByPeriod($startDate, $endDate);

// Get monthly revenue trends (last 12 months)
$service->getMonthlyRevenueTrends($months = 12);
```

### 3. Caching System

- **Cache Duration:** 5 minutes (300 seconds)
- **Cache Keys:**
  - `revenue_total`
  - `revenue_registration` 
  - `revenue_bills`
- **Performance:** Cache hit ~0.03ms vs Cache miss ~0.76ms

### 4. Cache Management

```bash
# Clear revenue cache via artisan command
php artisan revenue:clear-cache
```

## Integration

### Controllers Updated

1. **DashboardController:** 
   - Uses `RevenueCalculationService` for consistent billing statistics
   - Added revenue breakdown and monthly/weekly analytics

2. **PaymentController:**
   - Uses `RevenueCalculationService` for admin transactions page
   - Added detailed revenue breakdown in statistics

## Data Structure

### Revenue Streams Response (RECOMMENDED):
```php
[
    'registration_revenue' => [
        'amount' => 1762250,
        'description' => 'Pendapatan dari Formulir Pendaftaran',
        'type' => 'registration'
    ],
    'bill_revenue' => [
        'amount' => 1750000,
        'description' => 'Pendapatan dari Pembayaran SPP/Tagihan',
        'type' => 'bills'
    ]
]
```

### Registration Stats Response:
```php
[
    'total_transactions' => 4,
    'paid_transactions' => 4,
    'pending_transactions' => 0,
    'failed_transactions' => 0,
    'total_revenue' => 1762250,
    'revenue_description' => 'Pendapatan dari Formulir Pendaftaran'
]
```

### Bill Stats Response:
```php
[
    'total_transactions' => 4,
    'paid_transactions' => 4,
    'pending_transactions' => 0,
    'failed_transactions' => 0,
    'total_revenue' => 1750000,
    'revenue_description' => 'Pendapatan dari Pembayaran SPP/Tagihan'
]
```

## Usage Examples

### Dashboard Controller (Multiple Revenue Streams):

```php
use App\Services\RevenueCalculationService;

class DashboardController extends Controller
{
    protected $revenueService;

    public function __construct(RevenueCalculationService $revenueService)
    {
        $this->revenueService = $revenueService;
    }

    public function index()
    {
        $revenueStreams = $this->revenueService->getRevenueStreams();
        
        return view('admin.dashboard', compact('revenueStreams'));
    }
}
```

### Payment Controller (Registration Only):

```php
public function adminTransactions()
{
    $stats = $this->revenueService->getRegistrationStats();
    
    return view('admin.transactions.index', compact('stats'));
}
```

### In Views (Dashboard):

```php
<!-- Display separate revenue streams -->
@foreach($revenueStreams as $key => $stream)
<div class="revenue-card">
    <h3>{{ $stream['description'] }}</h3>
    <p class="amount">Rp {{ number_format($stream['amount'], 0, ',', '.') }}</p>
    <small>{{ ucfirst($stream['type']) }}</small>
</div>
@endforeach
```

### In Views (Transactions):

```php
<!-- Display context-specific revenue -->
<div class="metric">
    <h3>{{ $stats['revenue_description'] }}</h3>
    <p>Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
    <small>{{ $stats['paid_transactions'] }} transaksi berhasil</small>
</div>
```

## Maintenance

### Cache Invalidation

Cache automatically expires after 5 minutes, but can be manually cleared when:
- New payments are processed
- Payment statuses are updated
- System maintenance is performed

```bash
php artisan revenue:clear-cache
```

### Monitoring

Monitor performance and cache hit rates through:
- Application logs
- Cache statistics
- Revenue calculation response times

## Benefits

1. **Consistency:** Same revenue calculation across all pages
2. **Performance:** Caching reduces database queries
3. **Maintainability:** Centralized revenue logic
4. **Flexibility:** Easy to add new revenue sources
5. **Analytics:** Built-in revenue breakdown and trends
6. **Scalability:** Efficient caching system

## Future Enhancements

1. **Real-time Updates:** WebSocket integration for live revenue updates
2. **Advanced Analytics:** Detailed revenue forecasting
3. **Export Features:** Revenue reports in PDF/Excel format
4. **API Endpoints:** REST API for revenue data
5. **Dashboard Widgets:** Interactive revenue charts
