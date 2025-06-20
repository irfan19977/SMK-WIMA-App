@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 col-md-11 col-lg-11 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Kelas</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('classes.store') }}" enctype="multipart/form-data">
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
                                <small class="text-muted">*Contoh: X TKJ</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Grade</label>
                                <input type="number" class="form-control @error('grade') is-invalid @enderror"
                                    name="grade" placeholder="Masukkan grade" value="{{ old('grade') }}">
                                @error('grade')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jurusan</label>
                                <select class="form-control select2 @error('major') is-invalid @enderror" name="major">
                                    <option value="">Pilih Jurusan</option>
                                    <option value="Teknik Komputer dan Jaringan" {{ old('major') == 'Teknik Komputer dan Jaringan' ? 'selected' : '' }}>Teknik Komputer dan Jaringan</option>
                                    <option value="Akuntansi" {{ old('major') == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                                    <option value="Design Komunikasi Visual" {{ old('major') == 'Design Komunikasi Visual' ? 'selected' : '' }}>Design Komunikasi Visual</option>
                                    <option value="Keperawatan" {{ old('major') == 'Keperawatan' ? 'selected' : '' }}>Keperawatan</option>
                                </select>
                                @error('major')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tahun Ajaran</label>
                                <input type="text" class="form-control @error('academic_yaer') is-invalid @enderror"
                                    name="academic_yaer" placeholder="Masukkan Tahun Ajaran" value="{{ date('Y') }}/{{ date('Y') + 1 }}">
                                @error('academic_yaer')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-primary mr-1" type="submit">Simpan</button>
                        <a href="{{ route('classes.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

