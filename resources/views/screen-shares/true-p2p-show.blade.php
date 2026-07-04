@extends('layouts.master')
@section('title')
    Screen Sharing Session
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
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
        .btn-purple {
            background-color: #6f42c1;
            border-color: #6f42c1;
            color: white;
        }
        .btn-purple:hover {
            background-color: #5a32a3;
            border-color: #5a32a3;
            color: white;
        }
        #drawingCanvas {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 5;
        }
    </style>
@endsection
@section('page-title')
    Screen Sharing Session
@endsection
@section('body')

    <body data-sidebar="colored">
    @endsection

    @section('content')
        <div class="row">
            <!-- Screen Sharing Area -->
            <div class="col-lg-8">
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
                                    <span id="participantCount">{{ $screenShare->participants->count() }}</span> viewers
                                    <span class="mx-2">|</span>
                                    <span id="connectionStatus" class="badge rounded-pill bg-warning">
                                        <i class="mdi mdi-circle me-1"></i>Not Connected
                                    </span>
                                </p>
                            </div>
                            <a href="{{ route('screen-shares.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Back to Sessions
                            </a>
                        </div>

                        @if($screenShare->status == 'active')
                            <div class="d-flex gap-2 mb-4">
                                <button type="button" class="btn btn-success control-button" id="startShareBtn">
                                    <i class="mdi mdi-play me-2"></i>Start Sharing
                                </button>
                                <button type="button" class="btn btn-warning control-button" id="pauseShareBtn" style="display: none;">
                                    <i class="mdi mdi-pause me-2"></i>Pause
                                </button>
                                <button type="button" class="btn btn-danger control-button" id="stopShareBtn" style="display: none;">
                                    <i class="mdi mdi-stop me-2"></i>Stop
                                </button>
                            </div>
                            
                            <!-- Camera Controls -->
                            <div class="d-flex gap-2 mb-4">
                                <button type="button" class="btn btn-info control-button" id="startCameraBtn">
                                    <i class="mdi mdi-camera me-2"></i>Start Camera
                                </button>
                                <button type="button" class="btn btn-secondary control-button" id="stopCameraBtn" style="display: none;">
                                    <i class="mdi mdi-camera-off me-2"></i>Stop Camera
                                </button>
                            </div>
                            
                            <!-- Finger Drawing Controls -->
                            <div class="d-flex gap-2 mb-4">
                                <button type="button" class="btn btn-purple control-button" id="startFingerDrawingBtn">
                                    <i class="mdi mdi-draw me-2"></i>Start Finger Drawing
                                </button>
                                <button type="button" class="btn btn-secondary control-button" id="stopFingerDrawingBtn" style="display: none;">
                                    <i class="mdi mdi-draw me-2"></i>Stop Drawing
                                </button>
                                <button type="button" class="btn btn-warning control-button" id="changeColorBtn">
                                    <i class="mdi mdi-palette me-2"></i>Change Color
                                </button>
                                <button type="button" class="btn btn-danger control-button" id="clearDrawingBtn">
                                    <i class="mdi mdi-eraser me-2"></i>Clear Drawing
                                </button>
                            </div>
                        @endif

                        <div class="text-center position-relative">
                            <!-- Screen Share Video -->
                            <video id="localVideo" autoplay muted playsinline style="width: 100%; max-height: 600px; display: none;"></video>
                            <canvas id="screenCanvas" width="800" height="450" style="width: 100%; max-height: 600px;"></canvas>
                            
                            <!-- Finger Drawing Video (Hidden, used for detection) -->
                            <video id="fingerDrawingVideo" autoplay muted playsinline style="display: none;"></video>
                            
                            <!-- Camera Video (Picture-in-Picture) -->
                            <video id="cameraVideo" autoplay muted playsinline 
                                   style="position: absolute; bottom: 20px; right: 20px; width: 200px; height: 150px; border: 2px solid #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.3); display: none; z-index: 10;">
                            </video>
                            
                            <div class="mt-3">
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="mdi mdi-information me-2"></i>
                                    <div>
                                        <span id="statusText">Click "Start Sharing" to begin sharing your screen with participants.</span>
                                        <br><small><span id="peerCount">Peers: 0</span> | <span id="p2pMode">Mode: True P2P</span> | <span id="cameraStatus">Camera: Off</span> | <span id="fingerDrawingStatus">Drawing: Off</span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Room Info -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Room Information</h6>
                        <div class="text-center">
                            <p class="mb-2 fw-bold">Room Code:</p>
                            <div class="room-code-display text-primary mb-3">{{ $screenShare->room_code }}</div>
                            <button class="btn btn-sm btn-outline-primary" onclick="copyRoomCode()">
                                <i class="mdi mdi-content-copy me-2"></i>Copy Code
                            </button>
                        </div>
                        <hr>
                        <div class="small text-muted">
                            <p class="mb-1"><strong>Started:</strong> {{ $screenShare->started_at ? $screenShare->started_at->format('M d, Y H:i') : 'Not started' }}</p>
                            <p class="mb-0"><strong>Status:</strong> 
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
                        <h6 class="card-title mb-3">True P2P Status</h6>
                        <div class="text-center">
                            <div id="p2pInfo" class="text-warning">
                                <i class="mdi mdi-network-wired mdi-36px mb-2"></i>
                                <p class="mb-0">Waiting for peers</p>
                                <small>Direct peer-to-peer connections</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Participants -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title mb-0">Participants ({{ $screenShare->participants->count() }})</h6>
                            <button class="btn btn-sm btn-outline-secondary" onclick="refreshParticipants()">
                                <i class="mdi mdi-refresh"></i>
                            </button>
                        </div>
                        <div class="participant-list">
                            @if($screenShare->participants->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($screenShare->participants as $participant)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="mdi mdi-account-circle text-muted me-2"></i>
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
                                    <i class="mdi mdi-account-group mdi-36px mb-2"></i>
                                    <p class="mb-0">No participants yet</p>
                                    <small>Share the room code with students to get started</small>
                                </div>
                            @endif
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
                                <span>Click "Start Sharing" to share screen</span>
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle text-success me-2"></i>
                                <span>Click "Start Camera" to show your face</span>
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle text-success me-2"></i>
                                <span>Click "Start Finger Drawing" to draw with hand gestures</span>
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle text-info me-2"></i>
                                <span>Point with index finger (others down) to draw</span>
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle text-warning me-2"></i>
                                <span>Raise all 5 fingers to erase drawings</span>
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle text-success me-2"></i>
                                <span>Drag camera video to reposition</span>
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle text-success me-2"></i>
                                <span>Double-click camera to resize</span>
                            </li>
                            <li class="mb-0">
                                <i class="mdi mdi-information text-info me-2"></i>
                                <span>Camera stops when sharing ends</span>
                            </li>
                        </ul>
                    </div>
                </div>
                @if($screenShare->status == 'active')
                    <div class="mt-3">
                        <form action="{{ route('screen-shares.end', $screenShare) }}" method="POST" id="end-session-form">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100" id="end-session-btn">
                                <i class="mdi mdi-stop-circle me-2"></i>End Session
                            </button>
                        </form>
                    </div>
                @endif

        <!-- P2P Indicator -->
        <div class="p2p-indicator" id="p2pIndicator" style="display: none;">
            <i class="mdi mdi-network-wired me-2"></i>
            <span id="p2pStatus">P2P Active</span>
        </div>
    @endsection

    @section('scripts')
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        
        <!-- Finger Drawing JS -->
        <script src="{{ URL::asset('js/finger-drawing.js') }}"></script>
        
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

// Camera variables
let cameraStream = null;
let cameraVideo = document.getElementById('cameraVideo');
let isCameraOn = false;

// Finger drawing variables
let fingerDrawing = null;
let fingerDrawingVideo = document.getElementById('fingerDrawingVideo');
let isFingerDrawingOn = false;

// True P2P using WebRTC Data Channels for peer discovery
const configuration = {
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'stun:stun1.l.google.com:19302' }
    ]
};

document.getElementById('startCameraBtn').addEventListener('click', async function() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 1280 },
                height: { ideal: 720 }
            },
            audio: true
        });
        
        cameraStream = stream;
        isCameraOn = true;
        
        // Update UI
        document.getElementById('startCameraBtn').style.display = 'none';
        document.getElementById('stopCameraBtn').style.display = 'inline-block';
        document.getElementById('cameraStatus').textContent = 'Camera: On';
        
        // Show camera video
        cameraVideo.srcObject = stream;
        cameraVideo.style.display = 'block';
        
        console.log('📹 Camera started successfully');
        
    } catch (error) {
        console.error('Error accessing camera:', error);
        Swal.fire({
            icon: 'error',
            title: 'Camera Access Failed',
            text: 'Unable to access camera. Please make sure you grant the necessary permissions.',
            confirmButtonColor: '#3085d6'
        });
    }
});

document.getElementById('stopCameraBtn').addEventListener('click', function() {
    stopCamera();
});

function stopCamera() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }
    
    isCameraOn = false;
    
    // Update UI
    document.getElementById('startCameraBtn').style.display = 'inline-block';
    document.getElementById('stopCameraBtn').style.display = 'none';
    document.getElementById('cameraStatus').textContent = 'Camera: Off';
    
    // Hide camera video
    cameraVideo.style.display = 'none';
    cameraVideo.srcObject = null;
    
    console.log('📹 Camera stopped');
}

// Make camera draggable
let isDragging = false;
let dragOffsetX = 0;
let dragOffsetY = 0;

cameraVideo.addEventListener('mousedown', function(e) {
    isDragging = true;
    dragOffsetX = e.clientX - cameraVideo.offsetLeft;
    dragOffsetY = e.clientY - cameraVideo.offsetTop;
    cameraVideo.style.cursor = 'grabbing';
});

document.addEventListener('mousemove', function(e) {
    if (isDragging) {
        const parentRect = cameraVideo.parentElement.getBoundingClientRect();
        let newX = e.clientX - parentRect.left - dragOffsetX;
        let newY = e.clientY - parentRect.top - dragOffsetY;
        
        // Keep within bounds
        newX = Math.max(0, Math.min(newX, parentRect.width - cameraVideo.offsetWidth));
        newY = Math.max(0, Math.min(newY, parentRect.height - cameraVideo.offsetHeight));
        
        cameraVideo.style.left = newX + 'px';
        cameraVideo.style.top = newY + 'px';
        cameraVideo.style.right = 'auto';
        cameraVideo.style.bottom = 'auto';
    }
});

document.addEventListener('mouseup', function() {
    isDragging = false;
    cameraVideo.style.cursor = 'grab';
});

cameraVideo.style.cursor = 'grab';

// Double click to toggle camera size
cameraVideo.addEventListener('dblclick', function() {
    if (cameraVideo.style.width === '200px') {
        cameraVideo.style.width = '300px';
        cameraVideo.style.height = '225px';
    } else {
        cameraVideo.style.width = '200px';
        cameraVideo.style.height = '150px';
    }
});

// Finger Drawing Event Listeners
document.getElementById('startFingerDrawingBtn').addEventListener('click', async function() {
    try {
        if (!fingerDrawing) {
            // Initialize finger drawing
            fingerDrawing = new FingerDrawing(fingerDrawingVideo, canvas);
            
            // Wait for initialization
            await new Promise(resolve => setTimeout(resolve, 1000));
        }
        
        // Start camera for finger detection
        await fingerDrawing.startCamera();
        isFingerDrawingOn = true;
        
        // Update UI
        document.getElementById('startFingerDrawingBtn').style.display = 'none';
        document.getElementById('stopFingerDrawingBtn').style.display = 'inline-block';
        document.getElementById('fingerDrawingStatus').textContent = 'Drawing: On';
        
        console.log('🎨 Finger drawing started');
        
    } catch (error) {
        console.error('Error starting finger drawing:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to start finger drawing. Please make sure camera permissions are granted.'
        });
    }
});

document.getElementById('stopFingerDrawingBtn').addEventListener('click', function() {
    if (fingerDrawing) {
        fingerDrawing.stopCamera();
        isFingerDrawingOn = false;
        
        // Update UI
        document.getElementById('startFingerDrawingBtn').style.display = 'inline-block';
        document.getElementById('stopFingerDrawingBtn').style.display = 'none';
        document.getElementById('fingerDrawingStatus').textContent = 'Drawing: Off';
        
        console.log('🎨 Finger drawing stopped');
    }
});

document.getElementById('changeColorBtn').addEventListener('click', function() {
    if (fingerDrawing) {
        fingerDrawing.switchColor();
    }
});

document.getElementById('clearDrawingBtn').addEventListener('click', function() {
    if (fingerDrawing) {
        fingerDrawing.clearCanvas();
    }
});

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
    
    // Also stop camera when sharing stops
    if (isCameraOn) {
        stopCamera();
    }
    
    // Also stop finger drawing when sharing stops
    if (isFingerDrawingOn) {
        if (fingerDrawing) {
            fingerDrawing.stopCamera();
        }
        isFingerDrawingOn = false;
        document.getElementById('startFingerDrawingBtn').style.display = 'inline-block';
        document.getElementById('stopFingerDrawingBtn').style.display = 'none';
        document.getElementById('fingerDrawingStatus').textContent = 'Drawing: Off';
    }
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

        // End session confirmation with SweetAlert2
        document.getElementById('end-session-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'All participants will be disconnected from this session.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, end it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const endBtn = document.getElementById('end-session-btn');
                    endBtn.disabled = true;
                    endBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Ending...';
                    
                    this.submit();
                }
            });
        });

        // Log that we're using true P2P
        console.log('🚀 True P2P WebRTC Screen Sharing - Zero server polling!');
        </script>
        
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
