@forelse($studentsData as $student)
<tr class="align-middle student-row"
    data-academic-year="{{ $student->academic_year ?? '2026/2027' }}"
    data-nama="{{ strtolower($student->nama_murid) }}"
    data-nisn="{{ strtolower($student->nisn ?? '') }}"
    data-no-pendaftaran="{{ strtolower($student->no_pendaftaran) }}"
    data-unit="{{ strtolower($student->unit) }}"
    data-status="{{ $student->student_status }}">
    <td class="px-3">
        <div class="form-check">
            <input class="form-check-input row-checkbox" type="checkbox" value="{{ $student->id }}" id="check{{ $student->id }}">
            <label class="form-check-label visually-hidden" for="check{{ $student->id }}">Select</label>
        </div>
    </td>

    <td class="px-3">
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                <i class="bi bi-person text-primary"></i>
            </div>
            <div>
                <div class="fw-semibold text-dark">{{ $student->nama_murid }}</div>
                @if($student->user)
                    <small class="text-muted">{{ $student->user->email }}</small>
                @endif
                <br>
                <small class="text-muted">{{ $student->jenjang }}</small>
            </div>
        </div>
    </td>

    <td class="px-3">
        @if($student->nisn)
            <span class="badge bg-info bg-opacity-10 text-info border border-info fw-normal">
                {{ $student->nisn }}
            </span>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>

    <td class="px-3">
        <code class="bg-light px-2 py-1 rounded fw-bold">{{ $student->no_pendaftaran }}</code>
    </td>

    <td class="px-3">
        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">
            <i class="bi bi-building me-1"></i>
            {{ strtoupper($student->unit) }}
        </span>
    </td>

    <td class="px-3">
        <div class="text-center">
            <div class="fw-bold text-primary">{{ \Carbon\Carbon::parse($student->tanggal_lahir)->format('d/m/Y') }}</div>
            <small class="text-muted">{{ \Carbon\Carbon::parse($student->tanggal_lahir)->age }} tahun</small>
        </div>
    </td>

    <td class="px-3">
        @php
            $statusConfig = [
                'active' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'Aktif'],
                'inactive' => ['class' => 'warning', 'icon' => 'pause-circle', 'text' => 'Tidak Aktif'],
                'graduated' => ['class' => 'info', 'icon' => 'mortarboard', 'text' => 'Lulus'],
                'dropped_out' => ['class' => 'danger', 'icon' => 'x-circle', 'text' => 'Keluar'],
                'transferred' => ['class' => 'secondary', 'icon' => 'arrow-right-circle', 'text' => 'Pindah'],
            ];

            $currentStatus = $statusConfig[$student->student_status] ?? $statusConfig['inactive'];
        @endphp

        <span class="badge bg-{{ $currentStatus['class'] }} bg-opacity-10 text-{{ $currentStatus['class'] }} border border-{{ $currentStatus['class'] }}">
            <i class="bi bi-{{ $currentStatus['icon'] }} me-1"></i>
            {{ $currentStatus['text'] }}
        </span>

        @if($student->student_status_notes)
            <div class="mt-1">
                <small class="text-muted" title="{{ $student->student_status_notes }}">
                    <i class="bi bi-info-circle"></i> Ada catatan
                </small>
            </div>
        @endif

        @if($student->student_activated_at)
            <div class="mt-1">
                <small class="text-muted">
                    Aktif: {{ \Carbon\Carbon::parse($student->student_activated_at)->format('d/m/Y') }}
                </small>
            </div>
        @endif
    </td>

    <td class="px-3 text-center">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="updateStatus({{ $student->id }})" title="Update Status">
                <i class="bi bi-diagram-3"></i>
            </button>
            <a href="{{ route('admin.data-siswa.detail', $student->id) }}" class="btn btn-outline-info btn-sm" title="Lihat Detail">
                <i class="bi bi-eye"></i>
            </a>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center py-5">
        <div class="text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <h5>Tidak ada data siswa</h5>
            <p class="mb-0">Belum ada siswa yang sesuai dengan filter yang dipilih.</p>
        </div>
    </td>
</tr>
@endforelse
