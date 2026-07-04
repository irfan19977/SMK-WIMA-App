@extends('layouts.master')
@section('title')
    Create Screen Sharing Session
@endsection
@section('page-title')
    Create Screen Sharing Session
@endsection
@section('body')

    <body data-sidebar="colored">
    @endsection
    @section('content')
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="card-title mb-1">Create Screen Sharing Session</h4>
                                <p class="text-muted mb-0">Set up a new screen sharing session for your students</p>
                            </div>
                            <a href="{{ route('screen-shares.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Back
                            </a>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Whoops!</strong> There were some problems with your input.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('screen-shares.store') }}" method="POST" id="create-session-form">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Session Title <small class="text-muted">(Optional)</small></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" 
                                       placeholder="e.g., Mathematics Lesson - Chapter 5">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Give your session a title to help students identify it.</div>
                            </div>

                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="mdi mdi-information me-2"></i>
                                <div>
                                    <strong>Note:</strong> A unique room code will be automatically generated when you create this session. 
                                    Students will use this code to join your screen sharing session.
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="understand" required>
                                    <label class="form-check-label" for="understand">
                                        I understand that screen sharing requires browser permissions and will broadcast my screen to all participants.
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    <i class="mdi mdi-plus me-2"></i>Create Session
                                </button>
                                <a href="{{ route('screen-shares.index') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-close me-2"></i>Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @endsection
    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('create-session-form');
                const submitBtn = document.getElementById('submit-btn');
                
                if (form && submitBtn) {
                    form.addEventListener('submit', function(e) {
                        // Show loading state
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Creating...';
                        
                        // Re-enable after 5 seconds in case of issues
                        setTimeout(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="mdi mdi-plus me-2"></i>Create Session';
                        }, 5000);
                    });
                }

                // Auto-focus on title field
                const titleField = document.getElementById('title');
                if (titleField) {
                    titleField.focus();
                }
            });
        </script>
        
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
