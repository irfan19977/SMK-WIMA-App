@forelse ($students as $student)
  <tr>
    <td class="text-center">{{ $loop->iteration }}</td>
    <td>
      @if($student->user_id)
        <a href="{{ route('profile.show') }}?user_id={{ $student->user_id }}" class="text-primary text-decoration-none fw-medium">
          {{ $student->name }}
        </a>
      @else
        {{ $student->name }}
      @endif
    </td>
    <td>{{ $student->nisn ?? '-' }}</td>
    <td>{{ $student->nik ?? '-' }}</td>
    <td>{{ $student->jurusan_utama ?? '-' }}</td>
    <td>{{ $student->jurusan_cadangan ?? '-' }}</td>
    <td>{{ $student->gender ?? '-' }}</td>
    <td>{{ $student->birth_place ?? '-' }}</td>
    <td>{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : '-' }}</td>
    <td>{{ optional($student->user)->phone ?? '-' }}</td>
    <td>
      @php($st = $student->status ?? 'calon siswa')
      <span class="badge rounded badge-soft-{{ $st === 'calon siswa' ? 'primary' : ($st === 'siswa' ? 'success' : 'primary') }} font-size-12">
        {{ $st === 'calon siswa' ? 'Calon siswa' : ($st === 'siswa' ? 'Siswa' : ucfirst($st)) }}
      </span>
    </td>
    <td>
      @isset($student->id)
      @if(($student->status ?? 'calon siswa') === 'calon siswa')
      <button type="button" class="btn btn-success btn-action mr-1 btn-accept" data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-jurusan-utama="{{ $student->jurusan_utama ?? '' }}" data-jurusan-cadangan="{{ $student->jurusan_cadangan ?? '' }}" title="Terima">
        <i class="fas fa-check"></i>
      </button>
      <button type="button" class="btn btn-danger btn-action btn-delete" data-id="{{ $student->id }}" data-name="{{ $student->name }}" title="Tolak">
        <i class="fas fa-times"></i>
      </button>
      @else
      <button type="button" class="btn btn-success btn-action" disabled title="Sudah Diterima">
        <i class="fas fa-check-circle"></i> Diterima
      </button>
      @endif
      @endisset
    </td>
  </tr>
@empty
  <tr>
    <td colspan="12" class="text-center">Tidak ada pendaftar baru</td>
  </tr>
@endforelse
