<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\RekapitulasiHasilUjk;
use App\Models\JadwalUji;
use App\Models\Penugasan;
use App\Models\Pendaftaran;
use App\Models\UserPersonalization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class RekapitulasiHasilUjkController extends Controller
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
        
        // Get existing rekapitulasi for these jadwals
        $rekapitulasi = RekapitulasiHasilUjk::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'tuk'])
            ->whereIn('jadwal_uji_id', $jadwalIds)
            ->latest()
            ->get();

        // Get jadwals that don't have rekapitulasi yet
        $jadwalsWithoutRekapitulasi = JadwalUji::with(['skemaSertifikasi', 'tuk'])
            ->whereIn('id', $jadwalIds)
            ->whereNotIn('id', $rekapitulasi->pluck('jadwal_uji_id'))
            ->get();

        return view('asesor.rekapitulasi-hasil-ujk.index', compact('rekapitulasi', 'jadwalsWithoutRekapitulasi'));
    }

    /**
     * Create new rekapitulasi for a jadwal
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
            return redirect()->route('asesor.rekapitulasi-hasil-ujk.index')
                ->with('error', 'Anda tidak memiliki akses ke jadwal ini');
        }

        $jadwal = JadwalUji::with(['skemaSertifikasi', 'tuk'])->findOrFail($jadwalId);

        // Get students (asesi) assigned to this asesor for this jadwal
        $assignedPendaftaranIds = DB::table('penugasan_pendaftaran')
            ->join('penugasan', 'penugasan_pendaftaran.penugasan_id', '=', 'penugasan.id')
            ->where('penugasan.jadwal_uji_id', $jadwalId)
            ->where('penugasan.asesor_id', $asesor->id)
            ->whereIn('penugasan.status', ['assigned', 'accepted', 'completed'])
            ->pluck('penugasan_pendaftaran.pendaftaran_id')
            ->unique()
            ->filter()
            ->toArray();

        $asesiList = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->whereIn('id', $assignedPendaftaranIds)
            ->get()
            ->map(function ($p) {
                // Get signature from persetujuan_data or asesmen_data
                $signature = null;
                if ($p->persetujuan_data) {
                    $persetujuanData = is_string($p->persetujuan_data) 
                        ? json_decode($p->persetujuan_data, true) 
                        : $p->persetujuan_data;
                    $signature = $persetujuanData['asesi_signature'] ?? null;
                }
                if (!$signature && $p->asesmen_data) {
                    $asesmenData = is_string($p->asesmen_data) 
                        ? json_decode($p->asesmen_data, true) 
                        : $p->asesmen_data;
                    $signature = $asesmenData['signature_data'] ?? null;
                }

                return [
                    'id' => $p->id,
                    'nama' => $p->user->nama_lengkap ?? $p->user->name,
                    'npm' => $p->user->npm ?? $p->user->nim ?? '-',
                    'signature' => $signature,
                ];
            });

        return view('asesor.rekapitulasi-hasil-ujk.create', compact('jadwal', 'asesiList'));
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
            return redirect()->route('asesor.rekapitulasi-hasil-ujk.index')
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
            'hasil_asesi' => 'nullable|array',
        ]);

        // Get students and their signatures
        $assignedPendaftaranIds = DB::table('penugasan_pendaftaran')
            ->join('penugasan', 'penugasan_pendaftaran.penugasan_id', '=', 'penugasan.id')
            ->where('penugasan.jadwal_uji_id', $jadwalId)
            ->where('penugasan.asesor_id', $asesor->id)
            ->whereIn('penugasan.status', ['assigned', 'accepted', 'completed'])
            ->pluck('penugasan_pendaftaran.pendaftaran_id')
            ->unique()
            ->filter()
            ->toArray();

        $hasilAsesi = [];
        foreach ($assignedPendaftaranIds as $pendaftaranId) {
            $k = $request->input("hasil_asesi.{$pendaftaranId}.k", false);
            $bk = $request->input("hasil_asesi.{$pendaftaranId}.bk", false);
            
            // Get signature from pendaftaran
            $pendaftaran = Pendaftaran::find($pendaftaranId);
            $signature = null;
            if ($pendaftaran) {
                if ($pendaftaran->persetujuan_data) {
                    $persetujuanData = is_string($pendaftaran->persetujuan_data) 
                        ? json_decode($pendaftaran->persetujuan_data, true) 
                        : $pendaftaran->persetujuan_data;
                    $signature = $persetujuanData['asesi_signature'] ?? null;
                }
                if (!$signature && $pendaftaran->asesmen_data) {
                    $asesmenData = is_string($pendaftaran->asesmen_data) 
                        ? json_decode($pendaftaran->asesmen_data, true) 
                        : $pendaftaran->asesmen_data;
                    $signature = $asesmenData['signature_data'] ?? null;
                }
            }

            $hasilAsesi[$pendaftaranId] = [
                'k' => (bool) $k,
                'bk' => (bool) $bk,
                'signature' => $signature,
            ];
        }

        $data = $request->all();
        $data['jadwal_uji_id'] = $jadwalId;
        $data['tuk_id'] = $jadwal->tuk_id;
        $data['created_by'] = Auth::id();
        $data['hasil_asesi'] = $hasilAsesi;
        
        // Set default skema from jadwal if not provided
        if (empty($data['skema'])) {
            $data['skema'] = $jadwal->skemaSertifikasi->nama_skema ?? '';
        }

        RekapitulasiHasilUjk::create($data);

        return redirect()->route('asesor.rekapitulasi-hasil-ujk.index')
            ->with('success', 'Rekapitulasi hasil UJK berhasil dibuat');
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

        $rekapitulasi = RekapitulasiHasilUjk::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'tuk'])
            ->findOrFail($id);

        // Check if jadwal exists
        if (!$rekapitulasi->jadwal_uji_id) {
            return redirect()->route('asesor.rekapitulasi-hasil-ujk.index')
                ->with('error', 'Rekapitulasi tidak memiliki jadwal yang valid');
        }

        // Check if asesor is assigned to this jadwal
        $penugasan = Penugasan::where('jadwal_uji_id', $rekapitulasi->jadwal_uji_id)
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->first();

        if (!$penugasan) {
            return redirect()->route('asesor.rekapitulasi-hasil-ujk.index')
                ->with('error', 'Anda tidak memiliki akses ke rekapitulasi ini');
        }

        $jadwal = $rekapitulasi->jadwalUji;
        
        if (!$jadwal) {
            return redirect()->route('asesor.rekapitulasi-hasil-ujk.index')
                ->with('error', 'Jadwal tidak ditemukan');
        }

        // Get students (asesi) assigned to this asesor for this jadwal
        $assignedPendaftaranIds = DB::table('penugasan_pendaftaran')
            ->join('penugasan', 'penugasan_pendaftaran.penugasan_id', '=', 'penugasan.id')
            ->where('penugasan.jadwal_uji_id', $rekapitulasi->jadwal_uji_id)
            ->where('penugasan.asesor_id', $asesor->id)
            ->whereIn('penugasan.status', ['assigned', 'accepted', 'completed'])
            ->pluck('penugasan_pendaftaran.pendaftaran_id')
            ->unique()
            ->filter()
            ->toArray();

        $asesiList = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->whereIn('id', $assignedPendaftaranIds)
            ->get()
            ->map(function ($p) use ($rekapitulasi) {
                // Get existing results from rekapitulasi
                $hasilAsesi = $rekapitulasi->hasil_asesi ?? [];
                $existingResult = $hasilAsesi[$p->id] ?? null;

                // Get signature from persetujuan_data or asesmen_data, or use existing
                $signature = $existingResult['signature'] ?? null;
                if (!$signature && $p->persetujuan_data) {
                    $persetujuanData = is_string($p->persetujuan_data) 
                        ? json_decode($p->persetujuan_data, true) 
                        : $p->persetujuan_data;
                    $signature = $persetujuanData['asesi_signature'] ?? null;
                }
                if (!$signature && $p->asesmen_data) {
                    $asesmenData = is_string($p->asesmen_data) 
                        ? json_decode($p->asesmen_data, true) 
                        : $p->asesmen_data;
                    $signature = $asesmenData['signature_data'] ?? null;
                }

                return [
                    'id' => $p->id,
                    'nama' => $p->user->nama_lengkap ?? $p->user->name,
                    'npm' => $p->user->npm ?? $p->user->nim ?? '-',
                    'k' => $existingResult['k'] ?? false,
                    'bk' => $existingResult['bk'] ?? false,
                    'signature' => $signature,
                ];
            });

        return view('asesor.rekapitulasi-hasil-ujk.edit', compact('rekapitulasi', 'asesiList'));
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

        $rekapitulasi = RekapitulasiHasilUjk::findOrFail($id);

        // Check if jadwal exists
        if (!$rekapitulasi->jadwal_uji_id) {
            return redirect()->route('asesor.rekapitulasi-hasil-ujk.index')
                ->with('error', 'Rekapitulasi tidak memiliki jadwal yang valid');
        }

        // Check if asesor is assigned to this jadwal
        $penugasan = Penugasan::where('jadwal_uji_id', $rekapitulasi->jadwal_uji_id)
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->first();

        if (!$penugasan) {
            return redirect()->route('asesor.rekapitulasi-hasil-ujk.index')
                ->with('error', 'Anda tidak memiliki akses ke rekapitulasi ini');
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
            'hasil_asesi' => 'nullable|array',
        ]);

        // Get students and their signatures
        $assignedPendaftaranIds = DB::table('penugasan_pendaftaran')
            ->join('penugasan', 'penugasan_pendaftaran.penugasan_id', '=', 'penugasan.id')
            ->where('penugasan.jadwal_uji_id', $rekapitulasi->jadwal_uji_id)
            ->where('penugasan.asesor_id', $asesor->id)
            ->whereIn('penugasan.status', ['assigned', 'accepted', 'completed'])
            ->pluck('penugasan_pendaftaran.pendaftaran_id')
            ->unique()
            ->filter()
            ->toArray();

        $hasilAsesi = [];
        foreach ($assignedPendaftaranIds as $pendaftaranId) {
            $k = $request->input("hasil_asesi.{$pendaftaranId}.k", false);
            $bk = $request->input("hasil_asesi.{$pendaftaranId}.bk", false);
            
            // Get signature from existing rekapitulasi or pendaftaran
            $existingResult = $rekapitulasi->hasil_asesi[$pendaftaranId] ?? null;
            $signature = $existingResult['signature'] ?? null;
            
            if (!$signature) {
                $pendaftaran = Pendaftaran::find($pendaftaranId);
                if ($pendaftaran) {
                    if ($pendaftaran->persetujuan_data) {
                        $persetujuanData = is_string($pendaftaran->persetujuan_data) 
                            ? json_decode($pendaftaran->persetujuan_data, true) 
                            : $pendaftaran->persetujuan_data;
                        $signature = $persetujuanData['asesi_signature'] ?? null;
                    }
                    if (!$signature && $pendaftaran->asesmen_data) {
                        $asesmenData = is_string($pendaftaran->asesmen_data) 
                            ? json_decode($pendaftaran->asesmen_data, true) 
                            : $pendaftaran->asesmen_data;
                        $signature = $asesmenData['signature_data'] ?? null;
                    }
                }
            }

            $hasilAsesi[$pendaftaranId] = [
                'k' => (bool) $k,
                'bk' => (bool) $bk,
                'signature' => $signature,
            ];
        }

        $data = $request->all();
        $data['hasil_asesi'] = $hasilAsesi;
        
        $rekapitulasi->update($data);

        return redirect()->route('asesor.rekapitulasi-hasil-ujk.index')
            ->with('success', 'Rekapitulasi hasil UJK berhasil diperbarui');
    }

    /**
     * Generate PDF for rekapitulasi hasil ujk
     */
    public function generatePDF($id)
    {
        $rekapitulasi = RekapitulasiHasilUjk::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'tuk'])
            ->findOrFail($id);

        // Get all asesi from hasil_asesi stored in rekapitulasi (not limited to current asesor)
        $hasilAsesi = $rekapitulasi->hasil_asesi ?? [];
        
        // Get all pendaftaran IDs from hasil_asesi
        $pendaftaranIds = array_keys($hasilAsesi);
        
        $asesiList = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->whereIn('id', $pendaftaranIds)
            ->get()
            ->map(function ($p) use ($hasilAsesi) {
                $result = $hasilAsesi[$p->id] ?? ['k' => false, 'bk' => false, 'signature' => null];
                
                // If signature is not in hasil_asesi, try to get it from pendaftaran
                $signature = $result['signature'] ?? null;
                if (!$signature) {
                    if ($p->persetujuan_data) {
                        $persetujuanData = is_string($p->persetujuan_data) 
                            ? json_decode($p->persetujuan_data, true) 
                            : $p->persetujuan_data;
                        $signature = $persetujuanData['asesi_signature'] ?? null;
                    }
                    if (!$signature && $p->asesmen_data) {
                        $asesmenData = is_string($p->asesmen_data) 
                            ? json_decode($p->asesmen_data, true) 
                            : $p->asesmen_data;
                        $signature = $asesmenData['signature_data'] ?? null;
                    }
                }
                
                return [
                    'nama' => $p->user->nama_lengkap ?? $p->user->name,
                    'npm' => $p->user->npm ?? $p->user->nim ?? '-',
                    'k' => $result['k'] ?? false,
                    'bk' => $result['bk'] ?? false,
                    'signature' => $signature,
                ];
            });

        // Get all asesor assigned to the same jadwal
        $penugasan = Penugasan::with(['asesor.user'])
            ->where('jadwal_uji_id', $rekapitulasi->jadwal_uji_id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->get();

        $asesorList = $penugasan->map(function ($p) {
            // Get signature from personalization
            $signature = null;
            if ($p->asesor && $p->asesor->user_id) {
                $personalization = UserPersonalization::where('user_id', $p->asesor->user_id)->first();
                if ($personalization && !empty($personalization->signature_data)) {
                    $signature = $personalization->signature_data;
                }
            }
            
            return [
                'id' => $p->asesor->id,
                'nama' => $p->asesor->nama_lengkap,
                'user_id' => $p->asesor->user_id,
                'signature' => $signature,
            ];
        })->unique('id')->values();

        // Calculate pages automatically based on number of asesi
        // Assuming ~10 asesi per page
        $totalAsesi = $asesiList->count();
        $halamanTotal = max(1, ceil($totalAsesi / 10));
        $halaman = 1; // Current page is always 1

        // Count K and BK
        $countK = $asesiList->where('k', true)->count();
        $countBK = $asesiList->where('bk', true)->count();

        // Format tanggal untuk PDF
        $hariTanggalWithDay = $rekapitulasi->hari_tanggal 
            ? \Carbon\Carbon::parse($rekapitulasi->hari_tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY')
            : '';
        $hariTanggalDateOnly = $rekapitulasi->hari_tanggal 
            ? \Carbon\Carbon::parse($rekapitulasi->hari_tanggal)->locale('id')->isoFormat('D MMMM YYYY')
            : '';
        $tanggalBerlaku = $rekapitulasi->tanggal_berlaku 
            ? \Carbon\Carbon::parse($rekapitulasi->tanggal_berlaku)->locale('id')->isoFormat('D MMMM YYYY')
            : '';

        $data = [
            'rekapitulasi' => $rekapitulasi,
            'asesiList' => $asesiList,
            'asesorList' => $asesorList,
            'hariTanggalWithDay' => $hariTanggalWithDay,
            'hariTanggalDateOnly' => $hariTanggalDateOnly,
            'tanggalBerlaku' => $tanggalBerlaku,
            'halaman' => $halaman,
            'halamanTotal' => $halamanTotal,
            'countK' => $countK,
            'countBK' => $countBK,
        ];

        $pdf = PDF::loadView('asesor.rekapitulasi-hasil-ujk.pdf', $data);
        return $pdf->download('rekapitulasi-hasil-ujk-' . $rekapitulasi->id . '.pdf');
    }
}
