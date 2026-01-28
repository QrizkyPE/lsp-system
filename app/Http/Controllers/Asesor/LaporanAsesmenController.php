<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\LaporanAsesmen;
use App\Models\JadwalUji;
use App\Models\Penugasan;
use App\Models\Pendaftaran;
use App\Models\UserPersonalization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class LaporanAsesmenController extends Controller
{
    /**
     * Tampilkan daftar jadwal yang memiliki / belum memiliki laporan asesmen.
     */
    public function index()
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Semua penugasan asesor
        $penugasan = Penugasan::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk'])
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->latest()
            ->get();

        $jadwalIds = $penugasan->pluck('jadwal_uji_id')->unique()->filter();

        // Laporan asesmen yang sudah dibuat untuk jadwal-jadwal ini (oleh asesor manapun)
        $laporan = LaporanAsesmen::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk'])
            ->whereIn('jadwal_uji_id', $jadwalIds)
            ->where('asesor_id', $asesor->id)
            ->latest()
            ->get();

        // Jadwal yang belum punya laporan asesmen dari asesor ini
        $jadwalsWithoutLaporan = JadwalUji::with(['skemaSertifikasi', 'tuk'])
            ->whereIn('id', $jadwalIds)
            ->whereNotIn('id', $laporan->pluck('jadwal_uji_id'))
            ->get();

        return view('asesor.laporan-asesmen.index', compact('laporan', 'jadwalsWithoutLaporan'));
    }

    /**
     * Form create laporan asesmen untuk satu jadwal.
     */
    public function create($jadwalId)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Pastikan asesor memang ditugaskan pada jadwal ini
        $penugasan = Penugasan::where('jadwal_uji_id', $jadwalId)
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->first();

        if (!$penugasan) {
            return redirect()->route('asesor.laporan-asesmen.index')
                ->with('error', 'Anda tidak memiliki akses ke jadwal ini');
        }

        $jadwal = JadwalUji::with(['skemaSertifikasi', 'tuk'])->findOrFail($jadwalId);

        // Ambil asesi yang ditugaskan ke asesor ini untuk jadwal ini (sama pola dengan rekapitulasi hasil ujk)
        $assignedPendaftaranIds = DB::table('penugasan_pendaftaran')
            ->join('penugasan', 'penugasan_pendaftaran.penugasan_id', '=', 'penugasan.id')
            ->where('penugasan.jadwal_uji_id', $jadwalId)
            ->where('penugasan.asesor_id', $asesor->id)
            ->whereIn('penugasan.status', ['assigned', 'accepted', 'completed'])
            ->pluck('penugasan_pendaftaran.pendaftaran_id')
            ->unique()
            ->filter()
            ->toArray();

        $asesiList = Pendaftaran::with(['user'])
            ->whereIn('id', $assignedPendaftaranIds)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nama' => $p->user->nama_lengkap ?? $p->user->name,
                ];
            });

        // Data untuk header auto input
        $asesorUser = Auth::user();
        $personalization = UserPersonalization::where('user_id', $asesorUser->id)->first();
        $asesorSignature = $personalization->signature_data ?? null;

        return view('asesor.laporan-asesmen.create', compact('jadwal', 'asesor', 'asesiList', 'asesorSignature'));
    }

    /**
     * Simpan laporan asesmen (sekalian dianggap submit ke admin).
     */
    public function store(Request $request, $jadwalId)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Pastikan asesor memang ditugaskan pada jadwal ini
        $penugasan = Penugasan::where('jadwal_uji_id', $jadwalId)
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->first();

        if (!$penugasan) {
            return redirect()->route('asesor.laporan-asesmen.index')
                ->with('error', 'Anda tidak memiliki akses ke jadwal ini');
        }

        $jadwal = JadwalUji::with(['skemaSertifikasi', 'tuk'])->findOrFail($jadwalId);

        $request->validate([
            'tanggal' => 'nullable|date',
            'tuk_type' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'hasil_asesi' => 'nullable|array',
            'aspek_positif_negatif' => 'nullable|string',
            'penolakan_hasil' => 'nullable|string',
            'saran_perbaikan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $hasilInput = $request->input('hasil_asesi', []);
        $hasilAsesi = [];
        foreach ($hasilInput as $pendaftaranId => $row) {
            $hasilAsesi[$pendaftaranId] = [
                'k' => !empty($row['k']),
                'bk' => !empty($row['bk']),
                'keterangan' => $row['keterangan'] ?? null,
            ];
        }

        $data = [
            'jadwal_uji_id' => $jadwalId,
            'tuk_id' => $jadwal->tuk_id,
            'tuk_type' => $request->input('tuk_type'),
            'asesor_id' => $asesor->id,
            'tanggal' => $request->input('tanggal') ?: ($jadwal->tanggal_mulai ?? now()),
            'hasil_asesi' => $hasilAsesi,
            'aspek_positif_negatif' => $request->input('aspek_positif_negatif'),
            'penolakan_hasil' => $request->input('penolakan_hasil'),
            'saran_perbaikan' => $request->input('saran_perbaikan'),
            'catatan' => $request->input('catatan'),
        ];

        LaporanAsesmen::create($data);

        return redirect()->route('asesor.laporan-asesmen.index')
            ->with('success', 'Laporan asesmen berhasil dibuat dan dikirim ke admin');
    }

    /**
     * Form edit laporan asesmen yang sudah dibuat.
     */
    public function edit($id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $laporan = LaporanAsesmen::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'asesor'])
            ->findOrFail($id);

        // Pastikan laporan milik asesor ini
        if ($laporan->asesor_id !== $asesor->id) {
            return redirect()->route('asesor.laporan-asesmen.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit laporan ini');
        }

        $jadwal = $laporan->jadwalUji;

        // Ambil asesi yang ditugaskan ke asesor ini untuk jadwal ini
        $assignedPendaftaranIds = DB::table('penugasan_pendaftaran')
            ->join('penugasan', 'penugasan_pendaftaran.penugasan_id', '=', 'penugasan.id')
            ->where('penugasan.jadwal_uji_id', $laporan->jadwal_uji_id)
            ->where('penugasan.asesor_id', $asesor->id)
            ->whereIn('penugasan.status', ['assigned', 'accepted', 'completed'])
            ->pluck('penugasan_pendaftaran.pendaftaran_id')
            ->unique()
            ->filter()
            ->toArray();

        $hasilExisting = $laporan->hasil_asesi ?? [];

        $asesiList = Pendaftaran::with('user')
            ->whereIn('id', $assignedPendaftaranIds)
            ->get()
            ->map(function ($p) use ($hasilExisting) {
                $row = $hasilExisting[$p->id] ?? ['k' => false, 'bk' => false, 'keterangan' => null];
                return [
                    'id' => $p->id,
                    'nama' => $p->user->nama_lengkap ?? $p->user->name,
                    'k' => $row['k'] ?? false,
                    'bk' => $row['bk'] ?? false,
                    'keterangan' => $row['keterangan'] ?? null,
                ];
            });

        $asesorUser = Auth::user();
        $personalization = UserPersonalization::where('user_id', $asesorUser->id)->first();
        $asesorSignature = $personalization->signature_data ?? null;

        return view('asesor.laporan-asesmen.edit', compact('laporan', 'jadwal', 'asesor', 'asesiList', 'asesorSignature'));
    }

    /**
     * Update laporan asesmen yang sudah dibuat.
     */
    public function update(Request $request, $id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $laporan = LaporanAsesmen::findOrFail($id);

        if ($laporan->asesor_id !== $asesor->id) {
            return redirect()->route('asesor.laporan-asesmen.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengubah laporan ini');
        }

        $request->validate([
            'tanggal' => 'nullable|date',
            'tuk_type' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'hasil_asesi' => 'nullable|array',
            'aspek_positif_negatif' => 'nullable|string',
            'penolakan_hasil' => 'nullable|string',
            'saran_perbaikan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $hasilInput = $request->input('hasil_asesi', []);
        $hasilAsesi = [];
        foreach ($hasilInput as $pendaftaranId => $row) {
            $hasilAsesi[$pendaftaranId] = [
                'k' => !empty($row['k']),
                'bk' => !empty($row['bk']),
                'keterangan' => $row['keterangan'] ?? null,
            ];
        }

        $laporan->update([
            'tanggal' => $request->input('tanggal') ?: $laporan->tanggal,
            'tuk_type' => $request->input('tuk_type'),
            'hasil_asesi' => $hasilAsesi,
            'aspek_positif_negatif' => $request->input('aspek_positif_negatif'),
            'penolakan_hasil' => $request->input('penolakan_hasil'),
            'saran_perbaikan' => $request->input('saran_perbaikan'),
            'catatan' => $request->input('catatan'),
        ]);

        return redirect()->route('asesor.laporan-asesmen.index')
            ->with('success', 'Laporan asesmen berhasil diperbarui');
    }

    /**
     * Generate PDF FR.AK.05 dari sisi asesor (opsional, kalau ingin asesor unduh sendiri).
     */
    public function generatePDF($id)
    {
        $laporan = LaporanAsesmen::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'asesor.user'])
            ->findOrFail($id);

        $pendaftaranIds = array_keys($laporan->hasil_asesi ?? []);

        $asesiList = Pendaftaran::with('user')
            ->whereIn('id', $pendaftaranIds)
            ->get()
            ->map(function ($p) use ($laporan) {
                $hasil = $laporan->hasil_asesi[$p->id] ?? ['k' => false, 'bk' => false, 'keterangan' => null];
                return [
                    'nama' => $p->user->nama_lengkap ?? $p->user->name,
                    'k' => $hasil['k'] ?? false,
                    'bk' => $hasil['bk'] ?? false,
                    'keterangan' => $hasil['keterangan'] ?? null,
                ];
            });

        $asesorUser = $laporan->asesor->user ?? Auth::user();
        $personalization = UserPersonalization::where('user_id', $asesorUser->id)->first();
        $asesorSignature = $personalization->signature_data ?? null;

        $data = [
            'laporan' => $laporan,
            'asesiList' => $asesiList,
            'asesorSignature' => $asesorSignature,
        ];

        $pdf = PDF::loadView('admin.laporan-asesmen-pdf', $data);
        return $pdf->download('laporan-asesmen-' . $laporan->id . '.pdf');
    }
}

