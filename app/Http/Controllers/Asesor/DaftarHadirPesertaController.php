<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\DaftarHadirPeserta;
use App\Models\JadwalUji;
use App\Models\Penugasan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class DaftarHadirPesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Get filter status from request
        $statusFilter = $request->get('status', 'all');

        // Get all jadwals where asesor is assigned
        $penugasan = Penugasan::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk'])
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->latest()
            ->get();

        // Get unique jadwals
        $jadwalIds = $penugasan->pluck('jadwal_uji_id')->unique()->filter();
        
        // Get existing daftar hadir peserta for these jadwals
        $daftarHadir = DaftarHadirPeserta::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'tuk'])
            ->whereIn('jadwal_uji_id', $jadwalIds)
            ->where('created_by', Auth::id())
            ->latest()
            ->get();

        // Get jadwals that don't have daftar hadir peserta yet
        $jadwalsWithoutDaftarHadir = JadwalUji::with(['skemaSertifikasi', 'tuk'])
            ->whereIn('id', $jadwalIds)
            ->whereNotIn('id', $daftarHadir->pluck('jadwal_uji_id'))
            ->get();

        // Apply status filter
        if ($statusFilter === 'sudah_dibuat') {
            $jadwalsWithoutDaftarHadir = collect([]);
        } elseif ($statusFilter === 'belum_dibuat') {
            $daftarHadir = collect([]);
        }
        // If 'all', show both (no filtering needed)

        return view('asesor.daftar-hadir-peserta.index', compact('daftarHadir', 'jadwalsWithoutDaftarHadir', 'statusFilter'));
    }

    /**
     * Show the form for creating a new resource.
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
            return redirect()->route('asesor.daftar-hadir-peserta.index')
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
                    'keterangan' => '',
                ];
            });

        return view('asesor.daftar-hadir-peserta.create', compact('jadwal', 'asesiList'));
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
            return redirect()->route('asesor.daftar-hadir-peserta.index')
                ->with('error', 'Anda tidak memiliki akses ke jadwal ini');
        }

        $jadwal = JadwalUji::with(['skemaSertifikasi', 'tuk'])->findOrFail($jadwalId);

        $request->validate([
            'no_dokumen' => 'nullable|string|max:255',
            'edisi_revisi' => 'nullable|string|max:255',
            'tanggal_berlaku' => 'nullable|date',
            'skema' => 'nullable|string|max:255',
            'hari_tanggal' => 'nullable|date',
            'penanggung_jawab_tuk' => 'nullable|string|max:255',
            'kehadiran_peserta' => 'nullable|array',
        ]);

        // Get students assigned to this asesor for this jadwal
        $assignedPendaftaranIds = DB::table('penugasan_pendaftaran')
            ->join('penugasan', 'penugasan_pendaftaran.penugasan_id', '=', 'penugasan.id')
            ->where('penugasan.jadwal_uji_id', $jadwalId)
            ->where('penugasan.asesor_id', $asesor->id)
            ->whereIn('penugasan.status', ['assigned', 'accepted', 'completed'])
            ->pluck('penugasan_pendaftaran.pendaftaran_id')
            ->unique()
            ->filter()
            ->toArray();

        $kehadiranPeserta = [];
        foreach ($assignedPendaftaranIds as $pendaftaranId) {
            $hadir = $request->input("kehadiran_peserta.{$pendaftaranId}.hadir", false);
            $keterangan = $request->input("kehadiran_peserta.{$pendaftaranId}.keterangan", '');
            
            // Get signature from pendaftaran if hadir
            $signature = null;
            if ($hadir) {
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

            $kehadiranPeserta[$pendaftaranId] = [
                'hadir' => (bool) $hadir,
                'signature' => $signature,
                'keterangan' => $keterangan,
            ];
        }

        $data = $request->all();
        $data['jadwal_uji_id'] = $jadwalId;
        $data['tuk_id'] = $jadwal->tuk_id;
        $data['created_by'] = Auth::id();
        $data['kehadiran_peserta'] = $kehadiranPeserta;
        
        // Set default skema from jadwal if not provided
        if (empty($data['skema'])) {
            $data['skema'] = $jadwal->skemaSertifikasi->nama_skema ?? '';
        }

        DaftarHadirPeserta::create($data);

        return redirect()->route('asesor.daftar-hadir-peserta.index')
            ->with('success', 'Daftar hadir peserta berhasil dibuat');
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

        $daftarHadir = DaftarHadirPeserta::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'tuk'])
            ->where('id', $id)
            ->where('created_by', Auth::id())
            ->firstOrFail();

        $jadwal = $daftarHadir->jadwalUji;
        
        if (!$jadwal) {
            return redirect()->route('asesor.daftar-hadir-peserta.index')
                ->with('error', 'Jadwal tidak ditemukan');
        }

        // Get students (asesi) assigned to this asesor for this jadwal
        $assignedPendaftaranIds = DB::table('penugasan_pendaftaran')
            ->join('penugasan', 'penugasan_pendaftaran.penugasan_id', '=', 'penugasan.id')
            ->where('penugasan.jadwal_uji_id', $daftarHadir->jadwal_uji_id)
            ->where('penugasan.asesor_id', $asesor->id)
            ->whereIn('penugasan.status', ['assigned', 'accepted', 'completed'])
            ->pluck('penugasan_pendaftaran.pendaftaran_id')
            ->unique()
            ->filter()
            ->toArray();

        $kehadiranPeserta = $daftarHadir->kehadiran_peserta ?? [];

        $asesiList = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->whereIn('id', $assignedPendaftaranIds)
            ->get()
            ->map(function ($p) use ($kehadiranPeserta) {
                // Get existing kehadiran
                $existingKehadiran = $kehadiranPeserta[$p->id] ?? null;
                $hadir = $existingKehadiran['hadir'] ?? false;
                $signature = $existingKehadiran['signature'] ?? null;

                // Get signature from pendaftaran if not in kehadiran
                if (!$signature && $hadir) {
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
                    'id' => $p->id,
                    'nama' => $p->user->nama_lengkap ?? $p->user->name,
                    'npm' => $p->user->npm ?? $p->user->nim ?? '-',
                    'hadir' => $hadir,
                    'signature' => $signature,
                    'keterangan' => $existingKehadiran['keterangan'] ?? '',
                ];
            });

        return view('asesor.daftar-hadir-peserta.edit', compact('daftarHadir', 'asesiList'));
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

        $daftarHadir = DaftarHadirPeserta::where('id', $id)
            ->where('created_by', Auth::id())
            ->firstOrFail();

        $request->validate([
            'no_dokumen' => 'nullable|string|max:255',
            'edisi_revisi' => 'nullable|string|max:255',
            'tanggal_berlaku' => 'nullable|date',
            'skema' => 'nullable|string|max:255',
            'hari_tanggal' => 'nullable|date',
            'penanggung_jawab_tuk' => 'nullable|string|max:255',
            'kepala_tuk' => 'nullable|string|max:255',
            'jumlah_peserta_huruf' => 'nullable|string|max:255',
            'kehadiran_peserta' => 'nullable|array',
        ]);

        // Get students assigned to this asesor for this jadwal
        $assignedPendaftaranIds = DB::table('penugasan_pendaftaran')
            ->join('penugasan', 'penugasan_pendaftaran.penugasan_id', '=', 'penugasan.id')
            ->where('penugasan.jadwal_uji_id', $daftarHadir->jadwal_uji_id)
            ->where('penugasan.asesor_id', $asesor->id)
            ->whereIn('penugasan.status', ['assigned', 'accepted', 'completed'])
            ->pluck('penugasan_pendaftaran.pendaftaran_id')
            ->unique()
            ->filter()
            ->toArray();

        $kehadiranPeserta = [];
        foreach ($assignedPendaftaranIds as $pendaftaranId) {
            $keterangan = $request->input("kehadiran_peserta.{$pendaftaranId}.keterangan", '');
            $hadir = $request->input("kehadiran_peserta.{$pendaftaranId}.hadir", false);
            
            // Get signature from existing or pendaftaran if hadir
            $existingKehadiran = $daftarHadir->kehadiran_peserta[$pendaftaranId] ?? null;
            $signature = $existingKehadiran['signature'] ?? null;
            
            if ($hadir && !$signature) {
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
            } elseif (!$hadir) {
                $signature = null; // Clear signature if not present
            }

            $kehadiranPeserta[$pendaftaranId] = [
                'hadir' => (bool) $hadir,
                'signature' => $signature,
                'keterangan' => $keterangan,
            ];
        }

        $data = $request->all();
        $data['kehadiran_peserta'] = $kehadiranPeserta;
        
        $daftarHadir->update($data);

        return redirect()->route('asesor.daftar-hadir-peserta.index')
            ->with('success', 'Daftar hadir peserta berhasil diperbarui');
    }

    /**
     * Generate PDF for daftar hadir peserta
     */
    public function generatePDF($id)
    {
        $daftarHadir = DaftarHadirPeserta::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'tuk'])
            ->findOrFail($id);

        // Get all asesi from kehadiran_peserta stored in daftar hadir
        $kehadiranPeserta = $daftarHadir->kehadiran_peserta ?? [];
        
        // Get all pendaftaran IDs from kehadiran_peserta
        $pendaftaranIds = array_keys($kehadiranPeserta);
        
        $asesiList = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->whereIn('id', $pendaftaranIds)
            ->get()
            ->map(function ($p) use ($kehadiranPeserta) {
                $kehadiran = $kehadiranPeserta[$p->id] ?? ['hadir' => false, 'signature' => null];
                
                return [
                    'nama' => $p->user->nama_lengkap ?? $p->user->name,
                    'npm' => $p->user->npm ?? $p->user->nim ?? '-',
                    'hadir' => $kehadiran['hadir'] ?? false,
                    'signature' => $kehadiran['hadir'] ? ($kehadiran['signature'] ?? null) : null,
                ];
            })
            ->filter(function ($asesi) {
                return $asesi['hadir']; // Only show those who are present
            })
            ->values();

        // Get only the asesor who created this daftar hadir
        $asesor = \App\Models\Asesor::where('user_id', $daftarHadir->created_by)->first();
        
        $asesorList = collect([]);
        if ($asesor) {
            // Get signature from personalization
            $signature = null;
            if ($asesor->user_id) {
                $personalization = \App\Models\UserPersonalization::where('user_id', $asesor->user_id)->first();
                if ($personalization && !empty($personalization->signature_data)) {
                    $signature = $personalization->signature_data;
                }
            }
            
            $asesorList = collect([[
                'nama' => $asesor->nama_lengkap,
                'no_reg' => $asesor->no_reg ?? '-',
                'signature' => $signature,
            ]]);
        }

        // Calculate pages automatically based on number of asesi
        $totalAsesi = $asesiList->count();
        $halamanTotal = max(1, ceil($totalAsesi / 10));
        $halaman = 1;

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
            'asesiList' => $asesiList,
            'asesorList' => $asesorList,
            'hariTanggalWithDay' => $hariTanggalWithDay,
            'hariTanggalDateOnly' => $hariTanggalDateOnly,
            'tanggalBerlaku' => $tanggalBerlaku,
            'halaman' => $halaman,
            'halamanTotal' => $halamanTotal,
        ];

        $pdf = PDF::loadView('asesor.daftar-hadir-peserta.pdf', $data);
        return $pdf->download('daftar-hadir-peserta-' . $daftarHadir->id . '.pdf');
    }

    /**
     * Generate PDF type 2 for daftar hadir peserta
     */
    public function generatePDF2($id)
    {
        $daftarHadir = DaftarHadirPeserta::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'tuk'])
            ->findOrFail($id);

        // Get all asesi from kehadiran_peserta
        $kehadiranPeserta = $daftarHadir->kehadiran_peserta ?? [];
        $pendaftaranIds = array_keys($kehadiranPeserta);

        // Get rekapitulasi hasil UJK for this jadwal to get K/BK results
        $rekapitulasi = \App\Models\RekapitulasiHasilUjk::where('jadwal_uji_id', $daftarHadir->jadwal_uji_id)
            ->first();
        
        $hasilAsesi = $rekapitulasi ? ($rekapitulasi->hasil_asesi ?? []) : [];

        $asesiList = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->whereIn('id', $pendaftaranIds)
            ->get()
            ->map(function ($p) use ($kehadiranPeserta, $hasilAsesi) {
                $kehadiran = $kehadiranPeserta[$p->id] ?? ['hadir' => false, 'signature' => null, 'keterangan' => ''];
                $hasil = $hasilAsesi[$p->id] ?? ['k' => false, 'bk' => false];
                
                return [
                    'id' => $p->id,
                    'nama' => $p->user->nama_lengkap ?? $p->user->name,
                    'npm' => $p->user->npm ?? $p->user->nim ?? '-',
                    'hadir' => $kehadiran['hadir'] ?? false,
                    'signature' => $kehadiran['signature'] ?? null,
                    'keterangan' => $kehadiran['keterangan'] ?? '',
                    'k' => $hasil['k'] ?? false,
                    'bk' => $hasil['bk'] ?? false,
                ];
            })
            ->values();

        // Count statistics
        $totalPeserta = $asesiList->count();
        $hadirCount = $asesiList->where('hadir', true)->count();
        $tidakHadirCount = $totalPeserta - $hadirCount;
        $countK = $asesiList->where('k', true)->count();
        $countBK = $asesiList->where('bk', true)->count();

        // Get asesor who created this
        $asesor = \App\Models\Asesor::where('user_id', $daftarHadir->created_by)->first();
        
        $asesorData = null;
        if ($asesor) {
            $signature = null;
            if ($asesor->user_id) {
                $personalization = \App\Models\UserPersonalization::where('user_id', $asesor->user_id)->first();
                if ($personalization && !empty($personalization->signature_data)) {
                    $signature = $personalization->signature_data;
                }
            }
            
            $asesorData = [
                'nama' => $asesor->nama_lengkap,
                'no_reg' => $asesor->no_reg ?? '-',
                'signature' => $signature,
            ];
        }

        // Format tanggal
        $hariTanggal = $daftarHadir->hari_tanggal 
            ? \Carbon\Carbon::parse($daftarHadir->hari_tanggal)->locale('id')
            : null;
        
        $hari = $hariTanggal ? $hariTanggal->isoFormat('dddd') : '';
        $tanggal = $hariTanggal ? $hariTanggal->isoFormat('D MMMM YYYY') : '';
        $tanggalDateOnly = $hariTanggal ? $hariTanggal->isoFormat('D MMMM YYYY') : '';
        
        $tanggalBerlaku = $daftarHadir->tanggal_berlaku 
            ? \Carbon\Carbon::parse($daftarHadir->tanggal_berlaku)->locale('id')->isoFormat('D MMMM YYYY')
            : '';

        $data = [
            'daftarHadir' => $daftarHadir,
            'asesiList' => $asesiList,
            'asesorData' => $asesorData,
            'hari' => $hari,
            'tanggal' => $tanggal,
            'tanggalDateOnly' => $tanggalDateOnly,
            'tanggalBerlaku' => $tanggalBerlaku,
            'totalPeserta' => $totalPeserta,
            'hadirCount' => $hadirCount,
            'tidakHadirCount' => $tidakHadirCount,
            'countK' => $countK,
            'countBK' => $countBK,
        ];

        $pdf = PDF::loadView('asesor.daftar-hadir-peserta.pdf2', $data);
        return $pdf->download('daftar-hadir-peserta-laporan-' . $daftarHadir->id . '.pdf');
    }
}
