<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        return view('admin.users.index', [
            'users' => User::query()->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $payload = $request->safe()->only(['name', 'email', 'password', 'role']);
        $user = User::create($payload);

        AuditLogger::log('admin.user.created', $user, ['role' => $user->role], $request);

        return redirect()->route('admin.users.index')->with('status', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $payload = $request->safe()->only(['name', 'email', 'role']);
        $password = $request->safe()->only('password')['password'] ?? null;

        if ($payload['role'] !== $user->role) {
            $this->authorize('changeRole', $user);
        }

        if (filled($password)) {
            $payload['password'] = $password;
        }

        $user->fill($payload);

        if (! $user->isDirty()) {
            return redirect()->route('admin.users.edit', $user)->with('warning', 'No changes found. User was not updated.');
        }

        $user->save();

        AuditLogger::log('admin.user.updated', $user, ['role' => $user->role], $request);

        return redirect()->route('admin.users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();
        AuditLogger::log('admin.user.deleted', $user);

        return redirect()->route('admin.users.index')->with('status', 'User deleted successfully.');
    }
}
