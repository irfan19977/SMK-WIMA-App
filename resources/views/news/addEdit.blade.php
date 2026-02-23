@extends('layouts.master')
@section('title')
    {{ isset($news) ? __('index.edit_news') : __('index.add_news') }}
@endsection
@section('page-title')
    {{ isset($news) ? __('index.edit_news') : __('index.add_news') }}
@endsection
@section('body')

    <body data-sidebar="colored">
    @endsection
    @section('content')
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ isset($news) ? __('index.edit_news') : __('index.add_news') }}</h4>
                        <p class="card-title-desc">{{ isset($news) ? __('index.edit_existing_news_in_database') : __('index.add_new_news_to_database') }} {{ __('index.with_form_validation_and_various_input_types') }}</p>
                        
                        <form class="was-validated" action="{{ isset($news) ? route('news.update', $news->id) : route('news.store') }}" method="POST">
                            @csrf
                            @isset($news)
                                @method('PUT')
                            @endisset
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">{{ __('index.title') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="{{ old('title', isset($news) ? $news->title : '') }}" required>
                                        <div class="invalid-feedback">
                                            {{ __('index.please_enter_news_title') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category" class="form-label">{{ __('index.category') }} <span class="text-danger">*</span></label>
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="">{{ __('index.select_category') }}</option>
                                            <option value="Pendidikan" {{ old('category', isset($news) ? $news->category : '') == 'Pendidikan' ? 'selected' : '' }}>{{ __('index.education') }}</option>
                                            <option value="Kegiatan" {{ old('category', isset($news) ? $news->category : '') == 'Kegiatan' ? 'selected' : '' }}>{{ __('index.activities') }}</option>
                                            <option value="Pengumuman" {{ old('category', isset($news) ? $news->category : '') == 'Pengumuman' ? 'selected' : '' }}>{{ __('index.human_resources') }}</option>
                                            <option value="Prestasi" {{ old('category', isset($news) ? $news->category : '') == 'Prestasi' ? 'selected' : '' }}>{{ __('index.announcement') }}</option>
                                            <option value="Lainnya" {{ old('category', isset($news) ? $news->category : '') == 'Lainnya' ? 'selected' : '' }}>{{ __('index.others') }}</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ __('index.please_select_news_category') }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="is_published" class="form-label">{{ __('index.status') }} <span class="text-danger">*</span></label>
                                        <select class="form-select" id="is_published" name="is_published" required>
                                            <option value="1" {{ old('is_published', isset($news) ? $news->is_published : 0) == 1 ? 'selected' : '' }}>{{ __('index.published') }}</option>
                                            <option value="0" {{ old('is_published', isset($news) ? $news->is_published : 0) == 0 ? 'selected' : '' }}>{{ __('index.draft') }}</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ __('index.please_select_news_status') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="excerpt" class="form-label">{{ __('index.excerpt') }}</label>
                                        <textarea class="form-control" id="excerpt" name="excerpt" rows="3" placeholder="{{ __('index.enter_news_excerpt') }}">{{ old('excerpt', isset($news) ? $news->excerpt : '') }}</textarea>
                                        <div class="invalid-feedback">
                                            {{ __('index.excerpt_not_valid') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="content" class="form-label">{{ __('index.content') }} <span class="text-danger">*</span></label>
                                        <textarea id="content" name="content" rows="8" required>{{ old('content', isset($news) ? $news->content : '') }}</textarea>
                                        <div class="invalid-feedback">
                                            {{ __('index.please_enter_news_content') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">{{ __('index.image') }}</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                        @if(isset($news) && $news->image)
                                            <small class="text-muted">{{ __('index.current_image') }}: <a href="{{ asset('storage/' . $news->image) }}" target="_blank">{{ __('index.view') }}</a></small>
                                        @endif
                                        <div class="invalid-feedback">
                                            {{ __('index.image_not_valid') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="mdi mdi-content-save"></i> {{ isset($news) ? __('index.update_news') : __('index.save_news') }}
                                            </button>
                                            <a href="{{ route('news.index') }}" class="btn btn-secondary">
                                                <i class="mdi mdi-arrow-left"></i> {{ __('index.back') }}
                                            </a>
                                        </div>
                                        <div>
                                            @isset($news)
                                                <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ $news->id }}', '{{ $news->title }}')">
                                                    <i class="mdi mdi-delete"></i> {{ __('index.delete_news') }}
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
