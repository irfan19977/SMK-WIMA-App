@extends('layouts.master')

@section('title')
    Jadwal Pelajaran
@endsection

@section('page-title')
    Jadwal Pelajaran
@endsection

@section('body')
    <body data-sidebar="colored">
@endsection

@section('content')
<div class="row mt-4">
    <!-- Schedule -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Jadwal Pelajaran</h4>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                        <i class="mdi mdi-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>
                
                @if(isset($orderedSchedules) && count($orderedSchedules) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderedSchedules as $day => $schedules)
                                    @foreach($schedules as $index => $schedule)
                                        <tr>
                                            @if($index == 0)
                                                <td rowspan="{{ count($schedules) }}" class="text-center align-middle">
                                                    <strong>{{ ucfirst($day) }}</strong>
                                                </td>
                                            @endif
                                            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                            <td>{{ $schedule->subject->name }}</td>
                                            <td>{{ $schedule->teacher->name }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="mdi mdi-calendar-alert display-4 text-muted"></i>
                        <p class="text-muted mt-3">Belum ada jadwal pelajaran</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
