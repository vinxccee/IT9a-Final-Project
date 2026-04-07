@extends('layouts.app')
@section('title', 'Welcome')
@section('content')
<div style="text-align:center;padding:5rem 2rem;max-width:700px;margin:0 auto;">
    <div style="font-size:4rem;margin-bottom:1.5rem;">🏨</div>
    <h1 style="font-family:'Playfair Display',serif;font-size:3rem;color:#C9A84C;margin-bottom:1rem;">
        Grand Azure Hotel
    </h1>
    <p style="color:#8B949E;font-size:1.1rem;line-height:1.7;margin-bottom:2.5rem;">
        A complete hotel management system for managing rooms, bookings, guests, and staff — all in one place.
    </p>
    @auth
        <a href="{{ route('dashboard') }}" class="btn btn-gold" style="font-size:1rem;padding:12px 32px;">
            <i class="fas fa-chart-pie"></i> Go to Dashboard
        </a>
    @else
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('login') }}" class="btn btn-gold" style="font-size:1rem;padding:12px 32px;">
                <i class="fas fa-right-to-bracket"></i> Login
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline" style="font-size:1rem;padding:12px 32px;">
                <i class="fas fa-user-plus"></i> Register
            </a>
        </div>
    @endauth
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;margin-top:4rem;">
        @foreach([['🛏️','Room Management','Manage all rooms and availability'],['📋','Reservations','Full booking CRUD system'],['👥','Guest Records','Track all hotel guests'],['🪪','Staff Directory','Manage hotel personnel']] as $f)
        <div style="background:#161B22;border:1px solid #30363D;border-radius:10px;padding:1.25rem;">
            <div style="font-size:1.8rem;margin-bottom:.5rem;">{{ $f[0] }}</div>
            <div style="font-weight:600;font-size:.875rem;margin-bottom:.25rem;">{{ $f[1] }}</div>
            <div style="font-size:.75rem;color:#8B949E;">{{ $f[2] }}</div>
        </div>
        @endforeach
    </div>
</div>
@endsection