@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Tabel Daftar Siswa -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Siswa</h5>
                    <div>
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" id="searchStudent" placeholder="Cari nama atau NISN...">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="studentsTable" class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">NISN</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="studentsList">
                                @forelse($students ?? [] as $index => $student)
                                    <tr data-student='{"id":"{{ $student->id ?? $index + 1 }}","nisn":"{{ $student->nisn ?? sprintf('%03d', $index + 1) }}","name":"{{ $student->name ?? 'Student ' . ($index + 1) }}","class":"{{ $student->class ?? 'X-A' }}","birthDate":"{{ $student->birth_date ?? '15 Januari 2008' }}","gender":"{{ $student->gender ?? 'Laki-laki' }}","address":"{{ $student->address ?? 'Jakarta' }}","status":"{{ $student->face_registered_at ? 'registered' : 'unregistered' }}","photo":"https://via.placeholder.com/50x50/007bff/ffffff?text={{ substr($student->name ?? 'ST', 0, 2) }}"}'>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <img src="https://via.placeholder.com/50x50/007bff/ffffff?text={{ substr($student->name ?? 'ST', 0, 2) }}" 
                                                 class="rounded-circle" width="50" height="50" alt="{{ $student->name ?? 'Student' }}">
                                        </td>
                                        <td>{{ $student->name ?? 'Student ' . ($index + 1) }}</td>
                                        <td>{{ $student->nisn ?? sprintf('%03d', $index + 1) }}</td>
                                        <td>{{ $student->class ?? 'X-A' }}</td>
                                        <td>
                                            @if($student->face_registered_at ?? false)
                                                <span class="badge badge-warning">Sudah Terdaftar</span>
                                            @else
                                                <span class="badge badge-success">Belum Terdaftar</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($student->face_registered_at ?? false)
                                                <button class="btn btn-warning btn-sm select-student">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            @else
                                                <button class="btn btn-primary btn-sm select-student">
                                                    <i class="fas fa-user-plus"></i> Pilih
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Sample data jika tidak ada data siswa -->
                                    <tr data-student='{"id":"1","nisn":"001","name":"Ahmad Rizki","class":"X-A","birthDate":"15 Januari 2008","gender":"Laki-laki","address":"Jl. Merdeka No. 123, Jakarta","status":"unregistered","photo":"https://via.placeholder.com/50x50/007bff/ffffff?text=AR"}'>
                                        <td>1</td>
                                        <td>
                                            <img src="https://via.placeholder.com/50x50/007bff/ffffff?text=AR" 
                                                 class="rounded-circle" width="50" height="50" alt="Ahmad Rizki">
                                        </td>
                                        <td>Ahmad Rizki</td>
                                        <td>001</td>
                                        <td>X-A</td>
                                        <td><span class="badge badge-success">Belum Terdaftar</span></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm select-student">
                                                <i class="fas fa-user-plus"></i> Pilih
                                            </button>
                                        </td>
                                    </tr>
                                    <tr data-student='{"id":"2","nisn":"002","name":"Siti Nurhaliza","class":"X-A","birthDate":"20 Februari 2008","gender":"Perempuan","address":"Jl. Sudirman No. 456, Jakarta","status":"registered","photo":"https://via.placeholder.com/50x50/28a745/ffffff?text=SN"}'>
                                        <td>2</td>
                                        <td>
                                            <img src="https://via.placeholder.com/50x50/28a745/ffffff?text=SN" 
                                                 class="rounded-circle" width="50" height="50" alt="Siti Nurhaliza">
                                        </td>
                                        <td>Siti Nurhaliza</td>
                                        <td>002</td>
                                        <td>X-A</td>
                                        <td><span class="badge badge-warning">Sudah Terdaftar</span></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm select-student">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                    <tr data-student='{"id":"3","nisn":"003","name":"Budi Santoso","class":"XI-A","birthDate":"10 Maret 2007","gender":"Laki-laki","address":"Jl. Gatot Subroto No. 789, Jakarta","status":"unregistered","photo":"https://via.placeholder.com/50x50/dc3545/ffffff?text=BS"}'>
                                        <td>3</td>
                                        <td>
                                            <img src="https://via.placeholder.com/50x50/dc3545/ffffff?text=BS" 
                                                 class="rounded-circle" width="50" height="50" alt="Budi Santoso">
                                        </td>
                                        <td>Budi Santoso</td>
                                        <td>003</td>
                                        <td>XI-A</td>
                                        <td><span class="badge badge-success">Belum Terdaftar</span></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm select-student">
                                                <i class="fas fa-user-plus"></i> Pilih
                                            </button>
                                        </td>
                                    </tr>
                                    <tr data-student='{"id":"4","nisn":"004","name":"Maya Sari","class":"XI-B","birthDate":"25 April 2007","gender":"Perempuan","address":"Jl. Thamrin No. 321, Jakarta","status":"unregistered","photo":"https://via.placeholder.com/50x50/ffc107/000000?text=MS"}'>
                                        <td>4</td>
                                        <td>
                                            <img src="https://via.placeholder.com/50x50/ffc107/000000?text=MS" 
                                                 class="rounded-circle" width="50" height="50" alt="Maya Sari">
                                        </td>
                                        <td>Maya Sari</td>
                                        <td>004</td>
                                        <td>XI-B</td>
                                        <td><span class="badge badge-success">Belum Terdaftar</span></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm select-student">
                                                <i class="fas fa-user-plus"></i> Pilih
                                            </button>
                                        </td>
                                    </tr>
                                    <tr data-student='{"id":"5","nisn":"005","name":"Dodi Setiawan","class":"XII-A","birthDate":"5 Mei 2006","gender":"Laki-laki","address":"Jl. Kebon Jeruk No. 654, Jakarta","status":"unregistered","photo":"https://via.placeholder.com/50x50/6f42c1/ffffff?text=DS"}'>
                                        <td>5</td>
                                        <td>
                                            <img src="https://via.placeholder.com/50x50/6f42c1/ffffff?text=DS" 
                                                 class="rounded-circle" width="50" height="50" alt="Dodi Setiawan">
                                        </td>
                                        <td>Dodi Setiawan</td>
                                        <td>005</td>
                                        <td>XII-A</td>
                                        <td><span class="badge badge-success">Belum Terdaftar</span></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm select-student">
                                                <i class="fas fa-user-plus"></i> Pilih
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">Menampilkan {{ count($students ?? []) > 0 ? count($students) : 5 }} dari {{ count($students ?? []) > 0 ? count($students) : 5 }} siswa</small>
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

<!-- Modal Face Scanner -->
<div class="modal fade" id="faceRegistrationModal" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-camera"></i> Registrasi Wajah
                </h5>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <!-- Camera Section -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <div class="camera-container position-relative d-inline-block">
                                        <video id="video" width="500" height="375" autoplay muted class="border rounded bg-light"></video>
                                        <canvas id="overlay" width="500" height="375"
                                                style="position:absolute; top:0; left:0; pointer-events:none; border-radius:.375rem;"></canvas>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <button class="btn btn-primary" id="modalStartCamera">
                                        <i class="fas fa-video"></i> Mulai Kamera
                                    </button>
                                    <button class="btn btn-secondary" id="modalStopCamera" disabled>
                                        <i class="fas fa-stop"></i> Stop Kamera
                                    </button>
                                    <button class="btn btn-success" id="modalCapturePhoto" disabled>
                                        <i class="fas fa-camera"></i> Ambil Foto
                                    </button>
                                    <button class="btn btn-warning" id="modalResetCapture">
                                        <i class="fas fa-redo"></i> Reset
                                    </button>
                                </div>

                                <!-- Messages -->
                                <div id="modalCameraMessage" class="mt-3" style="display:none;">
                                    <div id="modalSuccessMessage" class="alert alert-success" style="display:none;">
                                        <i class="fas fa-check-circle"></i> <span id="modalSuccessText"></span>
                                    </div>
                                    <div id="modalErrorMessage" class="alert alert-warning" style="display:none;">
                                        <i class="fas fa-times-circle"></i> <span id="modalErrorText"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Control Section -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Status Registrasi</h6>
                            </div>
                            <div class="card-body">
                                <!-- Photo Preview -->
                                <div id="modalPhotoPreview" class="mt-3" style="display: none;">
                                    <label class="font-weight-bold small">Foto yang Diambil:</label>
                                    <div class="text-center border rounded p-2">
                                        <img id="modalCapturedImage" class="img-fluid rounded" style="max-height: 150px;" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="font-weight-bold">Status:</label>
                                    <div id="modalCaptureStatus" class="alert alert-info small">
                                        <i class="fas fa-info-circle"></i> Mulai kamera untuk memulai...
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="font-weight-bold small">Wajah Terdeteksi:</label>
                                        <div class="text-center">
                                            <span class="badge badge-secondary badge-pill" id="modalFaceCount">0</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="font-weight-bold small">Kualitas:</label>
                                        <div class="text-center">
                                            <span id="modalFaceQuality" class="badge badge-secondary">-</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="font-weight-bold small">Siap Capture:</label>
                                        <div class="text-center">
                                            <span id="modalReadyStatus" class="badge badge-danger">Belum Siap</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
                <button type="button" class="btn btn-success" id="modalSaveRegistration" disabled>
                    <i class="fas fa-save"></i> Simpan Registrasi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Success -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle"></i> Berhasil!
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h5>Registrasi Wajah Berhasil!</h5>
                <p id="finalSuccessMessage">Face recognition untuk siswa telah berhasil didaftarkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/face-api.min.js') }}"></script>
<script>
// Perbaikan untuk script modal face registration
$(function() {
    let currentStream = null;
    let modelsLoaded = false;
    let isDetecting = false;
    let detectionInterval = null;
    let selectedStudent = null;
    let capturedImageData = null;
    let faceEncoding = null;

    const video = document.getElementById('video');
    const canvas = document.getElementById('overlay');
    const ctx = canvas.getContext('2d');

    // === SEARCH FUNCTIONALITY ===
    $('#searchStudent').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterStudents(searchTerm);
    });

    function filterStudents(searchTerm = '') {
        $('#studentsList tr').each(function() {
            const $row = $(this);
            const studentData = $row.data('student');
            if (!studentData) return;

            const name = studentData.name.toLowerCase();
            const nisn = studentData.nisn.toString();
            
            const matchesSearch = searchTerm === '' || 
                                name.includes(searchTerm) || 
                                nisn.includes(searchTerm);
            
            if (matchesSearch) {
                $row.show();
            } else {
                $row.hide();
            }
        });
    }

    // === STUDENT SELECTION ===
    $(document).on('click', '.select-student', function() {
        const $row = $(this).closest('tr');
        const studentData = $row.data('student');
        
        if (!studentData) return;
        
        selectedStudent = {
            id: studentData.id,
            name: studentData.name,
            nisn: studentData.nisn,
            class: studentData.class,
            status: studentData.status,
            photo: studentData.photo
        };
        
        showRegistrationModal();
    });

    function showRegistrationModal() {
        if (!selectedStudent) return;
        
        $('#selectedStudentName').text(selectedStudent.name);
        $('#selectedStudentNISN').text(selectedStudent.nisn);
        $('#selectedStudentClass').text(selectedStudent.class);
        $('#selectedStudentPhoto').attr('src', selectedStudent.photo);
        
        $('#faceRegistrationModal').modal('show');
    }

    // === FACE API INITIALIZATION ===
    (async function initializeFaceAPI(){
        try{
            updateStatus('🔄 Loading Face-API models...');
            
            // Ubah path sesuai dengan lokasi model di project Anda
            const modelPath = '{{ asset("models") }}' || '/models';
            
            await faceapi.nets.tinyFaceDetector.loadFromUri(modelPath);
            await faceapi.nets.faceLandmark68Net.loadFromUri(modelPath);
            await faceapi.nets.faceRecognitionNet.loadFromUri(modelPath);
            
            modelsLoaded = true;
            updateStatus('✅ Models loaded successfully!');
        }catch(e){
            console.error('Model loading error:', e);
            updateStatus('❌ Error loading models: ' + e.message);
            showMessage('error', 'Failed to load AI models. Please refresh the page.');
        }
    })();

    // === MODAL EVENT HANDLERS ===
    $('#modalStartCamera').on('click', startCamera);
    $('#modalStopCamera').on('click', stopCamera);
    $('#modalCapturePhoto').on('click', capturePhoto);
    $('#modalSaveRegistration').on('click', saveRegistration);
    $('#modalResetCapture').on('click', resetCapture);

    async function startCamera(){
        if(!modelsLoaded){ 
            showMessage('error', 'AI models are still loading. Please wait.'); 
            return; 
        }

        try{
            updateStatus('📷 Starting camera...');
            if(currentStream) stopCamera();

            const stream = await navigator.mediaDevices.getUserMedia({
                video: { 
                    width: { ideal: 500 }, 
                    height: { ideal: 375 }, 
                    facingMode: 'user' 
                },
                audio: false
            });
            
            video.srcObject = stream;
            currentStream = stream;

            $('#modalStartCamera').prop('disabled', true);
            $('#modalStopCamera').prop('disabled', false);
            $('.camera-container').addClass('active');

            await new Promise(resolve => {
                video.onloadedmetadata = () => {
                    video.play().then(resolve).catch(e => {
                        console.error('Video play error:', e);
                        resolve();
                    });
                };
            });

            faceapi.matchDimensions(canvas, video, true);
            updateStatus('✅ Camera active');
            startFaceDetection();
            
        }catch(e){
            console.error('Camera error:', e);
            showMessage('error', 'Camera access failed: ' + e.message);
            updateStatus('❌ Failed to access camera');
            stopCamera();
        }
    }

    function stopCamera(){
        updateStatus('🛑 Stopping camera...');
        if(currentStream){
            currentStream.getTracks().forEach(t => t.stop());
            currentStream = null;
        }
        if(detectionInterval){
            clearInterval(detectionInterval);
            detectionInterval = null;
        }
        isDetecting = false;
        $('#modalStartCamera').prop('disabled', false);
        $('#modalStopCamera').prop('disabled', true);
        $('#modalCapturePhoto').prop('disabled', true);
        $('.camera-container').removeClass('active detecting');
        ctx.clearRect(0,0,canvas.width, canvas.height);
        updateReadyStatus(false);
        updateStatus('Camera stopped');
    }

    async function startFaceDetection(){
        isDetecting = true;
        $('.camera-container').addClass('detecting');

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

                const dims = faceapi.matchDimensions(canvas, video, true);
                const resized = faceapi.resizeResults(detections, dims);

                ctx.clearRect(0,0,canvas.width, canvas.height);
                $('#modalFaceCount').text(resized.length);

                if(resized.length === 0){
                    updateStatus('No face detected');
                    updateReadyStatus(false);
                    $('#modalCapturePhoto').prop('disabled', true);
                    return;
                }
                if(resized.length > 1){
                    updateStatus('Multiple faces detected');
                    updateReadyStatus(false);
                    $('#modalCapturePhoto').prop('disabled', true);
                    return;
                }

                const det = resized[0];
                const box = det.detection.box;

                if(!isValidBox(box)){
                    updateReadyStatus(false);
                    $('#modalCapturePhoto').prop('disabled', true);
                    return;
                }

                const quality = evaluateFaceQuality(det, dims);
                drawFaceBox(ctx, box, det, quality);
                updateFaceQuality(quality);

                if(quality.score >= 0.8){
                    updateStatus('✅ Perfect! Ready to capture');
                    updateReadyStatus(true);
                    $('#modalCapturePhoto').prop('disabled', false);
                }else{
                    updateStatus('Face detected. Improve position');
                    updateReadyStatus(false);
                    $('#modalCapturePhoto').prop('disabled', true);
                }
            }catch(e){
                console.error('Detection error:', e);
            }
        }, 120);
    }

    function isValidBox(box){
        return box &&
               Number.isFinite(box.x) &&
               Number.isFinite(box.y) &&
               Number.isFinite(box.width) &&
               Number.isFinite(box.height) &&
               box.width > 0 && box.height > 0;
    }

    function evaluateFaceQuality(det, dims){
        let score = det.detection.score;
        const box = det.detection.box;

        // Size penalty
        const area = box.width * box.height;
        if(area < 100 * 100) score *= 0.7;

        // Position penalty
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
        let color = '#00ff00';
        if(quality.score < 0.6) color = '#ff0000';
        else if(quality.score < 0.8) color = '#ffc107';

        ctx.save();
        ctx.strokeStyle = color;
        ctx.lineWidth = 3;
        ctx.strokeRect(Math.round(box.x), Math.round(box.y), Math.round(box.width), Math.round(box.height));

        ctx.fillStyle = color;
        ctx.font = 'bold 12px Arial';
        const conf = Math.round(det.detection.score * 100);
        ctx.fillText(`${quality.quality} - ${conf}%`, Math.round(box.x), Math.round(box.y - 8));

        if(det.landmarks && det.landmarks.positions){
            ctx.fillStyle = color;
            for(const p of det.landmarks.positions){
                if(Number.isFinite(p.x) && Number.isFinite(p.y)){
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, 1, 0, Math.PI*2);
                    ctx.fill();
                }
            }
        }
        ctx.restore();
    }

    async function capturePhoto(){
        if(!selectedStudent){ 
            showMessage('error', 'Please select a student first'); 
            return; 
        }

        try{
            updateStatus('📸 Capturing photo...');
            const snap = document.createElement('canvas');
            snap.width = video.videoWidth || 320;
            snap.height = video.videoHeight || 240;
            snap.getContext('2d').drawImage(video, 0, 0, snap.width, snap.height);

            const det = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ 
                    inputSize: 224, 
                    scoreThreshold: 0.5 
                }))
                .withFaceLandmarks()
                .withFaceDescriptor();

            if(!det){
                showMessage('error', 'No face detected during capture');
                return;
            }

            faceEncoding = Array.from(det.descriptor);
            capturedImageData = snap.toDataURL('image/jpeg', 0.85);

            $('#modalCapturedImage').attr('src', capturedImageData);
            $('#modalPhotoPreview').show();
            $('#modalSaveRegistration').prop('disabled', false);

            updateStatus('✅ Photo captured successfully');
            showMessage('success', 'Photo captured successfully!');
            
        }catch(e){
            console.error('Capture error:', e);
            showMessage('error', 'Capture failed: ' + e.message);
        }
    }

    async function saveRegistration(){
        if(!selectedStudent || !capturedImageData || !faceEncoding){
            showMessage('error', 'Missing required data for registration'); 
            return;
        }

        try{
            updateStatus('💾 Saving registration...');
            
            // Show loading state
            const $saveBtn = $('#modalSaveRegistration');
            const originalText = $saveBtn.html();
            $saveBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...').prop('disabled', true);

            const formData = new FormData();
            formData.append('student_id', selectedStudent.id);
            formData.append('face_encoding', JSON.stringify(faceEncoding));

            // Convert base64 to blob
            const blob = await (await fetch(capturedImageData)).blob();
            formData.append('face_photo', blob, `${selectedStudent.nisn}_face.jpg`);

            // PERBAIKAN: Gunakan route Laravel yang benar
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                throw new Error('CSRF token not found. Please refresh the page.');
            }

            // Ubah endpoint sesuai dengan route Anda
            const endpoint = "{{ route('face-recognition.store') }}";
            
            const response = await fetch(endpoint, {
                method: 'POST',
                body: formData,
                headers: { 
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            // Handle response dengan lebih baik
            let result;
            try {
                result = await response.json();
            } catch (parseError) {
                console.error('JSON parse error:', parseError);
                throw new Error('Invalid server response');
            }

            if (response.ok && result.success) {
                updateStatus('✅ Registration saved successfully!');
                showMessage('success', `Face registration completed for ${selectedStudent.name}`);
                
                // Update table row
                updateStudentRow(selectedStudent.id);
                
                // Show success modal
                $('#finalSuccessMessage').text(`Face recognition untuk ${selectedStudent.name} telah berhasil didaftarkan.`);
                $('#faceRegistrationModal').modal('hide');
                $('#successModal').modal('show');
                
                resetModal();
            } else {
                // Handle server errors
                const errorMessage = result.message || result.error || 'Registration failed';
                throw new Error(errorMessage);
            }
            
        }catch(e){
            console.error('Save registration error:', e);
            
            // Show specific error messages
            let errorMsg = 'Failed to save registration';
            if (e.message.includes('CSRF')) {
                errorMsg = 'Security token expired. Please refresh the page.';
            } else if (e.message.includes('network')) {
                errorMsg = 'Network error. Please check your connection.';
            } else if (e.message) {
                errorMsg = e.message;
            }
            
            showMessage('error', errorMsg);
            updateStatus('❌ Save failed');
            
        }finally{
            // Restore button state
            $('#modalSaveRegistration').html(originalText).prop('disabled', false);
        }
    }

    function resetCapture(){
        $('#modalPhotoPreview').hide();
        $('#modalSaveRegistration').prop('disabled', true);
        capturedImageData = null;
        faceEncoding = null;
        updateStatus('Ready for new capture');
    }

    function resetModal(){
        stopCamera();
        resetCapture();
        selectedStudent = null;
        updateStatus('Select student and start camera');
    }

    function updateStudentRow(studentId) {
        $(`tr[data-student]`).each(function() {
            const studentData = $(this).data('student');
            if (studentData && studentData.id == studentId) {
                // Update status badge
                $(this).find('.badge').removeClass('badge-success').addClass('badge-warning').text('Sudah Terdaftar');
                // Update button
                $(this).find('.select-student').removeClass('btn-primary').addClass('btn-warning')
                    .html('<i class="fas fa-edit"></i> Edit');
                // Update data
                studentData.status = 'registered';
                $(this).data('student', studentData);
            }
        });
    }

    function updateStatus(message){
        $('#modalCaptureStatus').html(`<i class="fas fa-info-circle"></i> ${message}`);
    }

    function updateReadyStatus(ready){
        $('#modalReadyStatus')
            .removeClass('badge-success badge-danger')
            .addClass(ready ? 'badge-success' : 'badge-danger')
            .text(ready ? 'Siap' : 'Belum Siap');
    }

    function updateFaceQuality(quality){
        $('#modalFaceQuality')
            .removeClass('badge-success badge-warning badge-danger')
            .addClass(quality.score >= 0.8 ? 'badge-success'
                   : quality.score >= 0.6 ? 'badge-warning'
                   : 'badge-danger')
            .text(quality.quality);
    }

    function showMessage(type, message) {
        const isSuccess = type === 'success';
        $('#modalSuccessMessage, #modalErrorMessage').hide();
        $('#modalCameraMessage').show();
        
        if (isSuccess) {
            $('#modalSuccessText').text(message);
            $('#modalSuccessMessage').show();
        } else {
            $('#modalErrorText').text(message);
            $('#modalErrorMessage').show();
        }
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            $('#modalCameraMessage').fadeOut();
        }, 5000);
    }

    // Modal event handlers
    $('#faceRegistrationModal').on('hidden.bs.modal', function() {
        resetModal();
    });

    // Cleanup on page unload
    $(window).on('beforeunload', function(){
        if(currentStream){ 
            currentStream.getTracks().forEach(t => t.stop()); 
        }
        if(detectionInterval){ 
            clearInterval(detectionInterval); 
        }
    });

    // Initialize
    updateStatus('Models loading...');
});
</script>
@endpush