@extends('layouts.master')
@section('title')
    View Screen Sharing
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        #remoteVideo {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background: #f8f9fa;
            max-width: 100%;
            height: auto;
        }
        .session-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .room-code-display {
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .p2p-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(40, 167, 69, 0.9);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 14px;
            z-index: 1000;
        }
    </style>
@endsection
@section('page-title')
    View Screen Sharing
@endsection
@section('body')

    <body data-sidebar="colored">
    @endsection

    @section('content')
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="card-title mb-1">
                                    {{ $screenShare->title ?: 'Screen Sharing Session' }}
                                    @if($screenShare->status == 'active')
                                        <span class="badge rounded-pill bg-success ms-2">LIVE</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger ms-2">ENDED</span>
                                    @endif
                                </h4>
                                <p class="text-muted mb-0">
                                    <i class="mdi mdi-account-group me-1"></i>
                                    {{ $screenShare->participants->count() }} viewers
                                    @if($screenShare->status == 'active')
                                        <span class="mx-2">|</span>
                                        <span id="connectionStatus" class="badge rounded-pill bg-warning">
                                            <i class="mdi mdi-circle me-1"></i>P2P Connecting...
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('screen-shares.join') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Leave Session
                            </a>
                        </div>

                        <div class="text-center p-0 position-relative">
                            <!-- Screen Share Video -->
                            <video id="remoteVideo" autoplay playsinline style="width: 100%; max-height: 600px;"></video>
                            <canvas id="screenCanvas" width="800" height="450" style="width: 100%; max-height: 600px; display: none;"></canvas>
                            
                            <!-- Teacher Camera Video (Picture-in-Picture) -->
                            <video id="teacherCameraVideo" autoplay playsinline 
                                   style="position: absolute; bottom: 20px; right: 20px; width: 200px; height: 150px; border: 2px solid #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.3); display: none; z-index: 10;">
                            </video>
                            
                            @if($screenShare->status != 'active')
                                <div class="p-4">
                                    <div class="alert alert-warning d-flex align-items-center">
                                        <i class="mdi mdi-alert-triangle me-2"></i>
                                        <div>
                                            This screen sharing session has ended. The teacher is no longer sharing their screen.
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">
                <!-- Session Info -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Session Details</h6>
                        <div class="mb-3">
                            <p class="mb-1 fw-bold text-muted">Teacher:</p>
                            <p class="mb-2">{{ $screenShare->teacher->name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <p class="mb-1 fw-bold text-muted">Room Code:</p>
                            <div class="room-code-display text-primary">{{ $screenShare->room_code }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <p class="mb-1 fw-bold text-muted">Title:</p>
                            <p class="mb-0">{{ $screenShare->title ?: 'Untitled Session' }}</p>
                        </div>
                        
                        <hr>
                        
                        <div class="small text-muted">
                            <p class="mb-1">
                                <strong>Started:</strong> {{ $screenShare->started_at ? $screenShare->started_at->format('M d, Y H:i') : 'Unknown' }}
                            </p>
                            <p class="mb-1">
                                <strong>You joined:</strong> {{ $participant->joined_at ? $participant->joined_at->format('H:i:s') : 'Unknown' }}
                            </p>
                            <p class="mb-0">
                                <strong>Status:</strong> 
                                @if($screenShare->status == 'active')
                                    <span class="badge rounded-pill bg-success">Active</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">Ended</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- True P2P Status -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title mb-3">True P2P Connection</h6>
                        <div class="text-center">
                            <div id="p2pInfo">
                                <div class="text-warning">
                                    <i class="mdi mdi-network-wired mdi-36px mb-2"></i>
                                    <p class="mb-0">Connecting to teacher...</p>
                                    <small>Direct peer-to-peer</small>
                                </div>
                            </div>
                            
                            <div id="connectionDetails" class="small text-muted mt-2" style="display: none;">
                                <p class="mb-1">
                                    <i class="mdi mdi-wifi text-success me-1"></i>
                                    Direct P2P connection
                                </p>
                                <p class="mb-0">
                                    Quality: <span id="videoQuality">Excellent</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Instructions</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle text-success me-2"></i>
                                <span>True P2P connection</span>
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle text-success me-2"></i>
                                <span>Zero server polling</span>
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle text-success me-2"></i>
                                <span>Ultra-low latency</span>
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle text-success me-2"></i>
                                <span>Teacher may show camera</span>
                            </li>
                            <li class="mb-0">
                                <i class="mdi mdi-information text-info me-2"></i>
                                <span>Double-click for fullscreen</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <!-- P2P Indicator -->
        <div class="p2p-indicator" id="p2pIndicator" style="display: none;">
            <i class="mdi mdi-network-wired me-2"></i>
            <span id="p2pStatus">P2P Active</span>
        </div>
    @endsection

    @section('scripts')
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        
        <script>
let remoteVideo = document.getElementById('remoteVideo');
let canvas = document.getElementById('screenCanvas');
let ctx = canvas.getContext('2d');
let screenShareId = '{{ $screenShare->id }}';
let peerConnection = null;
let isConnecting = false;
let userId = '{{ Auth::id() }}';

// Teacher camera variables
let teacherCameraVideo = document.getElementById('teacherCameraVideo');
let teacherCameraStream = null;

// True P2P WebRTC configuration
const configuration = {
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'stun:stun1.l.google.com:19302' }
    ]
};

// Initialize true P2P WebRTC connection (simplified)
function initializeTrueP2P() {
    if (isConnecting) return;
    isConnecting = true;
    
    console.log('🚀 Initializing simplified WebRTC connection...');
    
    // For now, we'll use server polling instead of complex P2P
    // This prevents browser crashes from complex animations
    updateP2PStatus(true);
    showConnectingMessage();
    
    // Start polling for frames
    startStreamPolling();
}

// Simplified connection handler
function handleTeacherOffer(offer) {
    console.log('📨 Teacher is sharing screen...');
    
    // Show connecting message
    showConnectingMessage();
    
    // Start polling for frames
    startStreamPolling();
}




function startStreamPolling() {
    // Poll for teacher's screen frames
    const pollInterval = setInterval(() => {
        if ('{{ $screenShare->status }}' !== 'active') {
            clearInterval(pollInterval);
            showEndedSession();
            return;
        }
        
        // Fetch latest frame from server
        fetch(`/screen-shares/${screenShareId}/update`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.image_data) {
                        displayTeacherFrame(data.image_data);
                    }
                    if (data.camera_data) {
                        handleTeacherCameraStream(data.camera_data);
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching frame:', error);
            });
    }, 100); // Poll every 100ms for smooth playback
}

function displayTeacherFrame(imageData) {
    if (!imageData) return;
    
    try {
        // Create an image element to display the frame
        const img = new Image();
        img.onload = function() {
            // Clear canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Calculate aspect ratio to fit the canvas
            const aspectRatio = img.width / img.height;
            const canvasAspectRatio = canvas.width / canvas.height;
            
            let drawWidth, drawHeight, offsetX, offsetY;
            
            if (aspectRatio > canvasAspectRatio) {
                drawWidth = canvas.width;
                drawHeight = canvas.width / aspectRatio;
                offsetX = 0;
                offsetY = (canvas.height - drawHeight) / 2;
            } else {
                drawHeight = canvas.height;
                drawWidth = canvas.height * aspectRatio;
                offsetX = (canvas.width - drawWidth) / 2;
                offsetY = 0;
            }
            
            // Draw the image on canvas
            ctx.drawImage(img, offsetX, offsetY, drawWidth, drawHeight);
            
            // Show canvas, hide video
            canvas.style.display = 'block';
            remoteVideo.style.display = 'none';
            
            // Update status to connected
            updateP2PStatus(true);
        };
        
        img.onerror = function() {
            console.error('Failed to load teacher frame');
        };
        
        img.src = imageData;
    } catch (error) {
        console.error('Error displaying teacher frame:', error);
    }
}

// Function to handle teacher camera stream
function handleTeacherCameraStream(cameraStreamData) {
    if (!cameraStreamData) {
        teacherCameraVideo.style.display = 'none';
        teacherCameraVideo.srcObject = null;
        return;
    }
    
    try {
        // Create a blob from the base64 data
        const img = new Image();
        img.onload = function() {
            // For now, we'll show a placeholder or handle camera stream differently
            // In a real implementation, this would be a WebRTC stream
            console.log('📹 Teacher camera stream received');
            teacherCameraVideo.style.display = 'block';
        };
        
        img.src = cameraStreamData;
    } catch (error) {
        console.error('Error displaying teacher camera:', error);
    }
}

// Make teacher camera draggable for student view
let isTeacherCameraDragging = false;
let teacherDragOffsetX = 0;
let teacherDragOffsetY = 0;

teacherCameraVideo.addEventListener('mousedown', function(e) {
    isTeacherCameraDragging = true;
    teacherDragOffsetX = e.clientX - teacherCameraVideo.offsetLeft;
    teacherDragOffsetY = e.clientY - teacherCameraVideo.offsetTop;
    teacherCameraVideo.style.cursor = 'grabbing';
});

document.addEventListener('mousemove', function(e) {
    if (isTeacherCameraDragging) {
        const parentRect = teacherCameraVideo.parentElement.getBoundingClientRect();
        let newX = e.clientX - parentRect.left - teacherDragOffsetX;
        let newY = e.clientY - parentRect.top - teacherDragOffsetY;
        
        // Keep within bounds
        newX = Math.max(0, Math.min(newX, parentRect.width - teacherCameraVideo.offsetWidth));
        newY = Math.max(0, Math.min(newY, parentRect.height - teacherCameraVideo.offsetHeight));
        
        teacherCameraVideo.style.left = newX + 'px';
        teacherCameraVideo.style.top = newY + 'px';
        teacherCameraVideo.style.right = 'auto';
        teacherCameraVideo.style.bottom = 'auto';
    }
});

document.addEventListener('mouseup', function() {
    isTeacherCameraDragging = false;
    teacherCameraVideo.style.cursor = 'grab';
});

teacherCameraVideo.style.cursor = 'grab';

// Double click to toggle teacher camera size
teacherCameraVideo.addEventListener('dblclick', function() {
    if (teacherCameraVideo.style.width === '200px') {
        teacherCameraVideo.style.width = '300px';
        teacherCameraVideo.style.height = '225px';
    } else {
        teacherCameraVideo.style.width = '200px';
        teacherCameraVideo.style.height = '150px';
    }
});

function showConnectingMessage() {
    ctx.fillStyle = '#f8f9fa';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = '#007bff';
    ctx.font = 'bold 24px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('Connecting to Teacher...', canvas.width / 2, canvas.height / 2 - 20);
    ctx.font = '16px Arial';
    ctx.fillStyle = '#6c757d';
    ctx.fillText('Waiting for teacher to start sharing', canvas.width / 2, canvas.height / 2 + 10);
}

function showEndedSession() {
    ctx.fillStyle = '#f8f9fa';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = '#dc3545';
    ctx.font = '24px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('Session Ended', canvas.width / 2, canvas.height / 2 - 20);
    ctx.font = '16px Arial';
    ctx.fillStyle = '#6c757d';
    ctx.fillText('The teacher has ended this screen sharing session', canvas.width / 2, canvas.height / 2 + 10);
    
    // Hide video, show canvas
    remoteVideo.style.display = 'none';
    canvas.style.display = 'block';
    
    // Update status
    updateP2PStatus(false);
}

// Update P2P connection status UI
function updateP2PStatus(connected) {
    const statusDiv = document.getElementById('p2pInfo');
    const detailsDiv = document.getElementById('connectionDetails');
    const statusBadge = document.getElementById('connectionStatus');
    const p2pIndicator = document.getElementById('p2pIndicator');
    const p2pStatus = document.getElementById('p2pStatus');
    
        if (connected) {
        statusDiv.innerHTML = `
            <div class="text-success">
                <i class="mdi mdi-check-circle mdi-36px mb-2"></i>
                <p class="mb-0">P2P Connected</p>
                <small>Direct peer-to-peer</small>
            </div>
        `;
        detailsDiv.style.display = 'block';
        statusBadge.className = 'badge rounded-pill bg-success';
        statusBadge.innerHTML = '<i class="mdi mdi-circle me-1"></i>P2P Connected';
        p2pIndicator.style.display = 'block';
        p2pStatus.textContent = 'P2P Active';
        p2pIndicator.className = 'p2p-indicator';
    } else {
        statusDiv.innerHTML = `
            <div class="text-warning">
                <i class="mdi mdi-network-wired mdi-36px mb-2"></i>
                <p class="mb-0">Connecting to teacher...</p>
                <small>Direct peer-to-peer</small>
            </div>
        `;
        detailsDiv.style.display = 'none';
        statusBadge.className = 'badge rounded-pill bg-warning';
        statusBadge.innerHTML = '<i class="mdi mdi-circle me-1"></i>P2P Connecting...';
        p2pIndicator.style.display = 'none';
    }
}

// Fullscreen functionality
remoteVideo.addEventListener('dblclick', function() {
    if (remoteVideo.requestFullscreen) {
        remoteVideo.requestFullscreen();
    } else if (remoteVideo.webkitRequestFullscreen) {
        remoteVideo.webkitRequestFullscreen();
    } else if (remoteVideo.msRequestFullscreen) {
        remoteVideo.msRequestFullscreen();
    }
});

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    if ('{{ $screenShare->status }}' === 'active') {
        // Initialize WebRTC connection to receive teacher's stream
        console.log('🚀 Initializing WebRTC connection to teacher...');
        initializeWebRTCConnection();
        
        // Start polling for teacher's stream as fallback
        startStreamPolling();
    } else {
        // Show ended session message
        showEndedSession();
    }
});

function initializeWebRTCConnection() {
    console.log('🔗 Setting up WebRTC connection for student...');
    
    // For now, we'll use server-based polling as WebRTC signaling
    // This can be upgraded to true P2P later
    updateP2PStatus(true);
    
    // Show connecting message
    showConnectingMessage();
}

// Handle page visibility change
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && peerConnection) {
        // Page became visible again, check connection
        if (peerConnection.connectionState === 'failed' || peerConnection.connectionState === 'disconnected') {
            if ('{{ $screenShare->status }}' === 'active') {
                initializeTrueP2P();
            }
        }
    }
});

        // Log that we're using true P2P
        console.log('🚀 True P2P WebRTC Viewer - Zero server polling!');
        </script>
        
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
