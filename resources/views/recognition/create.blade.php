@extends('layouts.app')

@section('title', 'Absensi Face Recognition')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-user-plus"></i> Face Recognition Registration
                    </h4>
                </div>
                <div class="card-body">

                    {{-- PILIH SISWA --}}
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">Select Student</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="studentSelect" class="form-label">Choose Student:</label>
                                            <select id="studentSelect" class="form-select">
                                                <option value="">-- Select Student --</option>
                                                @foreach($students as $student)
                                                    <option value="{{ $student->id }}"
                                                        data-name="{{ $student->name }}"
                                                        data-nisn="{{ $student->nisn }}"
                                                        data-registered="{{ $student->face_registered_at ? 'true' : 'false' }}">
                                                        {{ $student->name }} ({{ $student->nisn }}) {{ $student->face_registered_at ? '✓ Registered' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="studentInfo" class="mt-1" style="display:none;">
                                                <div class="alert alert-info mb-0">
                                                    <h6 id="selectedStudentName" class="mb-1"></h6>
                                                    <p class="mb-1">NISN: <span id="selectedStudentNisn"></span></p>
                                                    <div id="registrationStatus"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- /row -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- KAMERA --}}
                        <div class="col-md-8">
                            <div class="text-center">
                                <div class="camera-container position-relative d-inline-block">
                                    <video id="video" width="640" height="480" autoplay muted class="border rounded bg-light"></video>
                                    <canvas id="overlay" width="640" height="480"
                                            style="position:absolute; top:0; left:0; pointer-events:none; border-radius:.375rem;"></canvas>
                                </div>

                                <div class="mt-3">
                                    <button id="startCamera" class="btn btn-primary me-2">
                                        <i class="fas fa-camera"></i> Start Camera
                                    </button>
                                    <button id="stopCamera" class="btn btn-secondary me-2" disabled>
                                        <i class="fas fa-stop"></i> Stop Camera
                                    </button>
                                    <button id="capturePhoto" class="btn btn-success me-2" disabled>
                                        <i class="fas fa-camera-retro"></i> Capture & Register
                                    </button>
                                    <button id="clearLog" class="btn btn-warning">
                                        <i class="fas fa-eraser"></i> Clear Log
                                    </button>
                                </div>

                                {{-- PREVIEW FOTO --}}
                                <div id="photoPreview" class="mt-3" style="display:none;">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Captured Photo</h6>
                                        </div>
                                        <div class="card-body text-center">
                                            <img id="capturedImage" class="img-thumbnail" style="max-width:300px;" />
                                            <div class="mt-2">
                                                <button id="saveRegistration" class="btn btn-success me-2">
                                                    <i class="fas fa-save"></i> Save Registration
                                                </button>
                                                <button id="retakePhoto" class="btn btn-warning">
                                                    <i class="fas fa-redo"></i> Retake
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- STATUS & LOG --}}
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="card-title mb-0">Registration Status</h5>
                                </div>
                                <div class="card-body">
                                    <div id="detectionStatus" class="text-center mb-3">
                                        <p class="text-muted">Select a student and start camera</p>
                                    </div>
                                    <div id="detectionStats" class="small text-muted">
                                        <div>Faces Detected: <span id="faceCount">0</span></div>
                                        <div>Face Quality: <span id="faceQuality">-</span></div>
                                        <div>Ready to Capture: <span id="readyStatus" class="text-danger">No</span></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border mt-3">
                                <div class="card-header bg-info text-white">
                                    <h6 class="card-title mb-0">Registration Log</h6>
                                </div>
                                <div class="card-body p-2">
                                    <div id="registrationLog" class="registration-info"
                                         style="max-height:300px; overflow-y:auto; font-family:monospace; font-size:11px;">
                                        <div class="text-muted">Log will appear here...</div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border mt-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="card-title mb-0">Recently Registered</h6>
                                </div>
                                <div class="card-body p-2">
                                    <div id="recentRegistrations" class="small">
                                        @forelse($recentRegistrations ?? [] as $student)
                                            <div class="border-bottom pb-1 mb-1">
                                                <strong>{{ $student->name }}</strong><br>
                                                <small class="text-muted">
                                                    @if($student->face_registered_at)
                                                        {{-- Safe check: pastikan face_registered_at adalah Carbon object --}}
                                                        @if(is_string($student->face_registered_at))
                                                            {{ \Carbon\Carbon::parse($student->face_registered_at)->diffForHumans() }}
                                                        @else
                                                            {{ $student->face_registered_at->diffForHumans() }}
                                                        @endif
                                                    @else
                                                        Just registered
                                                    @endif
                                                </small>
                                            </div>
                                        @empty
                                            <div class="text-muted">No recent registrations</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                        </div> <!-- /col-md-4 -->
                    </div> <!-- /row -->

                </div> <!-- /card-body -->
            </div> <!-- /card -->

        </div>
    </div>
</div>

<style>
.registration-info{ background:#f8f9fa; border:1px solid #dee2e6; border-radius:4px; }
.face-quality-good{ color:#28a745; font-weight:bold; }
.face-quality-medium{ color:#ffc107; font-weight:bold; }
.face-quality-poor{ color:#dc3545; font-weight:bold; }
.camera-container video, .camera-container canvas{ display:block; }
</style>
@endsection

@push('scripts')
<script src="{{ asset('js/face-api.min.js') }}"></script>
<script>
$(function() {
    let currentStream = null;
    let modelsLoaded = false;
    let isDetecting = false;
    let detectionInterval = null;
    let selectedStudent = null;
    let capturedImageData = null;
    let faceEncoding = null;

    const $log = $('#registrationLog');
    const $faceCount = $('#faceCount');
    const $faceQuality = $('#faceQuality');
    const $readyStatus = $('#readyStatus');

    const video = document.getElementById('video');
    const canvas = document.getElementById('overlay');
    const ctx = canvas.getContext('2d');

    // === INIT MODELS ===
    (async function initializeFaceAPI(){
        try{
            addLog('🔄 Loading Face-API models...');
            const modelPath = '/models';
            await faceapi.nets.tinyFaceDetector.loadFromUri(modelPath);
            await faceapi.nets.faceLandmark68Net.loadFromUri(modelPath);
            await faceapi.nets.faceRecognitionNet.loadFromUri(modelPath);
            modelsLoaded = true;
            addLog('🎉 Models loaded successfully!');
            updateStatus('Models loaded. Select student and start camera.');
        }catch(e){
            addLog('❌ Error loading models: ' + e.message);
            updateStatus('Failed to load AI models.');
        }
    })();

    // === EVENTS ===
    $('#studentSelect').on('change', handleStudentSelection);
    $('#startCamera').on('click', startCamera);
    $('#stopCamera').on('click', stopCamera);
    $('#capturePhoto').on('click', capturePhoto);
    $('#saveRegistration').on('click', saveRegistration);
    $('#retakePhoto').on('click', retakePhoto);
    $('#clearLog').on('click', clearLog);

    function handleStudentSelection(){
        const $opt = $('#studentSelect').find(':selected');
        if(!$opt.val()){
            selectedStudent = null;
            $('#studentInfo').hide();
            $('#photoPreview').hide();
            return;
        }
        selectedStudent = {
            id: $opt.val(),
            name: $opt.data('name'),
            nisn: $opt.data('nisn'),
            registered: $opt.data('registered') === 'true'
        };
        $('#selectedStudentName').text(selectedStudent.name);
        $('#selectedStudentNisn').text(selectedStudent.nisn);
        $('#registrationStatus').html(selectedStudent.registered
            ? '<span class="text-warning">⚠️ Face already registered</span>'
            : '<span class="text-info">✨ Ready for registration</span>');
        $('#studentInfo').show();
        addLog(`👤 Selected: ${selectedStudent.name} (${selectedStudent.nisn})`);
    }

    async function startCamera(){
        if(!modelsLoaded){ addLog('⚠️ Models not loaded yet'); return; }
        if(!selectedStudent){ alert('Please select a student first'); return; }

        try{
            addLog('📷 Starting camera...');
            if(currentStream) stopCamera();

            const stream = await navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' },
                audio: false
            });
            video.srcObject = stream;
            currentStream = stream;

            $('#startCamera').prop('disabled', true);
            $('#stopCamera').prop('disabled', false);

            await new Promise(resolve => {
                video.onloadedmetadata = () => {
                    video.play().then(resolve);
                };
            });

            // Samakan kanvas dengan dimensi video aktual
            faceapi.matchDimensions(canvas, video, true);
            addLog(`✅ Camera active: ${video.videoWidth}x${video.videoHeight}`);
            updateStatus('Camera active. Position face in frame...');
            startFaceDetection();
        }catch(e){
            addLog('❌ Camera error: ' + e.message);
            updateStatus('Failed to access camera.');
            stopCamera();
        }
    }

    function stopCamera(){
        addLog('🛑 Stopping camera...');
        if(currentStream){
            currentStream.getTracks().forEach(t => t.stop());
            currentStream = null;
        }
        if(detectionInterval){
            clearInterval(detectionInterval);
            detectionInterval = null;
        }
        isDetecting = false;
        $('#startCamera').prop('disabled', false);
        $('#stopCamera').prop('disabled', true);
        $('#capturePhoto').prop('disabled', true);
        ctx.clearRect(0,0,canvas.width, canvas.height);
        updateReadyStatus(false);
        updateStatus('Camera stopped');
    }

    async function startFaceDetection(){
        isDetecting = true;

        detectionInterval = setInterval(async () => {
            if(!isDetecting || !currentStream || video.readyState < 2) return;

            try{
                const detections = await faceapi
                    .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions({
                        inputSize: 224,
                        scoreThreshold: 0.5
                    }))
                    .withFaceLandmarks()
                    .withFaceDescriptors();

                // Resize hasil ke dimensi canvas/video saat ini
                const dims = faceapi.matchDimensions(canvas, video, true);
                const resized = faceapi.resizeResults(detections, dims);

                // Bersihkan kanvas
                ctx.clearRect(0,0,canvas.width, canvas.height);

                // Update count
                $faceCount.text(resized.length);

                if(resized.length === 0){
                    updateStatus('No face detected. Please position your face in frame.');
                    updateReadyStatus(false);
                    $('#capturePhoto').prop('disabled', true);
                    return;
                }
                if(resized.length > 1){
                    updateStatus('Multiple faces detected. Ensure only one person in frame.');
                    updateReadyStatus(false);
                    $('#capturePhoto').prop('disabled', true);
                    return;
                }

                const det = resized[0];
                const box = det.detection.box;

                if(!isValidBox(box)){
                    // Kunci: jangan pakai top/bottom/left/right (yang bisa null)
                    addLog('⚠️ Skipped drawing (invalid box coords)');
                    updateReadyStatus(false);
                    $('#capturePhoto').prop('disabled', true);
                    return;
                }

                const quality = evaluateFaceQuality(det, dims);
                drawFaceBox(ctx, box, det, quality);

                // Update UI kualitas
                updateFaceQuality(quality);

                if(quality.score >= 0.8){
                    updateStatus('Perfect! Face detected with good quality.');
                    updateReadyStatus(true);
                    $('#capturePhoto').prop('disabled', false);
                }else{
                    updateStatus('Face detected. Improve lighting/position for better quality.');
                    updateReadyStatus(false);
                    $('#capturePhoto').prop('disabled', true);
                }
            }catch(e){
                addLog('❌ Detection error: ' + e.message);
            }
        }, 120);
    }

    function isValidBox(box){
        // face-api Rectangle: x,y,width,height -> hindari properti top/bottom/left/right yang bisa null
        return box &&
               Number.isFinite(box.x) &&
               Number.isFinite(box.y) &&
               Number.isFinite(box.width) &&
               Number.isFinite(box.height) &&
               box.width > 0 && box.height > 0;
    }

    function evaluateFaceQuality(det, dims){
        let score = det.detection.score; // confidence detector
        const box = det.detection.box;

        // ukuran wajah minimum ~100x100
        const area = box.width * box.height;
        if(area < 100 * 100) score *= 0.7;

        // posisi relatif ke tengah frame
        const cx = box.x + box.width/2;
        const cy = box.y + box.height/2;
        const midX = (dims?.width ?? canvas.width) / 2;
        const midY = (dims?.height ?? canvas.height) / 2;
        const dist = Math.hypot(cx - midX, cy - midY);
        if(dist > 100) score *= 0.9;

        const quality = score >= 0.8 ? 'Good' : score >= 0.6 ? 'Medium' : 'Poor';
        return { score, quality };
    }

    function drawFaceBox(ctx, box, det, quality){
        // pilih warna berdasarkan kualitas
        let color = '#00ff00';
        if(quality.score < 0.6) color = '#ff0000';
        else if(quality.score < 0.8) color = '#ffc107';

        ctx.save();
        ctx.strokeStyle = color;
        ctx.lineWidth = 3;
        ctx.strokeRect(Math.round(box.x), Math.round(box.y), Math.round(box.width), Math.round(box.height));

        ctx.fillStyle = color;
        ctx.font = 'bold 16px Arial';
        const conf = Math.round(det.detection.score * 100);
        ctx.fillText(`${quality.quality} - ${conf}%`, Math.round(box.x), Math.round(box.y - 8));

        // gambar landmark jika ada (aman-kan koordinat)
        if(det.landmarks && det.landmarks.positions){
            ctx.fillStyle = color;
            for(const p of det.landmarks.positions){
                if(Number.isFinite(p.x) && Number.isFinite(p.y)){
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, 1.2, 0, Math.PI*2);
                    ctx.fill();
                }
            }
        }
        ctx.restore();
    }

    async function capturePhoto(){
        if(!selectedStudent){ alert('Please select a student first'); return; }

        try{
            addLog(`📸 Capturing photo for ${selectedStudent.name}...`);
            // ambil snapshot aslinya dari video
            const snap = document.createElement('canvas');
            snap.width = video.videoWidth || 640;
            snap.height = video.videoHeight || 480;
            snap.getContext('2d').drawImage(video, 0, 0, snap.width, snap.height);

            // deteksi sekali lagi untuk descriptor (encoding)
            const det = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 }))
                .withFaceLandmarks()
                .withFaceDescriptor();

            if(!det){
                addLog('❌ No face detected during capture');
                alert('No face detected. Please try again.');
                return;
            }

            faceEncoding = Array.from(det.descriptor);
            capturedImageData = snap.toDataURL('image/jpeg', 0.85);

            $('#capturedImage').attr('src', capturedImageData);
            $('#photoPreview').show();

            addLog(`✅ Photo captured. Encoding length: ${faceEncoding.length}`);
        }catch(e){
            addLog('❌ Capture error: ' + e.message);
            alert('Failed to capture photo: ' + e.message);
        }
    }

    async function saveRegistration(){
        if(!selectedStudent || !capturedImageData || !faceEncoding){
            alert('Missing required data for registration'); return;
        }

        try{
            addLog(`💾 Saving registration for ${selectedStudent.name}...`);

            const formData = new FormData();
            formData.append('student_id', selectedStudent.id);
            formData.append('face_encoding', JSON.stringify(faceEncoding));

            // base64 → Blob
            const blob = await (await fetch(capturedImageData)).blob();
            formData.append('face_photo', blob, `${selectedStudent.nisn}_face.jpg`);

            const res = await fetch("{{ route('face-recognition.store') }}", {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            const json = await res.json();
            if(json.success){
                addLog('✅ Registration saved.');
                alert(`Face registration completed for ${selectedStudent.name}`);
                addToRecentRegistrations(selectedStudent.name);

                // tandai option sebagai registered
                const $opt = $(`#studentSelect option[value="${selectedStudent.id}"]`);
                if(!$opt.text().includes('✓ Registered')){
                    $opt.text($opt.text() + ' ✓ Registered').data('registered','true');
                }

                resetForm();
            }else{
                throw new Error(json.message || 'Registration failed');
            }
        }catch(e){
            addLog('❌ Save error: ' + e.message);
            alert('Failed to save registration: ' + e.message);
        }
    }

    function retakePhoto(){
        $('#photoPreview').hide();
        capturedImageData = null;
        faceEncoding = null;
        addLog('🔄 Ready for new photo capture');
    }

    function resetForm(){
        stopCamera();
        $('#studentSelect').val('');
        $('#studentInfo').hide();
        $('#photoPreview').hide();
        selectedStudent = null;
        capturedImageData = null;
        faceEncoding = null;
        updateStatus('Select a student to begin registration');
    }

    function updateStatus(message){
        const ts = new Date().toLocaleTimeString();
        $('#detectionStatus').html(`
            <div class="small text-muted">[${ts}]</div>
            <div><strong>${message}</strong></div>
        `);
    }

    function updateReadyStatus(ready){
        $readyStatus
            .toggleClass('text-success', !!ready)
            .toggleClass('text-danger', !ready)
            .text(ready ? 'Yes' : 'No');
    }

    function updateFaceQuality(quality){
        $faceQuality
            .removeClass('face-quality-good face-quality-medium face-quality-poor')
            .addClass(quality.score >= 0.8 ? 'face-quality-good'
                   : quality.score >= 0.6 ? 'face-quality-medium'
                   : 'face-quality-poor')
            .text(quality.quality);
    }

    function addToRecentRegistrations(name){
        const $wrap = $('#recentRegistrations');
        const node = `
            <div class="border-bottom pb-1 mb-1">
                <strong>${name}</strong><br>
                <small class="text-muted">Just now</small>
            </div>
        `;
        if($wrap.find('.text-muted:contains("No recent")').length){
            $wrap.html(node);
        }else{
            $wrap.prepend(node);
        }
        const children = $wrap.children();
        if(children.length > 5) children.last().remove();
    }

    function addLog(msg){
        const line = `[${new Date().toLocaleTimeString()}] ${msg}`;
        const hasPlaceholder = $log.text().includes('Log will appear here...');
        if(hasPlaceholder){ $log.html(`<div>${line}</div>`); }
        else{ $log.append(`<div>${line}</div>`); }
        $log.scrollTop($log[0].scrollHeight);
    }

    function clearLog(){
        $log.html('<div class="text-muted">Log cleared...</div>');
    }

    // Bersih-bersih saat unload
    $(window).on('beforeunload', function(){
        if(currentStream){ currentStream.getTracks().forEach(t => t.stop()); }
        if(detectionInterval){ clearInterval(detectionInterval); }
    });
});

</script>
@endpush
