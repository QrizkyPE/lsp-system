<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Penugasan;
use App\Models\Dokumen;
use App\Models\Pendaftaran;

class AsesorController extends Controller
{
    public function dashboard()
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $totalPenugasan = Penugasan::where('asesor_id', $asesor->id)->count();
        $acceptedPenugasan = Penugasan::where('asesor_id', $asesor->id)->where('status', 'accepted')->count();
        $pendingDokumen = Dokumen::where('status', 'submitted')->count();
        $completedAsesmen = Pendaftaran::where('status', 'completed')->count();
        $recentPenugasan = Penugasan::with(['jadwalUji.skemaSertifikasi'])
            ->where('asesor_id', $asesor->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('asesor.dashboard', compact(
            'totalPenugasan',
            'acceptedPenugasan',
            'pendingDokumen',
            'completedAsesmen',
            'recentPenugasan'
        ));
    }

    public function penugasan()
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $penugasan = Penugasan::with(['jadwalUji.skemaSertifikasi'])
            ->where('asesor_id', $asesor->id)
            ->latest()
            ->paginate(10);

        return view('asesor.penugasan', compact('penugasan'));
    }

    public function dokumen()
    {
        $dokumen = Dokumen::with(['pendaftaran.user', 'pendaftaran.skemaSertifikasi'])
            ->where('status', 'submitted')
            ->latest()
            ->paginate(10);

        return view('asesor.dokumen', compact('dokumen'));
    }

    public function approveDokumen($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $dokumen->update([
            'status' => 'approved',
            'tanggal_approve' => now()
        ]);

        return redirect()->route('asesor.dokumen')
            ->with('success', 'Dokumen berhasil disetujui');
    }

    public function rejectDokumen(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string'
        ]);

        $dokumen = Dokumen::findOrFail($id);
        $dokumen->update([
            'status' => 'rejected',
            'catatan' => $request->catatan
        ]);

        return redirect()->route('asesor.dokumen')
            ->with('success', 'Dokumen berhasil ditolak');
    }

    public function asesmen()
    {
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi', 'dokumen'])
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('asesor.asesmen', compact('pendaftaran'));
    }

    public function submitAsesmen(Request $request, $id)
    {
        $request->validate([
            'hasil_asesmen' => 'required|in:kompeten,belum_kompeten',
            'catatan_asesmen' => 'required|string'
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => 'completed',
            'hasil_asesmen' => $request->hasil_asesmen,
            'catatan_asesmen' => $request->catatan_asesmen,
            'tanggal_selesai' => now()
        ]);

        return redirect()->route('asesor.asesmen')
            ->with('success', 'Hasil asesmen berhasil disimpan');
    }
}
