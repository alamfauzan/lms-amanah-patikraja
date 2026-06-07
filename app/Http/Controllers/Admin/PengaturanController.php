<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $school_name = Setting::getVal('school_name', 'Madrasah Al-Ilm');
        $lms_name = Setting::getVal('lms_name', 'Al-Ilm Learning System');

        return view('admin.pengaturan.index', compact('school_name', 'lms_name'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'lms_name' => ['required', 'string', 'max:255'],
        ]);

        Setting::setVal('school_name', $request->school_name);
        Setting::setVal('lms_name', $request->lms_name);

        return redirect()->route('admin.pengaturan.index')
            ->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
