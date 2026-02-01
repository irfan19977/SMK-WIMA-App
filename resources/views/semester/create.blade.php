@extends('layouts.app')

@section('content')

<section class="section">
  <div class="card">
    <div class="card-header">
      <h4>Tambah Semester Baru</h4>
      <div class="card-header-action">
        <a href="{{ route('semester.index') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i> Kembali
        </a>
      </div>
    </div>

    <div class="card-body">
      <form action="{{ route('semester.store') }}" method="POST">
        @csrf
        
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="academic_year">Tahun Akademik <span class="text-danger">*</span></label>
              <select name="academic_year" id="academic_year" class="form-control" required>
                <option value="">Pilih Tahun Akademik</option>
                @php
                  $academicYears = \App\Helpers\AcademicYearHelper::generateAcademicYears(0, 3);
                @endphp
                @foreach($academicYears as $year)
                  <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
              </select>
              @error('academic_year')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="semester_type">Tipe Semester <span class="text-danger">*</span></label>
              <select name="semester_type" id="semester_type" class="form-control" required>
                <option value="">Pilih Semester</option>
                <option value="ganjil">Ganjil</option>
                <option value="genap">Genap</option>
              </select>
              @error('semester_type')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="start_date">Tanggal Mulai <span class="text-danger">*</span></label>
              <input type="date" name="start_date" id="start_date" class="form-control" required>
              @error('start_date')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="end_date">Tanggal Selesai <span class="text-danger">*</span></label>
              <input type="date" name="end_date" id="end_date" class="form-control" required>
              @error('end_date')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" name="is_active" id="is_active" class="custom-control-input" value="1">
            <label for="is_active" class="custom-control-label">
              Aktifkan semester ini (akan menonaktifkan semester lain yang sedang aktif)
            </label>
          </div>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan
          </button>
          <a href="{{ route('semester.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Batal
          </a>
        </div>
      </form>
    </div>
  </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const academicYearSelect = document.getElementById('academic_year');
  const semesterTypeSelect = document.getElementById('semester_type');
  const startDateInput = document.getElementById('start_date');
  const endDateInput = document.getElementById('end_date');

  function autoSetDates() {
    const academicYear = academicYearSelect.value;
    const semesterType = semesterTypeSelect.value;

    if (academicYear && semesterType) {
      const [startYear, endYear] = academicYear.split('/').map(Number);
      
      if (semesterType === 'ganjil') {
        // Semester Ganjil: Juli - Desember
        startDateInput.value = `${startYear}-07-01`;
        endDateInput.value = `${startYear}-12-31`;
      } else if (semesterType === 'genap') {
        // Semester Genap: Januari - Juni
        startDateInput.value = `${endYear}-01-01`;
        endDateInput.value = `${endYear}-06-30`;
      }
    }
  }

  academicYearSelect.addEventListener('change', autoSetDates);
  semesterTypeSelect.addEventListener('change', autoSetDates);
});
</script>
@endpush

@endsection
