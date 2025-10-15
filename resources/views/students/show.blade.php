@extends('layouts.app')

@section('content')
<section class="section">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4>Detail Siswa</h4>
      <div>
        <a href="{{ route('students.index') }}" class="btn btn-light">
          <i class="fas fa-arrow-left"></i> Kembali
        </a>
        @isset($student->id)
        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary">
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
                  <img src="{{ asset('storage/' . $photo) }}" alt="Foto" class="rounded preview-file" style="width: 160px; height: 160px; object-fit: cover; cursor: pointer;" data-src="{{ asset('storage/' . $photo) }}" data-title="Foto {{ $student->name }}" data-type="image">
                @else
                  <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:160px;height:160px;margin:0 auto;font-size:48px;">
                    {{ strtoupper(substr($student->name ?? 'N/A', 0, 1)) }}
                  </div>
                @endif
              </div>
              <h5 class="mb-1">{{ $student->name ?? '-' }}</h5>
              <span class="badge badge-success">
                Siswa
              </span>
              <div class="mb-2 mt-2"><i class="fas fa-envelope mr-2"></i>{{ $student->user->email ?? '-' }}</div>
              <div class="mb-2">
                <i class="fas fa-phone mr-2"></i>
                @if($student->user->phone)
                  <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->user->phone) }}" target="_blank" class="text-decoration-none" title="Chat via WhatsApp">
                    <b>{{ $student->user->phone }}</b>
                  </a>
                @else
                  -
                @endif
              </div>
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
                      <th>No. Absen</th>
                      <td>{{ $student->no_absen ?? '-' }}</td>
                    </tr>
                    <tr>
                      <th>No. Kartu</th>
                      <td>{{ $student->no_card ?? '-' }}</td>
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
                      <th>Status Akun</th>
                      <td>
                        <span class="badge {{ $student->user->status ? 'badge-success' : 'badge-danger' }}">
                          {{ $student->user->status ? 'Aktif' : 'Diblokir' }}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <th>Tanggal Registrasi</th>
                      <td>{{ $student->created_at ? $student->created_at->format('d M Y H:i') : '-' }}</td>
                    </tr>
                    @if($student->face_registered_at)
                    <tr>
                      <th>Face Recognition</th>
                      <td>
                        <span class="badge badge-info">
                          <i class="fas fa-check mr-1"></i>Terdaftar
                        </span>
                        <small class="text-muted d-block">{{ $student->face_registered_at->format('d M Y H:i') }}</small>
                      </td>
                    </tr>
                    @endif
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
                            <img src="{{ asset('storage/' . $doc['path']) }}" alt="{{ $doc['label'] }}" class="img-fluid rounded preview-file" data-src="{{ asset('storage/' . $doc['path']) }}" data-title="{{ $doc['label'] }}" data-type="image">
                          @elseif($ext === 'pdf')
                            <div class="text-center p-3 border rounded bg-light preview-file" data-src="{{ asset('storage/' . $doc['path']) }}" data-title="{{ $doc['label'] }}" data-type="pdf" style="cursor: pointer;">
                              <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                              <div class="small">Klik untuk preview PDF</div>
                            </div>
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

      <!-- Face Recognition Photo Section (if available) -->
      @if($student->face_photo)
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header">
              <h5 class="mb-0">Foto Face Recognition</h5>
            </div>
            <div class="card-body text-center">
              <img src="{{ asset('storage/' . $student->face_photo) }}" alt="Foto Face Recognition"
                   class="img-fluid rounded preview-file"
                   data-src="{{ asset('storage/' . $student->face_photo) }}"
                   data-title="Foto Face Recognition - {{ $student->name }}"
                   data-type="image"
                   style="max-width: 300px; cursor: pointer;">
              <p class="mt-2 text-muted">Foto yang digunakan untuk sistem face recognition</p>
            </div>
          </div>
        </div>
      </div>
      @endif
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
    const pdfEl = document.getElementById('pdfPreviewElement');
    const titleEl = document.getElementById('imagePreviewTitle');
    const downloadBtn = document.getElementById('imagePreviewDownload');

    function openModal(src, title, type = 'image') {
      titleEl.textContent = title || 'Preview File';

      if (type === 'pdf') {
        // Show PDF in iframe
        pdfEl.src = src;
        pdfEl.style.display = 'block';
        imgEl.style.display = 'none';
        downloadBtn.setAttribute('href', src);
        downloadBtn.setAttribute('download', (title || 'document').replace(/\s+/g,'_') + '.pdf');
      } else {
        // Show image
        imgEl.src = src;
        imgEl.style.display = 'block';
        pdfEl.style.display = 'none';
        downloadBtn.setAttribute('href', src);
        downloadBtn.setAttribute('download', (title || 'image').replace(/\s+/g,'_'));
      }

      $(modalEl).modal('show');
    }

    document.querySelectorAll('.preview-file').forEach(function(el) {
      el.addEventListener('click', function(e) {
        e.preventDefault();
        const src = this.getAttribute('data-src');
        const title = this.getAttribute('data-title');
        const type = this.getAttribute('data-type') || 'image';
        if (src) openModal(src, title, type);
      });
    });

    function createImageModal() {
      if (document.getElementById('imagePreviewModal')) return document.getElementById('imagePreviewModal');
      const html = `
        <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewTitle">Preview File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center">
                <img id="imagePreviewElement" src="" alt="Preview" class="img-fluid rounded" style="max-height: 70vh; display: none;">
                <iframe id="pdfPreviewElement" src="" style="width: 100%; height: 70vh; border: 1px solid #ddd; display: none;" title="PDF Preview"></iframe>
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
