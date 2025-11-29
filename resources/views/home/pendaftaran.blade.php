@extends('home.layouts.app')

@section('content')
    <div class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('frontend/images/bg.png') }}'); background-attachment:fixed;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-8 ftco-animate text-center">
            <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('/') }}">Home</a></span> <span>Pendaftaran</span></p>
            <h1 class="mb-3 bread">Pendaftaran Peserta Didik Baru</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section bg-light">
      <div class="container">
        <div class="row justify-content-center mb-4 pb-2">
          <div class="col-md-10 heading-section ftco-animate">
            <h2 class="mb-3 text-center">Informasi Pendaftaran</h2>
            <p><strong>Persyaratan Umum:</strong></p>
            <ul>
              <li>Lulusan SMP/MTs atau sederajat.</li>
              <li>Fotokopi ijazah/Surat Keterangan Lulus (SKL).</li>
              <li>Fotokopi Kartu Keluarga (KK).</li>
              <li>Pas foto terbaru ukuran 3x4 berwarna.</li>
            </ul>
            <p><strong>Fasilitas yang Didapat:</strong></p>
            <ul>
              <li>Laboratorium praktik lengkap sesuai kompetensi keahlian.</li>
              <li>Akses internet dan WiFi di lingkungan sekolah.</li>
              <li>Program praktik kerja lapangan (PKL) di industri mitra.</li>
              <li>Layanan bimbingan konseling dan pengembangan karier.</li>
            </ul>
            <p><strong>Alur Pendaftaran:</strong></p>
            <ol>
              <li>Mengisi formulir pendaftaran online.</li>
              <li>Mengunggah dokumen yang dipersyaratkan.</li>
              <li>Membuat akun untuk memantau status pendaftaran.</li>
            </ol>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-10 ftco-animate">
            <div class="bg-white p-4 p-md-5 rounded shadow-sm registration-card" id="registration-card">
              <h3 class="mb-4 text-center">Formulir Pendaftaran</h3>
              <p class="text-center text-muted mb-4">Lengkapi data pada setiap bagian, lalu klik <strong>Selanjutnya</strong> hingga semua tahap selesai.</p>
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="font-weight-bold" id="step-indicator-text">Bagian 1 dari 3</span>
                  <small class="text-muted">Progres pendaftaran</small>
                </div>
                <div class="progress" style="height: 8px;">
                  <div id="step-progress-bar" class="progress-bar bg-primary" role="progressbar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

              <form id="form-pendaftaran" method="POST" action="{{ route('pendaftaran.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-step" id="step-1">
                  <h4 class="mb-3">Bagian 1: Informasi Calon Peserta Didik</h4>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="name">Nama Lengkap</label>
                      <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan Nama Lengkap" autofocus>
                      @error('name')
                          <div class="text-danger small">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-md-6">
                      <label for="phone">Nomor Whatsap</label>
                      <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Masukkan Nomor Whatsapp">
                      @error('phone')
                          <div class="text-danger small">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="nik">NIK</label>
                      <input type="number" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}" placeholder="Masukkan NIK 16 Angka">
                      @error('nik')
                          <div class="text-danger small">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-md-6">
                      <label for="nisn">NISN</label>
                      <input type="tel" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" value="{{ old('nisn') }}" required>
                      @error('nisn')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="jurusan_utama">Jurusan Utama</label>
                      <select class="form-control @error('jurusan_utama') is-invalid @enderror" id="jurusan_utama" name="jurusan_utama" required>
                        <option value="">-- Pilih Jurusan Utama --</option>
                        <option value="Teknik Kendaraan Ringan" {{ old('jurusan_utama') == 'Teknik Kendaraan Ringan' ? 'selected' : '' }}>Teknik Kendaraan Ringan (TKR)</option>
                        <option value="Teknik Bisnis Sepeda Motor" {{ old('jurusan_utama') == 'Teknik Bisnis Sepeda Motor' ? 'selected' : '' }}>Teknik Bisnis Sepeda Motor (TBSM)</option>
                        <option value="Teknik Kimia Industri" {{ old('jurusan_utama') == 'Teknik Kimia Industri' ? 'selected' : '' }}>Teknik Kimia Industri (TKI)</option>
                        <option value="Teknik Komputer dan Jaringan" {{ old('jurusan_utama') == 'Teknik Komputer dan Jaringan' ? 'selected' : '' }}>Teknik Komputer dan Jaringan (TKJ)</option>
                      </select>
                      @error('jurusan_utama')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-md-6">
                      <label for="jurusan_cadangan">Jurusan Cadangan</label>
                      <select class="form-control @error('jurusan_cadangan') is-invalid @enderror" id="jurusan_cadangan" name="jurusan_cadangan" required>
                        <option value="">-- Pilih Jurusan Cadangan --</option>
                        <option value="Teknik Kendaraan Ringan" {{ old('jurusan_cadangan') == 'Teknik Kendaraan Ringan' ? 'selected' : '' }}>Teknik Kendaraan Ringan (TKR)</option>
                        <option value="Teknik Bisnis Sepeda Motor" {{ old('jurusan_cadangan') == 'Teknik Bisnis Sepeda Motor' ? 'selected' : '' }}>Teknik Bisnis Sepeda Motor (TBSM)</option>
                        <option value="Teknik Kimia Industri" {{ old('jurusan_cadangan') == 'Teknik Kimia Industri' ? 'selected' : '' }}>Teknik Kimia Industri (TKI)</option>
                        <option value="Teknik Komputer dan Jaringan" {{ old('jurusan_cadangan') == 'Teknik Komputer dan Jaringan' ? 'selected' : '' }}>Teknik Komputer dan Jaringan (TKJ)</option>
                      </select>
                      @error('jurusan_cadangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="gender">Jenis Kelamin</label>
                      <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                      </select>
                      @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-md-6">
                      <label for="birth_date">Tanggal Lahir</label>
                      <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                      @error('birth_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="birth_place">Tempat Lahir</label>
                      <input type="text" class="form-control @error('birth_place') is-invalid @enderror" id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required>
                      @error('birth_place')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-md-6">
                      <label for="religion">Agama</label>
                      <select class="form-control @error('religion') is-invalid @enderror" id="religion" name="religion" required>
                        <option value="">-- Pilih Agama --</option>
                        <option value="Islam" {{ old('religion') == 'Islam' ? 'selected' : '' }}>Islam</option>
                        <option value="Kristen" {{ old('religion') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                        <option value="Katolik" {{ old('religion') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                        <option value="Hindu" {{ old('religion') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="Buddha" {{ old('religion') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                        <option value="Konghucu" {{ old('religion') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                      </select>
                      @error('religion')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="address">Alamat Lengkap</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                    @error('address')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="asalSekolah">Asal Sekolah</label>
                    <input type="text" class="form-control @error('asalSekolah') is-invalid @enderror" id="asalSekolah" name="asalSekolah" value="{{ old('asalSekolah') }}">
                    @error('asalSekolah')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="form-step d-none" id="step-2">
                  <h4 class="mb-3">Bagian 2: Upload Dokumen</h4>
                  <div class="row">
                    <div class="col-md-5 mb-4 mb-md-0">
                      <div class="form-group mb-2">
                        <label for="photo_path">Foto 3x4 (Pas Foto)</label>
                        <input type="file" class="form-control-file file-input-hidden" id="photo_path" name="photo_path" accept="image/*" required>
                      </div>
                      <div class="upload-preview d-flex align-items-center justify-content-center" id="preview-foto" style="min-height: 420px;">
                        <span class="upload-placeholder">Upload File Disini</span>
                        <img id="preview-foto-img" src="" alt="Preview Foto" style="display:none;" />
                        <div class="upload-overlay">
                          <button type="button" class="btn btn-light" id="btn-view-foto">Lihat</button>
                          <button type="button" class="btn btn-primary" id="btn-reupload-foto">Upload</button>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-7">
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <div class="form-group mb-2">
                            <label for="ijazah">Ijazah / SKL</label>
                            <input type="file" class="form-control-file file-input-hidden" id="ijazah" name="ijazah" accept=".jpg,.jpeg,.png,.pdf" required>
                            <div class="upload-preview" id="preview-ijasah" data-label="Ijazah / SKL">
                              <span class="upload-placeholder">Belum ada file ijazah yang dipilih</span>
                              <div class="upload-overlay">
                                <button type="button" class="btn btn-light" id="btn-view-ijasah">Lihat</button>
                                <button type="button" class="btn btn-primary" id="btn-reupload-ijasah">Upload</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 mb-3">
                          <div class="form-group mb-2">
                            <label for="kartu_keluarga">Kartu Keluarga (KK)</label>
                            <input type="file" class="form-control-file file-input-hidden" id="kartu_keluarga" name="kartu_keluarga" accept=".jpg,.jpeg,.png,.pdf" required>
                            <div class="upload-preview" id="preview-kk" data-label="Kartu Keluarga (KK)">
                              <span class="upload-placeholder">Belum ada file KK yang dipilih</span>
                              <div class="upload-overlay">
                                <button type="button" class="btn btn-light" id="btn-view-kk">Lihat</button>
                                <button type="button" class="btn btn-primary" id="btn-reupload-kk">Upload</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 mb-3">
                          <div class="form-group mb-2">
                            <label for="akte_lahir">Akte Lahir</label>
                            <input type="file" class="form-control-file file-input-hidden" id="akte_lahir" name="akte_lahir" accept=".jpg,.jpeg,.png,.pdf" required>
                            <div class="upload-preview" id="preview-akte-lahir" data-label="Akte Lahir">
                              <span class="upload-placeholder">Belum ada file akte lahir yang dipilih</span>
                              <div class="upload-overlay">
                                <button type="button" class="btn btn-light" id="btn-view-akte-lahir">Lihat</button>
                                <button type="button" class="btn btn-primary" id="btn-reupload-akte-lahir">Upload</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 mb-3">
                          <div class="form-group mb-2">
                            <label for="ktp">KTP</label>
                            <input type="file" class="form-control-file file-input-hidden" id="ktp" name="ktp" accept=".jpg,.jpeg,.png,.pdf" required>
                            <div class="upload-preview" id="preview-ktp" data-label="KTP">
                              <span class="upload-placeholder">Belum ada file KTP yang dipilih</span>
                              <div class="upload-overlay">
                                <button type="button" class="btn btn-light" id="btn-view-ktp">Lihat</button>
                                <button type="button" class="btn btn-primary" id="btn-reupload-ktp">Upload</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="form-group mb-0">
                            <label for="sertifikat">Sertifikat Pendukung (opsional)</label>
                            <input type="file" class="form-control-file file-input-hidden" id="sertifikat" name="sertifikat" multiple accept=".jpg,.jpeg,.png,.pdf">
                            <div class="upload-preview" id="preview-sertifikat" data-label="Sertifikat Pendukung">
                              <span class="upload-placeholder">Belum ada file sertifikat yang dipilih</span>
                              <div class="upload-overlay">
                                <button type="button" class="btn btn-light" id="btn-view-sertifikat">Lihat</button>
                                <button type="button" class="btn btn-primary" id="btn-reupload-sertifikat">Upload</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-step d-none" id="step-3">
                  <h4 class="mb-3">Bagian 3: Pembuatan Akun</h4>
                  <div class="row justify-content-center">
                    <div class="col-md-8">
                      <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                          <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" required>
                          <button type="button" class="password-toggle-btn" id="togglePassword">
                            <span class="oi oi-eye"></span>
                          </button>
                        </div>
                        @error('password')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="password-wrapper">
                          <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" required>
                          <button type="button" class="password-toggle-btn" id="togglePasswordConfirm">
                            <span class="oi oi-eye"></span>
                          </button>
                        </div>
                        @error('password_confirmation')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                  <button type="button" class="btn btn-secondary d-none" id="btnPrev">Kembali</button>
                  <button type="button" class="btn btn-primary ml-auto" id="btnNext">Selanjutnya</button>
                  <button type="submit" class="btn btn-success d-none ml-auto" id="btnSubmit">Kirim Pendaftaran</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Modal Preview Foto -->
    <div class="modal fade" id="modalPreviewFoto" tabindex="-1" role="dialog" aria-labelledby="modalPreviewFotoLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalPreviewFotoLabel">Preview Foto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            <img id="modal-foto-img" src="" alt="Preview Foto Besar" style="max-width: 100%; height: auto; max-height: 70vh;" />
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Preview Dokumen (Ijazah, KK, Sertifikat) -->
    <div class="modal fade" id="modalPreviewDokumen" tabindex="-1" role="dialog" aria-labelledby="modalPreviewDokumenLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalPreviewDokumenLabel">Preview Dokumen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="modal-dokumen-content">
          </div>
        </div>
      </div>
    </div>
@endsection

@push('styles')
    <style>
      .registration-card .form-control,
      .registration-card .input-group .form-control {
        border-radius: 0.4rem;
      }

      .registration-card textarea.form-control {
        border-radius: 0.5rem;
      }

      .registration-card .btn {
        border-radius: 999px;
      }

      .password-wrapper {
        position: relative;
      }

      .password-wrapper .form-control {
        padding-right: 2.5rem;
      }

      .password-toggle-btn {
        position: absolute;
        top: 50%;
        right: 0.75rem;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        padding: 0;
        color: #6c757d;
      }

      .registration-card .upload-preview {
        border: 2px dashed #ced4da;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        text-align: center;
        font-size: 0.9rem;
        color: #6c757d;
        margin-top: 0.5rem;
        background-color: #f8f9fa;
        cursor: pointer;
        position: relative;
        overflow: hidden;
      }

      .registration-card .file-input-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0,0,0,0);
        border: 0;
      }

      #preview-foto-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
      }

      #preview-ijasah,
      #preview-kk,
      #preview-sertifikat {
        min-height: 110px;
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
      }

      #modalPreviewFoto .modal-body {
        display: flex;
        align-items: center;
        justify-content: center;
      }

      #modalPreviewDokumen .modal-body {
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .upload-placeholder {
        display: inline-block;
      }

      .upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
        pointer-events: none;
      }

      .upload-overlay .btn {
        border-radius: 999px;
        padding: 0.4rem 1.1rem;
        font-size: 0.8rem;
        font-weight: 500;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
        border-width: 0;
      }

      .upload-overlay .btn-light {
        color: #343a40;
      }

      .upload-overlay .btn + .btn {
        margin-left: 0.5rem;
      }

      .upload-preview:hover .upload-overlay {
        opacity: 1;
        pointer-events: auto;
      }

      .invalid-feedback {
        display: none;
      }

      .is-invalid ~ .invalid-feedback {
        display: block;
      }

      .is-invalid {
        border-color: #dc3545;
      }
    </style>
@endpush

@push('scripts')
    <script>
      (function() {
        var currentStep = 1;
        var totalSteps = 3;
        var btnNext = document.getElementById('btnNext');
        var btnPrev = document.getElementById('btnPrev');
        var btnSubmit = document.getElementById('btnSubmit');
        var stepIndicatorText = document.getElementById('step-indicator-text');
        var stepProgressBar = document.getElementById('step-progress-bar');

        function showStep(step) {
          for (var i = 1; i <= totalSteps; i++) {
            var el = document.getElementById('step-' + i);
            if (!el) continue;
            if (i === step) {
              el.classList.remove('d-none');
            } else {
              el.classList.add('d-none');
            }
          }

          if (step === 1) {
            btnPrev.classList.add('d-none');
            btnNext.classList.remove('d-none');
            btnSubmit.classList.add('d-none');
          } else if (step === totalSteps) {
            btnPrev.classList.remove('d-none');
            btnNext.classList.add('d-none');
            btnSubmit.classList.remove('d-none');
          } else {
            btnPrev.classList.remove('d-none');
            btnNext.classList.remove('d-none');
            btnSubmit.classList.add('d-none');
          }

          if (stepIndicatorText) {
            stepIndicatorText.textContent = 'Bagian ' + step + ' dari ' + totalSteps;
          }

          if (stepProgressBar) {
            var percent = Math.round((step / totalSteps) * 100);
            stepProgressBar.style.width = percent + '%';
            stepProgressBar.setAttribute('aria-valuenow', String(percent));
          }

          // Scroll ke atas kartu form setiap kali ganti step (dengan offset agar judul tidak tertutup navbar)
          var formContainer = document.getElementById('registration-card');
          if (formContainer) {
            var rect = formContainer.getBoundingClientRect();
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            var targetTop = rect.top + scrollTop - 80; // offset 80px dari atas
            window.scrollTo({ top: targetTop, behavior: 'smooth' });
          } else {
            window.scrollTo({ top: 0, behavior: 'smooth' });
          }
        }

        if (btnNext) {
          btnNext.addEventListener('click', function() {
            var form = document.getElementById('form-pendaftaran');
            var currentStepEl = document.getElementById('step-' + currentStep);
            
            // Cari semua input required di step saat ini
            var requiredInputs = currentStepEl.querySelectorAll('[required]');
            var isValid = true;
            
            // Validasi setiap input required
            requiredInputs.forEach(function(input) {
              var feedback = document.getElementById('feedback-' + input.id);
              
              if (input.type === 'file') {
                // Validasi file input
                if (!input.files || input.files.length === 0) {
                  input.classList.add('is-invalid');
                  if (feedback) {
                    feedback.classList.add('d-block');
                  }
                  isValid = false;
                } else {
                  input.classList.remove('is-invalid');
                  if (feedback) {
                    feedback.classList.remove('d-block');
                  }
                }
              } else {
                // Validasi text input
                if (!input.value || input.value.trim() === '') {
                  input.classList.add('is-invalid');
                  isValid = false;
                } else {
                  input.classList.remove('is-invalid');
                }
              }
            });
            
            // Jika ada yang kosong, tampilkan pesan error dengan SweetAlert
            if (!isValid) {
              Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Mohon lengkapi semua field yang wajib diisi terlebih dahulu.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
              });
              return;
            }
            
            if (currentStep < totalSteps) {
              currentStep += 1;
              showStep(currentStep);
            }
          });
        }

        if (btnPrev) {
          btnPrev.addEventListener('click', function() {
            if (currentStep > 1) {
              currentStep -= 1;
              showStep(currentStep);
            }
          });
        }

        var togglePassword = document.getElementById('togglePassword');
        var togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        var passwordInput = document.getElementById('password');
        var passwordConfirmInput = document.getElementById('konfirmasiPassword');
        var fotoInput = document.getElementById('photo_path');
        var ijasahInput = document.getElementById('ijazah');
        var kkInput = document.getElementById('kartu_keluarga');
        var akteLahirInput = document.getElementById('akte_lahir');
        var ktpInput = document.getElementById('ktp');
        var sertifikatInput = document.getElementById('sertifikat');
        var previewFoto = document.getElementById('preview-foto');
        var previewIjasah = document.getElementById('preview-ijasah');
        var previewKk = document.getElementById('preview-kk');
        var previewAkteLahir = document.getElementById('preview-akte-lahir');
        var previewKtp = document.getElementById('preview-ktp');
        var previewSertifikat = document.getElementById('preview-sertifikat');
        var previewFotoImg = document.getElementById('preview-foto-img');
        var uploadPlaceholder = document.querySelector('#preview-foto .upload-placeholder');
        var previewIjasahPlaceholder = document.querySelector('#preview-ijasah .upload-placeholder');
        var previewKkPlaceholder = document.querySelector('#preview-kk .upload-placeholder');
        var previewAkteLahirPlaceholder = document.querySelector('#preview-akte-lahir .upload-placeholder');
        var previewKtpPlaceholder = document.querySelector('#preview-ktp .upload-placeholder');
        var previewSertifikatPlaceholder = document.querySelector('#preview-sertifikat .upload-placeholder');
        var btnViewFoto = document.getElementById('btn-view-foto');
        var btnReuploadFoto = document.getElementById('btn-reupload-foto');
        var btnViewIjasah = document.getElementById('btn-view-ijasah');
        var btnReuploadIjasah = document.getElementById('btn-reupload-ijasah');
        var btnViewKk = document.getElementById('btn-view-kk');
        var btnReuploadKk = document.getElementById('btn-reupload-kk');
        var btnViewAkteLahir = document.getElementById('btn-view-akte-lahir');
        var btnReuploadAkteLahir = document.getElementById('btn-reupload-akte-lahir');
        var btnViewKtp = document.getElementById('btn-view-ktp');
        var btnReuploadKtp = document.getElementById('btn-reupload-ktp');
        var btnViewSertifikat = document.getElementById('btn-view-sertifikat');
        var btnReuploadSertifikat = document.getElementById('btn-reupload-sertifikat');
        var modalFotoImg = document.getElementById('modal-foto-img');
        var modalDokumenContent = document.getElementById('modal-dokumen-content');
        var modalDokumenTitle = document.getElementById('modalPreviewDokumenLabel');
        var ijasahFile = null;
        var kkFile = null;
        var akteLahirFile = null;
        var ktpFile = null;
        var sertifikatFiles = [];
        var dokumenObjectUrl = null;

        if (togglePassword && passwordInput) {
          togglePassword.addEventListener('click', function() {
            var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
          });
        }

        if (togglePasswordConfirm && passwordConfirmInput) {
          togglePasswordConfirm.addEventListener('click', function() {
            var type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmInput.setAttribute('type', type);
          });
        }

        if (fotoInput && previewFoto && previewFotoImg && uploadPlaceholder) {
          fotoInput.addEventListener('change', function() {
            if (fotoInput.files && fotoInput.files.length > 0) {
              var file = fotoInput.files[0];
              if (file.type && file.type.indexOf('image') === 0) {
                var reader = new FileReader();
                reader.onload = function(e) {
                  previewFotoImg.src = e.target.result;
                  previewFotoImg.style.display = 'block';
                  uploadPlaceholder.style.display = 'none';
                  if (modalFotoImg) {
                    modalFotoImg.src = e.target.result;
                  }
                };
                reader.readAsDataURL(file);
              } else {
                previewFotoImg.src = '';
                previewFotoImg.style.display = 'none';
                uploadPlaceholder.style.display = 'inline-block';
              }
            } else {
              previewFotoImg.src = '';
              previewFotoImg.style.display = 'none';
              uploadPlaceholder.style.display = 'inline-block';
            }
          });
        }

        if (ijasahInput && previewIjasah && previewIjasahPlaceholder) {
          ijasahInput.addEventListener('change', function() {
            if (ijasahInput.files && ijasahInput.files.length > 0) {
              ijasahFile = ijasahInput.files[0];
              previewIjasahPlaceholder.textContent = ijasahFile.name;
            } else {
              ijasahFile = null;
              previewIjasahPlaceholder.textContent = 'Belum ada file ijazah yang dipilih';
            }
          });
        }

        if (kkInput && previewKk && previewKkPlaceholder) {
          kkInput.addEventListener('change', function() {
            if (kkInput.files && kkInput.files.length > 0) {
              kkFile = kkInput.files[0];
              previewKkPlaceholder.textContent = kkFile.name;
            } else {
              kkFile = null;
              previewKkPlaceholder.textContent = 'Belum ada file KK yang dipilih';
            }
          });
        }

        if (akteLahirInput && previewAkteLahir && previewAkteLahirPlaceholder) {
          akteLahirInput.addEventListener('change', function() {
            if (akteLahirInput.files && akteLahirInput.files.length > 0) {
              akteLahirFile = akteLahirInput.files[0];
              previewAkteLahirPlaceholder.textContent = akteLahirFile.name;
            } else {
              akteLahirFile = null;
              previewAkteLahirPlaceholder.textContent = 'Belum ada file akte lahir yang dipilih';
            }
          });
        }

        if (ktpInput && previewKtp && previewKtpPlaceholder) {
          ktpInput.addEventListener('change', function() {
            if (ktpInput.files && ktpInput.files.length > 0) {
              ktpFile = ktpInput.files[0];
              previewKtpPlaceholder.textContent = ktpFile.name;
            } else {
              ktpFile = null;
              previewKtpPlaceholder.textContent = 'Belum ada file KTP yang dipilih';
            }
          });
        }

        if (sertifikatInput && previewSertifikat && previewSertifikatPlaceholder) {
          sertifikatInput.addEventListener('change', function() {
            if (sertifikatInput.files && sertifikatInput.files.length > 0) {
              sertifikatFiles = [];
              var names = [];
              for (var i = 0; i < sertifikatInput.files.length; i++) {
                sertifikatFiles.push(sertifikatInput.files[i]);
                names.push(sertifikatInput.files[i].name);
              }
              previewSertifikatPlaceholder.textContent = names.join(', ');
            } else {
              sertifikatFiles = [];
              previewSertifikatPlaceholder.textContent = 'Belum ada file sertifikat yang dipilih';
            }
          });
        }

        if (previewFoto && fotoInput) {
          previewFoto.addEventListener('click', function(e) {
            if (e.target !== previewFotoImg && e.target.tagName !== 'BUTTON') {
              fotoInput.click();
            }
          });
        }

        if (btnReuploadFoto && fotoInput) {
          btnReuploadFoto.addEventListener('click', function(e) {
            e.stopPropagation();
            fotoInput.click();
          });
        }

        if (btnViewFoto && modalFotoImg) {
          btnViewFoto.addEventListener('click', function(e) {
            e.stopPropagation();
            if (previewFotoImg && previewFotoImg.src) {
              modalFotoImg.src = previewFotoImg.src;
              if (typeof $ !== 'undefined' && $('#modalPreviewFoto').modal) {
                $('#modalPreviewFoto').modal('show');
              }
            }
          });
        }

        function openDokumenPreview(file, label) {
          if (!file || !modalDokumenContent) return;

          if (dokumenObjectUrl) {
            URL.revokeObjectURL(dokumenObjectUrl);
            dokumenObjectUrl = null;
          }

          var url = URL.createObjectURL(file);
          dokumenObjectUrl = url;

          modalDokumenContent.innerHTML = '';

          var isImage = file.type && file.type.indexOf('image') === 0;
          var isPdf = file.type === 'application/pdf' || /\.pdf$/i.test(file.name || '');

          if (modalDokumenTitle && label) {
            modalDokumenTitle.textContent = 'Preview ' + label;
          }

          if (isImage) {
            var img = document.createElement('img');
            img.src = url;
            img.style.maxWidth = '100%';
            img.style.height = 'auto';
            img.style.maxHeight = '70vh';
            modalDokumenContent.appendChild(img);
          } else if (isPdf) {
            var iframe = document.createElement('iframe');
            iframe.src = url;
            iframe.style.width = '100%';
            iframe.style.height = '70vh';
            iframe.style.border = 'none';
            modalDokumenContent.appendChild(iframe);
          } else {
            var info = document.createElement('div');
            info.className = 'text-center';
            info.innerHTML = '<p>Tipe file tidak dapat dipreview langsung.</p><p><strong>' + (file.name || '') + '</strong></p>';
            modalDokumenContent.appendChild(info);
          }

          if (typeof $ !== 'undefined' && $('#modalPreviewDokumen').modal) {
            $('#modalPreviewDokumen').modal('show');
          }
        }

        if (btnReuploadIjasah && ijasahInput) {
          btnReuploadIjasah.addEventListener('click', function(e) {
            e.stopPropagation();
            ijasahInput.click();
          });
        }

        if (btnViewIjasah) {
          btnViewIjasah.addEventListener('click', function(e) {
            e.stopPropagation();
            if (ijasahFile) {
              openDokumenPreview(ijasahFile, 'Ijazah / SKL');
            }
          });
        }

        if (btnReuploadKk && kkInput) {
          btnReuploadKk.addEventListener('click', function(e) {
            e.stopPropagation();
            kkInput.click();
          });
        }

        if (btnViewKk) {
          btnViewKk.addEventListener('click', function(e) {
            e.stopPropagation();
            if (kkFile) {
              openDokumenPreview(kkFile, 'Kartu Keluarga (KK)');
            }
          });
        }

        if (btnReuploadAkteLahir && akteLahirInput) {
          btnReuploadAkteLahir.addEventListener('click', function(e) {
            e.stopPropagation();
            akteLahirInput.click();
          });
        }

        if (btnViewAkteLahir) {
          btnViewAkteLahir.addEventListener('click', function(e) {
            e.stopPropagation();
            if (akteLahirFile) {
              openDokumenPreview(akteLahirFile, 'Akte Lahir');
            }
          });
        }

        if (btnReuploadKtp && ktpInput) {
          btnReuploadKtp.addEventListener('click', function(e) {
            e.stopPropagation();
            ktpInput.click();
          });
        }

        if (btnViewKtp) {
          btnViewKtp.addEventListener('click', function(e) {
            e.stopPropagation();
            if (ktpFile) {
              openDokumenPreview(ktpFile, 'KTP');
            }
          });
        }

        if (btnReuploadSertifikat && sertifikatInput) {
          btnReuploadSertifikat.addEventListener('click', function(e) {
            e.stopPropagation();
            sertifikatInput.click();
          });
        }

        if (btnViewSertifikat) {
          btnViewSertifikat.addEventListener('click', function(e) {
            e.stopPropagation();
            if (sertifikatFiles && sertifikatFiles.length > 0) {
              openDokumenPreview(sertifikatFiles[0], 'Sertifikat Pendukung');
            }
          });
        }

        if (previewIjasah && ijasahInput) {
          previewIjasah.addEventListener('click', function() {
            ijasahInput.click();
          });
        }

        if (previewKk && kkInput) {
          previewKk.addEventListener('click', function() {
            kkInput.click();
          });
        }

        if (previewAkteLahir && akteLahirInput) {
          previewAkteLahir.addEventListener('click', function() {
            akteLahirInput.click();
          });
        }

        if (previewKtp && ktpInput) {
          previewKtp.addEventListener('click', function() {
            ktpInput.click();
          });
        }

        if (previewSertifikat && sertifikatInput) {
          previewSertifikat.addEventListener('click', function() {
            sertifikatInput.click();
          });
        }

        showStep(currentStep);
      })();
    </script>
@endpush