<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\BandingAsesmen;
use App\Models\RekamanAsesmenKompetensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BandingAsesmenController extends Controller
{
    /**
     * Display a listing of banding asesmen
     */
    public function index()
    {
        $bandingAsesmen = BandingAsesmen::with(['rekamanAsesmen', 'pendaftaran.skemaSertifikasi'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('mahasiswa.banding-asesmen.index', compact('bandingAsesmen'));
    }

    /**
     * Show the form to select rekaman asesmen for banding
     */
    public function selectRekaman()
    {
        $user = Auth::user();

        // Get all rekaman asesmen for this user
        $rekamanAsesmen = RekamanAsesmenKompetensi::with(['asesor', 'pendaftaran.skemaSertifikasi'])
            ->whereHas('pendaftaran', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        // Get existing banding IDs to filter out
        $existingBandingIds = BandingAsesmen::where('user_id', $user->id)
            ->pluck('rekaman_asesmen_id')
            ->toArray();

        // Filter out rekaman asesmen that already have banding
        $availableRekaman = $rekamanAsesmen->filter(function($rekaman) use ($existingBandingIds) {
            return !in_array($rekaman->id, $existingBandingIds);
        });

        return view('mahasiswa.banding-asesmen.select-rekaman', compact('availableRekaman'));
    }

    /**
     * Show the form for creating a new banding asesmen
     */
    public function create($rekamanAsesmenId)
    {
        $user = Auth::user();
        
        $rekamanAsesmen = RekamanAsesmenKompetensi::with(['asesor', 'pendaftaran.skemaSertifikasi'])
            ->whereHas('pendaftaran', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->findOrFail($rekamanAsesmenId);

        // Check if banding already exists for this rekaman asesmen
        $existingBanding = BandingAsesmen::where('rekaman_asesmen_id', $rekamanAsesmenId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingBanding) {
            return redirect()->route('mahasiswa.banding-asesmen.show', $existingBanding->id)
                ->with('info', 'Banding asesmen untuk rekaman ini sudah pernah dibuat.');
        }

        return view('mahasiswa.banding-asesmen.create', compact('rekamanAsesmen'));
    }

    /**
     * Store a newly created banding asesmen
     */
    public function store(Request $request, $rekamanAsesmenId)
    {
        $user = Auth::user();

        $rekamanAsesmen = RekamanAsesmenKompetensi::with(['asesor', 'pendaftaran.skemaSertifikasi'])
            ->whereHas('pendaftaran', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->findOrFail($rekamanAsesmenId);

        // Check if banding already exists
        $existingBanding = BandingAsesmen::where('rekaman_asesmen_id', $rekamanAsesmenId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingBanding) {
            return redirect()->route('mahasiswa.banding-asesmen.show', $existingBanding->id)
                ->with('error', 'Banding asesmen untuk rekaman ini sudah pernah dibuat.');
        }

        $request->validate([
            'proses_banding_dijelaskan' => 'required|in:ya,tidak',
            'mendiskusikan_banding' => 'required|in:ya,tidak',
            'melibatkan_orang_lain' => 'required|in:ya,tidak',
            'alasan_banding' => 'required|string',
            'mahasiswa_signature' => 'required|string',
            'tanggal_banding' => 'required|date',
        ]);

        BandingAsesmen::create([
            'nama_asesi' => $user->name,
            'nama_asesor' => $rekamanAsesmen->nama_asesor,
            'tanggal_asesmen' => $rekamanAsesmen->tanggal_mulai,
            'proses_banding_dijelaskan' => $request->proses_banding_dijelaskan,
            'mendiskusikan_banding' => $request->mendiskusikan_banding,
            'melibatkan_orang_lain' => $request->melibatkan_orang_lain,
            'skema_sertifikasi' => $rekamanAsesmen->pendaftaran->skemaSertifikasi->nama_skema,
            'nomor_skema' => $rekamanAsesmen->nomor_skema,
            'alasan_banding' => $request->alasan_banding,
            'mahasiswa_signature' => $request->mahasiswa_signature,
            'tanggal_banding' => $request->tanggal_banding,
            'rekaman_asesmen_id' => $rekamanAsesmenId,
            'pendaftaran_id' => $rekamanAsesmen->pendaftaran_id,
            'user_id' => $user->id,
        ]);

        return redirect()->route('mahasiswa.banding-asesmen.index')
            ->with('success', 'Banding asesmen berhasil diajukan.');
    }

    /**
     * Display the specified banding asesmen
     */
    public function show($id)
    {
        $bandingAsesmen = BandingAsesmen::with(['rekamanAsesmen', 'pendaftaran.skemaSertifikasi'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('mahasiswa.banding-asesmen.show', compact('bandingAsesmen'));
    }
}
