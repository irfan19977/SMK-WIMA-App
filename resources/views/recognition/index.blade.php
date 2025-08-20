@extends('layouts.app')

@section('title', 'Absensi Face Recognition')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Card Kiri - Profil Siswa -->
        <div class="col-md-7">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Profil Siswa</h5>
                </div>
                <div class="card-body text-center">
                    <!-- User Profile Section (Always visible now) -->
                    <div id="userProfile">
                        <!-- Foto Siswa -->
                        <div class="mb-4">
                            <img id="userPhoto" 
                                 class="img-fluid rounded-circle" 
                                 style="width: 200px; height: 200px; object-fit: cover; border: 3px solid #dee2e6;" 
                                 src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2RkZCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjI0IiBmaWxsPSIjOTk5IiBkeT0iLjNlbSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+Tm8gUGhvdG88L3RleHQ+PC9zdmc+"
                                 alt="Foto Siswa">
                        </div>
                        
                        <!-- Data Siswa -->
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Nama Lengkap:</label>
                                <p class="form-control-plaintext border rounded p-2 bg-light" id="userName">
                                    Belum ada siswa terdeteksi
                                </p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">NISN:</label>
                                <p class="form-control-plaintext border rounded p-2 bg-light" id="userNisn">
                                    -
                                </p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kelas:</label>
                                <p class="form-control-plaintext border rounded p-2 bg-light" id="userClass">
                                    -
                                </p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Lahir:</label>
                                <p class="form-control-plaintext border rounded p-2 bg-light" id="userBirthDate">
                                    -
                                </p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jenis Kelamin:</label>
                                <p class="form-control-plaintext border rounded p-2 bg-light" id="userGender">
                                    -
                                </p>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Alamat:</label>
                                <p class="form-control-plaintext border rounded p-2 bg-light" id="userAddress">
                                    -
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card Kanan -->
        <div class="col-md-5">
            <!-- Card Scan Wajah -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Scan Wajah</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="camera-container position-relative d-inline-block">
                            <video id="video" width="320" height="240" autoplay muted class="border rounded bg-light"></video>
                            <canvas id="overlay" width="320" height="240"
                                    style="position:absolute; top:0; left:0; pointer-events:none; border-radius:.375rem;"></canvas>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg" id="startCamera">
                            <i class="fas fa-camera"></i> Mulai Scan
                        </button>
                        <button class="btn btn-secondary btn-lg" id="stopCamera" disabled>
                            <i class="fas fa-stop"></i> Stop Scan
                        </button>
                        {{-- <button class="btn btn-success btn-lg" id="scanFace" disabled>
                            <i class="fas fa-search"></i> Scan Wajah
                        </button>
                        <button class="btn btn-warning btn-lg" id="clearResult">
                            <i class="fas fa-eraser"></i> Clear
                        </button> --}}
                    </div>

                    <!-- Simple Result Messages -->
                    <div id="scanMessage" class="mt-3" style="display:none;">
                        <div id="successMessage" class="alert alert-success" style="display:none;">
                            <i class="fas fa-check-circle"></i> <span id="successText"></span>
                        </div>
                        <div id="errorMessage" class="alert alert-warning" style="display:none;">
                            <i class="fas fa-times-circle"></i> <span id="errorText"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card Status Deteksi -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status Deteksi Wajah</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Status:</label>
                            <div id="recognitionStatus" class="alert alert-secondary">
                                <div class="spinner-border text-info" role="status" style="display:none;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <i class="fas fa-clock"></i> Menunggu scan wajah...
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Wajah Terdeteksi:</label>
                            <p class="form-control-plaintext border rounded p-2 bg-light">
                                <span class="badge bg-secondary" id="faceCount">0</span>
                            </p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status Siap:</label>
                            <p class="form-control-plaintext border rounded p-2 bg-light">
                                <span id="recognitionReady" class="badge bg-danger">Belum Siap</span>
                            </p>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button class="btn btn-info" id="toggleAutoScan" style="display:none;">
                                    <i class="fas fa-sync"></i> Auto-Scan: ON
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabel Riwayat Absensi -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Riwayat Absensi Hari Ini</h5>
                    <div>
                        <button class="btn btn-sm btn-outline-primary" id="refresh-table">
                            <i class="fas fa-refresh"></i> Refresh
                        </button>
                        <button class="btn btn-sm btn-outline-success" id="export-table">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="attendanceTable" class="table table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">NISN</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Waktu Absen</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Akurasi</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceHistory">
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-clock fa-2x mb-2"></i><br>
                                        Belum ada riwayat scan
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">Menampilkan data riwayat absensi</small>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.confidence-excellent{ color:#28a745; font-weight:bold; }
.confidence-good{ color:#17a2b8; font-weight:bold; }
.confidence-fair{ color:#ffc107; font-weight:bold; }
.confidence-poor{ color:#dc3545; font-weight:bold; }
.camera-container video, .camera-container canvas{ 
    display:block; 
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
#attendanceTable tbody tr:hover {
    background-color: #f8f9fa;
}
</style>
@endsection

@push('scripts')
<script>
$(function() {
    // Global variables
    let currentStream = null;
    let modelsLoaded = false;
    let isDetecting = false;
    let detectionInterval = null;
    let lastFaceEncoding = null;
    let scanCount = 0;
    let autoScanEnabled = true; // Enable auto-scan by default
    let lastScanTime = 0;
    let scanCooldown = 3000; // 3 seconds cooldown between scans
    let profileClearTimer = null; // Timer untuk clear profile
    let noFaceDetectionCount = 0; // Counter untuk deteksi tidak ada wajah
    const NO_FACE_THRESHOLD = 30; // 30 cycles (~3 detik) sebelum clear profile

    // DOM elements
    const $faceCount = $('#faceCount');
    const $recognitionReady = $('#recognitionReady');
    const $spinner = $('.spinner-border');

    const video = document.getElementById('video');
    const canvas = document.getElementById('overlay');
    const ctx = canvas.getContext('2d');

    // === INITIALIZE FACE-API MODELS ===
    (async function initializeFaceAPI(){
        try{
            showSpinner(true);
            updateStatus('Memuat model AI...');
            
            const modelPath = '/models';
            
            // Load models in parallel for faster loading
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(modelPath),
                faceapi.nets.faceLandmark68Net.loadFromUri(modelPath),
                faceapi.nets.faceRecognitionNet.loadFromUri(modelPath)
            ]);
            
            modelsLoaded = true;
            updateStatus('Model berhasil dimuat. Tekan "Mulai Scan" atau <kbd>S</kbd> untuk mulai scan otomatis.');
            showSpinner(false);
        }catch(e){
            updateStatus('Gagal memuat model AI. Silakan refresh halaman.');
            showSpinner(false);
        }
    })();

    // === EVENT LISTENERS ===
    $('#startCamera').on('click', startCamera);
    $('#stopCamera').on('click', stopCamera);
    $('#scanFace').on('click', scanFace);
    $('#clearResult').on('click', clearResult);

    // Auto-scan toggle button
    $('#toggleAutoScan').on('click', function() {
        autoScanEnabled = !autoScanEnabled;
        $(this).text(`Auto-Scan: ${autoScanEnabled ? 'ON' : 'OFF'}`);
        $(this).removeClass('btn-info btn-secondary').addClass(autoScanEnabled ? 'btn-info' : 'btn-secondary');
        updateStatus(`Scan otomatis ${autoScanEnabled ? 'diaktifkan' : 'dinonaktifkan'}`);
    });

    // === KEYBOARD SHORTCUTS ===
    $(document).on('keydown', function(e){
        if(e.ctrlKey || e.metaKey) return;
        
        switch(e.key.toLowerCase()){
            case 's':
                if(!$('#startCamera').prop('disabled')){
                    e.preventDefault();
                    startCamera();
                }
                break;
            case 'q':
                if(!$('#stopCamera').prop('disabled')){
                    e.preventDefault();
                    stopCamera();
                }
                break;
            case ' ':
                if(!$('#scanFace').prop('disabled')){
                    e.preventDefault();
                    scanFace();
                }
                break;
            case 'a':
                e.preventDefault();
                autoScanEnabled = !autoScanEnabled;
                $('#toggleAutoScan').click();
                break;
            case 'escape':
                e.preventDefault();
                clearResult();
                break;
        }
    });

    // === CAMERA FUNCTIONS ===
    async function startCamera(){
        if(!modelsLoaded){ 
            updateStatus('Model belum dimuat. Harap tunggu...'); 
            return; 
        }

        try{
            showSpinner(true);
            updateStatus('Memulai kamera...');
            
            if(currentStream) stopCamera();

            const stream = await navigator.mediaDevices.getUserMedia({
                video: { 
                    width: { ideal: 320 }, 
                    height: { ideal: 240 }, 
                    facingMode: 'user' 
                },
                audio: false
            });
            
            video.srcObject = stream;
            currentStream = stream;

            $('#startCamera').prop('disabled', true);
            $('#stopCamera').prop('disabled', false);

            // Wait for video to be ready and start detection immediately
            video.onloadedmetadata = () => {
                video.play().then(() => {
                    faceapi.matchDimensions(canvas, video, true);
                    updateStatus('Kamera aktif. Scan otomatis dimulai...');
                    showSpinner(false);
                    
                    // Start detection immediately after camera is ready
                    setTimeout(() => {
                        startFaceDetection();
                    }, 100);
                });
            };
        }catch(e){
            updateStatus('Gagal mengakses kamera. Periksa izin kamera.');
            showSpinner(false);
            stopCamera();
        }
    }

    function stopCamera(){
        // PERBAIKAN: Stop semua operasi terlebih dahulu
        isDetecting = false;
        
        // Clear detection interval
        if(detectionInterval){
            clearInterval(detectionInterval);
            detectionInterval = null;
        }
        
        // Clear profile clear timer
        if(profileClearTimer){
            clearTimeout(profileClearTimer);
            profileClearTimer = null;
        }
        
        // Stop camera stream
        if(currentStream){
            currentStream.getTracks().forEach(t => t.stop());
            currentStream = null;
        }
        
        // Reset variables
        noFaceDetectionCount = 0;
        lastFaceEncoding = null;
        
        // Update UI state
        $('#startCamera').prop('disabled', false);
        $('#stopCamera').prop('disabled', true);
        $('#scanFace').prop('disabled', true);
        
        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        updateRecognitionReady(false);
        
        // PERBAIKAN: Reset profil dengan delay untuk memastikan semua operasi selesai
        setTimeout(() => {
            resetUserProfile();
            clearResult();
            updateStatus('Kamera berhenti. Klik "Mulai Scan" untuk memulai lagi.');
        }, 100); // Small delay to ensure all operations complete
    }

    // === FACE DETECTION ===
    async function startFaceDetection(){
        isDetecting = true;

        detectionInterval = setInterval(async () => {
            if(!isDetecting || !currentStream || video.readyState < 2) return;

            try{
                const detections = await faceapi
                    .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions({
                        inputSize: 224,
                        scoreThreshold: 0.4
                    }))
                    .withFaceLandmarks()
                    .withFaceDescriptors();

                const dims = faceapi.matchDimensions(canvas, video, true);
                const resized = faceapi.resizeResults(detections, dims);

                // Clear canvas
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                $faceCount.text(resized.length);

                if(resized.length === 0){
                    // PERBAIKAN: Increment counter saat tidak ada wajah
                    noFaceDetectionCount++;
                    
                    updateStatus('Tidak ada wajah terdeteksi. Posisikan wajah Anda di dalam frame.');
                    updateRecognitionReady(false);
                    $('#scanFace').prop('disabled', true);
                    
                    // PERBAIKAN: Clear profil setelah beberapa saat tidak ada wajah
                    if(noFaceDetectionCount >= NO_FACE_THRESHOLD) {
                        if(!profileClearTimer) {
                            profileClearTimer = setTimeout(() => {
                                resetUserProfile();
                                updateStatus('Tidak ada wajah terdeteksi dalam waktu lama. Profil dikosongkan.');
                                profileClearTimer = null;
                            }, 1000); // Delay 1 detik sebelum clear
                        }
                    }
                    return;
                }
                
                // PERBAIKAN: Reset counter dan timer jika ada wajah terdeteksi
                noFaceDetectionCount = 0;
                if(profileClearTimer) {
                    clearTimeout(profileClearTimer);
                    profileClearTimer = null;
                }
                
                if(resized.length > 1){
                    updateStatus('Terdeteksi beberapa wajah. Pastikan hanya satu orang di dalam frame.');
                    updateRecognitionReady(false);
                    $('#scanFace').prop('disabled', true);
                    drawMultipleFaces(ctx, resized);
                    
                    // PERBAIKAN: Clear profil saat ada multiple faces
                    if(!profileClearTimer) {
                        profileClearTimer = setTimeout(() => {
                            resetUserProfile();
                            profileClearTimer = null;
                        }, 2000); // Clear setelah 2 detik multiple faces
                    }
                    return;
                }

                const detection = resized[0];
                const box = detection.detection.box;

                if(!isValidBox(box)){
                    updateRecognitionReady(false);
                    $('#scanFace').prop('disabled', true);
                    return;
                }

                const quality = evaluateFaceQuality(detection, dims);
                
                // Draw face box and get the color, then draw landmarks with same color
                const detectionColor = drawFaceBox(ctx, box, detection, quality);
                drawFaceLandmarks(ctx, detection.landmarks, detectionColor);

                // Store face encoding
                lastFaceEncoding = Array.from(detection.descriptor);

                if(quality.score >= 0.6){
                    updateRecognitionReady(true);
                    $('#scanFace').prop('disabled', false);
                    
                    // Auto-scan logic
                    const currentTime = Date.now();
                    if(autoScanEnabled && (currentTime - lastScanTime) > scanCooldown) {
                        updateStatus(`Wajah berkualitas ${quality.quality.toLowerCase()} terdeteksi. Melakukan scan otomatis...`);
                        lastScanTime = currentTime;
                        
                        // Auto-scan after short delay
                        setTimeout(() => {
                            if(lastFaceEncoding && autoScanEnabled) {
                                scanFace();
                            }
                        }, 500);
                    } else if(autoScanEnabled) {
                        const remaining = Math.ceil((scanCooldown - (currentTime - lastScanTime)) / 1000);
                        updateStatus(`Wajah terdeteksi. Scan berikutnya dalam ${remaining} detik...`);
                    } else {
                        updateStatus(`Wajah terdeteksi dengan kualitas ${quality.quality.toLowerCase()}. Tekan <kbd>Space</kbd> untuk scan!`);
                    }
                }else{
                    updateStatus('Wajah terdeteksi. Perbaiki pencahayaan atau posisi untuk kualitas lebih baik.');
                    updateRecognitionReady(false);
                    $('#scanFace').prop('disabled', true);
                    
                    // PERBAIKAN: Clear profil saat kualitas wajah buruk setelah beberapa saat
                    if(!profileClearTimer) {
                        profileClearTimer = setTimeout(() => {
                            resetUserProfile();
                            updateStatus('Kualitas wajah kurang baik dalam waktu lama. Profil dikosongkan.');
                            profileClearTimer = null;
                        }, 5000); // Clear setelah 5 detik kualitas buruk
                    }
                }
                
            }catch(e){
                // Silent error handling
            }
        }, 100);
    }

    // === FACE QUALITY & VALIDATION ===
    function isValidBox(box){
        return box &&
            Number.isFinite(box.x) &&
            Number.isFinite(box.y) &&
            Number.isFinite(box.width) &&
            Number.isFinite(box.height) &&
            box.width > 0 && box.height > 0;
    }

    function evaluateFaceQuality(detection, dims){
        let score = detection.detection.score;
        const box = detection.detection.box;

        // Face size evaluation
        const area = box.width * box.height;
        if(area < 30 * 30) score *= 0.5;
        else if(area < 50 * 50) score *= 0.7;
        else if(area > 60 * 60) score *= 1.1;

        // Face position evaluation
        const cx = box.x + box.width/2;
        const cy = box.y + box.height/2;
        const midX = (dims?.width ?? canvas.width) / 2;
        const midY = (dims?.height ?? canvas.height) / 2;
        const dist = Math.hypot(cx - midX, cy - midY);
        
        if(dist > 125) score *= 0.5;
        else if(dist > 75) score *= 0.7;
        else if(dist < 25) score *= 1.1;

        // Face proportion evaluation
        const ratio = box.width / box.height;
        if(ratio < 0.6 || ratio > 1.6) score *= 0.6;
        else if(ratio >= 0.8 && ratio <= 1.2) score *= 1.1;

        let quality;
        if(score >= 0.90) quality = 'Excellent';      // 90%+
        else if(score >= 0.80) quality = 'Good';      // 80-89%
        else if(score >= 0.75) quality = 'Fair';      // 75-79%
        else quality = 'Poor';                        // <75%

        // Hanya allow scan jika minimum Fair (75%)
        const isAcceptable = score >= 0.75;
        
        return { score, quality, isAcceptable };
    }

    // === DRAWING FUNCTIONS ===
    function drawFaceBox(ctx, box, detection, quality){
        const colors = {
            'Excellent': '#28a745',
            'Good': '#17a2b8',
            'Fair': '#ffc107',
            'Poor': '#dc3545'
        };
        
        const color = colors[quality.quality] || '#6c757d';

        ctx.save();
        ctx.strokeStyle = color;
        ctx.lineWidth = 2;
        ctx.strokeRect(Math.round(box.x), Math.round(box.y), Math.round(box.width), Math.round(box.height));

        // Label
        ctx.fillStyle = color;
        ctx.font = 'bold 12px Arial';
        const conf = Math.round(detection.detection.score * 100);
        const text = `${quality.quality} - ${conf}%`;
        ctx.fillText(text, Math.round(box.x), Math.round(box.y - 6));
        ctx.restore();
        
        // Return the color to be used for landmarks
        return color;
    }

    function drawFaceLandmarks(ctx, landmarks, baseColor = '#FFFFFF') {
        if (!landmarks) return;

        ctx.save();
        
        // Create color variations based on baseColor with different opacities
        const lighterColor = baseColor + '80'; // 50% opacity
        const darkerColor = baseColor + 'CC';  // 80% opacity
        const mediumColor = baseColor + 'B3';  // 70% opacity
        
        // Draw different parts with colors consistent with detection box
        const landmarkParts = {
            jaw: { points: landmarks.getJawOutline(), color: lighterColor, size: 1 },
            leftEyebrow: { points: landmarks.getLeftEyeBrow(), color: mediumColor, size: 1 },
            rightEyebrow: { points: landmarks.getRightEyeBrow(), color: mediumColor, size: 1 },
            nose: { points: landmarks.getNose(), color: baseColor, size: 1.2 },
            leftEye: { points: landmarks.getLeftEye(), color: darkerColor, size: 1.3 },
            rightEye: { points: landmarks.getRightEye(), color: darkerColor, size: 1.3 },
            mouth: { points: landmarks.getMouth(), color: baseColor, size: 1.2 }
        };

        // Draw each part
        Object.values(landmarkParts).forEach(part => {
            ctx.fillStyle = part.color;
            part.points.forEach(point => {
                ctx.beginPath();
                ctx.arc(point.x, point.y, part.size, 0, 2 * Math.PI);
                ctx.fill();
            });
        });

        // Draw connecting lines with same color but more transparent
        ctx.strokeStyle = baseColor + '40'; // 25% opacity
        ctx.lineWidth = 0.8;
        
        // Connect jaw outline
        const jawPoints = landmarks.getJawOutline();
        ctx.beginPath();
        ctx.moveTo(jawPoints[0].x, jawPoints[0].y);
        jawPoints.forEach(point => ctx.lineTo(point.x, point.y));
        ctx.stroke();

        // Connect eye points
        [landmarks.getLeftEye(), landmarks.getRightEye()].forEach(eyePoints => {
            ctx.beginPath();
            ctx.moveTo(eyePoints[0].x, eyePoints[0].y);
            eyePoints.forEach(point => ctx.lineTo(point.x, point.y));
            ctx.closePath();
            ctx.stroke();
        });

        // Connect mouth
        const mouthPoints = landmarks.getMouth();
        ctx.beginPath();
        ctx.moveTo(mouthPoints[0].x, mouthPoints[0].y);
        mouthPoints.forEach(point => ctx.lineTo(point.x, point.y));
        ctx.closePath();
        ctx.stroke();

        ctx.restore();
    }

    function drawMultipleFaces(ctx, detections){
        ctx.save();
        const multiColor = '#dc3545';
        ctx.strokeStyle = multiColor;
        ctx.lineWidth = 2;
        
        detections.forEach((detection, index) => {
            const box = detection.detection.box;
            if(isValidBox(box)){
                ctx.strokeRect(Math.round(box.x), Math.round(box.y), Math.round(box.width), Math.round(box.height));
                // Draw landmarks for multiple faces with same red color
                if(detection.landmarks) {
                    drawFaceLandmarks(ctx, detection.landmarks, multiColor);
                }
            }
        });
        
        ctx.fillStyle = multiColor;
        ctx.font = 'bold 12px Arial';
        ctx.fillText(`${detections.length} wajah`, 10, 20);
        ctx.restore();
    }

    // === FACE SCANNING WITH ATTENDANCE ===
    async function scanFace(){
        if(!lastFaceEncoding){
            showScanMessage('Tidak ada encoding wajah tersedia. Tunggu deteksi wajah.', 'error');
            return;
        }

        try{
            scanCount++;
            updateStatus('Sedang mencocokkan wajah, harap tunggu...');
            showSpinner(true);

            const response = await fetch("{{ route('face-recognition.identify') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    face_encoding: JSON.stringify(lastFaceEncoding)
                })
            });

            const result = await response.json();

            if(result.success){
                if(result.match_found){
                    const confidence = result.confidence;
                    showUserProfile(result);
                    
                    // LANGSUNG proses absensi otomatis
                    await processAutoAttendance(result.student, confidence);
                    
                    // Temporary disable auto-scan after successful recognition
                    if(autoScanEnabled) {
                        updateStatus(`${result.student.name} berhasil dikenali! Auto-scan akan berlanjut dalam beberapa detik...`);
                    }
                }else{
                    resetUserProfile();
                    showScanMessage('Wajah tidak dikenali dalam database', 'error');
                    addToAttendanceHistory(null, new Date().toLocaleString('id-ID'), 0, false, 'Tidak Dikenali');
                }
            }else{
                throw new Error(result.message || 'Pengenalan gagal');
            }
        }catch(e){
            updateStatus('Scan wajah gagal. Silakan coba lagi.');
            showScanMessage('Gagal mengenali wajah: ' + e.message, 'error');
        }finally{
            showSpinner(false);
        }
    }

    // === PROSES ABSENSI OTOMATIS ===
    async function processAutoAttendance(student, confidence) {
        // Cek apakah kamera masih aktif
        if(!currentStream || !isDetecting) {
            console.log('Camera stopped, aborting attendance process');
            return;
        }
        
        try {
            updateStatus('Memproses absensi otomatis...');
            
            const attendanceResponse = await fetch('/face-recognition/auto-attendance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    student_id: student.id,
                    confidence: confidence
                })
            });

            // Cek lagi setelah request selesai
            if(!currentStream || !isDetecting) {
                console.log('Camera stopped during request, aborting');
                return;
            }

            const attendanceResult = await attendanceResponse.json();
            const studentData = attendanceResult.student || student;

            if (attendanceResult.success) {
                const type = attendanceResult.type || 'attendance';
                const statusText = type === 'check_out' ? 'Pulang' : 'Masuk';
                
                showScanMessage(attendanceResult.message, 'success');
                showUserProfile({ student: studentData, confidence: confidence });
                
                addToAttendanceHistory(
                    studentData, 
                    new Date().toLocaleString('id-ID'), 
                    Math.round(confidence * 100), 
                    true,
                    `${statusText} - ${attendanceResult.status || 'Berhasil'}`
                );
                
                updateStatus(`Absensi ${statusText.toLowerCase()} berhasil untuk ${studentData.name}`);
                
            } else {
                const message = attendanceResult.message || 'Status tidak diketahui';
                showUserProfile({ student: studentData, confidence: confidence });
                
                if (message.toLowerCase().includes('sudah absen') || 
                    message.toLowerCase().includes('sudah melakukan absen') || 
                    message.toLowerCase().includes('already')) {
                    
                    showScanMessage(`${studentData.name} dikenali (${Math.round(confidence * 100)}% match) - ${message}`, 'info');
                    
                    addToAttendanceHistory(
                        studentData, 
                        new Date().toLocaleString('id-ID'), 
                        Math.round(confidence * 100), 
                        true,
                        'Sudah Absen Hari Ini'
                    );
                    
                    updateStatus(`${studentData.name} sudah melakukan absensi hari ini`);
                    
                } else {
                    showScanMessage(`${studentData.name} dikenali (${Math.round(confidence * 100)}% match) - ${message}`, 'warning');
                    
                    addToAttendanceHistory(
                        studentData, 
                        new Date().toLocaleString('id-ID'), 
                        Math.round(confidence * 100), 
                        true,
                        message
                    );
                    
                    updateStatus(`Siswa dikenali tapi ada masalah: ${message}`);
                }
            }

        } catch (error) {
            console.error('Error in processAutoAttendance:', error);
            
            // Cek sekali lagi sebelum update UI
            if(currentStream && isDetecting) {
                showScanMessage(`${student.name} dikenali tapi absensi gagal: ${error.message}`, 'warning');
                showUserProfile({ student: student, confidence: confidence });
                addToAttendanceHistory(
                    student, 
                    new Date().toLocaleString('id-ID'), 
                    Math.round(confidence * 100), 
                    true,
                    'Error Sistem'
                );
                updateStatus(`Error sistem saat memproses absensi untuk ${student.name}`);
            }
        }
    }

    // === PERBAIKAN FUNGSI showUserProfile ===
    function showUserProfile(result){
        // PERBAIKAN: Cek apakah kamera masih aktif
        if(!currentStream || !isDetecting) {
            console.log('Camera stopped, ignoring profile update');
            return; // Jangan update profil jika kamera sudah stop
        }
        
        // Clear timer saat ada profil baru yang akan ditampilkan
        if(profileClearTimer) {
            clearTimeout(profileClearTimer);
            profileClearTimer = null;
        }
        
        const student = result.student;
        
        $('#userName').text(student.name || 'Tidak dikenali');
        $('#userNisn').text(student.nisn || '-');
        $('#userGender').text(student.gender || '-');
        $('#userBirthDate').text(student.birth_date || '-');
        $('#userAddress').text(student.address || '-');
        $('#userClass').text(student.class || '-');
        
        // Handle foto
        if(student.face_photo_url){
            $('#userPhoto').attr('src', student.face_photo_url);
        } else { 
            $('#userPhoto').attr('src', 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2RkZCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjI0IiBmaWxsPSIjOTk5IiBkeT0iLjNlbSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+Tm8gUGhvdG88L3RleHQ+PC9zdmc+');
        }
    }

    function resetUserProfile(){
        // Force clear any existing timer
        if(profileClearTimer) {
            clearTimeout(profileClearTimer);
            profileClearTimer = null;
        }
        
        // Reset all profile fields
        $('#userName').text('Belum ada siswa terdeteksi');
        $('#userNisn').text('-');
        $('#userGender').text('-');
        $('#userBirthDate').text('-');
        $('#userAddress').text('-');
        $('#userClass').text('-');
        $('#userPhoto').attr('src', 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2RkZCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjI0IiBmaWxsPSIjOTk5IiBkeT0iLjNlbSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+Tm8gUGhvdG88L3RleHQ+PC9zdmc+');
        
        console.log('Profile reset completed'); // Debug log
    }

    function showScanMessage(message, type){
        if(type === 'success'){
            $('#successText').text(message);
            $('#successMessage').show();
            $('#errorMessage').hide();
        }else if(type === 'warning'){
            $('#errorText').text(message);
            $('#errorMessage').removeClass('alert-warning alert-danger').addClass('alert-warning').show();
            $('#successMessage').hide();
        }else if(type === 'info'){
            $('#errorText').text(message);
            $('#errorMessage').removeClass('alert-warning alert-danger').addClass('alert-info').show();
            $('#successMessage').hide();
        }else{
            $('#errorText').text(message);
            $('#errorMessage').removeClass('alert-warning alert-danger alert-info').addClass('alert-danger').show();
            $('#successMessage').hide();
        }
        $('#scanMessage').show();
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            $('#scanMessage').hide();
        }, 5000);
    }

    function clearResult(){
        resetUserProfile();
        $('#scanMessage').hide();
        updateStatus('Hasil dibersihkan. Siap untuk scan berikutnya.');
    }

    // === ATTENDANCE HISTORY ===
    function addToAttendanceHistory(student, time, confidence, isMatch, attendanceStatus){
        const $tbody = $('#attendanceHistory');
        
        // Remove "no history" message if exists
        if($tbody.find('td[colspan="8"]').length){
            $tbody.html('');
        }
        
        const rowNumber = $tbody.children().length + 1;
        const studentName = student ? (student.name || 'Tidak dikenali') : 'Tidak dikenali';
        const studentNisn = student ? (student.nisn || '-') : '-';
        
        // SIMPLIFIED: Hanya menggunakan field 'class'
        const studentClass = student ? (student.class || 'Kelas tidak tersedia') : 'Kelas tidak tersedia';
        
        const studentPhoto = student && student.face_photo_url ? 
            student.face_photo_url : 
            'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIGZpbGw9IiNlZWUiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1zaXplPSIxMCIgZmlsbD0iIzk5OSIgZHk9Ii4zZW0iIHRleHQtYW5jaG9yPSJtaWRkbGUiPk4vQTwvdGV4dD48L3N2Zz4=';
        
        let statusBadge;
        if (!isMatch) {
            statusBadge = '<span class="badge bg-warning">Tidak dikenali</span>';
        } else if (attendanceStatus && (attendanceStatus.includes('Berhasil') || attendanceStatus.includes('Masuk') || attendanceStatus.includes('Pulang'))) {
            statusBadge = '<span class="badge bg-success">Absen Berhasil</span>';
        } else if (attendanceStatus && (attendanceStatus.toLowerCase().includes('sudah absen') || 
                                        attendanceStatus.toLowerCase().includes('already') ||
                                        attendanceStatus.includes('Sudah Absen'))) {
            statusBadge = '<span class="badge bg-info">Sudah Absen</span>';
        } else if (attendanceStatus === 'Error Sistem' || attendanceStatus === 'Error Absensi') {
            statusBadge = '<span class="badge bg-danger">Error Sistem</span>';
        } else {
            statusBadge = '<span class="badge bg-secondary">Dikenali</span>';
        }
        
        const confidenceText = isMatch ? `${confidence}%` : 'N/A';
        const confidenceClass = confidence >= 90 ? 'text-success' : 
                            confidence >= 80 ? 'text-info' : 
                            confidence >= 70 ? 'text-warning' : 'text-danger';
        
        const newRow = `
            <tr>
                <td>${rowNumber}</td>
                <td>
                    <img src="${studentPhoto}" 
                        class="rounded-circle" 
                        style="width: 40px; height: 40px; object-fit: cover;" 
                        alt="Foto"
                        onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIGZpbGw9IiNlZWUiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1zaXplPSIxMCIgZmlsbD0iIzk5OSIgZHk9Ii4zZW0iIHRleHQtYW5jaG9yPSJtaWRkbGUiPk4vQTwvdGV4dD48L3N2Zz4='">
                </td>
                <td>${studentName}</td>
                <td>${studentNisn}</td>
                <td><span class="badge bg-primary">${studentClass}</span></td>
                <td>${time}</td>
                <td>${statusBadge}</td>
                <td>
                    <span class="${confidenceClass} fw-bold">${confidenceText}</span>
                    ${isMatch && confidence > 0 ? `
                        <div class="progress mt-1" style="height: 4px;">
                            <div class="progress-bar ${confidence >= 90 ? 'bg-success' : confidence >= 80 ? 'bg-info' : confidence >= 70 ? 'bg-warning' : 'bg-danger'}" 
                                style="width: ${confidence}%"></div>
                        </div>
                    ` : ''}
                </td>
            </tr>
        `;
        
        $tbody.prepend(newRow);
        
        // Keep only latest 20 records
        const rows = $tbody.children();
        if(rows.length > 20) rows.last().remove();
        
        // Update row numbers
        $tbody.children().each(function(index){
            $(this).find('td:first').text(index + 1);
        });
    }

    // === UI HELPER FUNCTIONS ===
    function updateStatus(message){
        const timestamp = new Date().toLocaleTimeString();
        $('#recognitionStatus').html(`
            <div class="small text-muted mb-1">[${timestamp}]</div>
            <div>${message}</div>
        `);
    }

    function updateRecognitionReady(ready){
        $recognitionReady
            .removeClass('bg-success bg-danger')
            .addClass(ready ? 'bg-success' : 'bg-danger')
            .text(ready ? 'Siap' : 'Belum Siap');
    }

    function showSpinner(show){
        $spinner.toggle(show);
    }

    // === CLEANUP ===
    $(window).on('beforeunload', function(){
        if(currentStream){ 
            currentStream.getTracks().forEach(track => track.stop()); 
        }
        if(detectionInterval){ 
            clearInterval(detectionInterval); 
        }
        if(profileClearTimer){
            clearTimeout(profileClearTimer);
        }
    });

    // Initialize
    updateStatus('Sistem Pengenalan Wajah dengan Auto-Absensi Siap. Klik "Mulai Scan" untuk memulai.');
});
</script>
@endpush