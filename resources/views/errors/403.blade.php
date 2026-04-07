@extends('layouts.app')
@section('title', 'Access Denied')
@section('content')
<div style="text-align:center;padding:5rem 2rem;">
    <div style="font-size:5rem;margin-bottom:1rem;">🔒</div>
    <h1 style="font-family:'Playfair Display',serif;font-size:2.5rem;color:#F85149;margin-bottom:1rem;">Access Denied</h1>
    <p style="color:#8B949E;font-size:1rem;margin-bottom:2rem;">{{ $exception->getMessage() ?: 'You do not have permission to view this page.' }}</p>
    <div style="display:flex;gap:10px;justify-content:center;">
        <a href="{{ route('dashboard') }}" class="btn btn-gold"><i class="fas fa-chart-pie"></i> Go to Dashboard</a>
        <a href="{{ route('home') }}" class="btn btn-outline"><i class="fas fa-house"></i> Home</a>
    </div>
</div>
@endsection