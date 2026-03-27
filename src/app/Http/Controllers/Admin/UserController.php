<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->orderBy('name')
            ->orderBy('prenom')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('libelle')->get();

        $structures = [
            'ADSUP',
            'DFIP',
            'CSPI',
            'SECAL',
            'Entreprises',
        ];

        return view('admin.users.create', compact('roles', 'structures'));
    }

    public function store(Request $request)
    {
        $structures = [
            'ADSUP',
            'DFIP',
            'CSPI',
            'SECAL',
            'Entreprises',
        ];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'prenom' => ['nullable', 'string', 'max:255'],
            'fonction' => ['nullable', 'string', 'max:255'],
            'structure' => ['nullable', 'string', 'in:' . implode(',', $structures)],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'prenom' => $validated['prenom'] ?? null,
            'fonction' => $validated['fonction'] ?? null,
            'structure' => $validated['structure'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => 0,
        ]);

        $user->roles()->attach($validated['role_id']);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('libelle')->get();

        $structures = [
            'ADSUP',
            'DFIP',
            'CSPI',
            'SECAL',
            'Entreprises',
        ];

        return view('admin.users.edit', compact('user', 'roles', 'structures'));
    }

    public function update(Request $request, User $user)
    {
        $structures = [
            'ADSUP',
            'DFIP',
            'CSPI',
            'SECAL',
            'Entreprises',
        ];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'prenom' => ['nullable', 'string', 'max:255'],
            'fonction' => ['nullable', 'string', 'max:255'],
            'structure' => ['nullable', 'string', 'in:' . implode(',', $structures)],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $user->update([
            'name' => $validated['name'],
            'prenom' => $validated['prenom'] ?? null,
            'fonction' => $validated['fonction'] ?? null,
            'structure' => $validated['structure'] ?? null,
            'email' => $validated['email'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Informations utilisateur mises à jour avec succès.');
    }

    public function deactivate(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors([
                'user' => 'Vous ne pouvez pas désactiver votre propre compte.',
            ]);
        }

        $user->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur désactivé avec succès.');
    }

    public function reactivate(User $user)
    {
        $user->update([
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur réactivé avec succès.');
    }
}