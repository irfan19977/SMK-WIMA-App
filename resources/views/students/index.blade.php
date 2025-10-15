@extends('layouts.app')

@section('content')

<section class="section">
  <div class="card">
    <div class="card-header">
      <h4>Daftar Siswa</h4>
      <div class="card-header-action">
        <div class="input-group">
            <button class="btn btn-primary" id="btn-create" data-toggle="tooltip"
                style="margin-right: 10px;" title="Tambah Data">
                <i class="fas fa-plus"></i>
            </button>
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
              <th>No. Kartu</th>
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
                <td>{{ $student->user->email ?? '-' }}</td>
                <td>{{ $student->user->phone ?? '-' }}</td>
                <td>{{ $student->no_card ?? '-' }}</td>
                <td>
                  <div class="form-group" style="margin-top: 25px">
                    <label class="custom-switch mt-2">
                      <input type="checkbox" class="custom-switch-input toggle-active"
                        data-id="{{ $student->user->id }}"
                        {{ $student->user->status ? 'checked' : '' }}>
                      <span class="custom-switch-indicator"></span>
                      <span class="custom-switch-description">{{ $student->user->status ? 'Aktif' : 'Diblokir' }}</span>
                    </label>
                  </div>
                </td>
                <td>
                  @isset($student->id)
                  <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-action mr-1" data-toggle="tooltip" title="Detail">
                    <i class="fas fa-eye"></i>
                  </a>
                  <button class="btn btn-primary btn-action mr-1 btn-edit"
                          data-id="{{ $student->id }}" data-toggle="tooltip" title="Edit">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                  <form id="delete-form-{{ $student->id }}" action="{{ route('students.destroy', $student->id) }}"
                      method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                        title="Delete" onclick="confirmDelete('{{ $student->id }}')">
                        <i class="fas fa-trash"></i>
                    </button>
                  </form>
                  @endisset
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center">Tidak ada data siswa</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
</section>

<!-- Modal untuk Create/Edit -->
<div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentModalLabel">Tambah Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="studentForm">
                @csrf
                <div class="modal-body">
                    <!-- Data Akun -->
                    <h6 class="mb-3"><i class="bi bi-key"></i> Data Akun</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
                                <div class="invalid-feedback d-none" id="email-error"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                                <div class="invalid-feedback d-none" id="password-error"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password" required>
                                <div class="invalid-feedback d-none" id="password_confirmation-error"></div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Data Pribadi -->
                    <h6 class="mb-3"><i class="bi bi-person-circle"></i> Data Pribadi</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama lengkap" required>
                                <div class="invalid-feedback d-none" id="name-error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Nomor WhatsApp</label>
                                <input type="number" class="form-control" id="phone" name="phone" placeholder="Masukkan nomor WhatsApp">
                                <div class="invalid-feedback d-none" id="phone-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nik">NIK <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="nik" name="nik" placeholder="16 digit angka" maxlength="16" required>
                                <div class="invalid-feedback d-none" id="nik-error"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nisn">NISN</label>
                                <input type="number" class="form-control" id="nisn" name="nisn" placeholder="10 digit angka" maxlength="10">
                                <div class="invalid-feedback d-none" id="nisn-error"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="no_absen">No. Absen</label>
                                <input type="text" class="form-control" id="no_absen" name="no_absen" placeholder="Masukkan No. Absen">
                                <div class="invalid-feedback d-none" id="no_absen-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="laki-laki">Laki-laki</option>
                                    <option value="perempuan">Perempuan</option>
                                </select>
                                <div class="invalid-feedback d-none" id="gender-error"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="birth_place">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="birth_place" name="birth_place" placeholder="Contoh: Jakarta" required>
                                <div class="invalid-feedback d-none" id="birth_place-error"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="birth_date">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                                <div class="invalid-feedback d-none" id="birth_date-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="religion">Agama <span class="text-danger">*</span></label>
                                <select class="form-control" id="religion" name="religion" required>
                                    <option value="">-- Pilih Agama --</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                                <div class="invalid-feedback d-none" id="religion-error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_card">No. Kartu</label>
                                <input type="text" class="form-control" id="no_card" name="no_card" placeholder="Masukkan nomor kartu">
                                <div class="invalid-feedback d-none" id="no_card-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Jl. Contoh No. 123, RT.01/RW.02, Kelurahan, Kecamatan, Kota, Kode Pos" required></textarea>
                                <div class="invalid-feedback d-none" id="address-error"></div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Upload Dokumen -->
                    <h6 class="mb-3"><i class="bi bi-file-earmark-arrow-up"></i> Upload Dokumen</h6>

                    <!-- Dokumen Cards Layout -->
                    <div class="row">
                        <div class="col-12">
                            
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Row 1: Upload Inputs -->
                                        <div class="col-12" style="margin-bottom: 10px;">
                                            <div class="row">
                                                <!-- Photo Upload -->
                                                <div class="col-md-6" style="margin-bottom: 10px;">
                                                    <div class="form-group">
                                                        <label for="photo_path">Pas Foto 3x4</label>
                                                        <input type="file" class="form-control" id="photo_path" name="photo_path" accept="image/*">
                                                        <small class="form-text text-muted">Format: JPG, PNG. Max: 500KB</small>
                                                        <div class="invalid-feedback d-none" id="photo_path-error"></div>
                                                    </div>
                                                </div>

                                                <!-- Ijazah Upload -->
                                                <div class="col-md-6" style="margin-bottom: 10px;">
                                                    <div class="form-group">
                                                        <label for="ijazah">Ijazah/SKL</label>
                                                        <input type="file" class="form-control" id="ijazah" name="ijazah" accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="form-text text-muted">Format: PDF, JPG, PNG. Max: 500KB</small>
                                                        <div class="invalid-feedback d-none" id="ijazah-error"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Row 2: Photo & Ijazah Previews -->
                                        <div class="col-12" style="margin-bottom: 10px;">
                                            <div class="row">
                                                <!-- Photo Preview Card -->
                                                <div class="col-md-6" style="margin-bottom: 10px;">
                                                    <div class="card shadow-sm" style="max-width: 300px;">
                                                        <div class="card-header py-2">
                                                            <h6 class="mb-0" style="font-size: 0.9rem;">Foto</h6>
                                                        </div>
                                                        <div class="card-body py-2">
                                                            <div class="border rounded p-2">
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <strong style="font-size: 0.8rem;">Pas Foto 3x4</strong>
                                                                    <a id="photo_download" href="" download style="display: none;" class="btn btn-sm btn-outline-success py-0 px-1" style="font-size: 0.7rem;">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="text-center">
                                                                    <div id="photo_preview" class="mt-1" style="display: none;">
                                                                        <img id="photo_preview_img" src="" alt="Preview" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; cursor: pointer; border: 1px solid #ddd;" onclick="showDocumentPreview(this.src, 'photo')" data-toggle="tooltip" title="Klik untuk memperbesar">
                                                                    </div>
                                                                    <div class="text-muted small" style="font-size: 0.7rem;">Foto saat ini</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Ijazah Preview Card -->
                                                <div class="col-md-6" style="margin-bottom: 10px;">
                                                    <div class="card shadow-sm" style="max-width: 300px;">
                                                        <div class="card-header py-2">
                                                            <h6 class="mb-0" style="font-size: 0.9rem;">Ijazah</h6>
                                                        </div>
                                                        <div class="card-body py-2">
                                                            <div class="border rounded p-2">
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <strong style="font-size: 0.8rem;">Ijazah/SKL</strong>
                                                                    <a id="ijazah_download" href="" download style="display: none;" class="btn btn-sm btn-outline-success py-0 px-1" style="font-size: 0.7rem;">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="text-center">
                                                                    <div id="ijazah_preview" class="mt-1" style="display: none; min-height: 80px; display: flex; align-items: center; justify-content: center;">
                                                                        <div id="ijazah_content" style="width: 100%;"></div>
                                                                    </div>
                                                                    <div class="text-muted small" style="font-size: 0.7rem;">Dokumen saat ini</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Row 3: Kartu Keluarga & Akte Lahir Uploads -->
                                        <div class="col-12" style="margin-bottom: 10px;">
                                            <div class="row">
                                                <!-- Kartu Keluarga Upload -->
                                                <div class="col-md-6" style="margin-bottom: 10px;">
                                                    <div class="form-group">
                                                        <label for="kartu_keluarga">Kartu Keluarga</label>
                                                        <input type="file" class="form-control" id="kartu_keluarga" name="kartu_keluarga" accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="form-text text-muted">Format: PDF, JPG, PNG. Max: 500KB</small>
                                                        <div class="invalid-feedback d-none" id="kartu_keluarga-error"></div>
                                                    </div>
                                                </div>

                                                <!-- Akte Lahir Upload -->
                                                <div class="col-md-6" style="margin-bottom: 10px;">
                                                    <div class="form-group">
                                                        <label for="akte_lahir">Akte Kelahiran</label>
                                                        <input type="file" class="form-control" id="akte_lahir" name="akte_lahir" accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="form-text text-muted">Format: PDF, JPG, PNG. Max: 500KB</small>
                                                        <div class="invalid-feedback d-none" id="akte_lahir-error"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Row 4: Kartu Keluarga & Akte Lahir Previews -->
                                        <div class="col-12" style="margin-bottom: 10px;">
                                            <div class="row">
                                                <!-- Kartu Keluarga Preview Card -->
                                                <div class="col-md-6" style="margin-bottom: 10px;">
                                                    <div class="card shadow-sm" style="max-width: 300px;">
                                                        <div class="card-header py-2">
                                                            <h6 class="mb-0" style="font-size: 0.9rem;">KK</h6>
                                                        </div>
                                                        <div class="card-body py-2">
                                                            <div class="border rounded p-2">
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <strong style="font-size: 0.8rem;">Kartu Keluarga</strong>
                                                                    <a id="kartu_keluarga_download" href="" download style="display: none;" class="btn btn-sm btn-outline-success py-0 px-1" style="font-size: 0.7rem;">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="text-center">
                                                                    <div id="kartu_keluarga_preview" class="mt-1" style="display: none; min-height: 80px; display: flex; align-items: center; justify-content: center;">
                                                                        <div id="kartu_keluarga_content" style="width: 100%;"></div>
                                                                    </div>
                                                                    <div class="text-muted small" style="font-size: 0.7rem;">Dokumen saat ini</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Akte Lahir Preview Card -->
                                                <div class="col-md-6" style="margin-bottom: 10px;">
                                                    <div class="card shadow-sm" style="max-width: 300px;">
                                                        <div class="card-header py-2">
                                                            <h6 class="mb-0" style="font-size: 0.9rem;">Akte</h6>
                                                        </div>
                                                        <div class="card-body py-2">
                                                            <div class="border rounded p-2">
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <strong style="font-size: 0.8rem;">Akte Kelahiran</strong>
                                                                    <a id="akte_lahir_download" href="" download style="display: none;" class="btn btn-sm btn-outline-success py-0 px-1" style="font-size: 0.7rem;">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="text-center">
                                                                    <div id="akte_lahir_preview" class="mt-1" style="display: none; min-height: 80px; display: flex; align-items: center; justify-content: center;">
                                                                        <div id="akte_lahir_content" style="width: 100%;"></div>
                                                                    </div>
                                                                    <div class="text-muted small" style="font-size: 0.7rem;">Dokumen saat ini</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Row 5: KTP & Sertifikat Uploads -->
                                        <div class="col-12" style="margin-bottom: 10px;">
                                            <div class="row">
                                                <!-- KTP Upload -->
                                                <div class="col-md-6" style="margin-bottom: 10px;">
                                                    <div class="form-group">
                                                        <label for="ktp">KTP Orang Tua</label>
                                                        <input type="file" class="form-control" id="ktp" name="ktp" accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="form-text text-muted">Format: PDF, JPG, PNG. Max: 500KB</small>
                                                        <div class="invalid-feedback d-none" id="ktp-error"></div>
                                                    </div>
                                                </div>

                                                <!-- Sertifikat Upload -->
                                                <div class="col-md-6" style="margin-bottom: 10px;">
                                                    <div class="form-group">
                                                        <label for="sertifikat">Sertifikat/Piagam (Opsional)</label>
                                                        <input type="file" class="form-control" id="sertifikat" name="sertifikat" accept=".pdf,.jpg,.jpeg,.png" multiple>
                                                        <small class="form-text text-muted">Format: PDF, JPG, PNG. Max: 500KB per file</small>
                                                        <div class="invalid-feedback d-none" id="sertifikat-error"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Row 6: KTP & Sertifikat Previews -->
                                        <div class="col-12" style="margin-bottom: 10px;">
                                            <div class="row">
                                                <!-- KTP Preview Card -->
                                                <div class="col-md-6" style="margin-bottom: 10px;">
                                                    <div class="card shadow-sm" style="max-width: 300px;">
                                                        <div class="card-header py-2">
                                                            <h6 class="mb-0" style="font-size: 0.9rem;">KTP</h6>
                                                        </div>
                                                        <div class="card-body py-2">
                                                            <div class="border rounded p-2">
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <strong style="font-size: 0.8rem;">KTP Orang Tua</strong>
                                                                    <a id="ktp_download" href="" download style="display: none;" class="btn btn-sm btn-outline-success py-0 px-1" style="font-size: 0.7rem;">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="text-center">
                                                                    <div id="ktp_preview" class="mt-1" style="display: none; min-height: 80px; display: flex; align-items: center; justify-content: center;">
                                                                        <div id="ktp_content" style="width: 100%;"></div>
                                                                    </div>
                                                                    <div class="text-muted small" style="font-size: 0.7rem;">Dokumen saat ini</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Sertifikat Preview Card -->
                                                <div class="col-md-6" style="margin-bottom: 10px;">
                                                    <div class="card shadow-sm" style="max-width: 300px;">
                                                        <div class="card-header py-2">
                                                            <h6 class="mb-0" style="font-size: 0.9rem;">Sertifikat</h6>
                                                        </div>
                                                        <div class="card-body py-2">
                                                            <div class="border rounded p-2">
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <strong style="font-size: 0.8rem;">Sertifikat/Piagam</strong>
                                                                    <!-- No download button for sertifikat as it can have multiple files -->
                                                                </div>
                                                                <div class="text-center">
                                                                    <div id="sertifikat_preview" class="mt-1" style="display: none; min-height: 80px;">
                                                                        <div id="sertifikat_preview_container" class="d-flex flex-wrap gap-2 justify-content-center"></div>
                                                                    </div>
                                                                    <div class="text-muted small" style="font-size: 0.7rem;">Dokumen saat ini</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Status -->
                    <h6 class="mb-3"><i class="bi bi-shield-check"></i> Status Akun</h6>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="custom-switch mt-2">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" class="custom-switch-input" id="status" name="status" value="1" checked>
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description" id="status-text">Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewModalLabel">Preview File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="fullImagePreview" src="" alt="Full Preview" style="max-width: 100%; max-height: 70vh; border: 1px solid #ddd; padding: 10px; display: none;">
                <iframe id="pdfPreview" src="" style="width: 100%; height: 70vh; border: 1px solid #ddd; display: none;" title="PDF Preview"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        let isEditMode = false;
        let editId = null;

        // Function to show full-size image in modal
        function showFullImage(imageSrc) {
            document.getElementById('fullImagePreview').src = imageSrc;
            document.getElementById('fullImagePreview').style.display = 'block';
            document.getElementById('pdfPreview').style.display = 'none';

            // Ensure modal backdrop doesn't interfere with scrolling
            $('#imagePreviewModal').modal({
                backdrop: 'static',
                keyboard: true
            });

            $('#imagePreviewModal').modal('show');
        }

        // Function to show document preview in modal
        function showDocumentPreview(fileUrl, fileType) {
            const modalTitle = document.getElementById('imagePreviewModalLabel');

            // Check if file is PDF or image
            if (fileUrl.toLowerCase().includes('.pdf')) {
                // For PDF files, show in iframe
                modalTitle.textContent = `Preview ${fileType.replace('_', ' ').toUpperCase()}`;
                document.getElementById('pdfPreview').src = fileUrl;
                document.getElementById('pdfPreview').style.display = 'block';
                document.getElementById('fullImagePreview').style.display = 'none';
            } else {
                // For image files, show as image
                modalTitle.textContent = `Preview ${fileType.replace('_', ' ').toUpperCase()}`;
                document.getElementById('fullImagePreview').src = fileUrl;
                document.getElementById('fullImagePreview').style.display = 'block';
                document.getElementById('pdfPreview').style.display = 'none';
            }

            // Ensure modal backdrop doesn't interfere with scrolling
            $('#imagePreviewModal').modal({
                backdrop: 'static',
                keyboard: true
            });

            $('#imagePreviewModal').modal('show');
        }

        // Fix for nested modal scroll issue
        $('#imagePreviewModal').on('hidden.bs.modal', function () {
            // Force body scroll restoration
            setTimeout(function() {
                $('body').addClass('modal-open');
                $('.modal-backdrop').last().remove();
                $('body').css({
                    'overflow': 'auto',
                    'padding-right': '0px'
                });
            }, 100);
        });

        function confirmDelete(id) {
            swal({
                title: "Apakah Anda Yakin?",
                text: "Data ini akan dihapus secara permanen!",
                icon: "warning",
                buttons: [
                    'Tidak',
                    'Ya, Hapus'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    const form = document.getElementById(`delete-form-${id}`);
                    const url = form.action;

                    // Opsi 1: Menggunakan FormData (Recommended)
                    const formData = new FormData();
                    formData.append('_method', 'DELETE');
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            swal({
                                title: "Berhasil!",
                                text: "Data berhasil dihapus.",
                                icon: "success",
                                timer: 3000,
                                buttons: false
                            }).then(() => {
                                // Reload table data
                                performSearch(document.getElementById('search-input').value);
                            });
                        } else {
                            swal("Gagal", data.message || "Terjadi kesalahan saat menghapus data.", "error");
                        }
                    })
                    .catch(error => {
                        swal("Gagal", "Terjadi kesalahan saat menghapus data.", "error");
                    });
                }
            });
        }

        function renumberTableRows() {
            const tableBody = document.querySelector('#sortable-table tbody');
            const rows = tableBody.querySelectorAll('tr');

            const currentPage = {{ $students->currentPage() ?? 1 }};
            const perPage = {{ $students->perPage() ?? 10 }};

            rows.forEach((row, index) => {
                const numberCell = row.querySelector('td:first-child');
                if (numberCell) {
                    numberCell.textContent = (currentPage - 1) * perPage + index + 1;
                }
            });
        }

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="q"]');
            const tableBody = document.querySelector('#sortable-table tbody');
            let searchTimeout;

            // Fungsi untuk melakukan pencarian
            function performSearch(query) {
                // Tampilkan loading
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Mencari data...</td></tr>';

                // Kirim request AJAX
                fetch(`{{ route('students.index') }}?q=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update tabel dengan data hasil pencarian
                    updateTable(data.students, data.currentPage, data.perPage);
                })
                .catch(error => {
                    tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Terjadi kesalahan saat mencari data</td></tr>';
                });
            }

            // Fungsi untuk update tabel
            function updateTable(students, currentPage = 1, perPage = 10) {
                if (students.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Tidak ada data siswa</td></tr>';
                    return;
                }

                let html = '';
                students.forEach((student, index) => {
                    const number = (currentPage - 1) * perPage + index + 1;

                    html += `
                        <tr>
                            <td class="text-center">${number}</td>
                            <td><a href="#" class="text-secondery font-weight-bold">${student.name}</a></td>
                            <td>${student.nisn || '-'}</td>
                            <td>${student.user ? student.user.email : '-'}</td>
                            <td>${student.user ? student.user.phone : '-'}</td>
                            <td>${student.no_card || '-'}</td>
                            <td>
                              <div class="form-group" style="margin-top: 25px">
                                <label class="custom-switch mt-2">
                                  <input type="checkbox" class="custom-switch-input toggle-active"
                                    data-id="${student.user ? student.user.id : ''}"
                                    ${student.user && student.user.status ? 'checked' : ''}>
                                  <span class="custom-switch-indicator"></span>
                                  <span class="custom-switch-description">${student.user && student.user.status ? 'Aktif' : 'Diblokir'}</span>
                                </label>
                              </div>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-action mr-1 btn-edit"
                                    data-id="${student.id}" data-toggle="tooltip" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <form id="delete-form-${student.id}" action="/students/${student.id}"
                                    method="POST" style="display:inline;">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                                        title="Delete" onclick="confirmDelete('${student.id}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    `;
                });

                tableBody.innerHTML = html;

                // Re-initialize edit buttons and toggle switches
                initializeEditButtons();
                initializeToggleSwitches();
            }

            // Event listener untuk input pencarian
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                // Clear timeout sebelumnya
                clearTimeout(searchTimeout);

                // Set timeout baru untuk menghindari terlalu banyak request
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300); // Delay 300ms
            });

            // Make performSearch available globally
            window.performSearch = performSearch;
        });

        // Clear search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const clearButton = document.getElementById('clear-search');
            const searchButton = document.getElementById('search-button');
            const searchInput = document.getElementById('search-input');

            // Event listener untuk tombol clear
            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.focus();

                // Hide clear button, show search button
                clearButton.style.display = 'none';
                searchButton.style.display = 'block';

                // Trigger search
                window.performSearch('');
            });

            // Show/hide buttons based on input
            searchInput.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    clearButton.style.display = 'block';
                    searchButton.style.display = 'none';
                } else {
                    clearButton.style.display = 'none';
                    searchButton.style.display = 'block';
                }
            });

            // Event listener untuk tombol search
            searchButton.addEventListener('click', function() {
                window.performSearch(searchInput.value);
            });

            // Initialize button visibility
            clearButton.style.display = 'none';
            searchButton.style.display = 'block';
        });

        // Modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const modal = $('#studentModal');
            const form = document.getElementById('studentForm');
            const modalTitle = document.getElementById('studentModalLabel');
            const submitBtn = document.getElementById('submitBtn');

            // Fix for modal backdrop issues
            modal.on('show.bs.modal', function () {
                // Ensure proper modal state
                $('body').css('overflow', 'hidden');
            });

            // Create button event
            document.getElementById('btn-create').addEventListener('click', function() {
                resetForm();
                isEditMode = false;
                editId = null;
                modalTitle.textContent = 'Tambah Siswa';
                submitBtn.textContent = 'Simpan';
                modal.modal('show');
            });

            // Edit button events (delegated)
            function initializeEditButtons() {
                document.querySelectorAll('.btn-edit').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        loadEditData(id);
                    });
                });
            }

            // Initialize edit buttons on page load
            initializeEditButtons();

            // Load edit data
            function loadEditData(id) {
                fetch(`/students/${id}/edit`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resetForm();
                        isEditMode = true;
                        editId = id;
                        modalTitle.textContent = data.title;
                        submitBtn.textContent = 'Update';

                        // Fill form with data
                        document.getElementById('name').value = data.student.name;
                        document.getElementById('nisn').value = data.student.nisn || '';
                        document.getElementById('nik').value = data.student.nik || '';
                        document.getElementById('email').value = data.student.user ? data.student.user.email : '';
                        document.getElementById('phone').value = data.student.user ? data.student.user.phone : '';
                        document.getElementById('no_card').value = data.student.no_card || '';
                        document.getElementById('no_absen').value = data.student.no_absen || '';
                        document.getElementById('gender').value = data.student.gender || '';
                        document.getElementById('birth_place').value = data.student.birth_place || '';
                        document.getElementById('birth_date').value = data.student.birth_date || '';
                        document.getElementById('religion').value = data.student.religion || '';
                        document.getElementById('address').value = data.student.address || '';

                        // Set status checkbox
                        const statusCheckbox = document.getElementById('status');
                        if (data.student.user && data.student.user.status) {
                            statusCheckbox.checked = true;
                            document.getElementById('status-text').textContent = 'Aktif';
                        } else {
                            statusCheckbox.checked = false;
                            document.getElementById('status-text').textContent = 'Diblokir';
                        }

                        // Show existing files preview
                        showExistingFiles(data.student);

                        modal.modal('show');
                    }
                })
                .catch(error => {
                    swal("Error", "Gagal memuat data", "error");
                });
            }

            // Reset form function
            function resetForm() {
                form.reset();
                // Clear validation errors
                document.querySelectorAll('.invalid-feedback').forEach(el => {
                    el.classList.add('d-none');
                    el.textContent = '';
                });
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                // Hide all file previews
                document.getElementById('photo_preview').style.display = 'none';
                document.getElementById('ijazah_preview').style.display = 'none';
                document.getElementById('kartu_keluarga_preview').style.display = 'none';
                document.getElementById('akte_lahir_preview').style.display = 'none';
                document.getElementById('ktp_preview').style.display = 'none';
                document.getElementById('sertifikat_preview').style.display = 'none';
            }

            // Show existing files preview
            function showExistingFiles(student) {
                // Show photo preview (check both photo_path from user table and photo from student table)
                if (student.user && student.user.photo_path) {
                    document.getElementById('photo_preview_img').src = '/storage/' + student.user.photo_path;
                    document.getElementById('photo_preview').style.display = 'block';
                } else if (student.photo_path) {
                    document.getElementById('photo_preview_img').src = '/storage/' + student.photo_path;
                    document.getElementById('photo_preview').style.display = 'block';
                } else {
                    document.getElementById('photo_preview').style.display = 'none';
                }

                // Show document previews with proper file type detection
                showDocumentPreviewByType('ijazah', student.ijazah);
                showDocumentPreviewByType('kartu_keluarga', student.kartu_keluarga);
                showDocumentPreviewByType('akte_lahir', student.akte_lahir);
                showDocumentPreviewByType('ktp', student.ktp);

                // Handle sertifikat (multiple files)
                if (student.sertifikat) {
                    const sertifikatContainer = document.getElementById('sertifikat_preview_container');
                    sertifikatContainer.innerHTML = '';

                    // Assuming sertifikat is stored as a single path or comma-separated paths
                    const sertifikatPaths = student.sertifikat.split(',');
                    sertifikatPaths.forEach((path, index) => {
                        if (path.trim()) {
                            showMultipleDocumentPreview(path.trim(), `sertifikat_${index + 1}`, sertifikatContainer);
                        }
                    });

                    if (sertifikatPaths.length > 0 && sertifikatPaths[0].trim()) {
                        document.getElementById('sertifikat_preview').style.display = 'block';
                    } else {
                        document.getElementById('sertifikat_preview').style.display = 'none';
                    }
                } else {
                    document.getElementById('sertifikat_preview').style.display = 'none';
                }
            }

            // Helper function to show document preview based on file type
            function showDocumentPreviewByType(fieldName, filePath) {
                const previewDiv = document.getElementById(fieldName + '_preview');
                const contentDiv = document.getElementById(fieldName + '_content');
                const downloadBtn = document.getElementById(fieldName + '_download');

                if (filePath) {
                    const fileExt = filePath.toLowerCase().split('.').pop();

                    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
                        // Show as image thumbnail
                        contentDiv.innerHTML = `<img src="/storage/${filePath}" alt="${fieldName}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; cursor: pointer; border: 1px solid #ddd;" onclick="showDocumentPreview(this.src, '${fieldName}')" data-toggle="tooltip" title="Klik untuk memperbesar">`;
                    } else if (fileExt === 'pdf') {
                        // Show as PDF icon
                        contentDiv.innerHTML = `
                            <div style="cursor: pointer; text-align: center;" onclick="showDocumentPreview('/storage/${filePath}', '${fieldName}')" data-toggle="tooltip" title="Klik untuk preview PDF">
                                <i class="fas fa-file-pdf fa-3x text-danger mb-2" style="opacity: 0.8;"></i>
                                <div class="small text-muted">PDF</div>
                            </div>
                        `;
                    } else {
                        contentDiv.innerHTML = `<div class="text-muted">Dokumen tersedia (${fileExt.toUpperCase()})</div>`;
                    }

                    // Show download button
                    if (downloadBtn) {
                        downloadBtn.href = `/storage/${filePath}`;
                        downloadBtn.style.display = 'inline-block';
                    }

                    previewDiv.style.display = 'block';
                } else {
                    previewDiv.style.display = 'none';
                    if (downloadBtn) {
                        downloadBtn.style.display = 'none';
                    }
                }
            }

            // Helper function for multiple document previews (sertifikat)
            function showMultipleDocumentPreview(filePath, fileTitle, container) {
                const fileExt = filePath.toLowerCase().split('.').pop();

                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
                    // Show as image thumbnail
                    const previewDiv = document.createElement('div');
                    previewDiv.style.cssText = 'width: 80px; height: 80px; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; display: flex; align-items: center; justify-content: center;';

                    const img = document.createElement('img');
                    img.src = '/storage/' + filePath;
                    img.alt = `${fileTitle} Preview`;
                    img.style.cssText = 'max-width: 100%; max-height: 100%; object-fit: cover; cursor: pointer;';
                    img.onclick = function() {
                        showDocumentPreview(this.src, fileTitle);
                    };
                    img.setAttribute('data-toggle', 'tooltip');
                    img.setAttribute('title', 'Klik untuk memperbesar');

                    previewDiv.appendChild(img);
                    container.appendChild(previewDiv);
                } else if (fileExt === 'pdf') {
                    // Show as PDF icon
                    const previewDiv = document.createElement('div');
                    previewDiv.style.cssText = 'width: 80px; height: 80px; border: 1px solid #ddd; border-radius: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; text-align: center; background: #f8f9fa;';
                    previewDiv.onclick = function() {
                        showDocumentPreview('/storage/' + filePath, fileTitle);
                    };
                    previewDiv.setAttribute('data-toggle', 'tooltip');
                    previewDiv.setAttribute('title', 'Klik untuk preview PDF');

                    previewDiv.innerHTML = `
                        <i class="fas fa-file-pdf fa-2x text-danger mb-1" style="opacity: 0.8;"></i>
                        <div class="small text-muted" style="font-size: 8px;">PDF</div>
                    `;

                    container.appendChild(previewDiv);
                }
            }

            // Make initializeEditButtons available globally
            window.initializeEditButtons = initializeEditButtons;

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                const url = isEditMode ? `/students/${editId}` : '/students';
                const method = 'POST';

                if (isEditMode) {
                    formData.append('_method', 'PUT');
                }

                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.textContent = 'Menyimpan...';

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modal.modal('hide');
                        swal({
                        title: "Berhasil!",
                        text: isEditMode ? "Data berhasil diperbarui." : "Data berhasil ditambahkan.",
                        icon: "success",
                        timer: 3000,
                        buttons: false
                        }).then(() => {
                        // Reload table data
                        window.performSearch(document.getElementById('search-input').value);
                        });
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                const errorElement = document.getElementById(key + '-error');
                                const inputElement = document.getElementById(key);
                                if (errorElement && inputElement) {
                                    errorElement.textContent = data.errors[key][0];
                                    errorElement.classList.remove('d-none');
                                    inputElement.classList.add('is-invalid');
                                }
                            });
                        } else {
                            swal("Gagal", data.message || "Terjadi kesalahan", "error");
                        }
                    }
                })
                .catch(error => {
                    swal("Error", "Terjadi kesalahan pada server", "error");
                })
                .finally(() => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.textContent = isEditMode ? 'Update' : 'Simpan';
                });
            });
        });

        // Toggle active status functionality
        function initializeToggleSwitches() {
            document.querySelectorAll('.toggle-active').forEach(function(toggle) {
                toggle.addEventListener('change', function() {
                    const studentId = this.getAttribute('data-id');
                    const isChecked = this.checked;
                    const descriptionElement = this.closest('.custom-switch').querySelector('.custom-switch-description');

                    // Show loading state
                    descriptionElement.textContent = 'Memperbarui...';

                    fetch(`{{ url('students') }}/${studentId}/toggle-active`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': `{{ csrf_token() }}`,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            descriptionElement.textContent = data.is_active ? 'Aktif' : 'Diblokir';

                            if (window.swal) {
                                swal({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 2000,
                                });
                            } else if (window.toastr) {
                                toastr.success(data.message);
                            } else {
                                alert(data.message);
                            }
                        } else {
                            // Restore original state
                            this.checked = !isChecked;
                            descriptionElement.textContent = isChecked ? 'Diblokir' : 'Aktif';

                            if (window.swal) {
                                swal({
                                    title: 'Gagal!',
                                    text: 'Gagal memperbarui status',
                                    icon: 'error'
                                });
                            } else if (window.toastr) {
                                toastr.error('Gagal memperbarui status');
                            } else {
                                alert('Gagal memperbarui status');
                            }
                        }
                    })
                    .catch(error => {
                        // Restore original state
                        this.checked = !isChecked;
                        descriptionElement.textContent = isChecked ? 'Diblokir' : 'Aktif';

                        console.error('Error:', error);
                        if (window.swal) {
                            swal({
                                title: 'Error!',
                                text: 'Terjadi kesalahan pada server',
                                icon: 'error'
                            });
                        } else if (window.toastr) {
                            toastr.error('Terjadi kesalahan pada server');
                        } else {
                            alert('Terjadi kesalahan pada server');
                        }
                    });
                });
            });
        }

        // Initialize toggle switches on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeToggleSwitches();
        });
    </script>
@endpush
