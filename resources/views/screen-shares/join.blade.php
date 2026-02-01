@extends('layouts.app')

@section('title', 'Join Screen Sharing Session')

@section('content')
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title">
            <h4><i class="fas fa-desktop mr-2"></i>Join Screen Sharing Session</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>

<div class="content">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h5 class="card-title mb-0">Enter Room Code</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('screen-shares.join.submit') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="room_code" class="font-weight-bold">Room Code</label>
                            <input type="text" 
                                   class="form-control form-control-lg text-center @error('room_code') is-invalid @enderror" 
                                   id="room_code" 
                                   name="room_code" 
                                   value="{{ old('room_code') }}" 
                                   placeholder="Enter 8-character code"
                                   maxlength="8"
                                   style="font-family: 'Courier New', monospace; font-size: 1.5rem; letter-spacing: 3px; text-transform: uppercase;"
                                   required>
                            @error('room_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Enter the 8-character room code provided by your teacher.</small>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-sign-in-alt mr-2"></i>Join Session
                            </button>
                        </div>
                    </form>

                    <hr>

                    <div class="text-center">
                        <h6 class="text-muted mb-3">How to join:</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                        <span class="font-weight-bold">1</span>
                                    </div>
                                    <p class="small text-muted mb-0">Get the room code from your teacher</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                        <span class="font-weight-bold">2</span>
                                    </div>
                                    <p class="small text-muted mb-0">Enter the code above</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                        <span class="font-weight-bold">3</span>
                                    </div>
                                    <p class="small text-muted mb-0">Click "Join Session"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Note:</strong> You must be logged in to join a screen sharing session. If you don't have an account, please contact your teacher.
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('room_code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});
</script>
@endpush
