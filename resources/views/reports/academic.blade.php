@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Laporan Nilai Akademik</h4>
                <div>
                    <a href="{{ route('reports.academic') }}" class="btn btn-outline-secondary btn-sm">Refresh</a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Jenis Laporan</label>
                        <select id="reportType" class="form-control">
                            <option value="mapel" selected>Nilai per Mata Pelajaran</option>
                            <option value="raport">Raport (Semua Mapel per Siswa)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kelas</label>
                        <select id="classFilter" class="form-control">
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <select id="subjectFilter" class="form-control" disabled>
                            <option value="">Pilih Mapel</option>
                            <option value="all">Semua Mapel (1 siswa 1 halaman)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Semester</label>
                        <select id="semesterFilter" class="form-control">
                            <option value="">Pilih Semester</option>
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tahun Akademik</label>
                        <select id="yearFilter" class="form-control">
                            <option value="">Pilih Tahun Akademik</option>
                            @foreach(App\Helpers\AcademicYearHelper::generateAcademicYears(2, 2) as $year)
                                <option value="{{ $year }}" {{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <button id="loadBtn" class="btn btn-primary mr-2">Tampilkan</button>
                        <button id="exportPdfBtn" class="btn btn-outline-danger btn-sm mr-2" disabled>Export PDF</button>
                        <button id="exportExcelBtn" class="btn btn-outline-success btn-sm" disabled>Export Excel</button>
                    </div>
                </div>

                <div id="statusBox" class="alert alert-info d-none"><strong>Status:</strong> <span id="statusText"></span></div>

                <!-- Table -->
                <div class="table-responsive" id="singleSubjectWrap">
                    <table class="table table-striped" id="reportTable">
                        <thead>
                            <tr>
                                <th>No Absen</th>
                                <th>Nama</th>
                                <th>NISN</th>
                                <th>UTS</th>
                                <th>UAS</th>
                                <th>Tugas</th>
                                <th>Sikap</th>
                                <th>Nilai Akhir</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div id="allSubjectsPreview" class="table-responsive mt-3 d-none"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const els = {
            reportType: document.getElementById('reportType'),
            class: document.getElementById('classFilter'),
            subject: document.getElementById('subjectFilter'),
            year: document.getElementById('yearFilter'),
            semester: document.getElementById('semesterFilter'),
            btn: document.getElementById('loadBtn'),
            statusBox: document.getElementById('statusBox'),
            statusText: document.getElementById('statusText'),
            tbody: document.querySelector('#reportTable tbody'),
            exportPdfBtn: document.getElementById('exportPdfBtn'),
            exportExcelBtn: document.getElementById('exportExcelBtn'),
        };

        function setStatus(msg) {
            els.statusText.textContent = msg;
            els.statusBox.classList.remove('d-none');
        }
        function clearStatus() {
            els.statusBox.classList.add('d-none');
            els.statusText.textContent = '';
        }
        function clearTable() {
            els.tbody.innerHTML = '';
        }
        function addRow(row) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.no_absen ?? '-'}</td>
                <td>${row.student_name ?? '-'}</td>
                <td>${row.student_nisn ?? '-'}</td>
                <td>${format(row.uts)}</td>
                <td>${format(row.uas)}</td>
                <td>${format(row.tugas)}</td>
                <td>${format(row.sikap)}</td>
                <td><strong>${format(row.final)}</strong></td>
                <td>${row.status}</td>
            `;
            els.tbody.appendChild(tr);
        }
        function format(v) {
            if (v === null || v === undefined || v === '') return '-';
            const n = Number(v);
            return isNaN(n) ? '-' : n.toFixed(2);
        }

        function buildParams() {
            const type = els.reportType.value || 'mapel';
            let subjectId = els.subject.value;

            // Untuk tipe raport, kita pakai subject_id = 'all' secara otomatis
            if (type === 'raport') {
                subjectId = 'all';
            }

            return {
                report_type: type,
                class_id: els.class.value,
                subject_id: subjectId,
                academic_year: els.year.value,
                semester: els.semester.value,
            };
        }

        async function loadSubjects() {
            const type = els.reportType.value || 'mapel';
            const classId = els.class.value;
            const year = els.year.value;
            els.subject.innerHTML = '';
            const base = document.createElement('option');
            base.value = '';
            base.textContent = 'Pilih Mapel';
            els.subject.appendChild(base);
            const allOpt = document.createElement('option');
            allOpt.value = 'all';
            allOpt.textContent = 'Semua Mapel (1 siswa 1 halaman)';
            els.subject.appendChild(allOpt);

            // Jika tipe raport, subject selalu 'all' dan select dikunci
            if (type === 'raport') {
                els.subject.value = 'all';
                els.subject.disabled = true;
                updateExportButtonsState();
                return;
            }

            if (!classId) {
                els.subject.disabled = true;
                updateExportButtonsState();
                return;
            }
            try {
                const url = new URL(`{{ route('student-grades.get-subjects-by-class') }}`);
                url.searchParams.set('class_id', classId);
                if (year) url.searchParams.set('academic_year', year);
                const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) throw new Error('Gagal memuat mata pelajaran');
                const json = await res.json();
                if (!json.success) throw new Error(json.message || 'Gagal memuat mata pelajaran');
                (json.data || []).forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = s.name;
                    els.subject.appendChild(opt);
                });
                els.subject.disabled = false;
            } catch (e) {
                els.subject.disabled = true;
            } finally {
                updateExportButtonsState();
            }
        }

        function updateExportButtonsState() {
            const p = buildParams();
            const type = p.report_type || 'mapel';

            if (type === 'raport') {
                // Raport: hanya butuh kelas + semester (subject_id otomatis 'all'), hanya PDF yang aktif
                els.exportPdfBtn.disabled = !(p.class_id && p.semester);
                els.exportExcelBtn.disabled = true;
            } else {
                // Nilai per mapel: butuh kelas + mapel + semester
                els.exportExcelBtn.disabled = !(p.class_id && p.subject_id && p.subject_id !== 'all' && p.semester);
                els.exportPdfBtn.disabled = !(p.class_id && p.subject_id && p.semester);
            }
        }

        els.reportType.addEventListener('change', function() {
            // Reset tampilan saat ganti jenis laporan
            clearStatus();
            clearTable();
            document.getElementById('singleSubjectWrap').classList.remove('d-none');
            document.getElementById('allSubjectsPreview').classList.add('d-none');
            loadSubjects();
            updateExportButtonsState();
        });

        els.subject.addEventListener('change', updateExportButtonsState);
        els.class.addEventListener('change', function(){ loadSubjects(); updateExportButtonsState(); });
        els.semester.addEventListener('change', updateExportButtonsState);
        els.year.addEventListener('change', function(){ loadSubjects(); updateExportButtonsState(); });

        // initial subjects state
        loadSubjects();

        function renderAllSubjectsPreview(pages) {
            const container = document.getElementById('allSubjectsPreview');
            // Build a single consolidated table with rowspans for student info
            let html = `
                <table class="table table-striped" id="allSubjectsTable">
                  <thead>
                    <tr>
                      <th>No Absen</th>
                      <th>Nama</th>
                      <th>NISN</th>
                      <th>Mata Pelajaran</th>
                      <th>UTS</th>
                      <th>UAS</th>
                      <th>Tugas</th>
                      <th>Nilai Akhir</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>`;

            pages.forEach(page => {
                const subjects = page.subjects || [];
                const rowspan = Math.max(1, subjects.length);
                if (rowspan === 0) {
                    html += `
                      <tr>
                        <td>${page.student.no_absen ?? '-'}</td>
                        <td>${page.student.name ?? '-'}</td>
                        <td>${page.student.nisn ?? '-'}</td>
                        <td colspan="6" class="text-center">Tidak ada mapel</td>
                      </tr>`;
                    return;
                }

                subjects.forEach((sub, idx) => {
                    html += '<tr>';
                    if (idx === 0) {
                        html += `<td rowspan="${rowspan}">${page.student.no_absen ?? '-'}</td>`;
                        html += `<td rowspan="${rowspan}">${page.student.name ?? '-'}</td>`;
                        html += `<td rowspan="${rowspan}">${page.student.nisn ?? '-'}</td>`;
                    }
                    html += `<td>${sub.subject_name}</td>`;
                    html += `<td class="text-center">${format(sub.uts)}</td>`;
                    html += `<td class="text-center">${format(sub.uas)}</td>`;
                    html += `<td class="text-center">${format(sub.tugas)}</td>`;
                    html += `<td class="text-center"><strong>${format(sub.final)}</strong></td>`;
                    html += `<td class="text-center">${sub.status}</td>`;
                    html += '</tr>';
                });
            });

            html += '</tbody></table>';
            container.innerHTML = html;
        }

        els.btn.addEventListener('click', function() {
            clearStatus();
            clearTable();

            const params = buildParams();

            if (!params.class_id || !params.semester) {
                setStatus('Lengkapi filter Kelas dan Semester');
                return;
            }

            if (params.report_type === 'raport') {
                // Mode raport: selalu subject_id = 'all'
                clearStatus();
                els.exportPdfBtn.disabled = !(params.class_id && params.semester);
                els.exportExcelBtn.disabled = true;
                document.getElementById('singleSubjectWrap').classList.add('d-none');
                const preview = document.getElementById('allSubjectsPreview');
                preview.classList.remove('d-none');
                setStatus('Memuat ringkasan semua mapel...');
                fetch(`{{ route('reports.academic.all-subjects-data') }}?` + new URLSearchParams({
                    class_id: params.class_id,
                    academic_year: params.academic_year,
                    semester: params.semester,
                }), { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                .then(r => r.json())
                .then(json => {
                    if (!json.success) {
                        setStatus(json.message || 'Gagal memuat ringkasan');
                        return;
                    }
                    renderAllSubjectsPreview(json.data || []);
                    clearStatus();
                })
                .catch(err => setStatus('Terjadi kesalahan memuat data: ' + (err?.message || err)));
                return;
            }

            // Mode nilai per mapel
            if (!params.subject_id) {
                setStatus('Lengkapi filter Kelas, Mapel, dan Semester');
                return;
            }

            document.getElementById('singleSubjectWrap').classList.remove('d-none');
            document.getElementById('allSubjectsPreview').classList.add('d-none');
            setStatus('Memuat data laporan...');
            fetch(`{{ route('reports.academic.semester-data') }}?` + new URLSearchParams({
                class_id: params.class_id,
                subject_id: params.subject_id,
                academic_year: params.academic_year,
                semester: params.semester,
            }), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(json => {
                if (!json.success) {
                    setStatus(json.message || 'Gagal memuat data laporan');
                    return;
                }
                clearTable();
                json.data.forEach(addRow);
                clearStatus();
                els.exportPdfBtn.disabled = false;
                els.exportExcelBtn.disabled = false;
            })
            .catch(err => {
                setStatus('Terjadi kesalahan memuat data: ' + (err?.message || err));
            });
        });

        // Export handlers
        els.exportPdfBtn.addEventListener('click', function() {
            const params = buildParams();
            if (!params.class_id || !params.subject_id || !params.semester) return;
            const url = new URL(`{{ route('reports.academic.export-pdf') }}`);
            Object.keys(params).forEach(k => { if (params[k]) url.searchParams.set(k, params[k]); });
            window.open(url.toString(), '_blank');
        });

        els.exportExcelBtn.addEventListener('click', function() {
            const params = buildParams();
            if (!params.class_id || !params.subject_id || !params.semester) return;
            const url = new URL(`{{ route('reports.academic.export-excel') }}`);
            Object.keys(params).forEach(k => { if (params[k]) url.searchParams.set(k, params[k]); });
            window.location.href = url.toString();
        });
    });
</script>
@endpush
