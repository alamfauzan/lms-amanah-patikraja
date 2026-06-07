<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = User::role('guru')->latest()->paginate(10);
        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $guru = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $guru->assignRole('guru');

        return redirect()->route('admin.guru.index')
            ->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $guru = User::role('guru')->findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $guru = User::role('guru')->findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $guru->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $guru->update($userData);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data Guru berhasil diubah.');
    }

    public function destroy($id)
    {
        $guru = User::role('guru')->findOrFail($id);
        $guru->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Guru berhasil dihapus.');
    }
}
