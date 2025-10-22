@extends('layouts.app')

@section('content')

<section class="section">
  <div class="card">
    <div class="card-header">
      <h4>Daftar Pendaftar Baru</h4>
      <div class="card-header-action d-flex">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Cari Siswa (Nama, NISN)" name="q" id="search-input" autocomplete="off">
          <div class="input-group-btn">
            <button type="button" class="btn btn-primary" id="search-button" style="margin-top: 1px;">
              <i class="fas fa-search"></i>
            </button>
            <button type="button" class="btn btn-primary" id="clear-search" title="Clear Search" style="display: none; margin-top: 1px;">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-6">
            <label for="kelasFilter" class="form-label">Tahun Kademik:</label>
            <select class="form-control select2" id="academic-year-filter">
              @php
                $currentYear = \App\Helpers\AcademicYearHelper::getCurrentAcademicYear();
                [$startY, $endY] = explode('/', $currentYear);
                $startY = (int) $startY;
                $endY = (int) $endY;
                $nextYear = ($startY + 1) . '/' . ($endY + 1);
                $academicYears = \App\Helpers\AcademicYearHelper::generateAcademicYears(1, 3); // 1 tahun sebelumnya, 3 tahun ke depan
                $selectedYear = request('tahun_akademik', $nextYear);
              @endphp
              @foreach($academicYears as $year)
                <option value="{{ $year }}" {{ $year === $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
              @endforeach
            </select>
        </div>
        <div class="col-md-6 d-flex align-items-end justify-content-end">
          <div>
            <button class="btn btn-primary btn-modern mr-2" onclick="exportExcel()">
              <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
            <button class="btn btn-primary btn-modern" onclick="printAttendance()">
              <i class="fas fa-print mr-2"></i>Cetak PDF
            </button>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped" id="sortable-table" style="font-size: 12px;">
          <thead>
            <tr class="text-center">
              <th>No.</th>
              <th>Nama</th>
              <th>NISN</th>
              <th>NIK</th>
              <th>Jurusan Utama</th>
              <th>Jurusan Cadangan</th>
              <th>Jenis Kelamin</th>
              <th>Tempat Lahir</th>
              <th>Tanggal Lahir</th>
              <th>Nomor HP</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="students-tbody">
            @include('pendaftaran._table')
          </tbody>
        </table>
      </div>
    </div>
</section>

@endsection

@push('styles')
  <style>
    #sortable-table th:nth-child(1) { width: 3%; }  /* No */
    #sortable-table th:nth-child(2) { width: 15%; } /* Nama */
    #sortable-table th:nth-child(3) { width: 8%; }  /* NISN */
    #sortable-table th:nth-child(4) { width: 10%; } /* NIK */
    #sortable-table th:nth-child(5) { width: 12%; } /* Jurusan Utama */
    #sortable-table th:nth-child(6) { width: 12%; } /* Jurusan Cadangan */
    #sortable-table th:nth-child(7) { width: 8%; }  /* Jenis Kelamin */
    #sortable-table th:nth-child(8) { width: 10%; } /* Tempat Lahir */
    #sortable-table th:nth-child(9) { width: 8%; }  /* Tanggal Lahir */
    #sortable-table th:nth-child(10) { width: 8%; } /* Nomor HP */
    #sortable-table th:nth-child(11) { width: 6%; } /* Status */
    #sortable-table th:nth-child(12) { width: 6%; } /* Aksi */

    #sortable-table td {
      padding: 8px 4px;
      vertical-align: middle;
    }

    .btn-action {
      padding: 4px 8px;
      font-size: 12px;
    }
  </style>
@endpush
@push('scripts')
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const clearButton = document.getElementById('clear-search');
    const searchButton = document.getElementById('search-button');
    const searchInput = document.getElementById('search-input');
    const academicYearFilter = document.getElementById('academic-year-filter');
    // Buttons use inline onclick handlers per user's preference

    if (academicYearFilter) {
      academicYearFilter.addEventListener('change', function() {
        reloadTable();
      });
      // Jika Select2 digunakan, dengarkan event select2:select
      if (window.$ && typeof $.fn.select2 === 'function') {
        $(academicYearFilter).on('select2:select', function () {
          reloadTable();
        });
      }
    }

    clearButton.addEventListener('click', function() {
      searchInput.value = '';
      clearTimeout(searchInput.searchTimeout);
      searchInput.focus();
      clearButton.style.display = 'none';
      searchButton.style.display = 'block';
      reloadTable();
    });

    async function reloadTable() {
      const url = new URL(window.location.href);
      const q = (searchInput.value || '').trim();
      if (q) url.searchParams.set('q', q); else url.searchParams.delete('q');
      if (academicYearFilter && academicYearFilter.value) {
        url.searchParams.set('tahun_akademik', academicYearFilter.value);
      } else {
        url.searchParams.delete('tahun_akademik');
      }
      // Minta partial saja
      url.searchParams.set('partial', '1');

      try {
        const res = await fetch(url.toString(), {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) throw new Error('Gagal memuat data');
        const html = await res.text();
        const tbody = document.getElementById('students-tbody');
        if (tbody) {
          tbody.innerHTML = html;
        }
        bindAcceptButtons();
        // Update URL tanpa reload dan tanpa param partial
        const cleanUrl = new URL(window.location.href);
        if (q) cleanUrl.searchParams.set('q', q); else cleanUrl.searchParams.delete('q');
        if (academicYearFilter && academicYearFilter.value) {
          cleanUrl.searchParams.set('tahun_akademik', academicYearFilter.value);
        } else {
          cleanUrl.searchParams.delete('tahun_akademik');
        }
        cleanUrl.searchParams.delete('partial');
        window.history.pushState({}, '', cleanUrl.toString());
      } catch (e) {
        if (window.toastr) toastr.error(e.message || 'Terjadi kesalahan');
      }
    }

    // Initialize search timeout
    searchInput.searchTimeout = null;

    searchInput.addEventListener('input', function() {
      if (this.value.trim() !== '') {
        clearButton.style.display = 'block';
        searchButton.style.display = 'none';
      } else {
        clearButton.style.display = 'none';
        searchButton.style.display = 'block';
      }

      // Debounce search - trigger after user stops typing for 500ms
      clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(() => {
        reloadTable();
      }, 500);
    });

    // Klik tombol search untuk reload
    if (searchButton) {
      searchButton.addEventListener('click', function() {
        clearTimeout(searchInput.searchTimeout);
        reloadTable();
      });
    }

    // Tekan Enter di input untuk reload
    if (searchInput) {
      searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          clearTimeout(searchInput.searchTimeout);
          reloadTable();
        }
      });
    }

    function bindAcceptButtons() {
      document.querySelectorAll('.btn-accept').forEach(function(btn) {
        btn.addEventListener('click', async function () {
          const id = this.getAttribute('data-id');
          if (!id) return;

        // SweetAlert v1 confirmation (align with layout)
        if (window.swal) {
          const confirmed = await new Promise(resolve => {
            swal({
              title: 'Terima calon siswa ini?',
              text: '',
              icon: 'warning',
              buttons: {
                cancel: 'Batal',
                confirm: 'Ya, Terima'
              },
              dangerMode: false,
            }).then(value => resolve(!!value));
          });
          if (!confirmed) return;
        } else {
          if (!confirm('Terima calon siswa ini?')) return;
        }

        try {
          const res = await fetch(`{{ url('pendaftaran-siswa') }}/${id}/accept`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': `{{ csrf_token() }}`,
              'Accept': 'application/json'
            }
          });

          if (!res.ok) {
            // Try to read message
            let msg = 'Gagal memproses permintaan.';
            try {
              const data = await res.json();
              if (data && data.message) msg = data.message;
            } catch (e) {}
            throw new Error(msg);
          }

          let message = 'Calon siswa telah diterima.';
          try {
            const data = await res.json();
            if (data && data.message) message = data.message;
          } catch (e) {}

          // Reload tabel agar nomor baris ($loop->iteration) terhitung ulang
          await reloadTable();

          if (window.swal) {
            await new Promise(resolve => {
              swal({
                title: 'Berhasil!',
                text: message,
                icon: 'success',
                timer: 2000,
              }).then(() => resolve());
            });
          } else if (window.toastr) {
            toastr.success(message);
          } else {
            alert(message);
          }
        } catch (err) {
          if (window.swal) {
            swal({
              title: 'Gagal!',
              text: err.message || 'Terjadi kesalahan.',
              icon: 'error'
            });
          } else if (window.toastr) {
            toastr.error(err.message || 'Terjadi kesalahan.');
          } else {
            alert(err.message || 'Terjadi kesalahan.');
          }
        }
      });
      });
    }

    // initial bind
    bindAcceptButtons();

    window.exportExcel = function() {
      const url = new URL(`{{ route('pendaftaran-siswa.export-excel') }}`);
      const q = (searchInput.value || '').trim();
      if (q) url.searchParams.set('q', q);
      if (academicYearFilter && academicYearFilter.value) {
        url.searchParams.set('tahun_akademik', academicYearFilter.value);
      }
      window.location.href = url.toString();
    }

    window.printAttendance = function() {
      const url = new URL(`{{ route('pendaftaran-siswa.print') }}`);
      const q = (searchInput.value || '').trim();
      if (q) url.searchParams.set('q', q);
      if (academicYearFilter && academicYearFilter.value) {
        url.searchParams.set('tahun_akademik', academicYearFilter.value);
      }
      window.open(url.toString(), '_blank');
    }
  });
  </script>
@endpush