<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pendaftaran;
use App\Models\SkemaSertifikasi;
use App\Models\JadwalUji;
use App\Models\Dokumen;
use App\Models\UnitKompetensiJudul;
use App\Models\ElemenJudul;
use App\Models\KriteriaUnjukKerjaJudul;

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
            'sumber_anggaran' => 'required|string|max:255',
            'pemberi_anggaran' => 'required|string|max:255',
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

        // Store in session for multi-step process
        session([
            'pendaftaran_data' => [
                'skema_sertifikasi_id' => $request->skema_sertifikasi_id,
                'jadwal_uji_id' => $request->jadwal_uji_id,
                'sumber_anggaran' => $request->sumber_anggaran,
                'pemberi_anggaran' => $request->pemberi_anggaran,
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

        return view('mahasiswa.pendaftaran-step2');
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
        $pendaftaranData['profil_data'] = $request->all();
        
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
        $skemaSertifikasi = SkemaSertifikasi::find($data['skema_sertifikasi_id']);
        if (!$skemaSertifikasi) {
            return redirect()->route('mahasiswa.pendaftaran.step1')
                ->with('error', 'Skema sertifikasi tidak ditemukan.');
        }

        // Get judul from skema sertifikasi
        $selectedJudul = $skemaSertifikasi->nama_skema;

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

        return view('mahasiswa.pendaftaran-step3', compact('skemaOptions', 'tujuanOptions', 'unitKompetensiData', 'selectedJudul'));
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
        
        // Debug: Log session data
        Log::info('Session data in step 4:', $data);
        Log::info('Bukti files:', $buktiFiles);
        Log::info('Bukti admin files:', $buktiAdminFiles);

        return view('mahasiswa.pendaftaran-step4', compact(
            'data', 'unitKompetensiData', 'elemenData', 'kriteriaData', 'buktiFiles', 'buktiAdminFiles', 'nomorSkema', 'selectedJudul'
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
        ]);

        // Update session data with step 4 completion
        $data['step'] = 4;
        $data['asesmen_mandiri'] = $request->input('kriteria', []);
        session(['pendaftaran_data' => $data]);

        return redirect()->route('mahasiswa.pendaftaran.step5')
            ->with('success', 'Asesmen mandiri berhasil disimpan.');
    }

    public function storePendaftaranStep3(Request $request)
    {
        $request->validate([
            'skema' => 'required|in:KKNI,Okupasi,Klaster',
            'judul' => 'required|string|max:255',
            'tujuan_asesmen' => 'required|string|max:255',
            'bukti_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'bukti_admin_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
        ];
        
        // Debug: Log uploaded files
        Log::info('Uploaded bukti files:', $buktiFiles);
        Log::info('Uploaded bukti admin files:', $buktiAdminFiles);
        Log::info('Session data after step 3:', $data);
        
        session(['pendaftaran_data' => $data]);

        // Next would be step 4
        return redirect()->route('mahasiswa.pendaftaran.step4');
    }

    public function storePendaftaran(Request $request)
    {
        $request->validate([
            'skema_sertifikasi_id' => 'required|exists:skema_sertifikasi,id',
            'jadwal_uji_id' => 'required|exists:jadwal_uji,id',
        ]);

        $user = Auth::user();
        $noPendaftaran = 'REG' . date('Ymd') . str_pad(Pendaftaran::count() + 1, 4, '0', STR_PAD_LEFT);

        Pendaftaran::create([
            'user_id' => $user->id,
            'skema_sertifikasi_id' => $request->skema_sertifikasi_id,
            'jadwal_uji_id' => $request->jadwal_uji_id,
            'no_pendaftaran' => $noPendaftaran,
            'status' => 'pending',
            'tanggal_pendaftaran' => now(),
        ]);

        // Update kuota terisi
        $jadwal = JadwalUji::find($request->jadwal_uji_id);
        $jadwal->increment('kuota_terisi');

        return redirect()->route('mahasiswa.pendaftaran')
            ->with('success', 'Pendaftaran berhasil diajukan');
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
}
