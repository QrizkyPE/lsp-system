<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\BandingAsesmen;

class AsesorBandingAsesmenController extends Controller
{
    /**
     * Display a listing of banding asesmen
     */
    public function index()
    {
        $bandingAsesmen = BandingAsesmen::with(['rekamanAsesmen', 'user', 'pendaftaran.skemaSertifikasi'])
            ->whereHas('rekamanAsesmen', function ($q) {
                $q->where('asesor_id', Auth::id());
            })
            ->latest()
            ->paginate(10);

        return view('asesor.banding-asesmen.index', compact('bandingAsesmen'));
    }

    /**
     * Display the specified banding asesmen
     */
    public function show($id)
    {
        $bandingAsesmen = BandingAsesmen::with(['rekamanAsesmen', 'user', 'pendaftaran.skemaSertifikasi'])
            ->whereHas('rekamanAsesmen', function ($q) {
                $q->where('asesor_id', Auth::id());
            })
            ->findOrFail($id);

        return view('asesor.banding-asesmen.show', compact('bandingAsesmen'));
    }
}
