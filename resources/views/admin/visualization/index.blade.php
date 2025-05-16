@extends('layouts.admin')

@section('title', 'Visualisasi Data Peserta')

@section('breadcrumb')
<li class="breadcrumb-item active">Visualisasi</li>
@endsection

@section('content')
<div id="visualization-app"></div>
@endsection

@push('scripts')
<!-- React will be mounted here -->
<script src="{{ asset('js/visualization-app.js') }}" defer></script>
@endpush