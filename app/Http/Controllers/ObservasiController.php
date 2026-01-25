<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Asesor;
use App\Models\ObservasiChecklist;
use App\Models\Pendaftaran;
use App\Models\SkemaSertifikasi;
use App\Models\Elemen;
use App\Models\KriteriaUnjukKerja;
use App\Models\UnitKompetensiJudul;
use App\Models\ElemenJudul;
use App\Models\KriteriaUnjukKerjaJudul;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ObservasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $observasiChecklists = ObservasiChecklist::with(['asesor', 'pendaftaran.user'])
            ->where('asesor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('asesor.observasi.index', compact('observasiChecklists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya tampilkan mahasiswa yang ditugaskan kepada asesor yang login (dari halaman penugasan admin)
        $asesor = Asesor::where('user_id', Auth::id())->first();
        $asesorId = $asesor ? $asesor->id : null;

        // Get pendaftaran that have completed persetujuan asesmen
        // Only include pendaftaran that are assigned to this asesor (via penugasan)
        // Exclude pendaftaran that already have observasi checklist with mahasiswa signature
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->when($asesorId, function ($query) use ($asesorId) {
                $query->whereHas('penugasan', function ($q) use ($asesorId) {
                    $q->where('asesor_id', $asesorId);
                });
            }, function ($query) {
                // Jika asesor tidak ditemukan, tidak tampilkan siapa pun
                $query->whereRaw('1 = 0');
            })
            ->whereHas('verifications', function($query) {
                $query->where('type', 'asesor_verification')
                      ->where('status', 'approved');
            })
            ->whereNotNull('persetujuan_data')
            ->whereDoesntHave('observasiChecklists', function($query) {
                $query->where('asesor_id', Auth::id())
                      ->whereNotNull('mahasiswa_signature');
            })
            ->get();

        return view('asesor.observasi.create', compact('pendaftaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('ObservasiController store method called', [
            'request_data' => $request->all(),
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name
        ]);
        
        // Convert JSON strings to arrays before validation
        $request->merge([
            'elemen_data' => is_string($request->elemen_data) ? json_decode($request->elemen_data, true) : $request->elemen_data,
            'observasi_data' => is_string($request->observasi_data) ? json_decode($request->observasi_data, true) : $request->observasi_data,
            'benchmark' => is_string($request->benchmark) ? json_decode($request->benchmark, true) : $request->benchmark,
            'penilaian_lanjut' => is_string($request->penilaian_lanjut) ? json_decode($request->penilaian_lanjut, true) : $request->penilaian_lanjut,
        ]);

        try {
            $request->validate([
                'pendaftaran_id' => 'required|exists:pendaftaran,id',
                'tuk' => 'required|in:sewaktu,tempat_kerja,mandiri',
                'tanggal' => 'required|date',
                'elemen_data' => 'required|array',
                'observasi_data' => 'nullable|array',
                'benchmark' => 'nullable|array',
                'penilaian_lanjut' => 'nullable|array',
                'umpan_balik' => 'nullable|string',
                'asesor_signature' => 'nullable|string',
                'tanggal_asesor' => 'nullable|date'
            ]);
            Log::info('Validation successful');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            throw $e;
        }

        Log::info('Request validation passed', [
            'observasi_data' => $request->observasi_data,
            'benchmark' => $request->benchmark,
            'penilaian_lanjut' => $request->penilaian_lanjut,
            'all_request_data' => $request->all()
        ]);
        
        Log::info('About to start try block');

        try {
            $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])->findOrFail($request->pendaftaran_id);

            // Pastikan pendaftaran ini ditugaskan kepada asesor yang login
            $asesor = Asesor::where('user_id', Auth::id())->first();
            if (!$asesor || !$pendaftaran->penugasan()->where('asesor_id', $asesor->id)->exists()) {
                return redirect()->back()
                    ->with('error', 'Mahasiswa tersebut tidak ditugaskan kepada Anda.')
                    ->withInput();
            }
            
            Log::info('Pendaftaran found', ['pendaftaran_id' => $pendaftaran->id]);
            
            $createData = [
                'judul' => $pendaftaran->skemaSertifikasi->nama_skema,
                'nomor_skema' => $pendaftaran->skemaSertifikasi->nomor_skema,
                'tuk' => $request->tuk,
                'nama_asesor' => Auth::user()->name,
                'nama_asesi' => $pendaftaran->user->name,
                'tanggal' => $request->tanggal,
                'elemen_data' => $request->elemen_data,
                'observasi_data' => $request->observasi_data,
                'benchmark' => $request->benchmark,
                'penilaian_lanjut' => $request->penilaian_lanjut,
                'umpan_balik' => $request->umpan_balik,
                'asesor_signature' => $request->asesor_signature,
                'tanggal_asesor' => $request->tanggal_asesor,
                'asesor_id' => Auth::id(),
                'pendaftaran_id' => $request->pendaftaran_id
            ];
            
            Log::info('About to create ObservasiChecklist', ['create_data' => $createData]);
            
            $observasiChecklist = ObservasiChecklist::create($createData);

            Log::info('ObservasiChecklist created successfully', ['id' => $observasiChecklist->id]);

            return redirect()->route('asesor.observasi.index')
                ->with('success', 'Ceklis observasi berhasil dibuat');
        } catch (\Exception $e) {
            Log::error('Error creating ObservasiChecklist', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan ceklis observasi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $observasiChecklist = ObservasiChecklist::with(['asesor', 'pendaftaran.user', 'pendaftaran.skemaSertifikasi'])
            ->where('asesor_id', Auth::id())
            ->findOrFail($id);

        return view('asesor.observasi.show', compact('observasiChecklist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $observasiChecklist = ObservasiChecklist::with(['pendaftaran.user', 'pendaftaran.skemaSertifikasi'])
            ->where('asesor_id', Auth::id())
            ->findOrFail($id);

        return view('asesor.observasi.edit', compact('observasiChecklist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tuk' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'tanggal' => 'required|date',
            'elemen_data' => 'required',
            'observasi_data' => 'nullable',
            'benchmark' => 'nullable',
            'penilaian_lanjut' => 'nullable'
        ]);

        $observasiChecklist = ObservasiChecklist::where('asesor_id', Auth::id())
            ->findOrFail($id);

        // Parse JSON data if they are strings
        $elemenData = is_string($request->elemen_data) ? json_decode($request->elemen_data, true) : $request->elemen_data;
        $observasiData = is_string($request->observasi_data) ? json_decode($request->observasi_data, true) : $request->observasi_data;
        $benchmark = is_string($request->benchmark) ? json_decode($request->benchmark, true) : $request->benchmark;
        $penilaianLanjut = is_string($request->penilaian_lanjut) ? json_decode($request->penilaian_lanjut, true) : $request->penilaian_lanjut;

        $observasiChecklist->update([
            'tuk' => $request->tuk,
            'tanggal' => $request->tanggal,
            'elemen_data' => $elemenData,
            'observasi_data' => $observasiData,
            'benchmark' => $benchmark,
            'penilaian_lanjut' => $penilaianLanjut
        ]);

        return redirect()->route('asesor.observasi.index')
            ->with('success', 'Ceklis observasi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $observasiChecklist = ObservasiChecklist::where('asesor_id', Auth::id())
            ->findOrFail($id);

        $observasiChecklist->delete();

        return redirect()->route('asesor.observasi.index')
            ->with('success', 'Ceklis observasi berhasil dihapus');
    }

    /**
     * Get elemen and kriteria unjuk kerja for a pendaftaran
     */
    public function getElemenKriteria($pendaftaranId)
    {
        $pendaftaran = Pendaftaran::with(['skemaSertifikasi.elemen.kriteriaUnjukKerja'])
            ->findOrFail($pendaftaranId);

        $elemen = $pendaftaran->skemaSertifikasi->elemen->map(function($elemen) {
            return [
                'id' => $elemen->id,
                'nama_elemen' => $elemen->nama_elemen,
                'kriteria' => $elemen->kriteriaUnjukKerja->map(function($kriteria) {
                    return [
                        'id' => $kriteria->id,
                        'nama_kriteria' => $kriteria->nama_kriteria
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'elemen' => $elemen
        ]);
    }

    /**
     * Get unit kompetensi, elemen and kriteria unjuk kerja for a pendaftaran
     */
    public function getUnitKompetensi($pendaftaranId)
    {
        $pendaftaran = Pendaftaran::with(['skemaSertifikasi'])
            ->findOrFail($pendaftaranId);

        // Hanya izinkan jika pendaftaran ditugaskan kepada asesor yang login
        $asesor = Asesor::where('user_id', Auth::id())->first();
        if (!$asesor || !$pendaftaran->penugasan()->where('asesor_id', $asesor->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Get judul sertifikasi from pendaftaran
        $judulSertifikasi = $pendaftaran->skemaSertifikasi->nama_skema;

        // Get unit kompetensi per judul
        $unitKompetensiJudul = UnitKompetensiJudul::where('judul_sertifikasi', $judulSertifikasi)
            ->orderBy('kode_unit')
            ->get();

        $unitKompetensi = $unitKompetensiJudul->map(function($unit) use ($judulSertifikasi) {
            // Get elemen for this unit
            $elemenJudul = ElemenJudul::where('judul_sertifikasi', $judulSertifikasi)
                ->where('kode_unit', $unit->kode_unit)
                ->orderBy('kode_elemen')
                ->get();

            return [
                'id' => $unit->id,
                'kode_unit' => $unit->kode_unit,
                'nama_unit_kompetensi' => $unit->judul_unit,
                'standar_kompetensi_kerja' => $unit->standar_kompetensi_kerja,
                'elemen' => $elemenJudul->map(function($elemen) use ($judulSertifikasi) {
                    // Get kriteria for this elemen
                    $kriteriaJudul = KriteriaUnjukKerjaJudul::where('judul_sertifikasi', $judulSertifikasi)
                        ->where('kode_unit', $elemen->kode_unit)
                        ->where('kode_elemen', $elemen->kode_elemen)
                        ->orderBy('nomor_kriteria')
                        ->get();

                    return [
                        'id' => $elemen->id,
                        'kode_elemen' => $elemen->kode_elemen,
                        'nama_elemen' => $elemen->nama_elemen,
                        'deskripsi' => $elemen->deskripsi,
                        'kriteria' => $kriteriaJudul->map(function($kriteria) {
                            return [
                                'id' => $kriteria->id,
                                'nomor_kriteria' => $kriteria->nomor_kriteria,
                                'nama_kriteria' => $kriteria->deskripsi_kriteria,
                                'jenis_bukti' => $kriteria->jenis_bukti,
                                'metode_asesmen' => $kriteria->metode_asesmen,
                                'perangkat_asesmen' => $kriteria->perangkat_asesmen
                            ];
                        })
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'unitKompetensi' => $unitKompetensi
        ]);
    }

    /**
     * Download PDF for observasi checklist
     */
    public function downloadPDF(string $id)
    {
        $observasiChecklist = ObservasiChecklist::with(['asesor', 'pendaftaran.user', 'pendaftaran.skemaSertifikasi'])
            ->where('asesor_id', Auth::id())
            ->findOrFail($id);

        // Format tanggal
        $tanggal = $observasiChecklist->tanggal 
            ? \Carbon\Carbon::parse($observasiChecklist->tanggal)->locale('id')->isoFormat('D MMMM YYYY')
            : '';

        // Format tanggal asesor dan mahasiswa
        $tanggalAsesor = $observasiChecklist->tanggal_asesor 
            ? \Carbon\Carbon::parse($observasiChecklist->tanggal_asesor)->locale('id')->isoFormat('D MMMM YYYY')
            : '';
        
        $tanggalMahasiswa = $observasiChecklist->tanggal_mahasiswa 
            ? \Carbon\Carbon::parse($observasiChecklist->tanggal_mahasiswa)->locale('id')->isoFormat('D MMMM YYYY')
            : '';

        $data = [
            'observasiChecklist' => $observasiChecklist,
            'tanggal' => $tanggal,
            'tanggalAsesor' => $tanggalAsesor,
            'tanggalMahasiswa' => $tanggalMahasiswa,
        ];

        $pdf = PDF::loadView('asesor.observasi.pdf', $data);
        return $pdf->download('ceklist-observasi-' . $observasiChecklist->id . '.pdf');
    }
}
