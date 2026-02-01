@extends('layouts.app')

@section('content')

<section class="section">
  <div class="card">
    <div class="card-header">
      <h4>Manajemen Semester</h4>
      <div class="card-header-action">
        <button type="button" class="btn btn-info" onclick="refreshSemester()">
          <i class="fas fa-sync"></i> Refresh Auto-Detect
        </button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#generateModal">
          <i class="fas fa-magic"></i> Generate Otomatis
        </button>
        <a href="{{ route('semester.create') }}" class="btn btn-primary">
          <i class="fas fa-plus"></i> Tambah Semester
        </a>
      </div>
    </div>

    <div class="card-body">
      @php
        $currentSemester = \App\Models\Semester::getCurrentActiveSemester();
        $currentDate = now()->format('d M Y');
      @endphp
      
      @if($currentSemester)
        <div class="alert alert-info">
          <strong>Semester Aktif Saat Ini:</strong> {{ $currentSemester->display_name }} 
          <small class="text-muted">({{ $currentDate }})</small><br>
          <small>Berlaku: {{ $currentSemester->start_date->format('d M Y') }} - {{ $currentSemester->end_date->format('d M Y') }}</small>
        </div>
      @else
        <div class="alert alert-warning">
          <strong>Perhatian!</strong> Tidak ada semester aktif untuk tanggal saat ini ({{ $currentDate }}).
          Silakan generate semester atau aktifkan semester yang sesuai.
        </div>
      @endif

      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>No.</th>
              <th>Tahun Akademik</th>
              <th>Semester</th>
              <th>Tanggal Mulai</th>
              <th>Tanggal Selesai</th>
              <th>Durasi</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($semesters as $index => $semester)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $semester->academic_year }}</td>
                <td>
                  <span class="badge badge-{{ $semester->semester_type === 'ganjil' ? 'info' : 'warning' }}">
                    {{ ucfirst($semester->semester_type) }}
                  </span>
                </td>
                <td>{{ $semester->start_date->format('d M Y') }}</td>
                <td>{{ $semester->end_date->format('d M Y') }}</td>
                <td>{{ $semester->duration_days }} hari</td>
                <td>
                  @if($semester->is_active)
                    <span class="badge badge-success">Aktif</span>
                  @elseif($semester->isCurrentlyActive())
                    <span class="badge badge-primary">Seharusnya Aktif</span>
                  @else
                    <span class="badge badge-secondary">Tidak Aktif</span>
                  @endif
                </td>
                <td>
                  <div class="btn-group">
                    @if(!$semester->is_active)
                      <a href="{{ route('semester.set-active', $semester->id) }}" 
                         class="btn btn-sm btn-success" 
                         title="Aktifkan Semester"
                         onclick="return confirm('Aktifkan semester ini?')">
                        <i class="fas fa-check"></i>
                      </a>
                    @endif
                    <a href="{{ route('semester.edit', $semester->id) }}" 
                       class="btn btn-sm btn-info" 
                       title="Edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('semester.destroy', $semester->id) }}" 
                          method="POST" 
                          style="display: inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" 
                              class="btn btn-sm btn-danger" 
                              title="Hapus"
                              onclick="return confirm('Hapus semester ini?')">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center">Belum ada data semester</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<!-- Generate Modal -->
<div class="modal fade" id="generateModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('semester.generate') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Generate Semester Otomatis</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Tahun Akademik</label>
            <select name="academic_year" class="form-control" required>
              @php
                $academicYears = \App\Helpers\AcademicYearHelper::generateAcademicYears(0, 3);
              @endphp
              @foreach($academicYears as $year)
                <option value="{{ $year }}">{{ $year }}</option>
              @endforeach
            </select>
          </div>
          <div class="alert alert-info">
            <strong>Info Generate:</strong><br>
            • <strong>Semester Ganjil:</strong> 1 Juli - 31 Desember<br>
            • <strong>Semester Genap:</strong> 1 Januari - 30 Juni<br>
            • <strong>Auto-Detect:</strong> Sistem akan otomatis aktifkan semester sesuai tanggal
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Generate</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function refreshSemester() {
  if (confirm('Refresh auto-detect semester?')) {
    window.location.reload();
  }
}
</script>
@endpush

@endsection
