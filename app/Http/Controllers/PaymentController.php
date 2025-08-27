<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private $xenditApiKey;

    public function __construct()
    {
        $this->xenditApiKey = env('XENDIT_SECRET_KEY');
    }

    private function isDemoMode()
    {
        return env('XENDIT_MODE') === 'demo' ||
            $this->xenditApiKey === 'demo_secret_key_for_testing' ||
            empty($this->xenditApiKey) ||
            $this->xenditApiKey === 'your_xendit_secret_key';
    }

    /**
     * Get payment amount based on jenjang
     */
    private function getPaymentAmount($jenjang)
    {
        $paymentAmounts = [
            'sanggar' => 3250000,
            'kelompok' => 3250000,
            'tka' => 3550000,
            'tkb' => 3550000,
            'sd' => 4250000,
            'smp' => 4550000,
            'sma' => 5250000,
        ];

        return $paymentAmounts[$jenjang] ?? 0;
    }

    /**
     * Get jenjang display name
     */
    private function getJenjangName($jenjang)
    {
        $jenjangNames = [
            'sanggar' => 'Sanggar Bermain',
            'kelompok' => 'Kelompok Bermain',
            'tka' => 'TK A',
            'tkb' => 'TK B',
            'sd' => 'SD',
            'smp' => 'SMP',
            'sma' => 'SMA',
        ];

        return $jenjangNames[$jenjang] ?? strtoupper($jenjang);
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin melihat semua pembayaran
            $pendaftars = Pendaftar::with(['latestPayment'])
                ->select('id', 'nama_murid', 'no_pendaftaran', 'unit', 'jenjang', 'payment_amount', 'sudah_bayar_formulir', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('payment.admin.index', compact('pendaftars'));
        } else {
            // User melihat tagihan sendiri
            $pendaftar = Pendaftar::where('user_id', $user->id)
                ->with(['payments' => function($query) {
                    $query->orderBy('created_at', 'desc');
                }])
                ->first();

            if (!$pendaftar) {
                return redirect()->route('dashboard')
                    ->with('error', 'Data pendaftaran tidak ditemukan.');
            }

            return view('payment.user.index', compact('pendaftar'));
        }
    }

    public function createInvoice(Request $request)
    {
        // Validate input
        $request->validate([
            'pendaftar_id' => 'required|exists:pendaftars,id',
            'amount' => 'required|numeric|min:1000000|max:10000000' // Min 1jt, Max 10jt
        ]);

        // Check if in demo mode
        // Check if in demo mode
        if ($this->isDemoMode()) {
            return $this->createDemoInvoice($request);
        }

        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)
            ->where('id', $request->pendaftar_id)
            ->first();

        if (!$pendaftar) {
            return redirect()->route('payment.index')
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        if ($pendaftar->sudah_bayar_formulir) {
            return redirect()->route('payment.index')
                ->with('info', 'Pembayaran sudah lunas.');
        }

        // Validate amount matches pendaftar's payment amount
        if ($request->amount != $pendaftar->payment_amount) {
            return redirect()->route('payment.index')
                ->with('error', 'Jumlah pembayaran tidak sesuai.');
        }

        try {
            $externalId = 'PPDB-' . $pendaftar->no_pendaftaran . '-' . time();
            $jenjangName = $this->getJenjangName($pendaftar->jenjang);

            // Validasi API Key format
            if (!str_starts_with($this->xenditApiKey, 'xnd_')) {
                throw new \Exception('Invalid Xendit API Key format');
            }

            $response = Http::withBasicAuth($this->xenditApiKey, '')
                ->timeout(30)
                ->post('https://api.xendit.co/v2/invoices', [
                    'external_id' => $externalId,
                    'amount' => $pendaftar->payment_amount,
                    'description' => 'Biaya Pendaftaran PPDB ' . $jenjangName . ' - ' . $pendaftar->unit . ' - ' . $pendaftar->nama_murid,
                    'invoice_duration' => 86400, // 24 hours
                    'customer' => [
                        'given_names' => $pendaftar->nama_murid,
                        'email' => $user->email,
                        'mobile_number' => $this->formatPhoneNumber($pendaftar->telp_ayah),
                        'addresses' => [
                            [
                                'city' => 'Jakarta',
                                'country' => 'Indonesia',
                                'postal_code' => '12345',
                                'state' => 'DKI Jakarta',
                                'street_line1' => $pendaftar->alamat
                            ]
                        ]
                    ],
                    'customer_notification_preference' => [
                        'invoice_created' => ['email'],
                        'invoice_reminder' => ['email'],
                        'invoice_paid' => ['email']
                    ],
                    'success_redirect_url' => route('payment.success'),
                    'failure_redirect_url' => route('payment.failed'),
                    'currency' => 'IDR',
                    'items' => [
                        [
                            'name' => 'Biaya Pendaftaran PPDB ' . $jenjangName,
                            'quantity' => 1,
                            'price' => $pendaftar->payment_amount,
                            'category' => 'Education'
                        ]
                    ],
                    'fees' => [
                        [
                            'type' => 'ADMIN',
                            'value' => 0
                        ]
                    ]
                ]);

            Log::info('Xendit API Response Status: ' . $response->status());
            Log::info('Xendit API Response Body: ' . $response->body());

            if ($response->successful()) {
                $invoiceData = $response->json();

                Payment::create([
                    'pendaftar_id' => $pendaftar->id,
                    'external_id' => $externalId,
                    'invoice_id' => $invoiceData['id'],
                    'invoice_url' => $invoiceData['invoice_url'],
                    'amount' => $pendaftar->payment_amount,
                    'status' => 'PENDING',
                    'xendit_response' => $invoiceData,
                ]);

                return redirect($invoiceData['invoice_url']);
            } else {
                $errorResponse = $response->json();
                Log::error('Xendit Invoice Creation Failed', [
                    'status' => $response->status(),
                    'response' => $errorResponse
                ]);

                $errorMessage = isset($errorResponse['message']) ? $errorResponse['message'] : 'Unknown error';
                return redirect()->route('payment.index')
                    ->with('error', 'Gagal membuat invoice pembayaran: ' . $errorMessage);
            }

        } catch (\Exception $e) {
            Log::error('Payment Invoice Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->route('payment.index')
                ->with('error', 'Terjadi kesalahan saat membuat invoice: ' . $e->getMessage());
        }
    }

    private function createDemoInvoice($request)
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)
            ->where('id', $request->pendaftar_id)
            ->first();

        if (!$pendaftar) {
            return redirect()->route('payment.index')
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        // Validate amount matches pendaftar's payment amount
        if ($request->amount != $pendaftar->payment_amount) {
            return redirect()->route('payment.index')
                ->with('error', 'Jumlah pembayaran tidak sesuai.');
        }

        // Create demo payment record
        $externalId = 'DEMO-' . $pendaftar->no_pendaftaran . '-' . time();

        $payment = Payment::create([
            'pendaftar_id' => $pendaftar->id,
            'external_id' => $externalId,
            'invoice_id' => 'demo_invoice_' . time(),
            'invoice_url' => route('payment.demo', ['external_id' => $externalId]),
            'amount' => $pendaftar->payment_amount,
            'status' => 'PENDING',
            'xendit_response' => [
                'demo' => true,
                'created' => now()->toISOString(),
                'jenjang' => $pendaftar->jenjang,
                'amount' => $pendaftar->payment_amount
            ],
        ]);

        return redirect()->route('payment.demo', ['external_id' => $externalId]);
    }

    public function demo(Request $request, $external_id)
    {
        $payment = Payment::where('external_id', $external_id)
            ->with('pendaftar')
            ->first();

        if (!$payment) {
            return redirect()->route('payment.index')
                ->with('error', 'Payment not found');
        }

        $jenjangName = $this->getJenjangName($payment->pendaftar->jenjang);

        return view('payment.demo', compact('payment', 'jenjangName'));
    }

    public function demoPayment(Request $request, $external_id)
    {
        $payment = Payment::where('external_id', $external_id)->first();

        if (!$payment) {
            return redirect()->route('payment.index')
                ->with('error', 'Payment not found');
        }

        // Simulate successful payment
        $payment->update([
            'status' => 'PAID',
            'paid_at' => now(),
        ]);

        $payment->pendaftar->update([
            'sudah_bayar_formulir' => true
        ]);

        return redirect()->route('payment.success');
    }

    private function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Add +62 if starts with 0
        if (str_starts_with($phone, '0')) {
            $phone = '+62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '+62')) {
            $phone = '+62' . $phone;
        }

        return $phone;
    }

    public function webhook(Request $request)
    {
        $xenditSignature = $request->header('x-callback-token');

        Log::info('Webhook received', [
            'headers' => $request->headers->all(),
            'body' => $request->all()
        ]);

        if ($xenditSignature !== env('XENDIT_WEBHOOK_TOKEN')) {
            Log::warning('Unauthorized webhook attempt', [
                'received_token' => $xenditSignature,
                'expected_token' => env('XENDIT_WEBHOOK_TOKEN')
            ]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->all();

        if ($data['status'] === 'PAID') {
            $payment = Payment::where('external_id', $data['external_id'])->first();

            if ($payment) {
                $payment->update([
                    'status' => 'PAID',
                    'paid_at' => now(),
                    'xendit_response' => $data,
                ]);

                $payment->pendaftar->update([
                    'sudah_bayar_formulir' => true
                ]);

                Log::info('Payment successful for: ' . $data['external_id'], [
                    'amount' => $payment->amount,
                    'pendaftar' => $payment->pendaftar->nama_murid,
                    'jenjang' => $payment->pendaftar->jenjang
                ]);
            } else {
                Log::warning('Payment not found for external_id: ' . $data['external_id']);
            }
        }

        return response()->json(['success' => true]);
    }

    public function success()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        return view('payment.success', compact('pendaftar'));
    }

    public function failed()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        return view('payment.failed', compact('pendaftar'));
    }

    /**
     * Show admin payment details
     */
    public function show($id)
    {
        $pendaftar = Pendaftar::with('payments')->findOrFail($id);
        $jenjangName = $this->getJenjangName($pendaftar->jenjang);

        return view('payment.admin.show', compact('pendaftar', 'jenjangName'));
    }

    /**
     * Manual payment confirmation (Admin only)
     */
    public function confirmPayment(Request $request, $id)
    {
        $pendaftar = Pendaftar::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $pendaftar->update([
            'sudah_bayar_formulir' => true
        ]);

        // Create manual payment record
        Payment::create([
            'pendaftar_id' => $pendaftar->id,
            'external_id' => 'MANUAL-' . $pendaftar->no_pendaftaran . '-' . time(),
            'invoice_id' => 'manual_payment_' . time(),
            'amount' => $pendaftar->payment_amount,
            'status' => 'PAID',
            'paid_at' => now(),
            'xendit_response' => [
                'manual_confirmation' => true,
                'confirmed_by' => Auth::user()->id,
                'confirmed_at' => now()->toISOString()
            ],
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi manual');
    }
}
