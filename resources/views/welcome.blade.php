<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Murid Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0d6efd" />
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid"
                                style="max-width: 150px;">
                        </div>

                        <!-- Judul -->
                        <h1 class="text-center mb-2 text-primary fw-bold">
                            Formulir Pendaftaran Murid Baru
                        </h1>
                        <h5 class="text-center text-muted mb-4">
                            Tahun Ajaran 2026/2027
                        </h5>
                        <p class="text-center fw-semibold text-secondary">
                            Kampus YAPI Al Azhar Rawamangun & Jatimakmur
                        </p>
                        <hr class="my-4">

                        <!-- Form -->
                        <form action="{{ route('pendaftaran.store') }}" method="POST" class="row g-3" enctype="multipart/form-data">
                            @csrf

                            <!-- Nama Calon Murid -->
                            <div class="col-12">
                                <label class="form-label">Nama Calon Murid</label>
                                <input type="text" name="nama_murid" class="form-control" required>
                            </div>

                            <!-- NISN -->
                            <div class="col-md-6">
                                <label class="form-label">NISN (opsional)</label>
                                <input type="text" name="nisn" class="form-control">
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control" required>
                            </div>

                            <!-- Alamat -->
                            <div class="col-12">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" rows="3" class="form-control" required></textarea>
                            </div>

                            <!-- Jenjang -->
                            <div class="col-md-6">
                                <label class="form-label">Pilih Jenjang</label>
                                <select id="jenjang" name="jenjang" class="form-select" required>
                                    <option value="">-- Pilih Jenjang --</option>
                                    <option value="sanggar">Sanggar Bermain</option>
                                    <option value="kelompok">Kelompok Bermain</option>
                                    <option value="tka">TK A</option>
                                    <option value="tkb">TK B</option>
                                    <option value="sd">SD</option>
                                    <option value="smp">SMP</option>
                                    <option value="sma">SMA</option>
                                </select>
                            </div>

                            <!-- Unit Sekolah -->
                            <div class="col-md-6">
                                <label class="form-label">Pilih Unit Sekolah</label>
                                <select id="unit" name="unit" class="form-select" required>
                                    <option value="">-- Pilih Unit Sekolah --</option>
                                </select>
                            </div>

                            <!-- Asal Sekolah -->
                            <div class="col-md-6 d-none" id="asal_sekolah_group">
                                <label class="form-label">Asal Sekolah</label>
                                <select id="asal_sekolah" name="asal_sekolah" class="form-select">
                                    <option value="">-- Asal Sekolah --</option>
                                    <option value="dalam">Dalam</option>
                                    <option value="luar">Luar</option>
                                    <option value="pindahan">Pindahan</option>
                                </select>
                            </div>

                            <!-- Nama Sekolah -->
                            <div class="col-md-6 d-none" id="nama_sekolah_group">
                                <label class="form-label">Nama Sekolah</label>
                                <div id="nama_sekolah_wrapper">
                                    <select id="nama_sekolah" name="nama_sekolah" class="form-select">
                                        <option value="">-- Nama Sekolah --</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Kelas (untuk pindahan) -->
                            <div class="col-md-6 d-none" id="kelas_group">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas" id="kelas" class="form-control">
                            </div>

                            <!-- Nama Ayah -->
                            <div class="col-md-6">
                                <label class="form-label">Nama Ayah</label>
                                <input type="text" name="nama_ayah" class="form-control" required>
                            </div>

                            <!-- No. Telp Ayah -->
                            <div class="col-md-6">
                                <label class="form-label">No. Telp/Whatsapp Ayah</label>
                                <input type="tel" name="telp_ayah" class="form-control" required>
                            </div>

                            <!-- Nama Ibu -->
                            <div class="col-md-6">
                                <label class="form-label">Nama Ibu</label>
                                <input type="text" name="nama_ibu" class="form-control" required>
                            </div>

                            <!-- No. Telp/WA Ibu -->
                            <div class="col-md-6">
                                <label class="form-label">No. Telp/Whatsapp Ibu</label>
                                <input type="tel" name="telp_ibu" class="form-control" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Foto Calon Murid (3x4)</label>
                                <input type="file" name="foto_murid" id="foto_murid" class="form-control"
                                    accept="image/*" required>
                                <div class="mt-2">
                                    <img id="preview_foto" src="" alt="Preview Foto" class="border rounded"
                                        style="width: 113px; height: 151px; object-fit: cover; display: none;">
                                    <!-- 3x4 cm kira2 = 113x151 px -->
                                </div>
                            </div>

                            <!-- Akta Kelahiran -->
                            <div class="col-md-6">
                                <label class="form-label">Upload Akta Kelahiran</label>
                                <input type="file" name="akta_kelahiran" class="form-control"
                                    accept=".jpg,.jpeg,.png,.pdf" required>
                            </div>

                            <!-- Kartu Keluarga -->
                            <div class="col-md-6">
                                <label class="form-label">Upload Kartu Keluarga</label>
                                <input type="file" name="kartu_keluarga" class="form-control"
                                    accept=".jpg,.jpeg,.png,.pdf" required>
                            </div>

                            <!-- Tombol -->
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm">
                                    Daftar Sekarang
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Worker untuk PWA -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').then(() => {
                console.log("Service Worker registered!");
            });
        }

        const jenjangSelect = document.getElementById("jenjang");
        const unitSelect = document.getElementById("unit");
        const asalSekolahGroup = document.getElementById("asal_sekolah_group");
        const asalSekolahSelect = document.getElementById("asal_sekolah");
        const namaSekolahGroup = document.getElementById("nama_sekolah_group");
        const namaSekolahWrapper = document.getElementById("nama_sekolah_wrapper");
        const kelasGroup = document.getElementById("kelas_group");

        const options = {
            sanggar: ["Playgroup Sakinah - Rawamangun", "RA Sakinah - Kebayoran"],
            kelompok: ["Playgroup Sakinah - Rawamangun", "RA Sakinah - Kebayoran"],
            tka: ["TK Islam Al Azhar 13 - Rawamangun"],
            tkb: ["TK Islam Al Azhar 13 - Rawamangun"],
            sd: ["SD Islam Al Azhar 13 - Rawamangun"],
            smp: ["SMP Islam Al Azhar 12 - Rawamangun", "SMP Islam Al Azhar 55 - Jatimakmur"],
            sma: ["SMA Islam Al Azhar 33 - Jatimakmur"]
        };

        jenjangSelect.addEventListener("change", function() {
            const selected = this.value;
            unitSelect.innerHTML = '<option value="">-- Pilih Unit Sekolah --</option>';
            asalSekolahGroup.classList.add("d-none");
            namaSekolahGroup.classList.add("d-none");
            kelasGroup.classList.add("d-none");

            if (options[selected]) {
                options[selected].forEach(unit => {
                    const opt = document.createElement("option");
                    opt.value = unit;
                    opt.textContent = unit;
                    unitSelect.appendChild(opt);
                });
            }

            if (["tka", "tkb", "sd", "smp", "sma"].includes(selected)) {
                asalSekolahGroup.classList.remove("d-none");
            }
        });

        asalSekolahSelect.addEventListener("change", function() {
            const selectedAsal = this.value;
            const selectedJenjang = jenjangSelect.value;
            namaSekolahGroup.classList.remove("d-none");
            namaSekolahWrapper.innerHTML = "";

            if (selectedAsal === "dalam") {
                const select = document.createElement("select");
                select.name = "nama_sekolah";
                select.className = "form-select";

                let sekolahDalam = [];
                if (selectedJenjang === "sd") {
                    sekolahDalam = ["TK Islam Al Azhar 13 - Rawamangun"];
                } else if (selectedJenjang === "smp") {
                    sekolahDalam = ["SD Islam Al Azhar 13 - Rawamangun"];
                } else if (selectedJenjang === "sma") {
                    sekolahDalam = ["SMP Islam Al Azhar 12 - Rawamangun", "SMP Islam Al Azhar 55 - Jatimakmur"];
                } else {
                    sekolahDalam = ["Playgroup Sakinah", "RA Sakinah"];
                }

                sekolahDalam.forEach(school => {
                    const opt = document.createElement("option");
                    opt.value = school;
                    opt.textContent = school;
                    select.appendChild(opt);
                });

                namaSekolahWrapper.appendChild(select);
                kelasGroup.classList.add("d-none");
            } else {
                const input = document.createElement("input");
                input.type = "text";
                input.name = "nama_sekolah";
                input.className = "form-control";
                namaSekolahWrapper.appendChild(input);

                if (selectedAsal === "pindahan") {
                    kelasGroup.classList.remove("d-none");
                } else {
                    kelasGroup.classList.add("d-none");
                }
            }
        });
    </script>

    <script>
        const fotoInput = document.getElementById("foto_murid");
        const previewFoto = document.getElementById("preview_foto");

        fotoInput.addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewFoto.src = e.target.result;
                    previewFoto.style.display = "block";
                };
                reader.readAsDataURL(file);
            } else {
                previewFoto.src = "";
                previewFoto.style.display = "none";
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
