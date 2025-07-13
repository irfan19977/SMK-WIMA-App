@extends('layouts.app')

@section('content')
<div class="row ">
    <div class="col-12 col-md-11 col-lg-11 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Edit Permission</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('permissions.update', $permissions->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Nama Permission</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $permissions->name }}"
                            placeholder="Masukkan Nama Permission" autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror"
                            name="description" placeholder="Masukkan Keterangan" value="{{ $permissions->description }}">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-primary mr-1" type="submit">Simpan</button>
                        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
