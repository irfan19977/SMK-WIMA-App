@extends('layouts.app')

@section('content')

<section class="section">
  <div class="card">
    <div class="card-header">
      <h4>Daftar Pendaftar Baru</h4>
      <div class="card-header-action">
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

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped" id="sortable-table">
          <thead>
            <tr class="text-center">
              <th>No.</th>
              <th>Nama</th>
              <th>NISN</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Tanggal Daftar</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($students as $student)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td><a href="#" class="text-secondery font-weight-bold">{{ $student->name }}</a></td>
                <td>{{ $student->nisn ?? '-' }}</td>
                <td>{{ $student->email ?? ($student->user->email ?? '-') }}</td>
                <td>{{ $student->phone ?? ($student->user->phone ?? '-') }}</td>
                <td>{{ $student->created_at ? $student->created_at->format('d M Y') : '-' }}</td>
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
                  <a href="{{ route('pendaftaran-siswa.edit', $student->id) }}" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="Edit">
                    <i class="fas fa-pencil-alt"></i>
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
          </tbody>
        </table>
      </div>
    </div>
</section>

@endsection

@push('scripts')
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const clearButton = document.getElementById('clear-search');
    const searchButton = document.getElementById('search-button');
    const searchInput = document.getElementById('search-input');

    clearButton.addEventListener('click', function() {
      searchInput.value = '';
      searchInput.focus();
      clearButton.style.display = 'none';
      searchButton.style.display = 'block';
      // Trigger event (optional hook if later adding AJAX search)
      const event = new Event('input', { bubbles: true });
      searchInput.dispatchEvent(event);
    });

    searchInput.addEventListener('input', function() {
      if (this.value.trim() !== '') {
        clearButton.style.display = 'block';
        searchButton.style.display = 'none';
      } else {
        clearButton.style.display = 'none';
        searchButton.style.display = 'block';
      }
    });

    // Handle accept (Terima) calon siswa
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

          // Remove row from table (karena status berubah jadi siswa, daftar ini hanya menampilkan calon siswa)
          const row = this.closest('tr');
          if (row) row.remove();

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
  });
  </script>
@endpush