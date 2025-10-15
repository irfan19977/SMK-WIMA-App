@forelse ($students as $student)
  <tr>
    <td class="text-center">{{ $loop->iteration }}</td>
    <td><a href="#" class="text-secondery font-weight-bold">{{ $student->name }}</a></td>
    <td>{{ $student->nisn ?? '-' }}</td>
    <td>{{ $student->email ?? ($student->user->email ?? '-') }}</td>
    <td>{{ $student->phone ?? ($student->user->phone ?? '-') }}</td>
    <td>
      @php($st = $student->status ?? 'calon siswa')
      <span class="badge {{ $st === 'calon siswa' ? 'badge-warning' : ($st === 'siswa' ? 'badge-success' : 'badge-secondary') }}">
        {{ $st === 'calon siswa' ? 'Calon siswa' : ($st === 'siswa' ? 'Siswa' : ucfirst($st)) }}
      </span>
    </td>
    <td>
      @isset($student->id)
      <a href="{{ route('pendaftaran-siswa.show', $student->id) }}" class="btn btn-info btn-action mr-1" data-toggle="tooltip" title="Detail">
        <i class="fas fa-eye"></i>
      </a>
      @if(($student->status ?? 'calon siswa') === 'calon siswa')
      <button type="button" class="btn btn-success btn-action mr-1 btn-accept" data-id="{{ $student->id }}" title="Terima">
        <i class="fas fa-check"></i>
      </button>
      @endif
      @endisset
    </td>
  </tr>
@empty
  <tr>
    <td colspan="8" class="text-center">Tidak ada pendaftar baru</td>
  </tr>
@endforelse
