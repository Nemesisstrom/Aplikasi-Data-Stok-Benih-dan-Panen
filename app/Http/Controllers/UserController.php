<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Satker;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Method __construct() dihapus karena otorisasi middleware sudah diatur via routes/web.php

    public function index()
    {
        $users = User::with(['roles', 'satker'])->latest()->paginate(10);
        $roles = Role::all();
        $satkers = Satker::all();

        return view('users.index', compact('users', 'roles', 'satkers'));
    }

    public function create()
    {
        $roles = Role::all();
        $satkers = Satker::all();
        return view('users.create', compact('roles', 'satkers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
            'satker_id' => 'nullable|exists:satkers,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'satker_id' => $request->satker_id,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $satkers = Satker::all();
        $userRole = $user->roles->pluck('name')->first();
        return view('users.edit', compact('user', 'roles', 'satkers', 'userRole'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|exists:roles,name',
            'satker_id' => 'nullable|exists:satkers,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'satker_id' => $request->satker_id,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
