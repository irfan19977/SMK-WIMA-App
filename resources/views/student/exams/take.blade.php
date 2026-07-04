@extends('layouts.master')

@section('title')
    Mengerjakan Ujian - {{ $exam->title }}
@endsection

@section('page-title')
    Mengerjakan Ujian
@endsection

@section('body')
    <body data-sidebar="colored">
@endsection

@section('content')
    <livewire:student-exam-take :exam-id="$exam->id" />
@endsection
