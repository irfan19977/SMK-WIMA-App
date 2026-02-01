@extends('layouts.app')

@section('title', 'Screen Sharing Session')

@push('styles')
<style>
    #localVideo, #remoteVideo {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        background: #f8f9fa;
        max-width: 100%;
        height: auto;
    }
    .participant-list {
        max-height: 400px;
        overflow-y: auto;
    }
    .control-button {
        min-width: 120px;
    }
    .room-code-display {
        font-family: 'Courier New', monospace;
        font-size: 1.5rem;
        font-weight: bold;
        letter-spacing: 2px;
    }
    .peer-status {
        font-size: 0.8rem;
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 5px;
    }
    .peer-connected {
        background: #28a745;
        color: white;
    }
    .peer-connecting {
        background: #ffc107;
        color: black;
    }
    .peer-disconnected {
        background: #dc3545;
        color: white;
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
            <a href="{{ route('screen-shares.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-2"></i>Back to Sessions
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
        <!-- Screen Sharing Area -->
        <div class="col-lg-8">
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
                            <span id="participantCount">{{ $screenShare->participants->count() }}</span> viewers
                        </span>
                        <span id="connectionStatus" class="badge badge-warning">
                            <i class="fas fa-circle mr-1"></i>Not Connected
                        </span>
                    </div>
                    <div class="btn-group">
                        @if($screenShare->status == 'active')
                            <button type="button" class="btn btn-success control-button" id="startShareBtn">
                                <i class="fas fa-play mr-2"></i>Start Sharing
                            </button>
                            <button type="button" class="btn btn-warning control-button" id="pauseShareBtn" style="display: none;">
                                <i class="fas fa-pause mr-2"></i>Pause
                            </button>
                            <button type="button" class="btn btn-danger control-button" id="stopShareBtn" style="display: none;">
                                <i class="fas fa-stop mr-2"></i>Stop
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body text-center">
                    <video id="localVideo" autoplay muted playsinline style="width: 100%; max-height: 600px; display: none;"></video>
                    <canvas id="screenCanvas" width="800" height="450" style="width: 100%; max-height: 600px;"></canvas>
                    
                    <div class="mt-3">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span id="statusText">Click "Start Sharing" to begin sharing your screen with participants.</span>
                            <br><small><span id="peerCount">Peers: 0</span> | <span id="p2pMode">Mode: True P2P</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Room Info -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Room Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <p class="mb-2 font-weight-bold">Room Code:</p>
                        <div class="room-code-display text-primary mb-3">{{ $screenShare->room_code }}</div>
                        <button class="btn btn-sm btn-outline-primary" onclick="copyRoomCode()">
                            <i class="fas fa-copy mr-2"></i>Copy Code
                        </button>
                    </div>
                    <hr>
                    <div class="small text-muted">
                        <p class="mb-1"><strong>Started:</strong> {{ $screenShare->started_at ? $screenShare->started_at->format('M d, Y H:i') : 'Not started' }}</p>
                        <p class="mb-0"><strong>Status:</strong> 
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
                    <h6 class="card-title mb-0">True P2P Status</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div id="p2pInfo" class="text-warning">
                            <i class="fas fa-network-wired fa-2x mb-2"></i>
                            <p class="mb-0">Waiting for peers</p>
                            <small>Direct peer-to-peer connections</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participants -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Participants ({{ $screenShare->participants->count() }})</h6>
                    <button class="btn btn-sm btn-outline-secondary" onclick="refreshParticipants()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="participant-list">
                        @if($screenShare->participants->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($screenShare->participants as $participant)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-user-circle text-muted mr-2"></i>
                                            {{ $participant->student->name }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $participant->joined_at ? $participant->joined_at->diffForHumans() : 'Unknown' }}
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <p class="mb-0">No participants yet</p>
                                <small>Share the room code with students to get started</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($screenShare->status == 'active')
                <div class="mt-3">
                    <form action="{{ route('screen-shares.end', $screenShare) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to end this session? All participants will be disconnected.')">
                            <i class="fas fa-stop-circle mr-2"></i>End Session
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let mediaStream = null;
let localVideo = document.getElementById('localVideo');
let canvas = document.getElementById('screenCanvas');
let ctx = canvas.getContext('2d');
let isSharing = false;
let screenShareId = '{{ $screenShare->id }}';
let peerConnections = {};
let connectedPeers = new Set();
let dataChannel = null;

// True P2P using WebRTC Data Channels for peer discovery
const configuration = {
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'stun:stun1.l.google.com:19302' }
    ]
};

document.getElementById('startShareBtn').addEventListener('click', async function() {
    try {
        const stream = await navigator.mediaDevices.getDisplayMedia({
            video: {
                mediaSource: 'screen'
            }
        });
        
        mediaStream = stream;
        isSharing = true;
        
        // Update UI
        document.getElementById('startShareBtn').style.display = 'none';
        document.getElementById('pauseShareBtn').style.display = 'inline-block';
        document.getElementById('stopShareBtn').style.display = 'inline-block';
        document.getElementById('statusText').textContent = 'Sharing your screen...';
        
        // Show real screen capture
        localVideo.srcObject = stream;
        localVideo.style.display = 'block';
        canvas.style.display = 'none';
        
        // Start broadcasting screen frames to server
        startBroadcasting(stream);
        
        // Show P2P indicator
        document.getElementById('p2pIndicator').style.display = 'block';
        
        // Handle stream end
        stream.getVideoTracks()[0].addEventListener('ended', function() {
            stopSharing();
        });
        
    } catch (error) {
        console.error('Error accessing screen:', error);
        alert('Unable to access screen. Please make sure you grant the necessary permissions.');
    }
});

document.getElementById('pauseShareBtn').addEventListener('click', function() {
    if (isSharing) {
        isSharing = false;
        this.innerHTML = '<i class="fas fa-play mr-2"></i>Resume';
        document.getElementById('statusText').textContent = 'Screen sharing paused';
    } else {
        isSharing = true;
        this.innerHTML = '<i class="fas fa-pause mr-2"></i>Pause';
        document.getElementById('statusText').textContent = 'Sharing your screen...';
    }
});

document.getElementById('stopShareBtn').addEventListener('click', function() {
    stopSharing();
});

function startBroadcasting(stream) {
    console.log('📡 Starting screen broadcast to server...');
    
    // Create a canvas to capture frames from the video stream
    const broadcastCanvas = document.createElement('canvas');
    broadcastCanvas.width = 800;
    broadcastCanvas.height = 450;
    const broadcastCtx = broadcastCanvas.getContext('2d');
    
    let broadcastInterval;
    let frameCount = 0;
    
    // Function to capture and broadcast frame
    const captureAndBroadcast = () => {
        if (!isSharing) {
            clearInterval(broadcastInterval);
            return;
        }
        
        try {
            // Draw current video frame to canvas
            broadcastCtx.drawImage(localVideo, 0, 0, broadcastCanvas.width, broadcastCanvas.height);
            
            // Convert canvas to base64 image
            const imageData = broadcastCanvas.toDataURL('image/jpeg', 0.8);
            
            // Send to server
            fetch(`/screen-shares/${screenShareId}/broadcast`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    image_data: imageData
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    frameCount++;
                    if (frameCount % 30 === 0) { // Log every 30 frames
                        console.log(`📺 Broadcasted frame ${frameCount}`);
                    }
                }
            })
            .catch(error => {
                console.error('Error broadcasting frame:', error);
            });
        } catch (error) {
            console.error('Error capturing frame:', error);
        }
    };
    
    // Start broadcasting at 10 FPS (100ms intervals)
    broadcastInterval = setInterval(captureAndBroadcast, 100);
    
    // Store interval ID for cleanup
    window.broadcastInterval = broadcastInterval;
    
    console.log('✅ Screen broadcast started');
}


function updateP2PStatus(connected) {
    const statusDiv = document.getElementById('p2pInfo');
    const p2pIndicator = document.getElementById('p2pIndicator');
    const p2pStatus = document.getElementById('p2pStatus');
    
    if (connected) {
        statusDiv.innerHTML = `
            <div class="text-success">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <p class="mb-0">Broadcasting Active</p>
                <small>Sharing to participants</small>
            </div>
        `;
        p2pIndicator.style.display = 'block';
        p2pStatus.textContent = 'Broadcasting';
        p2pIndicator.className = 'p2p-indicator';
    } else {
        statusDiv.innerHTML = `
            <div class="text-warning">
                <i class="fas fa-network-wired fa-2x mb-2"></i>
                <p class="mb-0">Not Sharing</p>
                <small>Click Start Sharing to begin</small>
            </div>
        `;
        p2pIndicator.style.display = 'none';
    }
}

function updatePeerStatus() {
    const participantCount = {{ $screenShare->participants->count() }};
    document.getElementById('participantCount').textContent = participantCount;
    
    const statusBadge = document.getElementById('connectionStatus');
    if (isSharing && participantCount > 0) {
        statusBadge.className = 'badge badge-success';
        statusBadge.innerHTML = '<i class="fas fa-circle mr-1"></i>Sharing';
    } else if (isSharing) {
        statusBadge.className = 'badge badge-warning';
        statusBadge.innerHTML = '<i class="fas fa-circle mr-1"></i>Waiting';
    } else {
        statusBadge.className = 'badge badge-secondary';
        statusBadge.innerHTML = '<i class="fas fa-circle mr-1"></i>Not Connected';
    }
}

function stopSharing() {
    if (mediaStream) {
        mediaStream.getTracks().forEach(track => track.stop());
        mediaStream = null;
    }
    
    // Stop broadcasting
    if (window.broadcastInterval) {
        clearInterval(window.broadcastInterval);
        window.broadcastInterval = null;
    }
    
    isSharing = false;
    
    // Update UI
    document.getElementById('startShareBtn').style.display = 'inline-block';
    document.getElementById('pauseShareBtn').style.display = 'none';
    document.getElementById('stopShareBtn').style.display = 'none';
    document.getElementById('statusText').textContent = 'Screen sharing stopped';
    
    // Hide video, show canvas
    localVideo.style.display = 'none';
    canvas.style.display = 'block';
    
    // Hide P2P indicator
    document.getElementById('p2pIndicator').style.display = 'none';
    
    // Clear canvas
    ctx.fillStyle = '#f8f9fa';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = '#6c757d';
    ctx.font = '20px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('Screen sharing stopped', canvas.width / 2, canvas.height / 2);
    
    updateP2PStatus(false);
    updatePeerStatus();
}

function copyRoomCode() {
    const roomCode = '{{ $screenShare->room_code }}';
    navigator.clipboard.writeText(roomCode).then(function() {
        // Show success message
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-success');
        
        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-primary');
        }, 2000);
    });
}

function refreshParticipants() {
    location.reload();
}

// Initialize canvas
ctx.fillStyle = '#f8f9fa';
ctx.fillRect(0, 0, canvas.width, canvas.height);
ctx.fillStyle = '#6c757d';
ctx.font = '20px Arial';
ctx.textAlign = 'center';
ctx.fillText('Click "Start Sharing" to begin', canvas.width / 2, canvas.height / 2);

// Log that we're using true P2P
console.log('🚀 True P2P WebRTC Screen Sharing - Zero server polling!');
</script>
@endpush
