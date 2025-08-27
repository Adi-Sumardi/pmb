{{-- filepath: /Users/yapi/project/ppdb-backend/resources/views/payment/demo.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Payment Gateway - PPDB YAPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .demo-container {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .payment-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
            position: relative;
        }

        .payment-header {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
            color: #2d3748;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .payment-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 154, 158, 0.8) 0%, rgba(254, 207, 239, 0.8) 100%);
            backdrop-filter: blur(10px);
        }

        .payment-header-content {
            position: relative;
            z-index: 2;
        }

        .demo-badge {
            background: linear-gradient(45deg, #ff6b6b, #ffa500);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 8px 15px rgba(255, 107, 107, 0.3);
            animation: pulse-glow 2s infinite;
        }

        @keyframes pulse-glow {
            0% { box-shadow: 0 8px 15px rgba(255, 107, 107, 0.3); }
            50% { box-shadow: 0 8px 25px rgba(255, 107, 107, 0.5); }
            100% { box-shadow: 0 8px 15px rgba(255, 107, 107, 0.3); }
        }

        .payment-amount {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .status-badge {
            border-radius: 50px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: linear-gradient(45deg, #ffd93d, #ff9a56);
            color: #333;
        }

        .status-paid {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .status-failed {
            background: linear-gradient(45deg, #ff6b6b, #ff5722);
            color: white;
        }

        .btn-payment {
            border-radius: 50px;
            padding: 1rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-success-custom {
            background: linear-gradient(45deg, #56ab2f, #a8e6cf);
            color: white;
        }

        .btn-success-custom:hover {
            background: linear-gradient(45deg, #4a9025, #96d4b8);
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(86, 171, 47, 0.3);
        }

        .btn-danger-custom {
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
            color: white;
        }

        .btn-danger-custom:hover {
            background: linear-gradient(45deg, #e03858, #e0431f);
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(255, 65, 108, 0.3);
        }

        .btn-back {
            background: linear-gradient(45deg, #6c757d, #495057);
            color: white;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: linear-gradient(45deg, #5a6268, #3d4043);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(108, 117, 125, 0.3);
            color: white;
        }

        .detail-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .detail-item {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 1rem;
            transition: background 0.3s ease;
        }

        .detail-item:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #6c757d;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            color: #2d3748;
            font-weight: 500;
            margin-top: 0.25rem;
        }

        .alert-info-custom {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 1px solid #2196f3;
            border-radius: 15px;
            color: #1976d2;
        }

        .alert-warning-custom {
            background: linear-gradient(135deg, #fff3e0 0%, #ffcc02 100%);
            border: 1px solid #ff9800;
            border-radius: 15px;
            color: #f57c00;
        }

        .floating-icons {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1;
        }

        .floating-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 10px;
            animation: float 3s ease-in-out infinite;
        }

        .floating-icon:nth-child(2) {
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .payment-steps {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 2rem 0;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 10px;
            position: relative;
        }

        .step.active {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .step::after {
            content: '';
            position: absolute;
            right: -25px;
            width: 20px;
            height: 2px;
            background: #e9ecef;
            z-index: -1;
        }

        .step:last-child::after {
            display: none;
        }

        .step.active::after {
            background: linear-gradient(45deg, #667eea, #764ba2);
        }
    </style>
</head>
<body>
    <div class="container-fluid py-5">
        <!-- Floating Icons -->
        <div class="floating-icons">
            <div class="floating-icon">
                <i class="bi bi-credit-card"></i>
            </div>
            <div class="floating-icon">
                <i class="bi bi-shield-check"></i>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="demo-container p-4">
                    <div class="payment-card">
                        <!-- Header -->
                        <div class="payment-header">
                            <div class="payment-header-content">
                                <span class="demo-badge mb-3 d-inline-block">
                                    🧪 Demo Mode
                                </span>
                                <h2 class="mb-2 fw-bold">Payment Gateway Simulation</h2>
                                <p class="mb-0 opacity-75">Testing Environment - PPDB YAPI</p>
                            </div>
                        </div>

                        <!-- Payment Steps -->
                        <div class="payment-steps">
                            <div class="step active">1</div>
                            <div class="step active">2</div>
                            <div class="step">3</div>
                        </div>

                        <div class="p-4">
                            <!-- Alert Info -->
                            <div class="alert alert-warning-custom d-flex align-items-center mb-4" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Demo Payment Active</h6>
                                    <small class="mb-0">This is a simulation of Xendit payment gateway for testing purposes only.</small>
                                </div>
                            </div>

                            <!-- Payment Amount Display -->
                            <div class="text-center mb-4 p-4 bg-light rounded-4">
                                <h6 class="text-muted mb-2">Total Amount</h6>
                                <div class="payment-amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                <small class="text-muted">Registration Fee for {{ $jenjangName }}</small>
                            </div>

                            <!-- Payment Details -->
                            <div class="detail-card mb-4">
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-person-circle me-2"></i>Student Name
                                    </div>
                                    <div class="detail-value">{{ $payment->pendaftar->nama_murid }}</div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-hash me-2"></i>Registration Number
                                    </div>
                                    <div class="detail-value">
                                        <span class="badge bg-primary rounded-pill">{{ $payment->pendaftar->no_pendaftaran }}</span>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-mortarboard me-2"></i>Education Level
                                    </div>
                                    <div class="detail-value">{{ $jenjangName }}</div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-building me-2"></i>School Unit
                                    </div>
                                    <div class="detail-value">{{ $payment->pendaftar->unit }}</div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-calendar-event me-2"></i>Created Date
                                    </div>
                                    <div class="detail-value">{{ $payment->created_at->format('d M Y, H:i') }}</div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-flag me-2"></i>Payment Status
                                    </div>
                                    <div class="detail-value">
                                        @if($payment->status === 'PENDING')
                                            <span class="status-badge status-pending">
                                                <i class="bi bi-clock me-1"></i>{{ $payment->status }}
                                            </span>
                                        @elseif($payment->status === 'PAID')
                                            <span class="status-badge status-paid">
                                                <i class="bi bi-check-circle me-1"></i>{{ $payment->status }}
                                            </span>
                                        @else
                                            <span class="status-badge status-failed">
                                                <i class="bi bi-x-circle me-1"></i>{{ $payment->status }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-code-square me-2"></i>External ID
                                    </div>
                                    <div class="detail-value">
                                        <code class="bg-light p-1 rounded">{{ $payment->external_id }}</code>
                                    </div>
                                </div>
                            </div>

                            @if($payment->status === 'PENDING')
                                <!-- Action Buttons -->
                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <form action="{{ route('payment.demo.pay', $payment->external_id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="success">
                                            <button type="submit" class="btn btn-payment btn-success-custom w-100 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                                                <div>
                                                    <div>Simulate Successful Payment</div>
                                                    <small class="opacity-75">Complete transaction</small>
                                                </div>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="col-12">
                                        <form action="{{ route('payment.demo.pay', $payment->external_id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="failed">
                                            <button type="submit" class="btn btn-payment btn-danger-custom w-100 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-x-circle-fill me-2 fs-5"></i>
                                                <div>
                                                    <div>Simulate Failed Payment</div>
                                                    <small class="opacity-75">Test error handling</small>
                                                </div>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <!-- Payment Already Processed -->
                                <div class="alert alert-info-custom d-flex align-items-center mb-4" role="alert">
                                    <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">Payment Already Processed</h6>
                                        <div class="mb-0">
                                            This payment has already been <strong>{{ strtolower($payment->status) }}</strong>.
                                            @if($payment->paid_at)
                                                <br><small>Processed at: {{ $payment->paid_at->format('d M Y, H:i:s') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Back Button -->
                            <div class="text-center">
                                <a href="{{ route('payment.index') }}" class="btn-back d-inline-flex align-items-center">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Back to Payment Dashboard
                                </a>
                            </div>

                            <!-- Footer Note -->
                            <div class="text-center mt-4">
                                <small class="text-muted">
                                    <i class="bi bi-shield-lock me-1"></i>
                                    In production, this will redirect to the actual Xendit payment page
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to detail items
            const detailItems = document.querySelectorAll('.detail-item');
            detailItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(10px)';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });

            // Add loading effect to buttons
            const buttons = document.querySelectorAll('.btn-payment');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    const originalClass = icon.className;
                    icon.className = 'bi bi-hourglass-split me-2 fs-5';

                    setTimeout(() => {
                        icon.className = originalClass;
                    }, 1000);
                });
            });
        });
    </script>
</body>
</html>
