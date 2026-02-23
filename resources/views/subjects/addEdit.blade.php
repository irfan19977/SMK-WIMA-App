@extends('layouts.master')
@section('title')
    {{ isset($subject) ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' }}
@endsection
@section('page-title')
    {{ isset($subject) ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' }}
@endsection
@section('body')

    <body data-sidebar="colored">
@endsection
@section('content')
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ isset($subject) ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' }}</h4>
                        <p class="card-title-desc">{{ isset($subject) ? 'Edit mata pelajaran yang ada di database' : 'Tambah mata pelajaran baru ke database' }} dengan form validation dan input yang sederhana.</p>
                        
                        <form class="was-validated" action="{{ isset($subject) ? route('subjects.update', $subject->id) : route('subjects.store') }}" method="POST">
                            @csrf
                            @isset($subject)
                                @method('PUT')
                            @endisset
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', isset($subject) ? $subject->name : '') }}" 
                                            placeholder="Masukkan nama mata pelajaran" required>
                                        <div class="invalid-feedback">
                                            Harap masukkan nama mata pelajaran.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="code" class="form-label">Kode Mata Pelajaran</label>
                                        <input type="text" class="form-control" id="code" name="code"
                                            value="{{ old('code', isset($subject) ? $subject->code : '') }}" 
                                            placeholder="Opsional: Kode unik">
                                        <div class="form-text">Kosongkan untuk generate otomatis</div>
                                        <div class="invalid-feedback">
                                            Kode tidak valid.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="mdi mdi-content-save"></i> {{ isset($subject) ? 'Update Mata Pelajaran' : 'Simpan Mata Pelajaran' }}
                                            </button>
                                            <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                                                <i class="mdi mdi-arrow-left"></i> Kembali
                                            </a>
                                        </div>
                                        <div>
                                            @isset($subject)
                                                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                                    <i class="mdi mdi-delete"></i> Hapus Mata Pelajaran
                                                </button>
                                            @endisset
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
@endsection
@section('scripts')
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        
        <script>
            // Auto-generate code when name is typed (only for create)
            @if(!isset($subject))
            document.addEventListener('DOMContentLoaded', function() {
                const nameInput = document.getElementById('name');
                const codeInput = document.getElementById('code');
                
                nameInput.addEventListener('input', function() {
                    // Only auto-generate if code is empty
                    if (!codeInput.value.trim()) {
                        // Generate simple code from name
                        let code = this.value.toUpperCase()
                            .replace(/[^A-Z0-9\s]/g, '')
                            .replace(/\s+/g, '_')
                            .substring(0, 10);
                        
                        codeInput.value = code;
                    }
                });
            });
            @endif

            // Delete confirmation (only for edit)
            @isset($subject)
            function confirmDelete() {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Mata pelajaran \"{{ $subject->name }}\" akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create form for delete
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("subjects.destroy", $subject->id) }}';
                        form.style.display = 'none';
                        
                        // Add CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (csrfToken) {
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = csrfToken.getAttribute('content');
                            form.appendChild(csrfInput);
                        }
                        
                        // Add method override for DELETE
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
            @endisset
        </script>
@endsection
