@forelse($studentsData as $student)
<tr class="align-middle">
    <td>
        <div class="fw-semibold text-primary">{{ $student->no_pendaftaran }}</div>
    </td>

    <td>
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                <i class="bi bi-person text-primary"></i>
            </div>
            <div>
                <div class="fw-semibold">{{ $student->nama_murid }}</div>
                @if($student->user)
                    <small class="text-muted">{{ $student->user->email }}</small>
                @endif
            </div>
        </div>
    </td>

    <td>
        <span class="badge bg-info bg-opacity-10 text-info border border-info">
            <i class="bi bi-building me-1"></i>
            {{ strtoupper($student->unit) }}
        </span>
    </td>

    <td>
        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">
            {{ $student->jenjang }}
        </span>
    </td>

    <td>
        @if($student->status === 'diverifikasi')
            <span class="badge bg-success">
                <i class="bi bi-check-circle me-1"></i>
                Diverifikasi
            </span>
        @elseif($student->status === 'pending')
            <span class="badge bg-warning">
                <i class="bi bi-clock me-1"></i>
                Pending
            </span>
        @else
            <span class="badge bg-secondary">
                <i class="bi bi-question-circle me-1"></i>
                {{ ucfirst($student->status) }}
            </span>
        @endif
    </td>

    <td>
        @if($student->overall_status === 'Lulus')
            <span class="badge bg-success">
                <i class="bi bi-trophy me-1"></i>
                Lulus
            </span>
        @elseif($student->overall_status === 'Tidak Lulus')
            <span class="badge bg-danger">
                <i class="bi bi-x-circle me-1"></i>
                Tidak Lulus
            </span>
        @elseif($student->overall_status === 'Proses')
            <span class="badge bg-warning">
                <i class="bi bi-gear me-1"></i>
                Proses
            </span>
        @else
            <span class="badge bg-secondary">
                <i class="bi bi-dash me-1"></i>
                {{ $student->overall_status ?? 'Belum Ditentukan' }}
            </span>
        @endif
    </td>

    <td>
        @if($student->sudah_bayar_formulir)
            <span class="badge bg-success">
                <i class="bi bi-check-circle me-1"></i>
                Lunas
            </span>
        @else
            <span class="badge bg-warning">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Belum Bayar
            </span>
        @endif
    </td>

    <td>
        <div class="text-muted small">
            <i class="bi bi-calendar me-1"></i>
            {{ $student->created_at->format('d M Y') }}
        </div>
        <div class="text-muted smaller">
            {{ $student->created_at->format('H:i') }}
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center py-5">
        <div class="d-flex flex-column align-items-center">
            <div class="bg-light rounded-circle p-3 mb-3">
                <i class="bi bi-people text-muted fs-1"></i>
            </div>
            <h6 class="text-muted mb-1">Tidak ada data siswa</h6>
            <p class="text-muted small mb-0">
                @if($search ?? false)
                    Tidak ditemukan siswa dengan kata kunci "{{ $search }}"
                @else
                    Belum ada siswa yang mendaftar untuk unit ini
                @endif
            </p>
        </div>
    </td>
</tr>
@endforelse

<style>
.smaller {
    font-size: 0.7rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375em 0.75em;
}

.table td {
    vertical-align: middle;
}

.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}
</style>
