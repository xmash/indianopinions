<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_staff');
    }

    public function index()
    {
        $users = User::orderBy('name')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form', [
            'user' => null,
            'roles' => UserRole::cases(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateUser($request);

        User::create([
            ...$data,
            'password' => Hash::make($data['password']),
        ]);

        return admin_redirect('admin.users.index')->with('success', 'Staff member created.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', [
            'user' => $user,
            'roles' => UserRole::cases(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validateUser($request, $user);

        $user->fill(collect($data)->except('password')->all());

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return admin_redirect('admin.users.index')->with('success', 'Staff member updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return admin_redirect('admin.users.index')->with('success', 'Staff member removed.');
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email'.($user ? ",{$user->id}" : ''),
            'role' => ['required', Rule::in(UserRole::values())],
            'is_active' => 'boolean',
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::defaults()],
        ]);
    }
}
