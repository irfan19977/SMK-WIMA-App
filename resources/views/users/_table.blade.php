@forelse ($users as $item)
<tr>
    <th scope="row">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</th>
    <td>
        <a href="{{ route('profile.show') }}?user_id={{ $item->id }}" class="text-decoration-none fw-bold text-primary">
            {{ $item->name }}
        </a>
    </td>
    <td>{{ $item->email }}</td>
    <td>{{ $item->admin_phone ?? '-' }}</td>
    <td>
        <span class="badge bg-{{ $item->role_name == 'Super Admin' ? 'danger' : ($item->role_name == 'Admin' ? 'warning' : 'info') }}">
            {{ $item->role_name ?? '-' }}
        </span>
    </td>
    <td>{{ \Carbon\Carbon::parse($item->join_date)->format('d M Y') }}</td>
    <td>
        <span class="badge bg-{{ $item->status ? 'info' : 'light' }}">
            {{ $item->status ? __('index.active') : __('index.blocked') }}
        </span>
    </td>
    <td>
        <div class="d-flex gap-2">
            <a href="{{ route('users.edit', $item->id) }}" class="btn btn-sm btn-soft-primary">
                <i class="mdi mdi-pencil"></i>
            </a>
            <button type="button" class="btn btn-sm btn-{{ $item->status ? 'soft-info' : 'soft-secondary' }} btn-toggle-active" 
                    data-id="{{ $item->id }}" 
                    data-name="{{ $item->name }}">
                <i class="mdi mdi-{{ $item->status ? 'toggle-switch' : 'toggle-switch-off' }}"></i>
            </button>
            @if(!$item->role_name || $item->role_name != 'Super Admin')
                <button type="button" class="btn btn-sm btn-soft-danger btn-delete" 
                        data-id="{{ $item->id }}" 
                        data-name="{{ $item->name }}">
                    <i class="mdi mdi-delete"></i>
                </button>
            @endif
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center">{{ __('index.no_users_found') }}</td>
</tr>
@endforelse
