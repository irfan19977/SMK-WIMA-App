@extends('home.layouts.app')

@section('content')
<main id="main">

    <!-- ======= Hero Section ======= -->
    <section class="hero-section inner-page position-relative overflow-hidden">
      <!-- Animated Background Elements -->
      <div class="position-absolute w-100 h-100" style="top: 0; left: 0; z-index: 1;">
        <div class="floating-shapes">
          <div class="shape shape-1"></div>
          <div class="shape shape-2"></div>
          <div class="shape shape-3"></div>
          <div class="shape shape-4"></div>
        </div>
      </div>

      <div class="wave">
        <svg width="1920px" height="265px" viewBox="0 0 1920 265" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
          <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <g id="Apple-TV" transform="translate(0.000000, -402.000000)" fill="#FFFFFF">
              <path d="M0,439.134243 C175.04074,464.89273 327.944386,477.771974 458.710937,477.771974 C654.860765,477.771974 870.645295,442.632362 1205.9828,410.192501 C1429.54114,388.565926 1667.54687,411.092417 1920,477.771974 L1920,667 L1017.15166,667 L0,667 L0,439.134243 Z" id="Path"></path>
            </g>
          </g>
        </svg>
      </div>

      <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center min-vh-50">
            <div class="col-12">
                <div class="row justify-content-center">
                    <div class="col-md-10 text-center hero-text">
                        <h1 class="display-4 fw-bold mb-4 text-white" data-aos="fade-up" data-aos-delay="300">
                            Pendaftaran Siswa Baru
                        </h1>
                        <div class="hero-stats" data-aos="fade-up" data-aos-delay="500">
                            <div class="row justify-content-center">
                                <div class="col-md-3 mb-3">
                                    <div class="stat-card card-quota">
                                        <div class="stat-number">200+</div>
                                        <div class="stat-label">Kuota Siswa</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="stat-card card-scholarship">
                                        <div class="stat-number">100%</div>
                                        <div class="stat-label">Beasiswa</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="stat-card card-online">
                                        <div class="stat-number">24/7</div>
                                        <div class="stat-label">Online</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>

    <!-- ======= Registration Form Section ======= -->
    <section class="section bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="registration-form-container">
                        <div class="form-header text-center mb-5">
                            <h2 class="section-title">Formulir Pendaftaran</h2>
                            <p class="text-muted">Lengkapi formulir di bawah ini dengan informasi yang benar dan akurat</p>
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
                        <form method="POST" action="{{ route('pendaftaran.store') }}" class="registration-form" enctype="multipart/form-data">
                            @csrf

                            <!-- Progress Bar -->
                            <div class="progress-wrapper mb-5">
                                <div class="progress-info text-center mb-3">
                                    <span class="progress-text">Langkah <span id="current-step">1</span> dari 3: <span id="step-title">Informasi Pribadi</span></span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" id="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="progress-steps mt-3">
                                    <div class="step-indicator active" data-step="1">1</div>
                                    <div class="step-indicator" data-step="2">2</div>
                                    <div class="step-indicator" data-step="3">3</div>
                                </div>
                            </div>

                            <!-- Step 1: Personal Information -->
                            <div class="form-step active" id="step-1">
                                <h4 class="section-subtitle mb-4">
                                    <i class="bi bi-person-circle me-2"></i>
                                    Data Pribadi <span class="badge bg-primary ms-2">Step 1 dari 3</span>
                                </h4>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">
                                            Nama Lengkap <span class="text-danger">*</span>
                                            <span class="form-help" data-bs-toggle="tooltip" data-bs-placement="top" title="Nama lengkap sesuai dengan identitas resmi">ⓘ</span>
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">
                                            Nomor WhatsApp
                                            <span class="form-help" data-bs-toggle="tooltip" data-bs-placement="top" title="Nomor WhatsApp untuk komunikasi (opsional)">ⓘ</span>
                                        </label>
                                        <input type="number" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Nomor WhatsApp" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nik" class="form-label">
                                            NIK <span class="text-danger">*</span>
                                            <span class="form-help" data-bs-toggle="tooltip" data-bs-placement="top" title="Nomor Induk Kependudukan (16 digit)">ⓘ</span>
                                        </label>
                                        <input type="number" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}" maxlength="16" placeholder="16 digit angka" required>
                                        @error('nik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nisn" class="form-label">
                                            NISN
                                            <span class="form-help" data-bs-toggle="tooltip" data-bs-placement="top" title="Nomor Induk Siswa Nasional">ⓘ</span>
                                        </label>
                                        <input type="number" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" value="{{ old('nisn') }}" maxlength="10" placeholder="10 digit angka">
                                        @error('nisn')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="form-label">
                                            Jenis Kelamin <span class="text-danger">*</span>
                                            <span class="form-help" data-bs-toggle="tooltip" data-bs-placement="top" title="Pilih jenis kelamin sesuai identitas">ⓘ</span>
                                        </label>
                                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="birth_date" class="form-label">
                                            Tanggal Lahir <span class="text-danger">*</span>
                                            <span class="form-help" data-bs-toggle="tooltip" data-bs-placement="top" title="Tanggal lahir sesuai dengan akte kelahiran">ⓘ</span>
                                        </label>
                                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="birth_place" class="form-label">
                                            Tempat Lahir <span class="text-danger">*</span>
                                            <span class="form-help" data-bs-toggle="tooltip" data-bs-placement="top" title="Kota/kabupaten tempat lahir sesuai identitas">ⓘ</span>
                                        </label>
                                        <input type="text" class="form-control @error('birth_place') is-invalid @enderror" id="birth_place" name="birth_place" value="{{ old('birth_place') }}" placeholder="Contoh: Jakarta" required>
                                        @error('birth_place')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="religion" class="form-label">
                                            Agama <span class="text-danger">*</span>
                                            <span class="form-help" data-bs-toggle="tooltip" data-bs-placement="top" title="Agama sesuai dengan identitas resmi">ⓘ</span>
                                        </label>
                                        <select class="form-select @error('religion') is-invalid @enderror" id="religion" name="religion" required>
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

                                <div class="mb-3">
                                    <label for="address" class="form-label">
                                        Alamat Lengkap <span class="text-danger">*</span>
                                        <span class="form-help" data-bs-toggle="tooltip" data-bs-placement="top" title="Alamat lengkap dengan RT/RW dan kode pos">ⓘ</span>
                                    </label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="5" placeholder="Jl. Contoh No. 123, RT.01/RW.02, Kelurahan, Kecamatan, Kota, Kode Pos" required style="height: 150px">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-center">
                                    <button type="button" class="btn btn-primary" id="next-step">
                                        Selanjutnya <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Document Upload -->
                            <div class="form-step" id="step-2" style="display: none;">
                                <h4 class="section-subtitle mb-4">
                                    <i class="bi bi-file-earmark-arrow-up me-2"></i>
                                    Upload Dokumen <span class="badge bg-success ms-2">Step 2 dari 3</span>
                                </h4>

                                <div class="alert alert-info mb-4">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Pastikan dokumen yang diupload:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>• Berformat PDF, JPG, atau PNG</li>
                                        <li>• Ukuran maksimal sesuai ketentuan</li>
                                        <li>• Foto/gambar yang jelas dan dapat dibaca</li>
                                        <li>• File tidak corrupt atau rusak</li>
                                    </ul>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="photo_path" class="form-label">Pas Foto 3x4 <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('photo_path') is-invalid @enderror" id="photo_path" name="photo_path" accept="image/*" required>
                                        <div class="form-text">Format: JPG, PNG. Max: 500KB</div>
                                        @error('photo_path')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="ijazah" class="form-label">Ijazah/SKL <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('ijazah') is-invalid @enderror" id="ijazah" name="ijazah" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <div class="form-text">Format: PDF, JPG, PNG. Max: 500KB</div>
                                        @error('ijazah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="kartu_keluarga" class="form-label">Kartu Keluarga <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('kartu_keluarga') is-invalid @enderror" id="kartu_keluarga" name="kartu_keluarga" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <div class="form-text">Format: PDF, JPG, PNG. Max: 500KB</div>
                                        @error('kartu_keluarga')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="akte_lahir" class="form-label">Akte Kelahiran <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('akte_lahir') is-invalid @enderror" id="akte_lahir" name="akte_lahir" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <div class="form-text">Format: PDF, JPG, PNG. Max: 500KB</div>
                                        @error('akte_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="ktp" class="form-label">KTP Orang Tua<span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('ktp') is-invalid @enderror" id="ktp" name="ktp" accept=".pdf,.jpg,.jpeg,.png" require>
                                        <div class="form-text">Format: PDF, JPG, PNG. Max: 500KB</div>
                                        @error('ktp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="sertifikat" class="form-label">Sertifikat/Piagam (Jika ada)</label>
                                        <input type="file" class="form-control @error('sertifikat') is-invalid @enderror" id="sertifikat" name="sertifikat" accept=".pdf,.jpg,.jpeg,.png" multiple>
                                        <div class="form-text">Format: PDF, JPG, PNG. Max: 500KB per file (Opsional)</div>
                                        @error('sertifikat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-outline-primary prev-step" id="prev-step">
                                            <i class="bi bi-arrow-left me-2"></i> Kembali: Data Pribadi
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button type="button" class="btn btn-primary next-step" id="next-step">
                                            Selanjutnya: Akun & Persyaratan <i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Account & Terms -->
                            <div class="form-step" id="step-3" style="display: none;">
                                <h4 class="section-subtitle mb-4">
                                    <i class="bi bi-key me-2"></i>
                                    Informasi Akun & Persyaratan <span class="badge bg-warning ms-2">Step Terakhir</span>
                                </h4>

                                <div class="alert alert-warning mb-4">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Step Terakhir!</strong> Pastikan semua informasi sudah benar sebelum mengirim formulir.
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="terms-container p-4 bg-primary rounded mb-4">
                                    <div class="form-check mb-3" style="color: white">
                                        <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms" name="terms" required>
                                        <label class="form-check-label" for="terms">
                                            Saya menyatakan bahwa semua informasi yang diberikan adalah benar dan dapat dipertanggungjawabkan.
                                            Saya juga menyetujui <a href="#" style="color: white"><b>syarat dan ketentuan</b></a> serta <a href="#" style="color: white"><b>kebijakan privasi</b></a> yang berlaku.
                                        </label>
                                        @error('terms')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-outline-primary prev-step">
                                            <i class="bi bi-arrow-left me-2"></i> Kembali: Upload Dokumen
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-check-circle me-2"></i>
                                            Kirim Pendaftaran
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======= Registration Info Section ======= -->
    <section class="section cta-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <div class="info-card">
                        <i class="bi bi-calendar-check display-4 mb-3 text-info"></i>
                        <h5 style="color: white"><b>Pendaftaran Online 24/7</b></h5>
                        <p>Daftar kapan saja dan di mana saja melalui sistem online kami</p>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="info-card">
                        <i class="bi bi-headset display-4 mb-3 text-info"></i>
                        <h5 style="color: white"><b>Customer Service</b></h5>
                        <p>Tim CS kami siap membantu Anda dengan pertanyaan seputar pendaftaran</p>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="info-card">
                        <i class="bi bi-shield-check display-4 mb-3 text-info"></i>
                        <h5 style="color: white"><b>Proses Seleksi</b></h5>
                        <p>Sistem seleksi yang transparan dan adil untuk semua calon siswa</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('styles')
    <style>
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape-1 {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }

        .shape-3 {
            width: 60px;
            height: 60px;
            top: 40%;
            left: 70%;
            animation-delay: 4s;
        }

        .shape-4 {
            width: 100px;
            height: 100px;
            bottom: 20%;
            left: 20%;
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .wave svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 265px;
        }

        .hero-text {
            padding: 40px 0;
        }

        .hero-stats {
            margin-top: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #636e72;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Different colors for each stat card */
        .card-quota {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card-scholarship {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card-online {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card-quota:hover {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .card-scholarship:hover {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .card-online:hover {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .registration-form-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 30px;
        }

        .section-title {
            color: #2d3436;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .form-section {
            margin-bottom: 40px;
            padding: 30px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .form-section:last-of-type {
            border-bottom: none;
        }

        .section-subtitle {
            color: #636e72;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .form-label {
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .progress-wrapper {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }

        .progress {
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        .form-step {
            margin-bottom: 40px;
            padding: 30px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .form-step:last-of-type {
            border-bottom: none;
        }

        .progress-steps {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .step-indicator {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .step-indicator.active {
            background: #007bff;
            color: white;
        }

        .step-indicator.completed {
            background: #28a745;
            color: white;
        }

        .next-step, .prev-step {
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .next-step:hover, .prev-step:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }

        .next-step:disabled, .prev-step:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-loading {
            position: relative;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: button-loading-spinner 1s ease infinite;
        }

        @keyframes button-loading-spinner {
            from {
                transform: rotate(0turn);
            }
            to {
                transform: rotate(1turn);
            }
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 25px;
            padding: 15px 40px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .form-help {
            cursor: help;
            color: #6c757d;
            font-size: 0.875rem;
            margin-left: 5px;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        .form-help:hover {
            opacity: 1;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .alert-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .alert ul {
            list-style: none;
            padding-left: 0;
        }

        .alert li {
            margin-bottom: 5px;
        }

        .alert li:last-child {
            margin-bottom: 0;
        }

        /* Tooltip styling */
        .tooltip {
            font-size: 0.875rem;
        }

        .tooltip-inner {
            background-color: #495057;
            color: white;
            border-radius: 6px;
            padding: 8px 12px;
            max-width: 250px;
        }

        .bs-tooltip-top .tooltip-arrow::before {
            border-top-color: #495057;
        }

        .bs-tooltip-bottom .tooltip-arrow::before {
            border-bottom-color: #495057;
        }

        /* Form validation states */
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .form-control.is-invalid:focus, .form-select.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        /* Progress steps styling */
        .progress-steps {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .step-indicator {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }

        .step-indicator.active {
            background: #007bff;
            color: white;
            border-color: #0056b3;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        .step-indicator.completed {
            background: #28a745;
            color: white;
            border-color: #1e7e34;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25);
        }

        @media (max-width: 768px) {
            .hero-section {
                min-height: 35vh;
            }

            .hero-text {
                padding: 30px 0;
            }

            .stat-card {
                margin-bottom: 20px;
            }

            .registration-form-container {
                padding: 20px;
            }

            .form-section {
                padding: 20px 0;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            const totalSteps = 3;
            
            // Step titles untuk progress bar
            const stepTitles = {
                1: 'Informasi Pribadi',
                2: 'Upload Dokumen',
                3: 'Akun & Persyaratan'
            };

            // Fungsi untuk update progress bar
            function updateProgressBar() {
                const progressPercentage = (currentStep / totalSteps) * 100;
                const progressBar = document.getElementById('progress-bar');
                const currentStepElement = document.getElementById('current-step');
                const stepTitleElement = document.getElementById('step-title');
                
                progressBar.style.width = progressPercentage + '%';
                progressBar.setAttribute('aria-valuenow', progressPercentage);
                currentStepElement.textContent = currentStep;
                stepTitleElement.textContent = stepTitles[currentStep];

                // Update step indicators
                document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                    const stepNumber = index + 1;
                    if (stepNumber < currentStep) {
                        indicator.classList.add('completed');
                        indicator.classList.remove('active');
                    } else if (stepNumber === currentStep) {
                        indicator.classList.add('active');
                        indicator.classList.remove('completed');
                    } else {
                        indicator.classList.remove('active', 'completed');
                    }
                });
            }

            // Fungsi untuk menampilkan pesan error
            function showError(field, message) {
                field.classList.add('is-invalid');
                
                // Hapus pesan error lama jika ada
                const oldError = field.parentElement.querySelector('.invalid-feedback.js-error');
                if (oldError) {
                    oldError.remove();
                }
                
                // Tambah pesan error baru
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback js-error';
                errorDiv.style.display = 'block';
                errorDiv.textContent = message;
                field.parentElement.appendChild(errorDiv);
            }

            // Fungsi untuk menghapus pesan error
            function clearError(field) {
                field.classList.remove('is-invalid');
                const errorDiv = field.parentElement.querySelector('.invalid-feedback.js-error');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }

            // Fungsi untuk show/hide step
            function showStep(stepNumber) {
                // Hide semua step
                document.querySelectorAll('.form-step').forEach(step => {
                    step.style.display = 'none';
                    step.classList.remove('active');
                });

                // Show step yang dipilih
                const targetStep = document.getElementById('step-' + stepNumber);
                if (targetStep) {
                    targetStep.style.display = 'block';
                    targetStep.classList.add('active');
                }

                currentStep = stepNumber;
                updateProgressBar();

                // Scroll ke atas form
                document.querySelector('.registration-form-container').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }

            // Fungsi validasi step 1
            function validateStep1() {
                const fields = {
                    'name': 'Nama Lengkap wajib diisi',
                    'phone': 'Nomor Whatsapp wajib diisi',
                    'gender': 'Jenis Kelamin wajib dipilih',
                    'birth_date': 'Tanggal Lahir wajib diisi',
                    'birth_place': 'Tempat Lahir wajib diisi',
                    'religion': 'Agama wajib dipilih',
                    'nik': 'NIK wajib diisi',
                    'nisn': 'NISN wajib diisi',
                    'address': 'Alamat Lengkap wajib diisi',
                    'phone': 'Nomor Telepon wajib diisi'
                };

                let isValid = true;
                let firstInvalidField = null;

                // Validasi semua field wajib
                for (const [fieldName, errorMessage] of Object.entries(fields)) {
                    const field = document.getElementById(fieldName);
                    if (field && !field.value.trim()) {
                        showError(field, errorMessage);
                        isValid = false;
                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                    } else if (field) {
                        clearError(field);
                    }
                }

                // Validasi khusus NIK (harus 16 digit)
                const nikField = document.getElementById('nik');
                if (nikField && nikField.value.trim()) {
                    if (nikField.value.length !== 16) {
                        showError(nikField, 'NIK harus 16 digit');
                        isValid = false;
                        if (!firstInvalidField) {
                            firstInvalidField = nikField;
                        }
                    } else if (!/^\d+$/.test(nikField.value)) {
                        showError(nikField, 'NIK harus berupa angka');
                        isValid = false;
                        if (!firstInvalidField) {
                            firstInvalidField = nikField;
                        }
                    }
                }

                // Validasi NISN jika diisi (harus 10 digit)
                const nisnField = document.getElementById('nisn');
                if (nisnField && nisnField.value.trim()) {
                    if (nisnField.value.length !== 10) {
                        showError(nisnField, 'NISN harus 10 digit');
                        isValid = false;
                        if (!firstInvalidField) {
                            firstInvalidField = nisnField;
                        }
                    } else if (!/^\d+$/.test(nisnField.value)) {
                        showError(nisnField, 'NISN harus berupa angka');
                        isValid = false;
                        if (!firstInvalidField) {
                            firstInvalidField = nisnField;
                        }
                    }
                }

                if (!isValid && firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                return isValid;
            }

            // Fungsi validasi step 2
            function validateStep2() {
                const requiredFiles = {
                    'photo_path': 'Pas Foto 3x4 wajib diupload',
                    'ijazah': 'Ijazah/SKL wajib diupload',
                    'kartu_keluarga': 'Kartu Keluarga wajib diupload',
                    'akte_lahir': 'Akte Kelahiran wajib diupload'
                };

                let isValid = true;
                let firstInvalidField = null;

                for (const [fieldName, errorMessage] of Object.entries(requiredFiles)) {
                    const field = document.getElementById(fieldName);
                    if (field && !field.files.length) {
                        showError(field, errorMessage);
                        isValid = false;
                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                    } else if (field) {
                        clearError(field);
                        
                        // Validasi ukuran file
                        const file = field.files[0];
                        const maxSize = 500000; // 500KB
                        
                        if (file && file.size > maxSize) {
                            showError(field, `Ukuran file maksimal 500KB`);
                            isValid = false;
                            if (!firstInvalidField) {
                                firstInvalidField = field;
                            }
                        }
                    }
                }

                if (!isValid && firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                return isValid;
            }

            // Fungsi validasi step 3
            function validateStep3() {
                let isValid = true;
                let firstInvalidField = null;

                // Validasi email
                const emailField = document.getElementById('email');
                if (emailField) {
                    if (!emailField.value.trim()) {
                        showError(emailField, 'Email wajib diisi');
                        isValid = false;
                        firstInvalidField = emailField;
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailField.value)) {
                        showError(emailField, 'Format email tidak valid');
                        isValid = false;
                        firstInvalidField = emailField;
                    } else {
                        clearError(emailField);
                    }
                }

                // Validasi password
                const passwordField = document.getElementById('password');
                const passwordConfField = document.getElementById('password_confirmation');
                
                if (passwordField) {
                    if (!passwordField.value) {
                        showError(passwordField, 'Password wajib diisi');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = passwordField;
                    } else if (passwordField.value.length < 8) {
                        showError(passwordField, 'Password minimal 8 karakter');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = passwordField;
                    } else {
                        clearError(passwordField);
                    }
                }

                if (passwordConfField) {
                    if (!passwordConfField.value) {
                        showError(passwordConfField, 'Konfirmasi Password wajib diisi');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = passwordConfField;
                    } else if (passwordField.value !== passwordConfField.value) {
                        showError(passwordConfField, 'Password tidak sama');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = passwordConfField;
                    } else {
                        clearError(passwordConfField);
                    }
                }

                // Validasi terms
                const termsField = document.getElementById('terms');
                if (termsField && !termsField.checked) {
                    showError(termsField, 'Anda harus menyetujui syarat dan ketentuan');
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = termsField;
                } else if (termsField) {
                    clearError(termsField);
                }

                if (!isValid && firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                return isValid;
            }

            // Event listener untuk tombol Next di step 1
            const nextStepBtn = document.querySelector('#step-1 #next-step');
            if (nextStepBtn) {
                nextStepBtn.addEventListener('click', function() {
                    if (validateStep1()) {
                        showStep(2);
                    }
                });
            }

            // Event listener untuk tombol Next di step 2
            const step2NextBtn = document.querySelector('#step-2 .next-step');
            if (step2NextBtn) {
                step2NextBtn.addEventListener('click', function() {
                    if (validateStep2()) {
                        showStep(3);
                    }
                });
            }

            // Event listener untuk semua tombol Previous
            document.querySelectorAll('.prev-step').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (currentStep > 1) {
                        showStep(currentStep - 1);
                    }
                });
            });

            // Remove invalid class dan error message saat user mulai mengisi field
            document.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('input', function() {
                    clearError(this);
                });
                
                field.addEventListener('change', function() {
                    clearError(this);
                });
            });

            // Validasi sebelum submit
            const form = document.querySelector('.registration-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Validasi step 3 sebelum submit
                    if (!validateStep3()) {
                        e.preventDefault();
                        return false;
                    }
                });
            }

            // Initialize tooltips (jika menggunakan Bootstrap)
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            if (typeof bootstrap !== 'undefined') {
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Initialize step pertama
            updateProgressBar();
        });
    </script>
@endpush
