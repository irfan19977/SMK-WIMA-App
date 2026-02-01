@extends('layouts.app')

@section('title', 'Screen Sharing Sessions')

@section('content')
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title">
            <h4><i class="fas fa-desktop mr-2"></i>Screen Sharing Sessions</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
        <div class="header-elements d-none">
            <a href="{{ route('screen-shares.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-2"></i>Create New Session
            </a>
        </div>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">My Screen Sharing Sessions</h5>
                    <a href="{{ route('screen-shares.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-2"></i>New Session
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($screenShares->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Room Code</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Participants</th>
                                        <th>Started At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($screenShares as $screenShare)
                                        <tr>
                                            <td>
                                                <span class="badge badge-info font-weight-bold">{{ $screenShare->room_code }}</span>
                                            </td>
                                            <td>{{ $screenShare->title ?: 'Untitled Session' }}</td>
                                            <td>
                                                @if($screenShare->status == 'active')
                                                    <span class="badge badge-success">Active</span>
                                                @elseif($screenShare->status == 'ended')
                                                    <span class="badge badge-danger">Ended</span>
                                                @else
                                                    <span class="badge badge-warning">Paused</span>
                                                @endif
                                            </td>
                                            <td>{{ $screenShare->participants->count() }} participants</td>
                                            <td>{{ $screenShare->started_at ? $screenShare->started_at->format('M d, Y H:i') : '-' }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('screen-shares.show', $screenShare) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($screenShare->status == 'active')
                                                        <a href="{{ route('screen-shares.show', $screenShare) }}" class="btn btn-sm btn-success">
                                                            <i class="fas fa-desktop"></i> Share
                                                        </a>
                                                        <form action="{{ route('screen-shares.end', $screenShare) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to end this session?')">
                                                                <i class="fas fa-stop"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $screenShares->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-desktop fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No screen sharing sessions yet</h5>
                            <p class="text-muted">Create your first screen sharing session to get started.</p>
                            <a href="{{ route('screen-shares.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>Create Session
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
