@extends('layouts.app')

@section('content')
<section class="section">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4>Detail Pendaftar</h4>
      <div>
        <a href="{{ route('pendaftaran-siswa.index') }}" class="btn btn-light">
          <i class="fas fa-arrow-left"></i> Kembali
        </a>
        @isset($student->id)
        <a href="{{ route('pendaftaran-siswa.edit', $student->id) }}" class="btn btn-primary">
          <i class="fas fa-edit"></i> Edit
        </a>
        @endisset
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <!-- Profil & Kontak -->
        <div class="col-lg-4 mb-4">
          <div class="card shadow-sm">
            <div class="card-body text-center">
              <div class="mb-3">
                @php($photo = $student->user->photo_path ?? null)
                @if($photo)
                  <a href="#" class="preview-image" data-src="{{ asset('storage/' . $photo) }}" data-title="Foto {{ $student->name }}">
                    <img src="{{ asset('storage/' . $photo) }}" alt="Foto" class="rounded" style="width: 160px; height: 160px; object-fit: cover;">
                  </a>
                @else
                  <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:160px;height:160px;margin:0 auto;font-size:48px;">
                    {{ strtoupper(substr($student->name ?? 'N/A', 0, 1)) }}
                  </div>
                @endif
              </div>
              <h5 class="mb-1">{{ $student->name ?? '-' }}</h5>
              <div class="text-muted mb-3">Calon Siswa</div>
              <div class="mb-2"><i class="fas fa-envelope mr-2"></i>{{ $student->user->email ?? '-' }}</div>
              <div class="mb-2"><i class="fas fa-phone mr-2"></i>{{ $student->user->phone ?? '-' }}</div>
              @php($status = $student->status ?? 'calon siswa')
              <span class="badge badge-{{ $status === 'calon siswa' ? 'warning' : ($status === 'accepted' ? 'success' : 'secondary') }}">{{ ucfirst($status) }}</span>
            </div>
          </div>
        </div>

        <!-- Data Lengkap -->
        <div class="col-lg-8 mb-4">
          <div class="card shadow-sm">
            <div class="card-header">
              <h5 class="mb-0">Data Pribadi</h5>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped mb-0">
                  <tbody>
                    <tr>
                      <th style="width: 240px;">Nama Lengkap</th>
                      <td>{{ $student->name ?? '-' }}</td>
                    </tr>
                    <tr>
                      <th>NISN</th>
                      <td>{{ $student->nisn ?? '-' }}</td>
                    </tr>
                    <tr>
                      <th>NIK</th>
                      <td>{{ $student->nik ?? '-' }}</td>
                    </tr>
                    <tr>
                      <th>Jenis Kelamin</th>
                      <td>{{ ucfirst($student->gender ?? '-') }}</td>
                    </tr>
                    <tr>
                      <th>Tempat Lahir</th>
                      <td>{{ $student->birth_place ?? '-' }}</td>
                    </tr>
                    <tr>
                      <th>Tanggal Lahir</th>
                      <td>{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                      <th>Agama</th>
                      <td>{{ ucfirst($student->religion ?? '-') }}</td>
                    </tr>
                    <tr>
                      <th>Alamat</th>
                      <td>{{ $student->address ?? '-' }}</td>
                    </tr>
                    <tr>
                      <th>Email</th>
                      <td>{{ $student->user->email ?? '-' }}</td>
                    </tr>
                    <tr>
                      <th>No. HP</th>
                      <td>{{ $student->user->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                      <th>Tanggal Pendaftaran</th>
                      <td>{{ $student->created_at ? $student->created_at->format('d M Y H:i') : '-' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Dokumen Lampiran: Full Width -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header">
              <h5 class="mb-0">Dokumen Lampiran</h5>
            </div>
            <div class="card-body">
              <div class="row">
                @foreach([
                  ['label' => 'Ijazah', 'path' => $student->ijazah ?? null],
                  ['label' => 'Kartu Keluarga', 'path' => $student->kartu_keluarga ?? null],
                  ['label' => 'Akte Lahir', 'path' => $student->akte_lahir ?? null],
                  ['label' => 'KTP', 'path' => $student->ktp ?? null],
                  ['label' => 'Sertifikat', 'path' => $student->sertifikat ?? null],
                ] as $doc)
                  <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
                    <div class="border rounded p-3 h-100 d-flex flex-column">
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>{{ $doc['label'] }}</strong>
                        @if($doc['path'])
                          <a href="{{ asset('storage/' . $doc['path']) }}" download class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download"></i>
                          </a>
                        @endif
                      </div>
                      <div class="flex-fill">
                        @if($doc['path'])
                          @php($ext = strtolower(pathinfo($doc['path'], PATHINFO_EXTENSION)))
                          @if(in_array($ext, ['jpg','jpeg','png','gif']))
                            <img src="{{ asset('storage/' . $doc['path']) }}" alt="{{ $doc['label'] }}" class="img-fluid rounded preview-image" data-src="{{ asset('storage/' . $doc['path']) }}" data-title="{{ $doc['label'] }}">
                          @else
                            <div class="text-muted">Dokumen tersedia ({{ strtoupper($ext) }})</div>
                          @endif
                        @else
                          <div class="text-muted">Tidak ada file</div>
                        @endif
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const modal = createImageModal();
    const modalEl = document.getElementById('imagePreviewModal');
    const imgEl = document.getElementById('imagePreviewElement');
    const titleEl = document.getElementById('imagePreviewTitle');
    const downloadBtn = document.getElementById('imagePreviewDownload');

    function openModal(src, title) {
      imgEl.src = src;
      titleEl.textContent = title || 'Preview Gambar';
      downloadBtn.setAttribute('href', src);
      downloadBtn.setAttribute('download', title ? title.replace(/\s+/g,'_') : 'gambar');
      // Bootstrap 4 modal
      $(modalEl).modal('show');
    }

    document.querySelectorAll('.preview-image').forEach(function(el) {
      el.addEventListener('click', function(e) {
        e.preventDefault();
        const src = this.getAttribute('data-src') || this.getAttribute('src');
        const title = this.getAttribute('data-title') || this.getAttribute('alt') || 'Preview Gambar';
        if (src) openModal(src, title);
      });
    });

    function createImageModal() {
      if (document.getElementById('imagePreviewModal')) return document.getElementById('imagePreviewModal');
      const html = `
        <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewTitle">Preview Gambar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center">
                <img id="imagePreviewElement" src="" alt="Preview" class="img-fluid rounded" style="max-height: 70vh;">
              </div>
              <div class="modal-footer">
                <a id="imagePreviewDownload" href="#" class="btn btn-primary" download>
                  <i class="fas fa-download"></i> Unduh
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
              </div>
            </div>
          </div>
        </div>`;
      const container = document.createElement('div');
      container.innerHTML = html;
      document.body.appendChild(container.firstElementChild);
      return document.getElementById('imagePreviewModal');
    }
  });
</script>
@endpush
