<form class="was-validated" action="{{ isset($schedule) ? route('setting-schedule.update', $schedule->id) : route('setting-schedule.store') }}" method="POST" id="schedule-form">
    @csrf
    @isset($schedule)
        @method('PUT')
    @endisset
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="day" class="form-label">{{ __('index.day') }} <span class="text-danger">*</span></label>
                <select class="form-select" id="day" name="day" required>
                    <option value="">{{ __('index.select_day') }}</option>
                    <option value="Senin" {{ isset($schedule) && $schedule->day == 'Senin' ? 'selected' : '' }}>{{ __('index.senin') }}</option>
                    <option value="Selasa" {{ isset($schedule) && $schedule->day == 'Selasa' ? 'selected' : '' }}>{{ __('index.selasa') }}</option>
                    <option value="Rabu" {{ isset($schedule) && $schedule->day == 'Rabu' ? 'selected' : '' }}>{{ __('index.rabu') }}</option>
                    <option value="Kamis" {{ isset($schedule) && $schedule->day == 'Kamis' ? 'selected' : '' }}>{{ __('index.kamis') }}</option>
                    <option value="Jumat" {{ isset($schedule) && $schedule->day == 'Jumat' ? 'selected' : '' }}>{{ __('index.jumat') }}</option>
                    <option value="Sabtu" {{ isset($schedule) && $schedule->day == 'Sabtu' ? 'selected' : '' }}>{{ __('index.sabtu') }}</option>
                </select>
                <div class="invalid-feedback">
                    {{ __('index.please_select_day') }}
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="mb-3">
                <label for="start_time" class="form-label">{{ __('index.start_time') }} <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="start_time" name="start_time"
                    value="{{ isset($schedule) ? substr($schedule->start_time, 0, 5) : old('start_time') }}" 
                    placeholder="07:00" required>
                <div class="invalid-feedback">
                    {{ __('index.please_enter_start_time') }}
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="mb-3">
                <label for="end_time" class="form-label">{{ __('index.end_time') }} <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="end_time" name="end_time"
                    value="{{ isset($schedule) ? substr($schedule->end_time, 0, 5) : old('end_time') }}" 
                    placeholder="15:00" required>
                <div class="invalid-feedback">
                    {{ __('index.please_enter_end_time') }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <div>
                    <button class="btn btn-primary" type="submit">
                        <i class="mdi mdi-content-save"></i> {{ isset($schedule) ? __('index.update_schedule') : __('index.save_schedule') }}
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close"></i> {{ __('index.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('schedule-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        // Show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Menyimpan...';

        const url = form.action;
        const method = form.querySelector('input[name="_method"]')?.value || 'POST';

        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('schedule-modal'));
                if (modal) {
                    modal.hide();
                }
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message || 'Data berhasil disimpan',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Refresh table
                if (typeof performAjaxSearch === 'function') {
                    performAjaxSearch();
                } else {
                    // Fallback: reload page
                    window.location.reload();
                }
            } else {
                // Handle validation errors
                if (data.errors) {
                    let errorMessage = '';
                    for (let field in data.errors) {
                        errorMessage += data.errors[field][0] + '\n';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Terjadi kesalahan'
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat menyimpan data'
            });
        })
        .finally(() => {
            // Reset button state
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        });
    });

    // Time validation
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');

    function validateTimes() {
        if (startTimeInput.value && endTimeInput.value) {
            const start = new Date('2000-01-01T' + startTimeInput.value);
            const end = new Date('2000-01-01T' + endTimeInput.value);
            
            if (end <= start) {
                endTimeInput.setCustomValidity('Jam pulang harus setelah jam masuk');
            } else {
                endTimeInput.setCustomValidity('');
            }
        }
    }

    startTimeInput.addEventListener('change', validateTimes);
    endTimeInput.addEventListener('change', validateTimes);
});
</script>
