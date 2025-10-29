<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\UmpanBalikAsesmen;

class AsesorUmpanBalikController extends Controller
{
    public function index()
    {
        $umpanBalik = UmpanBalikAsesmen::with(['rekamanAsesmen'])
            ->whereHas('rekamanAsesmen', function ($q) {
                $q->where('asesor_id', Auth::id());
            })
            ->latest()
            ->paginate(10);

        return view('asesor.umpan-balik.index', compact('umpanBalik'));
    }

    public function show($id)
    {
        $umpanBalik = UmpanBalikAsesmen::with(['rekamanAsesmen'])
            ->whereHas('rekamanAsesmen', function ($q) {
                $q->where('asesor_id', Auth::id());
            })
            ->findOrFail($id);

        return view('asesor.umpan-balik.show', compact('umpanBalik'));
    }
}
