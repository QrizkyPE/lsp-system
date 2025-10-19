<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Pendaftaran;
use App\Models\SkemaSertifikasi;
use App\Models\JadwalUji;
use App\Models\Dokumen;
use App\Models\UnitKompetensiJudul;
use App\Models\ElemenJudul;
use App\Models\KriteriaUnjukKerjaJudul;
use App\Models\PendaftaranVerification;

class MahasiswaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $totalPendaftaran = Pendaftaran::where('user_id', $user->id)->count();
        $approvedPendaftaran = Pendaftaran::where('user_id', $user->id)->where('status', 'approved')->count();
        $sertifikat = Pendaftaran::where('user_id', $user->id)->where('hasil_asesmen', 'kompeten')->count();
        $pendingPendaftaran = Pendaftaran::where('user_id', $user->id)->where('status', 'pending')->count();
        $recentPendaftaran = Pendaftaran::with('skemaSertifikasi')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('mahasiswa.dashboard', compact(
            'totalPendaftaran',
            'approvedPendaftaran',
            'sertifikat',
            'pendingPendaftaran',
            'recentPendaftaran'
        ));
    }

    public function pendaftaran()
    {
        $user = Auth::user();
        $pendaftaran = Pendaftaran::with(['skemaSertifikasi', 'jadwalUji'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
        $skemas = SkemaSertifikasi::where('status', true)->get();
        $jadwals = JadwalUji::with('skemaSertifikasi')->where('status', 'open')->get();
        
        return view('mahasiswa.pendaftaran', compact('pendaftaran', 'skemas', 'jadwals'));
    }

    public function pendaftaranStep1()
    {
        $skemas = SkemaSertifikasi::where('status', true)->get();
        $jadwalUji = JadwalUji::with(['skemaSertifikasi', 'tuk'])
            ->where('status', 'open')
            ->where('kuota_terisi', '<', DB::raw('kuota_maksimal'))
            ->get();
        
        return view('mahasiswa.pendaftaran-step1', compact('skemas', 'jadwalUji'));
    }

    public function storePendaftaranStep1(Request $request)
    {
        $request->validate([
            'skema_sertifikasi_id' => 'required|exists:skema_sertifikasi,id',
            'jadwal_uji_id' => 'required|exists:jadwal_uji,id',
        ]);

        // Check if user already registered for this jadwal
        $existingPendaftaran = Pendaftaran::where('user_id', Auth::id())
            ->where('jadwal_uji_id', $request->jadwal_uji_id)
            ->first();

        if ($existingPendaftaran) {
            return redirect()->back()
                ->with('error', 'Anda sudah terdaftar untuk jadwal ini')
                ->withInput();
        }

        // Check quota
        $jadwal = JadwalUji::find($request->jadwal_uji_id);
        if ($jadwal->kuota_terisi >= $jadwal->kuota_maksimal) {
            return redirect()->back()
                ->with('error', 'Kuota untuk jadwal ini sudah penuh')
                ->withInput();
        }

        // Create or update pendaftaran record
        $user = Auth::user();
        
        // Use database transaction to prevent race conditions
        $pendaftaran = DB::transaction(function () use ($user, $request) {
            // Check if user already has a draft or in_progress pendaftaran
            $existingPendaftaran = Pendaftaran::where('user_id', $user->id)
                ->whereIn('status', ['draft', 'in_progress'])
                ->first();
            
            if ($existingPendaftaran) {
                // Update existing draft - keep the same no_pendaftaran
                $existingPendaftaran->update([
                    'skema_sertifikasi_id' => $request->skema_sertifikasi_id,
                    'jadwal_uji_id' => $request->jadwal_uji_id,
                    'tanggal_pendaftaran' => now(),
                ]);
                return $existingPendaftaran;
            } else {
                // Generate unique no_pendaftaran for new record
                do {
                    $noPendaftaran = 'REG' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                } while (Pendaftaran::where('no_pendaftaran', $noPendaftaran)->exists());
                
                // Create new draft
                return Pendaftaran::create([
                    'user_id' => $user->id,
                    'skema_sertifikasi_id' => $request->skema_sertifikasi_id,
                    'jadwal_uji_id' => $request->jadwal_uji_id,
                    'no_pendaftaran' => $noPendaftaran,
                    'status' => 'draft',
                    'tanggal_pendaftaran' => now(),
                ]);
            }
        });

        // Store in session for multi-step process
        session([
            'pendaftaran_data' => [
                'pendaftaran_id' => $pendaftaran->id,
                'pengajuan' => [
                    'skema_sertifikasi_id' => $request->skema_sertifikasi_id,
                    'jadwal_uji_id' => $request->jadwal_uji_id,
                ],
                'step' => 1
            ]
        ]);

        return redirect()->route('mahasiswa.pendaftaran.step2');
    }

    public function pendaftaranStep2()
    {
        // Check if step 1 data exists in session
        if (!session('pendaftaran_data') || session('pendaftaran_data.step') != 1) {
            return redirect()->route('mahasiswa.pendaftaran.step1')
                ->with('error', 'Silakan lengkapi step 1 terlebih dahulu');
        }

        // Load existing data if available
        $pendaftaranData = session('pendaftaran_data');
        $existingData = [];
        
        if (isset($pendaftaranData['pendaftaran_id'])) {
            $pendaftaran = Pendaftaran::find($pendaftaranData['pendaftaran_id']);
            if ($pendaftaran && $pendaftaran->profil_data) {
                $existingData = $pendaftaran->profil_data;
            }
        }

        return view('mahasiswa.pendaftaran-step2', compact('existingData'));
    }

    public function storePendaftaranStep2(Request $request)
    {
        $request->validate([
            // Data Pribadi
            'nama_lengkap' => 'required|string|max:255',
            'no_ktp' => 'required|string|max:20',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kebangsaan' => 'required|string|max:255',
            'alamat_rumah' => 'required|string',
            'kode_pos' => 'required|string|max:10',
            'rumah' => 'nullable|string|max:255',
            'kantor' => 'nullable|string|max:255',
            'no_telp' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'kualifikasi_pendidikan' => 'required|string|max:255',
            
            // Data Pekerjaan
            'pekerjaan' => 'required|string|max:255',
            'nama_institusi' => 'nullable|required_unless:pekerjaan,Belum/Tidak Bekerja|string|max:255',
            'jabatan' => 'nullable|required_unless:pekerjaan,Belum/Tidak Bekerja|string|max:255',
            'alamat_lembaga' => 'nullable|required_unless:pekerjaan,Belum/Tidak Bekerja|string',
            'kode_pos_lembaga' => 'nullable|required_unless:pekerjaan,Belum/Tidak Bekerja|string|max:10',
            'no_telp_lembaga' => 'nullable|required_unless:pekerjaan,Belum/Tidak Bekerja|string|max:20',
            'no_fax_lembaga' => 'nullable|required_unless:pekerjaan,Belum/Tidak Bekerja|string|max:20',
            'email_lembaga' => 'nullable|required_unless:pekerjaan,Belum/Tidak Bekerja|email|max:255',
        ]);

        // Check if step 1 data exists in session
        if (!session('pendaftaran_data') || session('pendaftaran_data.step') != 1) {
            return redirect()->route('mahasiswa.pendaftaran.step1')
                ->with('error', 'Silakan lengkapi step 1 terlebih dahulu');
        }

        // Update session with step 2 data
        $pendaftaranData = session('pendaftaran_data');
        $pendaftaranData['step'] = 2;
        $pendaftaranData['profil'] = $request->all();
        
        // Update pendaftaran record with profil data
        if (isset($pendaftaranData['pendaftaran_id'])) {
            $pendaftaran = Pendaftaran::find($pendaftaranData['pendaftaran_id']);
            if ($pendaftaran) {
                $pendaftaran->update([
                    'profil_data' => json_encode($request->all()),
                ]);
            }
        }
        
        session(['pendaftaran_data' => $pendaftaranData]);

        return redirect()->route('mahasiswa.pendaftaran.step3');
    }

    public function pendaftaranStep3()
    {
        // ensure step 2 done
        $data = session('pendaftaran_data');
        if (!$data || ($data['step'] ?? 0) < 2) {
            return redirect()->route('mahasiswa.pendaftaran.step2')
                ->with('error', 'Silakan lengkapi step sebelumnya.');
        }

        // Get skema sertifikasi from step 1
        $skemaSertifikasiId = $data['pengajuan']['skema_sertifikasi_id'] ?? null;
        if (!$skemaSertifikasiId) {
            return redirect()->route('mahasiswa.pendaftaran.step1')
                ->with('error', 'Data pengajuan tidak lengkap. Silakan mulai dari awal.');
        }
        
        $skemaSertifikasi = SkemaSertifikasi::find($skemaSertifikasiId);
        if (!$skemaSertifikasi) {
            return redirect()->route('mahasiswa.pendaftaran.step1')
                ->with('error', 'Skema sertifikasi tidak ditemukan.');
        }

        // Get judul from skema sertifikasi
        $selectedJudul = $skemaSertifikasi->nama_skema;
        
        // Load existing data if available
        $existingData = [];
        if (isset($data['pendaftaran_id'])) {
            $pendaftaran = Pendaftaran::find($data['pendaftaran_id']);
            if ($pendaftaran && $pendaftaran->sertifikasi_data) {
                $existingData = $pendaftaran->sertifikasi_data;
            }
        }

        // Provide options to the view
        $skemaOptions = ['KKNI', 'Okupasi', 'Klaster'];
        $tujuanOptions = [
            'Sertifikasi',
            'Pengakuan Kompetensi Terkini (PKT)',
            'Rekognisi Pembelajaran Lampau (RPL)',
            'Lainnya',
        ];

        // Get unit kompetensi data from database based on selected judul
        $unitKompetensiData = UnitKompetensiJudul::where('judul_sertifikasi', $selectedJudul)
            ->orderBy('id')->get();

        return view('mahasiswa.pendaftaran-step3', compact('skemaOptions', 'tujuanOptions', 'unitKompetensiData', 'selectedJudul', 'existingData'));
    }

    public function pendaftaranStep4()
    {
        // Ensure step 3 done
        $data = session('pendaftaran_data');
        if (!$data || ($data['step'] ?? 0) < 3) {
            return redirect()->route('mahasiswa.pendaftaran.step3')
                ->with('error', 'Silakan lengkapi step sebelumnya.');
        }

        // Get selected judul from step 3
        $selectedJudul = $data['sertifikasi']['judul'] ?? '';
        
        if (empty($selectedJudul)) {
            return redirect()->route('mahasiswa.pendaftaran.step3')
                ->with('error', 'Judul sertifikasi belum dipilih.');
        }
        
        // Load existing data if available
        $existingData = [];
        if (isset($data['pendaftaran_id'])) {
            $pendaftaran = Pendaftaran::find($data['pendaftaran_id']);
            if ($pendaftaran && $pendaftaran->asesmen_data) {
                $existingData = $pendaftaran->asesmen_data;
            }
        }

        // Get unit kompetensi data for selected judul
        $unitKompetensiData = UnitKompetensiJudul::where('judul_sertifikasi', $selectedJudul)
            ->orderBy('id')
            ->get();

        // Get elemen data for selected judul
        $elemenData = ElemenJudul::where('judul_sertifikasi', $selectedJudul)
            ->orderBy('kode_unit')
            ->orderBy('kode_elemen')
            ->get();

        // Get kriteria unjuk kerja data for selected judul
        $kriteriaData = KriteriaUnjukKerjaJudul::where('judul_sertifikasi', $selectedJudul)
            ->orderBy('kode_unit')
            ->orderBy('kode_elemen')
            ->orderBy('nomor_kriteria')
            ->get();

        // Get nomor skema based on judul
        $nomorSkemaByJudul = [
            'PENGEMBANG WEB (WEB DEVELOPER)' => '621/UMDP/XI/Q/2022',
            'TEKNISI PERPAJAKAN (PAJAK PENGHASILAN ORANG PRIBADI)' => '612/UMDP/XI/Q/2022',
            'System Analyst' => '606/UMDP/XI/Q/2022',
            'Junior Web Programmer' => '617/UMDP/XI/Q/2022',
            'Database Administrator' => '603/UMDP/XI/Q/2022',
            'Analis Senior Hubungan Industrial' => '617/UMDP/XI/Q/2022'
        ];

        $nomorSkema = $nomorSkemaByJudul[$selectedJudul] ?? '';

        // Get uploaded files from step 3
        $buktiFiles = $data['sertifikasi']['bukti_files'] ?? [];
        $buktiAdminFiles = $data['sertifikasi']['bukti_admin_files'] ?? [];
        $signatureData = $data['sertifikasi']['signature_data'] ?? '';
        

        return view('mahasiswa.pendaftaran-step4', compact(
            'data', 'unitKompetensiData', 'elemenData', 'kriteriaData', 'buktiFiles', 'buktiAdminFiles', 'nomorSkema', 'selectedJudul', 'signatureData', 'existingData'
        ));
    }

    public function storePendaftaranStep4(Request $request)
    {
        // Ensure step 3 done
        $data = session('pendaftaran_data');
        if (!$data || ($data['step'] ?? 0) < 3) {
            return redirect()->route('mahasiswa.pendaftaran.step3')
                ->with('error', 'Silakan lengkapi step sebelumnya.');
        }

        // Validate kriteria responses
        $request->validate([
            'kriteria.*.kompeten' => 'nullable|boolean',
            'kriteria.*.belum_kompeten' => 'nullable|boolean',
            'signature_data' => 'nullable|string',
        ]);

        // Update session data with step 4 completion
        $data['step'] = 4;
        $data['asesmen_mandiri'] = $request->input('kriteria', []);
        
        // Use existing signature if no new signature provided
        if (empty($request->signature_data) && !empty($data['sertifikasi']['signature_data'])) {
            $data['asesmen_mandiri']['signature_data'] = $data['sertifikasi']['signature_data'];
        } else {
            $data['asesmen_mandiri']['signature_data'] = $request->signature_data;
        }
        
        // Update pendaftaran record with asesmen data and change status to pending
        if (isset($data['pendaftaran_id'])) {
            $pendaftaran = Pendaftaran::find($data['pendaftaran_id']);
            if ($pendaftaran) {
                $pendaftaran->update([
                    'asesmen_data' => json_encode($data['asesmen_mandiri']),
                    'status' => 'pending',
                ]);
            }
        } else {
            return redirect()->route('mahasiswa.pendaftaran.step1')
                ->with('error', 'Data pengajuan tidak lengkap. Silakan mulai dari awal.');
        }

        // Create verification records for admin and asesor
        PendaftaranVerification::create([
            'pendaftaran_id' => $pendaftaran->id,
            'verifier_id' => null, // Will be filled when admin verifies
            'type' => 'admin_verification',
            'status' => 'pending',
        ]);

        PendaftaranVerification::create([
            'pendaftaran_id' => $pendaftaran->id,
            'verifier_id' => null, // Will be filled when asesor verifies
            'type' => 'asesor_verification',
            'status' => 'pending',
        ]);

        // Update kuota terisi
        $jadwal = JadwalUji::find($pendaftaran->jadwal_uji_id);
        if ($jadwal) {
            $jadwal->increment('kuota_terisi');
        }

        // Clear session data
        session()->forget('pendaftaran_data');

        return redirect()->route('mahasiswa.pendaftaran')
            ->with('success', 'Pendaftaran berhasil diajukan. Data permohonan sertifikasi akan dikirim ke Admin dan Asesmen Mandiri akan dikirim ke Asesor untuk verifikasi.');
    }

    public function storePendaftaranStep3(Request $request)
    {
        $request->validate([
            'skema' => 'required|in:KKNI,Okupasi,Klaster',
            'judul' => 'required|string|max:255',
            'tujuan_asesmen' => 'required|string|max:255',
            'bukti_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'bukti_admin_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'signature_data' => 'nullable|string',
        ]);

        $data = session('pendaftaran_data');
        if (!$data || ($data['step'] ?? 0) < 2) {
            return redirect()->route('mahasiswa.pendaftaran.step2')
                ->with('error', 'Silakan lengkapi step sebelumnya.');
        }

        // Handle file uploads for bukti persyaratan dasar
        $buktiFiles = [];
        if ($request->hasFile('bukti_files')) {
            foreach ($request->file('bukti_files') as $key => $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . $key . '_' . $file->getClientOriginalName();
                    $file->storeAs('bukti_persyaratan', $filename, 'public');
                    $buktiFiles[] = [
                        'filename' => $filename,
                        'original_name' => $file->getClientOriginalName(),
                        'bukti_type' => $request->input('bukti_types.' . $key),
                        'kode_unit' => $request->input('bukti_kode_units.' . $key)
                    ];
                }
            }
        }

        // Handle file uploads for bukti administratif
        $buktiAdminFiles = [];
        if ($request->hasFile('bukti_admin_files')) {
            foreach ($request->file('bukti_admin_files') as $key => $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_admin_' . $key . '_' . $file->getClientOriginalName();
                    $file->storeAs('bukti_administratif', $filename, 'public');
                    $buktiAdminFiles[] = [
                        'filename' => $filename,
                        'original_name' => $file->getClientOriginalName(),
                        'bukti_type' => $request->input('bukti_admin_types.' . $key),
                        'kode_unit' => $request->input('bukti_admin_kode_units.' . $key)
                    ];
                }
            }
        }

        $data['step'] = 3;
        $data['sertifikasi'] = [
            'skema' => $request->skema,
            'judul' => trim($request->judul),
            'tujuan_asesmen' => trim($request->tujuan_asesmen),
            'bukti_files' => $buktiFiles,
            'bukti_admin_files' => $buktiAdminFiles,
            'signature_data' => $request->signature_data,
            'bukti_persyaratan' => $request->input('bukti_persyaratan', []),
            'bukti_administratif' => $request->input('bukti_administratif', []),
        ];
        
        // Update pendaftaran record with sertifikasi data
        if (isset($data['pendaftaran_id'])) {
            $pendaftaran = Pendaftaran::find($data['pendaftaran_id']);
            if ($pendaftaran) {
                $pendaftaran->update([
                    'sertifikasi_data' => json_encode($data['sertifikasi']),
                ]);
            }
        }
        
        session(['pendaftaran_data' => $data]);

        // Next would be step 4
        return redirect()->route('mahasiswa.pendaftaran.step4');
    }

    public function clearPendaftaranSession()
    {
        session()->forget('pendaftaran_data');
        return redirect()->route('mahasiswa.pendaftaran')
            ->with('success', 'Data pendaftaran yang belum selesai telah dihapus.');
    }

    public function jadwal()
    {
        $jadwals = JadwalUji::with(['skemaSertifikasi', 'tuk'])
            ->where('status', 'open')
            ->latest()
            ->paginate(10);
        
        return view('mahasiswa.jadwal', compact('jadwals'));
    }

    public function dokumen()
    {
        $user = Auth::user();
        $pendaftaran = Pendaftaran::with('dokumen')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->latest()
            ->get();
        
        return view('mahasiswa.dokumen', compact('pendaftaran'));
    }

    public function storeDokumen(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'jenis_dokumen' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('dokumen', $fileName, 'public');

        Dokumen::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'jenis_dokumen' => $request->jenis_dokumen,
            'nama_dokumen' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'status' => 'submitted',
            'tanggal_submit' => now(),
        ]);

        return redirect()->route('mahasiswa.dokumen')
            ->with('success', 'Dokumen berhasil diupload');
    }

    public function hasil()
    {
        $user = Auth::user();
        $pendaftaran = Pendaftaran::with(['skemaSertifikasi', 'dokumen'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['completed', 'failed'])
            ->latest()
            ->get();
        
        return view('mahasiswa.hasil', compact('pendaftaran'));
    }

    public function submitBanding(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'alasan_banding' => 'required|string',
        ]);

        // Create banding document (AK.04)
        Dokumen::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'jenis_dokumen' => 'AK.04',
            'nama_dokumen' => 'Banding Asesmen',
            'file_path' => '',
            'file_name' => '',
            'file_size' => 0,
            'mime_type' => 'text/plain',
            'status' => 'submitted',
            'data_dokumen' => ['alasan_banding' => $request->alasan_banding],
            'tanggal_submit' => now(),
        ]);

        return redirect()->route('mahasiswa.hasil')
            ->with('success', 'Banding berhasil diajukan');
    }

    public function riwayatPendaftaran()
    {
        $pendaftaran = Pendaftaran::with(['skemaSertifikasi', 'jadwalUji', 'verifications'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        // Calculate summary statistics
        $totalPendaftaran = Pendaftaran::where('user_id', Auth::id())->count();
        $pendingPendaftaran = Pendaftaran::where('user_id', Auth::id())->where('status', 'pending')->count();
        $approvedPendaftaran = Pendaftaran::where('user_id', Auth::id())->where('status', 'approved')->count();
        $rejectedPendaftaran = Pendaftaran::where('user_id', Auth::id())->where('status', 'rejected')->count();

        $skemaOptions = SkemaSertifikasi::all();

        return view('mahasiswa.riwayat-pendaftaran', compact(
            'pendaftaran', 
            'totalPendaftaran', 
            'pendingPendaftaran', 
            'approvedPendaftaran', 
            'rejectedPendaftaran',
            'skemaOptions'
        ));
    }

    public function detailPendaftaran($id)
    {
        $user = Auth::user();
        
        // Get pendaftaran with all related data
        $pendaftaran = Pendaftaran::with([
            'user', 
            'skemaSertifikasi', 
            'jadwalUji.tuk',
            'verifications' => function($query) {
                $query->with('verifier');
            }
        ])
        ->where('id', $id)
        ->where('user_id', $user->id)
        ->firstOrFail();

        // Decode JSON data safely
        $profilData = null;
        $sertifikasiData = null;
        $asesmenData = null;

        if ($pendaftaran->profil_data) {
            if (is_string($pendaftaran->profil_data)) {
                $decoded = json_decode($pendaftaran->profil_data, true);
                $profilData = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
                
            } else {
                $profilData = $pendaftaran->profil_data;
            }
        }

        if ($pendaftaran->sertifikasi_data) {
            if (is_string($pendaftaran->sertifikasi_data)) {
                $decoded = json_decode($pendaftaran->sertifikasi_data, true);
                $sertifikasiData = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
            } else {
                $sertifikasiData = $pendaftaran->sertifikasi_data;
            }
        }

        if ($pendaftaran->asesmen_data) {
            if (is_string($pendaftaran->asesmen_data)) {
                $decoded = json_decode($pendaftaran->asesmen_data, true);
                $asesmenData = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
            } else {
                $asesmenData = $pendaftaran->asesmen_data;
            }
        }

        // Get unit kompetensi, elemen, dan kriteria if sertifikasi data exists
        $unitKompetensiJudul = collect();
        $elemenJudul = collect();
        $kriteriaUnjukKerjaJudul = collect();

        if ($sertifikasiData && isset($sertifikasiData['judul']) && is_array($sertifikasiData['judul'])) {
            $judul = $sertifikasiData['judul'];
            
            if (!empty($judul)) {
                $unitKompetensiJudul = UnitKompetensiJudul::with(['unitKompetensi'])
                    ->whereIn('id', $judul)
                    ->get();

                $elemenJudul = ElemenJudul::with(['elemen'])
                    ->whereIn('unit_kompetensi_judul_id', $judul)
                    ->get();

                $kriteriaUnjukKerjaJudul = KriteriaUnjukKerjaJudul::with(['kriteriaUnjukKerja'])
                    ->whereIn('elemen_judul_id', $elemenJudul->pluck('id'))
                    ->get();
            }
        }


        return view('mahasiswa.detail-pendaftaran', compact(
            'pendaftaran',
            'profilData',
            'sertifikasiData', 
            'asesmenData',
            'unitKompetensiJudul',
            'elemenJudul',
            'kriteriaUnjukKerjaJudul'
        ));
    }
}
