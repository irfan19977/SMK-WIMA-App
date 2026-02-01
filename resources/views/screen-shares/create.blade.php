@extends('layouts.app')

@section('title', 'Create Screen Sharing Session')

@section('content')
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title">
            <h4><i class="fas fa-desktop mr-2"></i>Create Screen Sharing Session</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
        <div class="header-elements d-none">
            <a href="{{ route('screen-shares.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">New Screen Sharing Session</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('screen-shares.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="title">Session Title (Optional)</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="e.g., Mathematics Lesson - Chapter 5">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Give your session a title to help students identify it.</small>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Note:</strong> A unique room code will be automatically generated when you create this session. 
                            Students will use this code to join your screen sharing session.
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="understand" required>
                                <label class="custom-control-label" for="understand">
                                    I understand that screen sharing requires browser permissions and will broadcast my screen to all participants.
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>Create Session
                            </button>
                            <a href="{{ route('screen-shares.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
