# ANALISIS MASALAH SISTEM PEMBAYARAN

## MASALAH UTAMA: DUPLIKASI PEMBAYARAN

Sistem saat ini memiliki **2 ALUR PEMBAYARAN YANG BERBEDA** untuk hal yang sama:

### 1. Alur Lama (Payment Model)
- **Tabel**: `payments`
- **Gateway**: Xendit langsung
- **Method**: Bank Transfer, QR Code, Retail Outlet
- **Status**: Berdasarkan webhook Xendit
- **Created by**: User memilih metode pembayaran di frontend

### 2. Alur Baru (StudentBill + BillPayment)
- **Tabel**: `student_bills` + `bill_payments`
- **Gateway**: Virtual Account (hardcoded)
- **Method**: virtual_account (hardcoded)
- **Status**: Auto-created setelah Payment sukses
- **Created by**: Otomatis di webhook handler

## PENYEBAB DUPLIKASI

Ketika user melakukan pembayaran:

1. **User memilih payment method** (Bank Transfer/QR/Retail) → Record masuk ke `payments`
2. **Setelah payment sukses**, webhook handler otomatis create record di `bill_payments` dengan hardcoded `payment_method: 'virtual_account'`

Ini menyebabkan **setiap transaksi tercatat 2 kali** dengan method berbeda!

## DATA SAAT INI

```
Rafael Struick:
- Payment: Rp 553.850 (BANK_TRANSFER via Xendit)
- BillPayment: Rp 550.000 (virtual_account - hardcoded)

Ahmad Zaelani:
- Payment: Rp 553.850 (QR_CODE via Xendit)  
- BillPayment: Rp 550.000 (virtual_account - hardcoded)

Himawari:
- Payment: Rp 553.850 (RETAIL_OUTLET via Xendit)
- BillPayment: Rp 550.000 (virtual_account - hardcoded)
```

## DAMPAK MASALAH

1. **Revenue Calculation Error**: Total revenue jadi dobel karena hitung dari 2 tabel
2. **Inconsistent Payment Method**: Payment asli hilang, semua jadi "virtual_account"
3. **Amount Mismatch**: Payment (553.850) vs BillPayment (550.000) beda karena fees
4. **Confusing Reports**: Laporan tidak akurat

## SOLUSI REKOMENDASI

### OPSI 1: GUNAKAN PAYMENT SAJA (Recommended)
- Hapus auto-create BillPayment di webhook
- Update RevenueCalculationService untuk hanya baca dari Payment
- Lebih simple, data konsisten

### OPSI 2: GUNAKAN STUDENTBILL + BILLPAYMENT SAJA
- Migrasi semua data Payment ke BillPayment
- Fix payment_method di BillPayment sesuai aslinya
- Lebih complex tapi lebih terstruktur untuk SPP/Uang Pangkal

### OPSI 3: HYBRID DENGAN MAPPING YANG BENAR
- Payment untuk registration_fee
- BillPayment untuk SPP/Uang Pangkal (future)
- Fix payment_method mapping di webhook

## REKOMENDASI UNTUK SPP & UANG PANGKAL

Untuk pembayaran SPP dan Uang Pangkal di masa depan:

1. **Gunakan StudentBill system** - lebih terstruktur untuk recurring payments
2. **Xendit Virtual Account** - untuk auto-debit bulanan SPP
3. **Integration dengan bank** - untuk kemudahan orang tua bayar SPP

## NEXT STEPS

1. Pilih solusi (Opsi 1 recommended untuk simplicity)
2. Fix RevenueCalculationService
3. Clean up duplikasi data
4. Plan untuk SPP/Uang Pangkal system
