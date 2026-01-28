<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\DaftarHadirAsesor;
use App\Models\JadwalUji;
use App\Models\Penugasan;
use App\Models\UserPersonalization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class DaftarHadirAsesorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Get all jadwals where asesor is assigned
        $penugasan = Penugasan::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk'])
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->latest()
            ->get();

        // Get unique jadwals
        $jadwalIds = $penugasan->pluck('jadwal_uji_id')->unique()->filter();
        
        // Get existing daftar hadir for these jadwals
        $daftarHadir = DaftarHadirAsesor::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'tuk'])
            ->whereIn('jadwal_uji_id', $jadwalIds)
            ->latest()
            ->get();

        // Get jadwals that don't have daftar hadir yet
        $jadwalsWithoutDaftarHadir = JadwalUji::with(['skemaSertifikasi', 'tuk'])
            ->whereIn('id', $jadwalIds)
            ->whereNotIn('id', $daftarHadir->pluck('jadwal_uji_id'))
            ->get();

        return view('asesor.daftar-hadir-asesor.index', compact('daftarHadir', 'jadwalsWithoutDaftarHadir'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Ambil data daftar hadir terlebih dahulu
        $daftarHadir = DaftarHadirAsesor::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'tuk'])
            ->findOrFail($id);

        // Pastikan asesor saat ini memang ditugaskan pada jadwal ini
        $memilikiAkses = Penugasan::where('jadwal_uji_id', $daftarHadir->jadwal_uji_id)
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->exists();

        if (!$memilikiAkses) {
            return redirect()->route('asesor.daftar-hadir-asesor.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit daftar hadir ini');
        }

        // Get all asesor assigned to the same jadwal
        $penugasan = Penugasan::with(['asesor.user'])
            ->where('jadwal_uji_id', $daftarHadir->jadwal_uji_id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->get();

        $asesorList = $penugasan->map(function ($p) {
            return [
                'id' => $p->asesor->id,
                'nama' => $p->asesor->nama_lengkap,
                'user_id' => $p->asesor->user_id,
            ];
        })->unique('id')->values();

        // Get signatures from personalization
        $signatures = [];
        foreach ($asesorList as $asesorItem) {
            $personalization = UserPersonalization::where('user_id', $asesorItem['user_id'])->first();
            if ($personalization && $personalization->signature_data) {
                $signatures[$asesorItem['id']] = $personalization->signature_data;
            }
        }

        // Get existing signatures from daftar hadir
        $existingSignatures = $daftarHadir->asesor_signatures ?? [];

        return view('asesor.daftar-hadir-asesor.edit', compact('daftarHadir', 'asesorList', 'signatures', 'existingSignatures'));
    }

    /**
     * Create new daftar hadir for a jadwal
     */
    public function create($jadwalId)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Check if asesor is assigned to this jadwal
        $penugasan = Penugasan::where('jadwal_uji_id', $jadwalId)
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->first();

        if (!$penugasan) {
            return redirect()->route('asesor.daftar-hadir-asesor.index')
                ->with('error', 'Anda tidak memiliki akses ke jadwal ini');
        }

        $jadwal = JadwalUji::with(['skemaSertifikasi', 'tuk'])->findOrFail($jadwalId);

        // Get all asesor assigned to the same jadwal
        $penugasanList = Penugasan::with(['asesor.user'])
            ->where('jadwal_uji_id', $jadwalId)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->get();

        $asesorList = $penugasanList->map(function ($p) {
            return [
                'id' => $p->asesor->id,
                'nama' => $p->asesor->nama_lengkap,
                'user_id' => $p->asesor->user_id,
            ];
        })->unique('id')->values();

        // Get signatures from personalization
        $signatures = [];
        foreach ($asesorList as $asesorItem) {
            $personalization = UserPersonalization::where('user_id', $asesorItem['user_id'])->first();
            if ($personalization && $personalization->signature_data) {
                $signatures[$asesorItem['id']] = $personalization->signature_data;
            }
        }

        return view('asesor.daftar-hadir-asesor.create', compact('jadwal', 'asesorList', 'signatures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $jadwalId)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Check if asesor is assigned to this jadwal
        $penugasan = Penugasan::where('jadwal_uji_id', $jadwalId)
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->first();

        if (!$penugasan) {
            return redirect()->route('asesor.daftar-hadir-asesor.index')
                ->with('error', 'Anda tidak memiliki akses ke jadwal ini');
        }

        $jadwal = JadwalUji::with(['skemaSertifikasi', 'tuk'])->findOrFail($jadwalId);

        $request->validate([
            'no_dokumen' => 'nullable|string|max:255',
            'edisi_revisi' => 'nullable|string|max:255',
            'tanggal_berlaku' => 'nullable|date',
            'skema' => 'nullable|string|max:255',
            'pukul_mulai' => 'nullable|date_format:H:i',
            'pukul_selesai' => 'nullable|date_format:H:i',
            'hari_tanggal' => 'nullable|date',
            'penanggung_jawab_tuk' => 'nullable|string|max:255',
            'asesor_signatures' => 'nullable|array',
        ]);

        // Get all asesor assigned to the same jadwal for calculating pages
        $penugasanList = Penugasan::with(['asesor.user'])
            ->where('jadwal_uji_id', $jadwalId)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->get();
        
        $totalAsesor = $penugasanList->unique('asesor_id')->count();

        $data = $request->all();
        $data['jadwal_uji_id'] = $jadwalId;
        $data['tuk_id'] = $jadwal->tuk_id;
        $data['created_by'] = Auth::id();
        $data['halaman'] = 1;
        $data['halaman_total'] = 1; // Default, akan dihitung di PDF
        
        // Set default skema from jadwal if not provided
        if (empty($data['skema'])) {
            $data['skema'] = $jadwal->skemaSertifikasi->nama_skema ?? '';
        }

        DaftarHadirAsesor::create($data);

        return redirect()->route('asesor.daftar-hadir-asesor.index')
            ->with('success', 'Daftar hadir asesor berhasil dibuat');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Ambil data daftar hadir terlebih dahulu
        $daftarHadir = DaftarHadirAsesor::findOrFail($id);

        // Pastikan asesor saat ini memang ditugaskan pada jadwal ini
        $memilikiAkses = Penugasan::where('jadwal_uji_id', $daftarHadir->jadwal_uji_id)
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->exists();

        if (!$memilikiAkses) {
            return redirect()->route('asesor.daftar-hadir-asesor.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengubah daftar hadir ini');
        }

        $request->validate([
            'no_dokumen' => 'nullable|string|max:255',
            'edisi_revisi' => 'nullable|string|max:255',
            'tanggal_berlaku' => 'nullable|date',
            'skema' => 'nullable|string|max:255',
            'pukul_mulai' => 'nullable|date_format:H:i',
            'pukul_selesai' => 'nullable|date_format:H:i',
            'hari_tanggal' => 'nullable|date',
            'penanggung_jawab_tuk' => 'nullable|string|max:255',
            'asesor_signatures' => 'nullable|array',
        ]);

        $data = $request->all();
        $data['halaman'] = 1;
        $data['halaman_total'] = 1; // Default, akan dihitung di PDF
        
        $daftarHadir->update($data);

        return redirect()->route('asesor.daftar-hadir-asesor.index')
            ->with('success', 'Daftar hadir asesor berhasil diperbarui');
    }

    /**
     * Generate PDF for daftar hadir asesor
     */
    public function generatePDF($id)
    {
        $daftarHadir = DaftarHadirAsesor::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'tuk'])
            ->findOrFail($id);

        // Get all asesor assigned to the same jadwal
        $penugasan = Penugasan::with(['asesor.user'])
            ->where('jadwal_uji_id', $daftarHadir->jadwal_uji_id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->get();

        $asesorList = $penugasan->map(function ($p) use ($daftarHadir) {
            $signature = null;
            $asesorSignatures = $daftarHadir->asesor_signatures ?? [];
            if (isset($asesorSignatures[$p->asesor->id])) {
                $signature = $asesorSignatures[$p->asesor->id];
            }
            
            return [
                'nama' => $p->asesor->nama_lengkap,
                'signature' => $signature,
            ];
        })->unique('nama')->values();

        // Calculate pages automatically based on number of asesor
        // Assuming ~10 asesor per page
        $totalAsesor = $asesorList->count();
        $halamanTotal = max(1, ceil($totalAsesor / 10));
        $halaman = 1; // Current page is always 1

        // Format tanggal untuk PDF
        $hariTanggalWithDay = $daftarHadir->hari_tanggal 
            ? \Carbon\Carbon::parse($daftarHadir->hari_tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY')
            : '';
        $hariTanggalDateOnly = $daftarHadir->hari_tanggal 
            ? \Carbon\Carbon::parse($daftarHadir->hari_tanggal)->locale('id')->isoFormat('D MMMM YYYY')
            : '';
        $tanggalBerlaku = $daftarHadir->tanggal_berlaku 
            ? \Carbon\Carbon::parse($daftarHadir->tanggal_berlaku)->locale('id')->isoFormat('D MMMM YYYY')
            : '';

        $data = [
            'daftarHadir' => $daftarHadir,
            'asesorList' => $asesorList,
            'hariTanggalWithDay' => $hariTanggalWithDay,
            'hariTanggalDateOnly' => $hariTanggalDateOnly,
            'tanggalBerlaku' => $tanggalBerlaku,
            'halaman' => $halaman,
            'halamanTotal' => $halamanTotal,
        ];

        $pdf = PDF::loadView('asesor.daftar-hadir-asesor.pdf', $data);
        return $pdf->download('daftar-hadir-asesor-' . $daftarHadir->id . '.pdf');
    }
}
