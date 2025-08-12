@extends('home.layouts.app')

@section('content')

<!-- ======= Hero Section ======= -->
<section class="hero-section registration-hero position-relative overflow-hidden">
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
            <h1 class="display-3 fw-bold mb-4" data-aos="fade-up" data-aos-delay="100">
              <span class="text-primary">Pendaftaran</span> 
              <span class="text-gradient">Siswa Baru</span>
            </h1>
            <p class="lead mb-5 text-muted" data-aos="fade-up" data-aos-delay="200">
              Bergabunglah bersama keluarga besar Sekolah XYZ dan wujudkan masa depan cemerlang Anda
            </p>
            <div class="hero-stats" data-aos="fade-up" data-aos-delay="300">
              <div class="row justify-content-center">
                <div class="col-md-3 col-6 mb-3">
                  <div class="stat-card">
                    <h4 class="text-success fw-bold">98%</h4>
                    <p class="small mb-0">Tingkat Kelulusan</p>
                  </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                  <div class="stat-card">
                    <h4 class="text-primary fw-bold">500+</h4>
                    <p class="small mb-0">Siswa Aktif</p>
                  </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                  <div class="stat-card">
                    <h4 class="text-warning fw-bold">50+</h4>
                    <p class="small mb-0">Guru Berpengalaman</p>
                  </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                  <div class="stat-card">
                    <h4 class="text-info fw-bold">25+</h4>
                    <p class="small mb-0">Tahun Berpengalaman</p>
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
<section class="section registration-section bg-light position-relative">
  <div class="section-bg-pattern"></div>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        
        <!-- Registration Steps -->
        <div class="registration-steps mb-5" data-aos="fade-up">
          <div class="steps-container">
            <div class="step active" data-step="1">
              <div class="step-number">1</div>
              <div class="step-title">Akun & Login</div>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="2">
              <div class="step-number">2</div>
              <div class="step-title">Data Pribadi</div>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="3">
              <div class="step-number">3</div>
              <div class="step-title">Data Orang Tua</div>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="4">
              <div class="step-number">4</div>
              <div class="step-title">Dokumen</div>
            </div>
          </div>
        </div>

        <!-- Registration Form -->
        <div class="registration-form-container" data-aos="fade-up" data-aos-delay="200">
          <form id="registrationForm" class="registration-form">
            
            <!-- Step 1: Account & Login Information -->
            <div class="form-step active" id="step1">
              <div class="step-header">
                <h3 class="fw-bold text-primary mb-2">
                  <i class="bi bi-person-circle me-2"></i>Buat Akun Dashboard
                </h3>
                <p class="text-muted">Buat akun untuk mengakses dashboard siswa dan monitoring kegiatan akademik</p>
              </div>
              
              <div class="row g-4">
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    <label for="email"><i class="bi bi-envelope me-2"></i>Email</label>
                    <div class="form-text">Email ini akan digunakan untuk login ke dashboard</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="email" class="form-control" id="confirm_email" name="confirm_email" placeholder="Konfirmasi Email" required>
                    <label for="confirm_email"><i class="bi bi-envelope-check me-2"></i>Konfirmasi Email</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                    <button type="button" class="btn-password-toggle" onclick="togglePassword('password')">
                      <i class="bi bi-eye" id="password-icon"></i>
                    </button>
                    <div class="form-text">Minimal 8 karakter, mengandung huruf dan angka</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating position-relative">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Password" required>
                    <label for="confirm_password"><i class="bi bi-lock-fill me-2"></i>Konfirmasi Password</label>
                    <button type="button" class="btn-password-toggle" onclick="togglePassword('confirm_password')">
                      <i class="bi bi-eye" id="confirm_password-icon"></i>
                    </button>
                  </div>
                </div>
              </div>
              
              <div class="step-actions">
                <button type="button" class="btn btn-primary btn-lg" onclick="nextStep()">
                  Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                </button>
              </div>
            </div>

            <!-- Step 2: Personal Data -->
            <div class="form-step" id="step2">
              <div class="step-header">
                <h3 class="fw-bold text-primary mb-2">
                  <i class="bi bi-person-badge me-2"></i>Data Pribadi Siswa
                </h3>
                <p class="text-muted">Lengkapi data pribadi siswa dengan benar dan sesuai dokumen resmi</p>
              </div>
              
              <!-- Photo Upload -->
              <div class="photo-upload-section mb-4">
                <div class="row align-items-center">
                  <div class="col-md-3 text-center">
                    <div class="photo-preview">
                      <img id="photo-preview" src="https://via.placeholder.com/150x200/e9ecef/6c757d?text=Foto+Siswa" alt="Preview Foto" class="img-fluid rounded">
                      <div class="photo-overlay">
                        <i class="bi bi-camera-fill"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="upload-area" onclick="document.getElementById('student_photo').click()">
                      <i class="bi bi-cloud-upload text-primary"></i>
                      <h6 class="fw-bold mt-2">Upload Foto Siswa</h6>
                      <p class="text-muted small mb-0">Klik untuk memilih foto atau drag & drop</p>
                      <p class="text-muted small">Format: JPG, PNG (Max: 2MB)</p>
                    </div>
                    <input type="file" id="student_photo" name="student_photo" accept="image/*" hidden>
                  </div>
                </div>
              </div>
              
              <div class="row g-4">
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nama Lengkap" required>
                    <label for="full_name"><i class="bi bi-person me-2"></i>Nama Lengkap</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Nama Panggilan">
                    <label for="nickname"><i class="bi bi-person-heart me-2"></i>Nama Panggilan</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <select class="form-select" id="gender" name="gender" required>
                      <option value="">Pilih Jenis Kelamin</option>
                      <option value="L">Laki-laki</option>
                      <option value="P">Perempuan</option>
                    </select>
                    <label for="gender"><i class="bi bi-gender-ambiguous me-2"></i>Jenis Kelamin</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                    <label for="birth_date"><i class="bi bi-calendar-heart me-2"></i>Tanggal Lahir</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="birth_place" name="birth_place" placeholder="Tempat Lahir" required>
                    <label for="birth_place"><i class="bi bi-geo-alt me-2"></i>Tempat Lahir</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <select class="form-select" id="religion" name="religion" required>
                      <option value="">Pilih Agama</option>
                      <option value="Islam">Islam</option>
                      <option value="Kristen">Kristen</option>
                      <option value="Katolik">Katolik</option>
                      <option value="Hindu">Hindu</option>
                      <option value="Buddha">Buddha</option>
                      <option value="Konghucu">Konghucu</option>
                    </select>
                    <label for="religion"><i class="bi bi-peace me-2"></i>Agama</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="nisn" name="nisn" placeholder="NISN">
                    <label for="nisn"><i class="bi bi-card-text me-2"></i>NISN</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="nik" name="nik" placeholder="NIK" required>
                    <label for="nik"><i class="bi bi-credit-card me-2"></i>NIK</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating">
                    <textarea class="form-control" id="address" name="address" placeholder="Alamat Lengkap" style="height: 100px" required></textarea>
                    <label for="address"><i class="bi bi-house me-2"></i>Alamat Lengkap</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="No. Telepon" required>
                    <label for="phone"><i class="bi bi-telephone me-2"></i>No. Telepon</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="whatsapp" name="whatsapp" placeholder="No. WhatsApp">
                    <label for="whatsapp"><i class="bi bi-whatsapp me-2"></i>No. WhatsApp</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating">
                    <select class="form-select" id="blood_type" name="blood_type">
                      <option value="">Pilih Golongan Darah</option>
                      <option value="A">A</option>
                      <option value="B">B</option>
                      <option value="AB">AB</option>
                      <option value="O">O</option>
                    </select>
                    <label for="blood_type"><i class="bi bi-droplet me-2"></i>Golongan Darah</label>
                  </div>
                </div>
              </div>
              
              <div class="step-actions">
                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="prevStep()">
                  <i class="bi bi-arrow-left me-2"></i>Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-lg" onclick="nextStep()">
                  Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                </button>
              </div>
            </div>

            <!-- Step 3: Parent Data -->
            <div class="form-step" id="step3">
              <div class="step-header">
                <h3 class="fw-bold text-primary mb-2">
                  <i class="bi bi-people me-2"></i>Data Orang Tua / Wali
                </h3>
                <p class="text-muted">Lengkapi data orang tua atau wali yang bertanggung jawab</p>
              </div>
              
              <!-- Father Data -->
              <div class="parent-section mb-5">
                <h5 class="section-title">
                  <i class="bi bi-person-fill text-primary me-2"></i>Data Ayah
                </h5>
                <div class="row g-4">
                  <div class="col-md-6">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Nama Ayah" required>
                      <label for="father_name"><i class="bi bi-person me-2"></i>Nama Lengkap Ayah</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="father_nik" name="father_nik" placeholder="NIK Ayah" required>
                      <label for="father_nik"><i class="bi bi-credit-card me-2"></i>NIK Ayah</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating">
                      <input type="date" class="form-control" id="father_birth_date" name="father_birth_date">
                      <label for="father_birth_date"><i class="bi bi-calendar me-2"></i>Tanggal Lahir Ayah</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="father_occupation" name="father_occupation" placeholder="Pekerjaan Ayah" required>
                      <label for="father_occupation"><i class="bi bi-briefcase me-2"></i>Pekerjaan Ayah</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating">
                      <select class="form-select" id="father_education" name="father_education">
                        <option value="">Pilih Pendidikan Terakhir</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                        <option value="D3">D3</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                        <option value="S3">S3</option>
                      </select>
                      <label for="father_education"><i class="bi bi-mortarboard me-2"></i>Pendidikan Terakhir</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="father_income" name="father_income" placeholder="Penghasilan Ayah">
                      <label for="father_income"><i class="bi bi-cash me-2"></i>Penghasilan per Bulan</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="father_phone" name="father_phone" placeholder="No. Telepon Ayah" required>
                      <label for="father_phone"><i class="bi bi-telephone me-2"></i>No. Telepon Ayah</label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Mother Data -->
              <div class="parent-section mb-5">
                <h5 class="section-title">
                  <i class="bi bi-person-heart text-danger me-2"></i>Data Ibu
                </h5>
                <div class="row g-4">
                  <div class="col-md-6">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Nama Ibu" required>
                      <label for="mother_name"><i class="bi bi-person me-2"></i>Nama Lengkap Ibu</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="mother_nik" name="mother_nik" placeholder="NIK Ibu" required>
                      <label for="mother_nik"><i class="bi bi-credit-card me-2"></i>NIK Ibu</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating">
                      <input type="date" class="form-control" id="mother_birth_date" name="mother_birth_date">
                      <label for="mother_birth_date"><i class="bi bi-calendar me-2"></i>Tanggal Lahir Ibu</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="mother_occupation" name="mother_occupation" placeholder="Pekerjaan Ibu" required>
                      <label for="mother_occupation"><i class="bi bi-briefcase me-2"></i>Pekerjaan Ibu</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating">
                      <select class="form-select" id="mother_education" name="mother_education">
                        <option value="">Pilih Pendidikan Terakhir</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                        <option value="D3">D3</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                        <option value="S3">S3</option>
                      </select>
                      <label for="mother_education"><i class="bi bi-mortarboard me-2"></i>Pendidikan Terakhir</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="mother_income" name="mother_income" placeholder="Penghasilan Ibu">
                      <label for="mother_income"><i class="bi bi-cash me-2"></i>Penghasilan per Bulan</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="mother_phone" name="mother_phone" placeholder="No. Telepon Ibu" required>
                      <label for="mother_phone"><i class="bi bi-telephone me-2"></i>No. Telepon Ibu</label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Guardian Data -->
              <div class="parent-section">
                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" id="has_guardian" name="has_guardian">
                  <label class="form-check-label fw-bold" for="has_guardian">
                    <i class="bi bi-person-plus me-2"></i>Ada Wali Selain Orang Tua
                  </label>
                </div>
                <div id="guardian_data" style="display: none;">
                  <h5 class="section-title">
                    <i class="bi bi-person-check text-success me-2"></i>Data Wali
                  </h5>
                  <div class="row g-4">
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="guardian_name" name="guardian_name" placeholder="Nama Wali">
                        <label for="guardian_name"><i class="bi bi-person me-2"></i>Nama Lengkap Wali</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="guardian_relation" name="guardian_relation" placeholder="Hubungan dengan Siswa">
                        <label for="guardian_relation"><i class="bi bi-people me-2"></i>Hubungan dengan Siswa</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="guardian_occupation" name="guardian_occupation" placeholder="Pekerjaan Wali">
                        <label for="guardian_occupation"><i class="bi bi-briefcase me-2"></i>Pekerjaan Wali</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="guardian_phone" name="guardian_phone" placeholder="No. Telepon Wali">
                        <label for="guardian_phone"><i class="bi bi-telephone me-2"></i>No. Telepon Wali</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="step-actions">
                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="prevStep()">
                  <i class="bi bi-arrow-left me-2"></i>Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-lg" onclick="nextStep()">
                  Lanjutkan <i class="bi bi-arrow-right ms-2"></i>
                </button>
              </div>
            </div>

            <!-- Step 4: Documents -->
            <div class="form-step" id="step4">
              <div class="step-header">
                <h3 class="fw-bold text-primary mb-2">
                  <i class="bi bi-file-earmark-text me-2"></i>Upload Dokumen
                </h3>
                <p class="text-muted">Upload dokumen yang diperlukan untuk proses pendaftaran</p>
              </div>
              
              <div class="documents-grid">
                <!-- Birth Certificate -->
                <div class="document-item">
                  <div class="document-header">
                    <i class="bi bi-file-earmark-person text-primary"></i>
                    <div>
                      <h6 class="fw-bold mb-1">Akta Kelahiran</h6>
                      <p class="text-muted small mb-0">Format: PDF, JPG, PNG (Max: 5MB)</p>
                    </div>
                    <span class="required-badge">Wajib</span>
                  </div>
                  <div class="upload-zone" onclick="document.getElementById('birth_certificate').click()">
                    <i class="bi bi-cloud-upload"></i>
                    <p class="mb-0">Klik untuk upload atau drag & drop</p>
                    <div class="upload-progress" style="display: none;">
                      <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                      </div>
                    </div>
                    <div class="upload-success" style="display: none;">
                      <i class="bi bi-check-circle-fill text-success"></i>
                      <span class="filename"></span>
                    </div>
                  </div>
                  <input type="file" id="birth_certificate" name="birth_certificate" accept=".pdf,.jpg,.png" hidden>
                </div>

                <!-- Family Card -->
                <div class="document-item">
                  <div class="document-header">
                    <i class="bi bi-file-earmark-medical text-success"></i>
                    <div>
                      <h6 class="fw-bold mb-1">Kartu Keluarga</h6>
                      <p class="text-muted small mb-0">Format: PDF, JPG, PNG (Max: 5MB)</p>
                    </div>
                    <span class="required-badge">Wajib</span>
                  </div>
                  <div class="upload-zone" onclick="document.getElementById('family_card').click()">
                    <i class="bi bi-cloud-upload"></i>
                    <p class="mb-0">Klik untuk upload atau drag & drop</p>
                    <div class="upload-progress" style="display: none;">
                      <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                      </div>
                    </div>
                    <div class="upload-success" style="display: none;">
                      <i class="bi bi-check-circle-fill text-success"></i>
                      <span class="filename"></span>
                    </div>
                  </div>
                  <input type="file" id="family_card" name="family_card" accept=".pdf,.jpg,.png" hidden>
                </div>

                <!-- Previous School Certificate -->
                <div class="document-item">
                  <div class="document-header">
                    <i class="bi bi-file-earmark-certificate text-warning"></i>
                    <div>
                      <h6 class="fw-bold mb-1">Ijazah/SKHUN Sekolah Sebelumnya</h6>
                      <p class="text-muted small mb-0">Format: PDF, JPG, PNG (Max: 5MB)</p>
                    </div>
                    <span class="required-badge">Wajib</span>
                  </div>
                  <div class="upload-zone" onclick="document.getElementById('previous_certificate').click()">
                    <i class="bi bi-cloud-upload"></i>
                    <p class="mb-0">Klik untuk upload atau drag & drop</p>
                    <div class="upload-progress" style="display: none;">
                      <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                      </div>
                    </div>
                    <div class="upload-success" style="display: none;">
                      <i class="bi bi-check-circle-fill text-success"></i>
                      <span class="filename"></span>
                    </div>
                  </div>
                  <input type="file" id="previous_certificate" name="previous_certificate" accept=".pdf,.jpg,.png" hidden>
                </div>

                <!-- Health Certificate -->
                <div class="document-item">
                  <div class="document-header">
                    <i class="bi bi-file-earmark-plus text-info"></i>
                    <div>
                      <h6 class="fw-bold mb-1">Surat Keterangan Sehat</h6>
                      <p class="text-muted small mb-0">Format: PDF, JPG, PNG (Max: 5MB)</p>
                    </div>
                    <span class="optional-badge">Opsional</span>
                  </div>
                  <div class="upload-zone" onclick="document.getElementById('health_certificate').click()">
                    <i class="bi bi-cloud-upload"></i>
                    <p class="mb-0">Klik untuk upload atau drag & drop</p>
                    <div class="upload-progress" style="display: none;">
                      <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                      </div>
                    </div>
                    <div class="upload-success" style="display: none;">
                      <i class="bi bi-check-circle-fill text-success"></i>
                      <span class="filename"></span>
                    </div>
                  </div>
                  <input type="file" id="health_certificate" name="health_certificate" accept=".pdf,.jpg,.png" hidden>
                </div>

                <!-- Achievement Certificate -->
                <div class="document-item">
                  <div class="document-header">
                    <i class="bi bi-file-earmark-award text-success"></i>
                    <div>
                      <h6 class="fw-bold mb-1">Sertifikat Prestasi</h6>
                      <p class="text-muted small mb-0">Format: PDF, JPG, PNG (Max: 5MB per file)</p>
                    </div>
                    <span class="optional-badge">Opsional</span>
                  </div>
                  <div class="upload-zone multiple" onclick="document.getElementById('achievement_certificates').click()">
                    <i class="bi bi-cloud-upload"></i>
                    <p class="mb-0">Klik untuk upload atau drag & drop</p>
                    <p class="small text-muted">Bisa upload multiple file</p>
                    <div class="upload-progress" style="display: none;">
                      <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                      </div>
                    </div>
                    <div class="upload-success" style="display: none;">
                      <i class="bi bi-check-circle-fill text-success"></i>
                      <span class="filename"></span>
                    </div>
                  </div>
                  <input type="file" id="achievement_certificates" name="achievement_certificates[]" accept=".pdf,.jpg,.png" multiple hidden>
                </div>
              </div>

              <!-- Terms and Conditions -->
              <div class="terms-section mt-5">
                <div class="terms-card">
                  <h5 class="fw-bold mb-3">
                    <i class="bi bi-shield-check text-success me-2"></i>Syarat dan Ketentuan
                  </h5>
                  <div class="terms-content">
                    <ul class="list-unstyled">
                      <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Data yang diisi harus benar dan sesuai dengan dokumen resmi</li>
                      <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Dokumen yang diupload harus jelas dan dapat dibaca</li>
                      <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Pendaftaran tidak dapat dibatalkan setelah submit</li>
                      <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Sekolah berhak memverifikasi kebenaran data dan dokumen</li>
                      <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Pengumuman hasil seleksi akan diinformasikan melalui email dan SMS</li>
                    </ul>
                  </div>
                  <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" required>
                    <label class="form-check-label fw-bold" for="agree_terms">
                      Saya menyetujui semua syarat dan ketentuan yang berlaku
                    </label>
                  </div>
                </div>
              </div>
              
              <div class="step-actions">
                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="prevStep()">
                  <i class="bi bi-arrow-left me-2"></i>Sebelumnya
                </button>
                <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                  <i class="bi bi-send me-2"></i>Kirim Pendaftaran
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======= Success Modal ======= -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center p-5">
        <div class="success-animation mb-4">
          <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
        </div>
        <h3 class="fw-bold text-success mb-3">Pendaftaran Berhasil!</h3>
        <p class="text-muted mb-4">
          Terima kasih telah mendaftar di Sekolah XYZ. Data Anda telah berhasil dikirim dan akan segera diproses oleh tim kami.
        </p>
        <div class="alert alert-info">
          <i class="bi bi-info-circle me-2"></i>
          <strong>Nomor Pendaftaran: #REG2024001</strong>
        </div>
        <p class="small text-muted mb-4">
          Simpan nomor pendaftaran ini untuk keperluan tracking status pendaftaran Anda.
        </p>
        <div class="d-grid gap-2">
          <button type="button" class="btn btn-success btn-lg" onclick="window.location.reload()">
            <i class="bi bi-house me-2"></i>Kembali ke Beranda
          </button>
          <button type="button" class="btn btn-outline-primary" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>Cetak Bukti Pendaftaran
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Hero Section */
.registration-hero {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 70vh;
  position: relative;
}

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

.text-gradient {
  background: linear-gradient(45deg, #007bff, #28a745, #ffc107);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.stat-card {
  background: rgba(255, 255, 255, 0.1);
  padding: 1.5rem;
  border-radius: 15px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Registration Steps */
.registration-steps {
  padding: 2rem;
  background: white;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.steps-container {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  gap: 1rem;
}

.step {
  display: flex;
  flex-direction: column;
  align-items: center;
  opacity: 0.5;
  transition: all 0.3s ease;
}

.step.active {
  opacity: 1;
}

.step.completed {
  opacity: 1;
}

.step-number {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: #e9ecef;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  color: #6c757d;
  margin-bottom: 0.5rem;
  transition: all 0.3s ease;
}

.step.active .step-number {
  background: var(--bs-primary);
  color: white;
}

.step.completed .step-number {
  background: var(--bs-success);
  color: white;
}

.step-title {
  font-size: 0.9rem;
  font-weight: 600;
  text-align: center;
  color: #6c757d;
}

.step.active .step-title {
  color: var(--bs-primary);
}

.step.completed .step-title {
  color: var(--bs-success);
}

.step-line {
  width: 80px;
  height: 2px;
  background: #e9ecef;
  margin: 0 1rem;
  transition: all 0.3s ease;
}

.step.completed + .step-line {
  background: var(--bs-success);
}

/* Form Container */
.registration-form-container {
  background: white;
  border-radius: 20px;
  padding: 3rem;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
}

.form-step {
  display: none;
}

.form-step.active {
  display: block;
  animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.step-header {
  text-align: center;
  margin-bottom: 3rem;
  padding-bottom: 2rem;
  border-bottom: 2px solid #f8f9fa;
}

.step-header h3 {
  font-size: 1.75rem;
  margin-bottom: 1rem;
}

/* Form Controls */
.form-floating {
  margin-bottom: 1rem;
}

.form-floating > .form-control,
.form-floating > .form-select {
  border: 2px solid #e9ecef;
  border-radius: 15px;
  padding: 1rem 0.75rem;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.form-floating > .form-control:focus,
.form-floating > .form-select:focus {
  border-color: var(--bs-primary);
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.form-floating > label {
  padding: 1rem 0.75rem;
  font-weight: 500;
  color: #6c757d;
}

/* Password Toggle */
.btn-password-toggle {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #6c757d;
  z-index: 10;
  cursor: pointer;
  transition: color 0.3s ease;
}

.btn-password-toggle:hover {
  color: var(--bs-primary);
}

/* Photo Upload */
.photo-upload-section {
  background: #f8f9fa;
  border-radius: 20px;
  padding: 2rem;
  border: 2px dashed #dee2e6;
}

.photo-preview {
  position: relative;
  width: 150px;
  height: 200px;
  margin: 0 auto;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.photo-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.photo-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
  cursor: pointer;
}

.photo-preview:hover .photo-overlay {
  opacity: 1;
}

.photo-overlay i {
  color: white;
  font-size: 2rem;
}

.upload-area {
  border: 2px dashed #dee2e6;
  border-radius: 15px;
  padding: 2rem;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  background: white;
}

.upload-area:hover {
  border-color: var(--bs-primary);
  background: rgba(13, 110, 253, 0.05);
}

.upload-area i {
  font-size: 2rem;
  color: var(--bs-primary);
  margin-bottom: 1rem;
}

/* Parent Sections */
.parent-section {
  background: #f8f9fa;
  border-radius: 20px;
  padding: 2rem;
  border-left: 4px solid var(--bs-primary);
}

.section-title {
  font-size: 1.25rem;
  margin-bottom: 1.5rem;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid #dee2e6;
}

/* Guardian Toggle */
#guardian_data {
  margin-top: 1rem;
  padding: 2rem;
  background: white;
  border-radius: 15px;
  border: 2px solid #e9ecef;
}

/* Documents Grid */
.documents-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
}

.document-item {
  background: white;
  border-radius: 20px;
  padding: 1.5rem;
  border: 2px solid #e9ecef;
  transition: all 0.3s ease;
}

.document-item:hover {
  border-color: var(--bs-primary);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.document-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
}

.document-header i {
  font-size: 2rem;
}

.document-header div {
  flex: 1;
}

.required-badge {
  background: var(--bs-danger);
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 15px;
  font-size: 0.75rem;
  font-weight: bold;
}

.optional-badge {
  background: var(--bs-secondary);
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 15px;
  font-size: 0.75rem;
  font-weight: bold;
}

.upload-zone {
  border: 2px dashed #dee2e6;
  border-radius: 15px;
  padding: 2rem;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  background: #f8f9fa;
}

.upload-zone:hover {
  border-color: var(--bs-primary);
  background: rgba(13, 110, 253, 0.05);
}

.upload-zone i {
  font-size: 2rem;
  color: var(--bs-primary);
  margin-bottom: 1rem;
  display: block;
}

.upload-progress {
  margin-top: 1rem;
}

.upload-success {
  margin-top: 1rem;
  color: var(--bs-success);
}

.upload-success i {
  font-size: 1.5rem;
  margin-right: 0.5rem;
}

/* Terms Section */
.terms-section {
  background: #f8f9fa;
  border-radius: 20px;
  padding: 2rem;
}

.terms-card {
  background: white;
  border-radius: 15px;
  padding: 2rem;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.terms-content ul li {
  padding: 0.5rem 0;
}

/* Step Actions */
.step-actions {
  display: flex;
  justify-content: space-between;
  gap: 1rem;
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 2px solid #f8f9fa;
}

.step-actions .btn {
  padding: 1rem 2rem;
  border-radius: 25px;
  font-weight: 600;
  transition: all 0.3s ease;
}

.step-actions .btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Success Modal */
.success-animation {
  animation: bounceIn 0.8s ease-out;
}

@keyframes bounceIn {
  0% {
    opacity: 0;
    transform: scale(0.3);
  }
  50% {
    opacity: 1;
    transform: scale(1.05);
  }
  70% {
    transform: scale(0.9);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

/* Section Background */
.section-bg-pattern::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(45deg, transparent 40%, rgba(0, 123, 255, 0.05) 50%, transparent 60%);
}

/* Responsive Design */
@media (max-width: 768px) {
  .registration-form-container {
    padding: 2rem 1.5rem;
  }
  
  .step-header h3 {
    font-size: 1.5rem;
  }
  
  .steps-container {
    flex-direction: column;
    gap: 1rem;
  }
  
  .step-line {
    width: 2px;
    height: 30px;
    margin: 0.5rem 0;
  }
  
  .documents-grid {
    grid-template-columns: 1fr;
  }
  
  .step-actions {
    flex-direction: column;
  }
  
  .parent-section {
    padding: 1.5rem;
  }
  
  .photo-upload-section {
    padding: 1.5rem;
  }
  
  .upload-area {
    padding: 1.5rem;
  }
}

@media (max-width: 576px) {
  .display-3 {
    font-size: 2rem;
  }
  
  .hero-stats .col-6 {
    margin-bottom: 1rem;
  }
  
  .stat-card {
    padding: 1rem;
  }
  
  .registration-steps {
    padding: 1.5rem;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 4;

    // Initialize form
    showStep(currentStep);

    // File upload handling
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', handleFileUpload);
    });

    // Photo preview
    const photoInput = document.getElementById('student_photo');
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Guardian checkbox
    const guardianCheckbox = document.getElementById('has_guardian');
    const guardianData = document.getElementById('guardian_data');
    
    guardianCheckbox.addEventListener('change', function() {
        if (this.checked) {
            guardianData.style.display = 'block';
        } else {
            guardianData.style.display = 'none';
        }
    });

    // Form validation
    const form = document.getElementById('registrationForm');
    form.addEventListener('submit', handleFormSubmit);

    // Email confirmation validation
    const email = document.getElementById('email');
    const confirmEmail = document.getElementById('confirm_email');
    
    confirmEmail.addEventListener('input', function() {
        if (this.value !== email.value) {
            this.setCustomValidity('Email tidak cocok');
        } else {
            this.setCustomValidity('');
        }
    });

    // Password confirmation validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    confirmPassword.addEventListener('input', function() {
        if (this.value !== password.value) {
            this.setCustomValidity('Password tidak cocok');
        } else {
            this.setCustomValidity('');
        }
    });

    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.form-step').forEach(s => {
            s.classList.remove('active');
        });
        
        // Show current step
        document.getElementById(`step${step}`).classList.add('active');
        
        // Update step indicators
        document.querySelectorAll('.step').forEach((s, index) => {
            s.classList.remove('active', 'completed');
            if (index + 1 < step) {
                s.classList.add('completed');
            } else if (index + 1 === step) {
                s.classList.add('active');
            }
        });
    }

    window.nextStep = function() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        }
    };

    window.prevStep = function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    };

    function validateCurrentStep() {
        const currentStepElement = document.getElementById(`step${currentStep}`);
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Custom validations
        if (currentStep === 1) {
            const email = document.getElementById('email').value;
            const confirmEmail = document.getElementById('confirm_email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (email !== confirmEmail) {
                document.getElementById('confirm_email').classList.add('is-invalid');
                isValid = false;
            }

            if (password !== confirmPassword) {
                document.getElementById('confirm_password').classList.add('is-invalid');
                isValid = false;
            }

            if (password.length < 8) {
                document.getElementById('password').classList.add('is-invalid');
                isValid = false;
            }
        }

        if (!isValid) {
            showAlert('Mohon lengkapi semua field yang wajib diisi', 'warning');
        }

        return isValid;
    }

    function handleFileUpload(e) {
        const file = e.target.files[0];
        const uploadZone = e.target.closest('.document-item').querySelector('.upload-zone');
        const progress = uploadZone.querySelector('.upload-progress');
        const success = uploadZone.querySelector('.upload-success');
        const progressBar = progress.querySelector('.progress-bar');

        if (file) {
            // Show progress
            progress.style.display = 'block';
            
            // Simulate upload progress
            let width = 0;
            const interval = setInterval(() => {
                width += 10;
                progressBar.style.width = width + '%';
                
                if (width >= 100) {
                    clearInterval(interval);
                    progress.style.display = 'none';
                    success.style.display = 'block';
                    success.querySelector('.filename').textContent = file.name;
                }
            }, 100);
        }
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        
        if (!validateCurrentStep()) {
            return;
        }

        // Check terms agreement
        const agreeTerms = document.getElementById('agree_terms');
        if (!agreeTerms.checked) {
            showAlert('Anda harus menyetujui syarat dan ketentuan', 'warning');
            return;
        }

        // Show loading
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Mengirim...';
        submitBtn.disabled = true;

        // Simulate form submission
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            // Show success modal
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        }, 3000);
    }

    window.togglePassword = function(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    };

    // Simple alert for validation
    function showAlert(message, type = 'info') {
        let alert = document.getElementById('formAlert');
        if (!alert) {
            alert = document.createElement('div');
            alert.id = 'formAlert';
            alert.className = `alert alert-${type}`;
            alert.style.position = 'fixed';
            alert.style.top = '20px';
            alert.style.left = '50%';
            alert.style.transform = 'translateX(-50%)';
            alert.style.zIndex = 9999;
            document.body.appendChild(alert);
        }
        alert.textContent = message;
        alert.className = `alert alert-${type}`;
        alert.style.display = 'block';
        setTimeout(() => {
            alert.style.display = 'none';
        }, 3000);
    }
});
</script>

@endsection