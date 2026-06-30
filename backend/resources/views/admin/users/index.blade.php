@extends('layouts.admin')
@section('page_title', 'Staff')

@section('content')
<x-admin.page-header title="Staff" subtitle="Editors and writers">
    <x-slot:actions>
        <a href="{{ admin_route('admin.users.create') }}" class="btn btn-primary">+ Add Staff</a>
    </x-slot:actions>
</x-admin.page-header>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge badge-primary">{{ $user->roleLabel() }}</span></td>
                    <td><span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ admin_route('admin.users.edit', $user) }}" class="link">Edit</a>
                        @if($user->id !== auth()->id())
                            <form method="POST" action="{{ admin_route('admin.users.destroy', $user) }}" style="display:inline" onsubmit="return confirm('Remove this staff member?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-sm" style="color: var(--danger);">Remove</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if($users->hasPages())
        <div class="card-body">{{ $users->links() }}</div>
    @endif
</div>
@endsection
