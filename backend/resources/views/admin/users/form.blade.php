@extends('layouts.admin')
@section('page_title', isset($user) ? 'Edit Staff' : 'Add Staff')

@section('content')
<x-admin.page-header :title="isset($user) ? 'Edit Staff Member' : 'Add Staff Member'">
    <x-slot:actions>
        <a href="{{ admin_route('admin.users.index') }}" class="btn btn-outline">Back</a>
    </x-slot:actions>
</x-admin.page-header>

<div class="card" style="max-width: 560px;">
    <div class="card-body">
        <form method="POST" action="{{ isset($user) ? admin_route('admin.users.update', $user) : admin_route('admin.users.store') }}" style="display: grid; gap: 16px;">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <div>
                <label class="field-label">Full name</label>
                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required class="input">
            </div>

            <div>
                <label class="field-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required class="input">
            </div>

            <div>
                <label class="field-label">Role</label>
                <select name="role" class="select">
                    @foreach($roles as $role)
                        <option value="{{ $role->value }}" {{ old('role', $user->role ?? 'writer') === $role->value ? 'selected' : '' }}>
                            {{ $role->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <label style="display:flex;align-items:center;gap:8px;font-size:14px;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
                Active account
            </label>

            <div>
                <label class="field-label">{{ isset($user) ? 'New password (leave blank to keep)' : 'Password' }}</label>
                <input type="password" name="password" class="input" {{ isset($user) ? '' : 'required' }}>
            </div>

            <div>
                <label class="field-label">Confirm password</label>
                <input type="password" name="password_confirmation" class="input">
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection
