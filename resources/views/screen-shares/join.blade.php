@extends('layouts.master')
@section('title')
    Join Screen Sharing Session
@endsection
@section('page-title')
    Join Screen Sharing Session
@endsection
@section('body')

    <body data-sidebar="colored">
    @endsection
    @section('content')
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                    <i class="mdi mdi-desktop-mac font-size-24"></i>
                                </div>
                            </div>
                            <h4 class="card-title mb-1">Join Screen Sharing Session</h4>
                            <p class="text-muted">Enter the room code to join your teacher's screen sharing session</p>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-alert-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-alert-circle me-2"></i>
                                <strong>Whoops!</strong> There were some problems with your input.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('screen-shares.join.submit') }}" method="POST" id="join-session-form">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="room_code" class="form-label fw-bold">Room Code</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-key"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control text-center @error('room_code') is-invalid @enderror" 
                                           id="room_code" 
                                           name="room_code" 
                                           value="{{ old('room_code') }}" 
                                           placeholder="XXXXXXXX"
                                           maxlength="8"
                                           style="font-family: 'Courier New', monospace; font-size: 1.5rem; letter-spacing: 3px; text-transform: uppercase;"
                                           required>
                                </div>
                                @error('room_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Enter the 8-character room code provided by your teacher.</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" id="join-btn">
                                    <i class="mdi mdi-login me-2"></i>Join Session
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <h6 class="text-muted mb-4">How to join:</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="avatar-sm mx-auto mb-2">
                                            <div class="avatar-title bg-primary text-white rounded-circle">
                                                <span class="fw-bold">1</span>
                                            </div>
                                        </div>
                                        <p class="small text-muted mb-0">Get the room code from your teacher</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="avatar-sm mx-auto mb-2">
                                            <div class="avatar-title bg-primary text-white rounded-circle">
                                                <span class="fw-bold">2</span>
                                            </div>
                                        </div>
                                        <p class="small text-muted mb-0">Enter the code in the field above</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="avatar-sm mx-auto mb-2">
                                            <div class="avatar-title bg-primary text-white rounded-circle">
                                                <span class="fw-bold">3</span>
                                            </div>
                                        </div>
                                        <p class="small text-muted mb-0">Click "Join Session" to connect</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info d-flex align-items-center mt-4">
                    <i class="mdi mdi-information me-2"></i>
                    <div>
                        <strong>Note:</strong> You must be logged in to join a screen sharing session. If you don't have an account, please contact your teacher.
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @endsection
    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('join-session-form');
                const joinBtn = document.getElementById('join-btn');
                const roomCodeInput = document.getElementById('room_code');
                
                // Auto-focus on room code field
                if (roomCodeInput) {
                    roomCodeInput.focus();
                }

                // Convert to uppercase and limit to 8 characters
                if (roomCodeInput) {
                    roomCodeInput.addEventListener('input', function(e) {
                        let value = e.target.value.toUpperCase();
                        // Remove any non-alphanumeric characters
                        value = value.replace(/[^A-Z0-9]/g, '');
                        // Limit to 8 characters
                        value = value.substring(0, 8);
                        e.target.value = value;
                    });

                    // Handle paste events
                    roomCodeInput.addEventListener('paste', function(e) {
                        e.preventDefault();
                        let pastedData = (e.clipboardData || window.clipboardData).getData('text');
                        pastedData = pastedData.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 8);
                        this.value = pastedData;
                    });
                }
                
                if (form && joinBtn) {
                    form.addEventListener('submit', function(e) {
                        // Show loading state
                        joinBtn.disabled = true;
                        joinBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Joining...';
                        
                        // Re-enable after 5 seconds in case of issues
                        setTimeout(() => {
                            joinBtn.disabled = false;
                            joinBtn.innerHTML = '<i class="mdi mdi-login me-2"></i>Join Session';
                        }, 5000);
                    });
                }
            });
        </script>
        
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
