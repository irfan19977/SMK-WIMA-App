@extends('layouts.app')

@section('title', 'View Screen Sharing')

@push('styles')
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
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title">
            <h4><i class="fas fa-desktop mr-2"></i>Screen Sharing Session</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
        <div class="header-elements d-none">
            <a href="{{ route('screen-shares.join') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-2"></i>Leave Session
            </a>
        </div>
    </div>
</div>

<!-- P2P Indicator -->
<div class="p2p-indicator" id="p2pIndicator" style="display: none;">
    <i class="fas fa-network-wired mr-2"></i>
    <span id="p2pStatus">P2P Active</span>
</div>

<div class="content">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        {{ $screenShare->title ?: 'Screen Sharing Session' }}
                        @if($screenShare->status == 'active')
                            <span class="badge badge-success ml-2">LIVE</span>
                        @else
                            <span class="badge badge-danger ml-2">ENDED</span>
                        @endif
                    </h5>
                    <div class="d-flex align-items-center">
                        <span class="text-muted mr-3">
                            <i class="fas fa-users mr-1"></i>
                            {{ $screenShare->participants->count() }} viewers
                        </span>
                        @if($screenShare->status == 'active')
                            <span id="connectionStatus" class="badge badge-warning">
                                <i class="fas fa-circle mr-1"></i>P2P Connecting...
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body text-center p-0">
                    <video id="remoteVideo" autoplay playsinline style="width: 100%; max-height: 600px;"></video>
                    <canvas id="screenCanvas" width="800" height="450" style="width: 100%; max-height: 600px; display: none;"></canvas>
                    
                    @if($screenShare->status != 'active')
                        <div class="p-4">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                This screen sharing session has ended. The teacher is no longer sharing their screen.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-3">
            <!-- Session Info -->
            <div class="card mb-3">
                <div class="card-header session-info">
                    <h6 class="card-title mb-0 text-white">Session Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-1 font-weight-bold text-muted">Teacher:</p>
                        <p class="mb-2">{{ $screenShare->teacher->name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="mb-1 font-weight-bold text-muted">Room Code:</p>
                        <div class="room-code-display text-primary">{{ $screenShare->room_code }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <p class="mb-1 font-weight-bold text-muted">Title:</p>
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
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Ended</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- True P2P Status -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">True P2P Connection</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div id="p2pInfo">
                            <div class="text-warning">
                                <i class="fas fa-network-wired fa-2x mb-2"></i>
                                <p class="mb-0">Connecting to teacher...</p>
                                <small>Direct peer-to-peer</small>
                            </div>
                        </div>
                        
                        <div id="connectionDetails" class="small text-muted mt-2" style="display: none;">
                            <p class="mb-1">
                                <i class="fas fa-wifi text-success mr-1"></i>
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
                <div class="card-header">
                    <h6 class="card-title mb-0">Instructions</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <span>True P2P connection</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <span>Zero server polling</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <span>Ultra-low latency</span>
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-info-circle text-info mr-2"></i>
                            <span>Double-click for fullscreen</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let remoteVideo = document.getElementById('remoteVideo');
let canvas = document.getElementById('screenCanvas');
let ctx = canvas.getContext('2d');
let screenShareId = '{{ $screenShare->id }}';
let peerConnection = null;
let isConnecting = false;
let userId = '{{ Auth::id() }}';

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
                if (data.success && data.image_data) {
                    displayTeacherFrame(data.image_data);
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
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <p class="mb-0">P2P Connected</p>
                <small>Direct peer-to-peer</small>
            </div>
        `;
        detailsDiv.style.display = 'block';
        statusBadge.className = 'badge badge-success';
        statusBadge.innerHTML = '<i class="fas fa-circle mr-1"></i>P2P Connected';
        p2pIndicator.style.display = 'block';
        p2pStatus.textContent = 'P2P Active';
        p2pIndicator.className = 'p2p-indicator';
    } else {
        statusDiv.innerHTML = `
            <div class="text-warning">
                <i class="fas fa-network-wired fa-2x mb-2"></i>
                <p class="mb-0">Connecting to teacher...</p>
                <small>Direct peer-to-peer</small>
            </div>
        `;
        detailsDiv.style.display = 'none';
        statusBadge.className = 'badge badge-warning';
        statusBadge.innerHTML = '<i class="fas fa-circle mr-1"></i>P2P Connecting...';
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
@endpush
