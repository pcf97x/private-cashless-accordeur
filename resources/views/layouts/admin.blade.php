{{-- Admin layout now uses the main app layout with sidebar --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    @yield('admin-content')
</div>
@endsection
