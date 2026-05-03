@extends('layouts.admin', ['title' => 'Create User'])

@section('content')
<div class="header">
    <h1>Create User</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="grid grid-2">
            <div>
                <label for="name">Name</label>
                <input id="name" name="name" value="{{ old('name') }}" required>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
                <small style="display:block; margin-top:6px; color:#64748b;">
                    Password rules: minimum 12 characters, must include uppercase, lowercase, number, and symbol.
                </small>
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required>
            </div>
            <div>
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="admin" @selected(old('role')==='admin' )>admin</option>
                    <option value="super_admin" @selected(old('role')==='super_admin' )>super_admin</option>
                </select>
                @error('role') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>
        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit" onclick="return confirm('Create this user?')">Save User</button>
        </div>
    </form>
</div>
@endsection