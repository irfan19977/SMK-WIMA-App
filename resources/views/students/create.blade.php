@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 col-md-11 col-lg-11 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Tambah User</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                    placeholder="Masukkan Nama" value="{{ old('name') }}" autofocus>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" placeholder="Masukkan Email" value="{{ old('email') }}">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password</label>
                                <div class="position-relative">
                                    <input id="password-input" type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" placeholder="Masukkan Password" style="padding-right: 40px;">
                                    <span class="position-absolute" id="password-addon" 
                                        style="right: 10px; top: 55%; transform: translateY(-50%); cursor: pointer;">
                                        <i class="ri-eye-fill" style="color: #666;"></i>
                                    </span>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor Telepon</label>
                                <input type="number" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" placeholder="Masukkan Nomor Telepon" value="{{ old('phone') }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>NISN</label>
                                <input type="number" class="form-control @error('nisn') is-invalid @enderror"
                                    name="nisn" placeholder="Masukkan NISN" value="{{ old('nisn') }}">
                                @error('nisn')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No Kartu</label>
                                <input type="text" class="form-control @error('no_card') is-invalid @enderror"
                                    name="no_card" placeholder="Masukkan No Kartu" id="no_card"
                                    value="{{ old('no_card') }}" readonly>
                                @error('no_card')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">*Tap Kartu RFID</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tempat Lahir</label>
                                <input type="text" class="form-control @error('birth_place') is-invalid @enderror"
                                    name="birth_place" placeholder="Masukkan No Kartu" id="birth_place"
                                    value="{{ old('birth_place') }}">
                                @error('birth_place')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                    name="birth_date" id="birth_date" value="{{ old('birth_date') }}">
                                @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select class="form-control select2 @error('gender') is-invalid @enderror"
                                    name="gender">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                                @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Medical Info</label>
                        <textarea class="form-control @error('medical_info') is-invalid @enderror" name="medical_info"
                            placeholder="Masukkan Medical Info" rows="3">{{ old('medical_info') }}</textarea>
                        @error('medical_info')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- API-based Location Dropdowns -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Provinsi</label>
                                <select id="province"
                                    class="form-control select2 @error('province') is-invalid @enderror">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                <input type="hidden" name="province" id="province_name" value="{{ old('province') }}">
                                @error('province')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kota/Kabupaten</label>
                                <select id="city" class="form-control select2 @error('city') is-invalid @enderror">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                                <input type="hidden" name="city" id="city_name" value="{{ old('city') }}">
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kecamatan</label>
                                <select id="district"
                                    class="form-control select2 @error('district') is-invalid @enderror">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                <input type="hidden" name="district" id="district_name" value="{{ old('district') }}">
                                @error('district')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Desa/Kelurahan</label>
                                <select id="village"
                                    class="form-control select2 @error('village') is-invalid @enderror">
                                    <option value="">Pilih Desa/Kelurahan</option>
                                </select>
                                <input type="hidden" name="village" id="village_name" value="{{ old('village') }}">
                                @error('village')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" name="address"
                            placeholder="Masukkan Alamat" rows="3">{{ old('address') }}</textarea>
                        @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo"
                            accept="image/*" id="photo-input">
                        @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <!-- Preview Container -->
                        <div class="mt-3" id="photo-preview-container" style="display: none;">
                            <label class="form-label">Preview:</label>
                            <div class="text-center">
                                <img id="photo-preview" src="" alt="Preview"
                                    style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 8px; object-fit: cover;">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-danger" id="remove-photo">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="control-label">Status Aktif</div>
                        <input type="hidden" name="status" value="0">
                        <label class="custom-switch mt-2">
                            <input type="checkbox" name="status" class="custom-switch-input" value="1"
                                {{ old('status', 1) ? 'checked' : '' }}>
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Aktif</span>
                        </label>
                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-primary mr-1" type="submit">Simpan</button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/user-create.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordToggle = document.getElementById('password-addon');
        const passwordField = document.getElementById('password-input');
            
        passwordToggle.addEventListener('click', function(e) {
            console.log("Toggle password button clicked!");
            e.preventDefault();
            e.stopPropagation();
                
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
                
            const iconElement = this.querySelector('i');
            if (type === 'text') {
                iconElement.classList.remove('ri-eye-fill');
                iconElement.classList.add('ri-eye-off-fill');
            } else {
                iconElement.classList.remove('ri-eye-off-fill');
                iconElement.classList.add('ri-eye-fill');
            }
                
            // Hilangkan fokus/kursor dari field password
            passwordField.blur();
        });
            
        passwordToggle.addEventListener('mousedown', function(e) {
            e.preventDefault();
        });
    });
</script>
<script>
        $(document).ready(function() {
             let lastKnownRFID = '';
             let isWaitingConfirmation = false;
             let pollingTimeout = null;
             let oldValue = $('#no_card').val(); // Store initial value
             
             function clearRFIDCache() {
                 return $.ajax({
                     url: '{{ route("clear.rfid") }}',
                     type: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     }
                 });
             }
             
             function pollRFID() {
                 if (isWaitingConfirmation) {
                     pollingTimeout = setTimeout(pollRFID, 1000);
                     return;
                 }
 
                 $.ajax({
                     url: '{{ route("get.latest.rfid") }}',
                     type: 'GET',
                     success: function(response) {
                         if (response.rfid && response.rfid !== lastKnownRFID && !isWaitingConfirmation) {
                             isWaitingConfirmation = true;
                             const currentRFID = response.rfid;
                             
                             // Check if card is already in use
                             if (response.is_used) {
                                 swal({
                                     title: "Kartu Sudah Digunakan!",
                                     text: response.message,
                                     icon: "error",
                                     timer: 3000,
                                     buttons: false
                                 });
                                 
                                 clearRFIDCache().then(() => {
                                     // Reset state
                                     isWaitingConfirmation = false;
                                     $('#no_card').val(oldValue);
                                 });
                                 
                                 return;
                             }
                             
                             // If card is not in use, proceed with confirmation
                             swal({
                                 title: "Kartu Terdeteksi!",
                                 text: `Apakah anda akan menggunakan kartu dengan nomor ${currentRFID}?`,
                                 icon: "warning",
                                 buttons: [
                                     'Tidak',
                                     'Ya, Gunakan'
                                 ],
                                 dangerMode: true,
                             }).then(function(isConfirm) {
                                 if (isConfirm) {
                                     // Jika user memilih Ya
                                     lastKnownRFID = currentRFID;
                                     oldValue = currentRFID; // Update old value
                                     $('#no_card').val(currentRFID);
                                     
                                     // Tambahkan efek highlight
                                     $('#no_card').addClass('bg-light');
                                     setTimeout(function() {
                                         $('#no_card').removeClass('bg-light');
                                     }, 500);
                                     
                                     // Notifikasi sukses
                                     swal({
                                         title: "Berhasil!",
                                         text: "Nomor kartu berhasil ditambahkan",
                                         icon: "success",
                                         timer: 1500,
                                         buttons: false
                                     }).then(() => {
                                         clearRFIDCache();
                                     });
                                 } else {
                                     // Jika user memilih Tidak, kembalikan ke nilai lama
                                     $('#no_card').val(oldValue);
                                     
                                     // Clear the cache when user clicks No
                                     clearRFIDCache().then(() => {
                                         // Notifikasi batal
                                         swal({
                                             title: "Dibatalkan",
                                             text: "Tetap menggunakan nomor kartu sebelumnya",
                                             icon: "info",
                                             timer: 1500,
                                             buttons: false
                                         });
                                     });
                                 }
                                 // Reset waiting confirmation state
                                 isWaitingConfirmation = false;
                             });
                         }
                     },
                     complete: function() {
                         pollingTimeout = setTimeout(pollRFID, 1000);
                     }
                 });
             }
             
             // Mulai RFID polling
             pollRFID();
             
             // Reset button handler
             $('#resetRFID').click(function() {
                 $('#no_card').val('');
                 lastKnownRFID = '';
                 oldValue = ''; // Reset old value as well
                 isWaitingConfirmation = false;
                 
                 clearRFIDCache().then(() => {
                     swal({
                         title: "Reset!",
                         text: "Nomor kartu telah direset",
                         icon: "info",
                         timer: 1500,
                         buttons: false
                     });
                 });
             });
 
             // Cleanup when leaving page
             $(window).on('beforeunload', function() {
                 if (pollingTimeout) {
                     clearTimeout(pollingTimeout);
                 }
             });
         });
</script>
@endpush
