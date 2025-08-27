<x-app-layout>
    <div class="container-fluid py-4" style="background: #f8f9fa; min-height: 100vh;">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <!-- Formal Registration Card -->
                <div class="registration-card">
                    <!-- Header Section -->
                    <div class="card-header-formal">
                        <div class="row align-items-center">
                            <div class="col-2">
                                <div class="logo-official">
                                    <img src="{{ asset('images/logo.png') }}" alt="Logo YAPI" class="logo-formal">
                                </div>
                            </div>
                            <div class="col-10 text-center">
                                <h4 class="institution-name">YAYASAN ASRAMA PELAJAR ISLAM</h4>
                                <h5 class="school-name">{{ $pendaftar->unit }}</h5>
                                <p class="address-text">Jl. Sunan Giri No. 1, Rawamangun, Jakarta Timur</p>
                                <p class="contact-text">Telp: (021) 7984-5555 | Email: info@yapi.sch.id | https://yapi.sch.id</p>
                            </div>
                        </div>
                        <hr class="divider-line">
                        <div class="card-title-section">
                            <h3 class="card-title">KARTU PENDAFTARAN CALON MURID BARU</h3>
                            <p class="academic-year">TAHUN AJARAN 2026/2027</p>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="card-content-formal">
                        <div class="row">
                            <!-- Left Column - Student Data -->
                            <div class="col-8">
                                <table class="data-table">
                                    <tr>
                                        <td class="label-col">No. Pendaftaran</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col"><strong>{{ $pendaftar->no_pendaftaran }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Nama Lengkap</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col"><strong>{{ strtoupper($pendaftar->nama_murid) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">NISN</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col">{{ $pendaftar->nisn ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Tanggal Lahir</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col">{{ \Carbon\Carbon::parse($pendaftar->tanggal_lahir)->locale('id')->translatedFormat('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Jenjang</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col">{{ strtoupper($pendaftar->jenjang) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Asal Sekolah</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col">{{ $pendaftar->nama_sekolah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Alamat</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col">{{ $pendaftar->alamat }}</td>
                                    </tr>
                                </table>

                                <!-- Parent Information -->
                                <div class="parent-section-formal">
                                    <h6 class="section-title">DATA ORANG TUA/WALI</h6>
                                    <table class="data-table">
                                        <tr>
                                            <td class="label-col">Nama Ayah</td>
                                            <td class="colon-col">:</td>
                                            <td class="value-col">{{ $pendaftar->nama_ayah }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-col">No. Telepon Ayah</td>
                                            <td class="colon-col">:</td>
                                            <td class="value-col">{{ $pendaftar->telp_ayah }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-col">Nama Ibu</td>
                                            <td class="colon-col">:</td>
                                            <td class="value-col">{{ $pendaftar->nama_ibu }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-col">No. Telepon Ibu</td>
                                            <td class="colon-col">:</td>
                                            <td class="value-col">{{ $pendaftar->telp_ibu }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Status and Date -->
                                <div class="status-section-formal">
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="status-text">
                                                <strong>Status: </strong>
                                                <span class="status-badge status-{{ $pendaftar->status }}">
                                                    {{ strtoupper($pendaftar->status) }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-6 text-end">
                                            <p class="registration-date">
                                                Terdaftar: {{ $pendaftar->created_at->locale('id')->translatedFormat('d F Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Photo -->
                            <div class="col-4 text-center">
                                <div class="photo-section-formal">
                                    <div class="photo-frame-formal">
                                        <img src="{{ asset('storage/' . ($pendaftar->foto_murid_path ?? 'images/default-foto.jpg')) }}"
                                             alt="Foto Calon Murid"
                                             class="student-photo-formal">
                                    </div>
                                    <p class="photo-label">Foto 3x4 cm</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Section -->
                    <div class="card-footer-formal">
                        <div class="row">
                            <div class="col-8">
                                <div class="notes-section">
                                    <h6 class="notes-title">CATATAN PENTING:</h6>
                                    <ul class="notes-list">
                                        <li>Kartu ini wajib dibawa saat verifikasi dokumen</li>
                                        <li>Harap datang tepat waktu sesuai jadwal yang ditentukan</li>
                                        <li>Lengkapi berkas pendaftaran sebelum batas waktu</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="signature-section">
                                    <p class="signature-place">Jakarta, {{ now()->locale('id')->translatedFormat('d F Y') }}</p>
                                    <p class="signature-title">Panitia PMB</p>
                                    <div class="signature-space"></div>
                                    <p class="signature-name">{{ auth()->user()->name ?? 'Admin PMB' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons (Hidden in Print) -->
                <div class="action-buttons">
                    <button class="btn btn-success btn-lg me-3" onclick="downloadPDF()" id="pdfBtn">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Reset and Base Styles */
        .registration-card {
            background: white;
            border: 2px solid #000;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: 20px 0;
            font-family: 'Times New Roman', serif;
            page-break-inside: avoid;
        }

        /* Header Styles */
        .card-header-formal {
            padding: 20px 30px 15px;
            border-bottom: 3px double #000;
        }

        .logo-official {
            text-align: center;
        }

        .logo-formal {
            height: 80px;
            width: auto;
        }

        .institution-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            color: #000;
            text-transform: uppercase;
        }

        .school-name {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
            color: #000;
            text-transform: uppercase;
        }

        .address-text, .contact-text {
            font-size: 12px;
            margin: 2px 0;
            color: #333;
        }

        .qr-code-section {
            border: 1px solid #000;
            padding: 10px;
            background: #f9f9f9;
        }

        .qr-label {
            display: block;
            font-size: 10px;
            margin-top: 5px;
            font-weight: bold;
        }

        .divider-line {
            border: 1px solid #000;
            margin: 15px 0 10px 0;
        }

        .card-title-section {
            text-align: center;
            margin: 10px 0;
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
            color: #000;
            text-decoration: underline;
        }

        .academic-year {
            font-size: 14px;
            margin: 5px 0 0 0;
            font-weight: bold;
            color: #000;
        }

        /* Content Styles */
        .card-content-formal {
            padding: 20px 30px;
        }

        .data-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .data-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .label-col {
            width: 35%;
            font-weight: bold;
            color: #000;
            font-size: 14px;
        }

        .colon-col {
            width: 5%;
            text-align: center;
            font-weight: bold;
        }

        .value-col {
            width: 60%;
            color: #000;
            font-size: 14px;
        }

        .parent-section-formal {
            margin: 25px 0 20px 0;
            padding-top: 15px;
            border-top: 1px solid #ccc;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #000;
            text-decoration: underline;
        }

        .status-section-formal {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
        }

        .status-text {
            font-size: 14px;
            margin: 0;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-valid {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-invalid {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .registration-date {
            font-size: 12px;
            margin: 0;
            color: #666;
        }

        /* Photo Styles */
        .photo-section-formal {
            margin-top: 20px;
        }

        .photo-frame-formal {
            border: 2px solid #000;
            width: 120px;
            height: 160px;
            margin: 0 auto 10px;
            overflow: hidden;
            background: #f9f9f9;
        }

        .student-photo-formal {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-label {
            font-size: 12px;
            margin: 0;
            font-weight: bold;
            color: #000;
        }

        /* Footer Styles */
        .card-footer-formal {
            padding: 20px 30px;
            border-top: 2px solid #000;
            background: #f9f9f9;
        }

        .notes-section {
            margin-bottom: 10px;
        }

        .notes-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #000;
        }

        .notes-list {
            margin: 0;
            padding-left: 20px;
            font-size: 12px;
            color: #333;
        }

        .notes-list li {
            margin-bottom: 3px;
        }

        .signature-section {
            text-align: center;
        }

        .signature-place, .signature-title, .signature-name {
            font-size: 12px;
            margin: 5px 0;
            color: #000;
        }

        .signature-title {
            font-weight: bold;
        }

        .signature-space {
            height: 60px;
            border-bottom: 1px solid #000;
            margin: 10px 20px;
        }

        .signature-name {
            font-weight: bold;
        }

        /* Action Buttons */
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }

        /* Print Styles - FIXED untuk 1 halaman */
        @media print {
            * {
                box-sizing: border-box;
            }

            body {
                background: white !important;
                -webkit-print-color-adjust: exact !important;
                margin: 0 !important;
                padding: 0 !important;
                font-size: 12px !important;
            }

            @page {
                size: A4;
                margin: 2cm !important; /* Kurangi margin untuk muat 1 halaman */
            }

            .container-fluid,
            .row,
            .col-lg-8,
            .col-xl-6 {
                all: unset !important;
                display: block !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .action-buttons {
                display: none !important;
            }

            .registration-card {
                box-shadow: none !important;
                margin: 0 !important;
                border: 2px solid #000 !important;
                width: 100% !important;
                position: static !important;
                transform: scale(0.85) !important; /* Scale down untuk muat 1 halaman */
                transform-origin: top left !important;
            }

            /* Kurangi padding untuk hemat space */
            .card-header-formal {
                padding: 15px 20px 10px !important;
            }

            .card-content-formal {
                padding: 15px 20px !important;
            }

            .card-footer-formal {
                padding: 15px 20px !important;
            }

            /* Kurangi font size sedikit */
            .institution-name {
                font-size: 16px !important;
            }

            .school-name {
                font-size: 14px !important;
            }

            .card-title {
                font-size: 18px !important;
            }

            .data-table td {
                padding: 3px 0 !important;
            }

            .logo-formal {
                height: 60px !important;
            }

            .photo-frame-formal {
                width: 100px !important;
                height: 130px !important;
            }

            /* Hide semua kecuali kartu */
            body * {
                visibility: hidden;
            }

            .registration-card,
            .registration-card * {
                visibility: visible;
            }
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Print Function - FIXED
            window.printCard = function() {
                const printBtn = document.getElementById('printBtn');
                const originalText = printBtn.innerHTML;

                printBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyiapkan...';
                printBtn.disabled = true;

                setTimeout(() => {
                    window.print();
                    printBtn.innerHTML = originalText;
                    printBtn.disabled = false;
                }, 1000);
            };

            // Download PDF Function - FIXED
            window.downloadPDF = function() {
                const pdfBtn = document.getElementById('pdfBtn');
                const originalText = pdfBtn.innerHTML;

                pdfBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Membuat PDF...';
                pdfBtn.disabled = true;

                // Ambil element kartu langsung
                const element = document.querySelector('.registration-card');

                const opt = {
                    margin: [0.5, 0.5, 0.5, 0.5], // Margin kecil dalam inch
                    filename: 'Kartu_Pendaftaran_{{ $pendaftar->no_pendaftaran }}.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
                        useCORS: true,
                        allowTaint: true,
                        backgroundColor: '#ffffff',
                        logging: true,
                        letterRendering: true,
                        scrollX: 0,
                        scrollY: 0
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'a4',
                        orientation: 'portrait'
                    }
                };

                // Generate PDF langsung dari element
                html2pdf().set(opt).from(element).save().then(() => {
                    pdfBtn.innerHTML = originalText;
                    pdfBtn.disabled = false;
                }).catch((error) => {
                    console.error('PDF generation error:', error);
                    pdfBtn.innerHTML = originalText;
                    pdfBtn.disabled = false;
                    alert('Terjadi kesalahan saat membuat PDF. Silakan coba lagi.');
                });
            };

            // Validate Button
            const validateBtn = document.getElementById('validateBtn');
            if (validateBtn) {
                validateBtn.addEventListener('click', function(e) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memvalidasi...';
                    this.disabled = true;
                });
            }
        });
    </script>
</x-app-layout>
