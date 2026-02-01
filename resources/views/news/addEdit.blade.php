@extends('layouts.master')
@section('title')
    {{ isset($news) ? 'Edit Berita' : 'Tambah Berita' }}
@endsection
@section('page-title')
    {{ isset($news) ? 'Edit Berita' : 'Tambah Berita' }}
@endsection
@section('body')

    <body data-sidebar="colored">
    @endsection
    @section('content')
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ isset($news) ? 'Edit Berita' : 'Tambah Berita' }}</h4>
                        <p class="card-title-desc">{{ isset($news) ? 'Edit berita yang ada di database' : 'Tambah berita baru ke database' }} dengan form validation dan berbagai input types.</p>
                        
                        <form class="was-validated" action="{{ isset($news) ? route('news.update', $news->id) : route('news.store') }}" method="POST">
                            @csrf
                            @isset($news)
                                @method('PUT')
                            @endisset
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Judul Berita <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="{{ old('title', isset($news) ? $news->title : '') }}" required>
                                        <div class="invalid-feedback">
                                            Harap masukkan judul berita.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="Pendidikan" {{ old('category', isset($news) ? $news->category : '') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                            <option value="Kegiatan" {{ old('category', isset($news) ? $news->category : '') == 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                                            <option value="Pengumuman" {{ old('category', isset($news) ? $news->category : '') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                                            <option value="Prestasi" {{ old('category', isset($news) ? $news->category : '') == 'Prestasi' ? 'selected' : '' }}>Prestasi</option>
                                            <option value="Lainnya" {{ old('category', isset($news) ? $news->category : '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Harap pilih kategori berita.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="is_published" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" id="is_published" name="is_published" required>
                                            <option value="1" {{ old('is_published', isset($news) ? $news->is_published : 0) == 1 ? 'selected' : '' }}>Terbit</option>
                                            <option value="0" {{ old('is_published', isset($news) ? $news->is_published : 0) == 0 ? 'selected' : '' }}>Draft</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Harap pilih status berita.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="excerpt" class="form-label">Ringkasan</label>
                                        <textarea class="form-control" id="excerpt" name="excerpt" rows="3" placeholder="Ringkasan singkat berita">{{ old('excerpt', isset($news) ? $news->excerpt : '') }}</textarea>
                                        <div class="invalid-feedback">
                                            Ringkasan tidak valid.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="content" class="form-label">Isi Berita <span class="text-danger">*</span></label>
                                        <textarea id="content" name="content" rows="8" required>{{ old('content', isset($news) ? $news->content : '') }}</textarea>
                                        <div class="invalid-feedback">
                                            Harap masukkan isi berita.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Gambar</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                        @if(isset($news) && $news->image)
                                            <small class="text-muted">Gambar saat ini: <a href="{{ asset('storage/' . $news->image) }}" target="_blank">Lihat</a></small>
                                        @endif
                                        <div class="invalid-feedback">
                                            Gambar tidak valid.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="mdi mdi-content-save"></i> {{ isset($news) ? 'Update Berita' : 'Simpan Berita' }}
                                            </button>
                                            <a href="{{ route('news.index') }}" class="btn btn-secondary">
                                                <i class="mdi mdi-arrow-left"></i> Kembali
                                            </a>
                                        </div>
                                        <div>
                                            @isset($news)
                                                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                                    <i class="mdi mdi-delete"></i> Hapus Berita
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
        <!-- Summernote CSS -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
        
        <!-- Summernote JS -->
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        
        <!-- Summernote init -->
        <script>
            jQuery(document).ready(function($) {
                $('#content').summernote({
                    height: 300,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    callbacks: {
                        onInit: function() {
                            console.log('Summernote initialized');
                        }
                    }
                });
            });
        </script>
        
        <!-- Bootstrap validation -->
        <script>
            // Confirm delete function
            function confirmDelete() {
                if (confirm('Apakah Anda yakin ingin menghapus berita ini?')) {
                    document.getElementById('deleteForm').submit();
                }
            }
        </script>
        
        @isset($news)
            <!-- Hidden delete form -->
            <form id="deleteForm" action="{{ route('news.destroy', $news->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endisset
        
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
