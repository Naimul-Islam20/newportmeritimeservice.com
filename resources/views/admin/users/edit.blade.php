@extends('layouts.admin', ['title' => 'Edit User'])

@section('content')
<div class="header">
    <h1>Edit User</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-2">
            <div>
                <label for="name">Name</label>
                <input id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="Password">
                <small style="display:block; margin-top:6px; color:#64748b;">Leave blank to keep current password.</small>
                <small style="display:block; margin-top:4px; color:#64748b;">
                    New password rules: minimum 12 characters, must include uppercase, lowercase, number, and symbol.
                </small>
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password">
            </div>
            <div>
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>admin</option>
                    <option value="super_admin" @selected(old('role', $user->role) === 'super_admin')>super_admin</option>
                </select>
                @error('role') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>
        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit" onclick="return confirm('Update this user?')">Update User</button>
        </div>
    </form>
</div>
@endsection