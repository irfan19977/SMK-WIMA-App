<!-- Subject Form for Modal -->
<form class="was-validated" action="{{ $action }}" method="POST" id="subject-form">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif
    
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('index.subject_name') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $subject ? $subject->name : '') }}" 
                    placeholder="{{ __('index.enter_subject_name') }}" required>
                <div class="invalid-feedback">
                    {{ __('index.please_enter_subject_name') }}
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="mb-3">
                <label for="code" class="form-label">{{ __('index.subject_code') }}</label>
                <input type="text" class="form-control" id="code" name="code"
                    value="{{ old('code', $subject ? $subject->code : '') }}" 
                    placeholder="{{ __('index.optional_unique_code') }}">
                <div class="form-text">{{ __('index.leave_empty_for_auto_generate') }}</div>
                <div class="invalid-feedback">
                    {{ __('index.invalid_code') }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> {{ $method === 'PUT' ? __('index.update') : __('index.save') }} {{ __('index.subject') }}
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close"></i> {{ __('index.cancel') }}
                    </button>
                </div>
                @if($method === 'PUT' && $subject)
                    <div>
                        <button type="button" class="btn btn-danger" onclick="deleteFromModal('{{ $subject->id }}', '{{ $subject->name }}')">
                            <i class="mdi mdi-delete"></i> {{ __('index.delete') }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>

<script>
    // Auto-generate code when name is typed (only for create)
    @if(!$subject)
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const codeInput = document.getElementById('code');
        
        nameInput.addEventListener('input', function() {
            // Only auto-generate if code is empty
            if (!codeInput.value.trim()) {
                // Generate simple code from name
                let code = this.value.toUpperCase()
                    .replace(/[^A-Z0-9\s]/g, '')
                    .replace(/\s+/g, '_')
                    .substring(0, 10);
                
                codeInput.value = code;
            }
        });
    });
    @endif

    // Form submission via AJAX
    document.getElementById('subject-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> {{ __("index.saving") }}...';
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('subject-modal'));
                modal.hide();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: '{{ __("index.success") }}!',
                    text: data.message || '{{ __("index.data_saved_successfully") }}',
                    timer: 1500,
                    showConfirmButton: false
                });
                
                // Reload table
                reloadTable();
            } else {
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("index.error") }}!',
                    text: data.message || '{{ __("index.error_occurred") }}'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: '{{ __("index.error") }}!',
                text: '{{ __("index.error_saving_data") }}'
            });
        })
        .finally(() => {
            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // Delete from modal
    function deleteFromModal(id, name) {
        Swal.fire({
            title: '{{ __("index.are_you_sure") }}',
            text: "{{ __("index.subject_will_be_deleted_part1") }}" + name + "{{ __("index.subject_will_be_deleted_part2") }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("index.yes_delete") }}',
            cancelButtonText: '{{ __("index.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                // Close modal first
                const modal = bootstrap.Modal.getInstance(document.getElementById('subject-modal'));
                modal.hide();
                
                // Create form for delete
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/subjects/${id}`;
                form.style.display = 'none';
                
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken.getAttribute('content');
                    form.appendChild(csrfInput);
                }
                
                // Add method override for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
