<?php

namespace App\Http\Controllers;

use App\Models\RekamanAsesmenKompetensi;
use App\Models\Pendaftaran;
use App\Models\UnitKompetensiJudul;
use App\Models\Penugasan;
use App\Models\SoalSubmission;
use App\Models\SoalUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekamanAsesmenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekamanAsesmen = RekamanAsesmenKompetensi::with(['asesor', 'pendaftaran.skemaSertifikasi'])
            ->where('asesor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('asesor.rekaman-asesmen.index', compact('rekamanAsesmen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get asesor from authenticated user
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Get pendaftaran IDs that already have rekaman asesmen
        $existingPendaftaranIds = RekamanAsesmenKompetensi::pluck('pendaftaran_id')->unique()->filter();

        // Get pendaftaran IDs that are assigned to this asesor through penugasan
        $assignedPendaftaranIds = Penugasan::where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->with('pendaftaran')
            ->get()
            ->pluck('pendaftaran')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->filter()
            ->toArray();

        // Get soal_upload_ids that are uploaded by this asesor
        $soalUploadIds = SoalUpload::where('asesor_id', $asesor->id)
            ->pluck('id')
            ->unique()
            ->filter()
            ->toArray();

        // Get pendaftaran IDs that have submitted jawaban soal (SoalSubmission exists) for soal uploaded by this asesor
        $pendaftaranIdsWithJawaban = [];
        if (!empty($soalUploadIds)) {
            $pendaftaranIdsWithJawaban = SoalSubmission::whereIn('soal_upload_id', $soalUploadIds)
                ->pluck('pendaftaran_id')
                ->unique()
                ->filter()
                ->toArray();
        }

        // If no pendaftaran assigned or no jawaban submitted, return empty result
        if (empty($assignedPendaftaranIds) || empty($pendaftaranIdsWithJawaban)) {
            $pendaftaran = collect();
        } else {
            // Get pendaftaran that:
            // 1. Are assigned to this asesor through penugasan
            // 2. Have completed persetujuan asesmen
            // 3. Have submitted jawaban soal (SoalSubmission exists)
            // 4. Don't already have rekaman asesmen
            $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])
                ->whereIn('id', $assignedPendaftaranIds)
                ->whereIn('id', $pendaftaranIdsWithJawaban) // Sudah mengupload jawaban soal
                ->whereIn('status', ['approved', 'in_progress', 'persetujuan_submitted', 'persetujuan_confirmed', 'completed'])
                ->whereNotNull('persetujuan_data') // Sudah melakukan persetujuan asesmen
                ->whereNotIn('id', $existingPendaftaranIds) // Belum memiliki rekaman asesmen
                ->get();
        }

        return view('asesor.rekaman-asesmen.create', compact('pendaftaran', 'asesor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'tuk' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'tanggal_mulai' => 'required|date',
            'waktu_mulai' => 'required',
            'tanggal_selesai' => 'required|date',
            'waktu_selesai' => 'required',
            'unit_kompetensi_data' => 'nullable|array',
            'rekomendasi_hasil' => 'nullable|in:kompeten,belum_kompeten',
            'tindak_lanjut' => 'nullable|string',
            'komentar_observasi' => 'nullable|string',
            'no_reg_asesor' => 'nullable|string',
            'asesor_signature' => 'nullable|string',
            'tanggal_asesor' => 'nullable|date'
        ]);

        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])->findOrFail($request->pendaftaran_id);

        $rekamanAsesmen = RekamanAsesmenKompetensi::create([
            'judul' => $pendaftaran->skemaSertifikasi->nama_skema,
            'nomor_skema' => $pendaftaran->skemaSertifikasi->nomor_skema,
            'tuk' => $request->tuk,
            'nama_asesor' => Auth::user()->name,
            'nama_asesi' => $pendaftaran->user->name,
            'tanggal_mulai' => $request->tanggal_mulai,
            'waktu_mulai' => $request->waktu_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'waktu_selesai' => $request->waktu_selesai,
            'unit_kompetensi_data' => $request->unit_kompetensi_data,
            'rekomendasi_hasil' => $request->rekomendasi_hasil,
            'tindak_lanjut' => $request->tindak_lanjut,
            'komentar_observasi' => $request->komentar_observasi,
            'no_reg_asesor' => $request->no_reg_asesor,
            'asesor_signature' => $request->asesor_signature,
            'tanggal_asesor' => $request->tanggal_asesor,
            'asesor_id' => Auth::id(),
            'pendaftaran_id' => $request->pendaftaran_id
        ]);

        return redirect()->route('asesor.rekaman-asesmen.index')
            ->with('success', 'Rekaman asesmen kompetensi berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rekamanAsesmen = RekamanAsesmenKompetensi::with(['asesor', 'pendaftaran.skemaSertifikasi'])
            ->where('asesor_id', Auth::id())
            ->findOrFail($id);

        // Get unit kompetensi from database to get actual names
        $unitKompetensiList = UnitKompetensiJudul::where('judul_sertifikasi', $rekamanAsesmen->pendaftaran->skemaSertifikasi->nama_skema)
            ->get()
            ->keyBy(function($unit) {
                return $unit->judul_unit;
            });

        // Merge stored data with database data to ensure correct unit names
        if ($rekamanAsesmen->unit_kompetensi_data) {
            $unitData = collect($rekamanAsesmen->unit_kompetensi_data)->map(function($unit, $index) use ($unitKompetensiList) {
                // If judul_unit is missing or incorrect, get from database by index
                if (empty($unit['judul_unit']) || !$unitKompetensiList->has($unit['judul_unit'])) {
                    $unitFromDb = $unitKompetensiList->values()->get($index);
                    if ($unitFromDb) {
                        $unit['judul_unit'] = $unitFromDb->judul_unit;
                    } else {
                        $unit['judul_unit'] = 'Unit ' . ($index + 1);
                    }
                }
                return $unit;
            })->toArray();
            $rekamanAsesmen->unit_kompetensi_data = $unitData;
        }

        return view('asesor.rekaman-asesmen.show', compact('rekamanAsesmen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $rekamanAsesmen = RekamanAsesmenKompetensi::with(['pendaftaran.skemaSertifikasi'])
            ->where('asesor_id', Auth::id())
            ->findOrFail($id);
            
        // Get unit kompetensi from database to get actual names
        $unitKompetensiList = UnitKompetensiJudul::where('judul_sertifikasi', $rekamanAsesmen->pendaftaran->skemaSertifikasi->nama_skema)
            ->get()
            ->keyBy(function($unit) {
                return $unit->judul_unit;
            });

        // Merge stored data with database data to ensure correct unit names
        if ($rekamanAsesmen->unit_kompetensi_data) {
            $unitData = collect($rekamanAsesmen->unit_kompetensi_data)->map(function($unit, $index) use ($unitKompetensiList) {
                // If judul_unit is missing or incorrect, get from database by index
                if (empty($unit['judul_unit']) || !$unitKompetensiList->has($unit['judul_unit'])) {
                    $unitFromDb = $unitKompetensiList->values()->get($index);
                    if ($unitFromDb) {
                        $unit['judul_unit'] = $unitFromDb->judul_unit;
                    } else {
                        $unit['judul_unit'] = 'Unit ' . ($index + 1);
                    }
                }
                return $unit;
            })->toArray();
            $rekamanAsesmen->unit_kompetensi_data = $unitData;
        }

        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->whereIn('status', ['approved', 'in_progress', 'persetujuan_submitted', 'persetujuan_confirmed', 'completed'])
            ->whereNotNull('persetujuan_data')
            ->get();

        $asesor = Auth::user()->asesor;

        return view('asesor.rekaman-asesmen.edit', compact('rekamanAsesmen', 'pendaftaran', 'asesor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rekamanAsesmen = RekamanAsesmenKompetensi::where('asesor_id', Auth::id())->findOrFail($id);

        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'tuk' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'tanggal_mulai' => 'required|date',
            'waktu_mulai' => 'required',
            'tanggal_selesai' => 'required|date',
            'waktu_selesai' => 'required',
            'unit_kompetensi_data' => 'nullable|array',
            'rekomendasi_hasil' => 'nullable|in:kompeten,belum_kompeten',
            'tindak_lanjut' => 'nullable|string',
            'komentar_observasi' => 'nullable|string',
            'no_reg_asesor' => 'nullable|string',
            'asesor_signature' => 'nullable|string',
            'tanggal_asesor' => 'nullable|date'
        ]);

        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])->findOrFail($request->pendaftaran_id);

        $rekamanAsesmen->update([
            'judul' => $pendaftaran->skemaSertifikasi->nama_skema,
            'nomor_skema' => $pendaftaran->skemaSertifikasi->nomor_skema,
            'tuk' => $request->tuk,
            'nama_asesor' => Auth::user()->name,
            'nama_asesi' => $pendaftaran->user->name,
            'tanggal_mulai' => $request->tanggal_mulai,
            'waktu_mulai' => $request->waktu_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'waktu_selesai' => $request->waktu_selesai,
            'unit_kompetensi_data' => $request->unit_kompetensi_data,
            'rekomendasi_hasil' => $request->rekomendasi_hasil,
            'tindak_lanjut' => $request->tindak_lanjut,
            'komentar_observasi' => $request->komentar_observasi,
            'no_reg_asesor' => $request->no_reg_asesor,
            'asesor_signature' => $request->asesor_signature,
            'tanggal_asesor' => $request->tanggal_asesor,
            'pendaftaran_id' => $request->pendaftaran_id
        ]);

        return redirect()->route('asesor.rekaman-asesmen.index')
            ->with('success', 'Rekaman asesmen kompetensi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rekamanAsesmen = RekamanAsesmenKompetensi::where('asesor_id', Auth::id())->findOrFail($id);
        $rekamanAsesmen->delete();

        return redirect()->route('asesor.rekaman-asesmen.index')
            ->with('success', 'Rekaman asesmen kompetensi berhasil dihapus');
    }

    /**
     * Get unit kompetensi data for selected pendaftaran
     */
    public function getUnitKompetensi($pendaftaranId)
    {
        $pendaftaran = Pendaftaran::with(['skemaSertifikasi'])->findOrFail($pendaftaranId);
        
        $unitKompetensi = UnitKompetensiJudul::where('judul_sertifikasi', $pendaftaran->skemaSertifikasi->nama_skema)
            ->where('status', true)
            ->get();

        return response()->json($unitKompetensi);
    }
}
