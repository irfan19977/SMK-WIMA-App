@forelse ($students as $student)
<tr>
    <th scope="row">{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</th>
    <td>
        <a href="{{ route('profile.show') }}?user_id={{ $student->user_id }}" class="text-decoration-none fw-bold text-primary">
            {{ $student->name }}
        </a>
    </td>
    <td>{{ $student->nisn ?? '-' }}</td>
    <td>{{ $student->user->email ?? '-' }}</td>
    <td>{{ $student->user->phone ?? '-' }}</td>
    <td>{{ $student->no_card ?? '-' }}</td>
    <td>
        <span class="badge rounded-pill {{ $student->status == 'siswa' ? 'bg-success' : 'bg-warning' }} font-size-12">
            {{ $student->status == 'siswa' ? 'Siswa' : 'Calon Siswa' }}
        </span>
    </td>
    <td>
        <div class="d-flex gap-1">
            <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                <i class="mdi mdi-eye"></i>
            </a>
            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-warning" title="Edit">
                <i class="mdi mdi-pencil"></i>
            </a>
            <button type="button" class="btn btn-sm btn-danger btn-delete" 
                    data-id="{{ $student->id }}" 
                    data-name="{{ $student->name }}" 
                    title="Hapus">
                <i class="mdi mdi-delete"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center">{{ __('index.no_data_available') }}</td>
</tr>
@endforelse
