<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifikasi = auth()->user()->notifikasi()->paginate(20);
        // Mark all as read
        auth()->user()->notifikasiTidakDibaca()->update(['dibaca_at' => now()]);
        return view('notifikasi.index', compact('notifikasi'));
    }

    public function markRead($id)
    {
        $notif = Notifikasi::where('user_id', auth()->id())->findOrFail($id);
        $notif->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        auth()->user()->notifikasiTidakDibaca()->update(['dibaca_at' => now()]);
        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function destroy($id)
    {
        Notifikasi::where('user_id', auth()->id())->findOrFail($id)->delete();
        return back()->with('success', 'Notifikasi dihapus.');
    }
}
