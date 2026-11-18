@extends('layouts.app')

@section('title', 'Masuk - UEMKAEM')

@section('content')
    <section style="max-width: 420px; margin: 40px auto;">
        <form method="POST" action="{{ route('login.store') }}" class="card" style="padding: 28px; display: grid; gap: 16px;">
            @csrf
            <h1 style="font-size: 1.6rem;">Masuk</h1>
            <p class="muted">Khusus administrator UEMKAEM.</p>

            <label class="field">
                <small>Email</small>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email') <small style="color: var(--terracotta);">{{ $message }}</small> @enderror
            </label>

            <label class="field">
                <small>Kata Sandi</small>
                <input type="password" name="password" required>
            </label>

            <button type="submit" class="btn btn-primary">Masuk</button>
        </form>
    </section>
@endsection
