<?php

namespace App\Http\Controllers;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan halaman Dasbor Tata Kelola & Pengawasan Ekosistem.
     *
     * Data di bawah masih berupa array (dummy) supaya mudah dibaca.
     * Nanti tinggal diganti dengan query Eloquent, misalnya:
     *   $curations = Product::where('status', 'pending')->get();
     */
    public function index()
    {
        // 5 kartu statistik paling atas
        $stats = [
            [
                'label' => 'VOLUME TRANSAKSI', 'chip' => '+18.4% YoY',
                'value' => 'Rp 4.285.900.000', 'note' => 'Nilai kriya terbit per Maret 2025',
                'icon' => 'trend', 'style' => '',
            ],
            [
                'label' => 'HAK BERSIH ARTISAN', 'chip' => '89.4% Disalurkan',
                'value' => 'Rp 3.831.594.600', 'note' => 'Rekening perajin tanpa potongan makelar',
                'icon' => 'shield-check', 'style' => 'is-dark',
            ],
            [
                'label' => 'SANGGAR BINAAN', 'chip' => '28 Provinsi',
                'value' => '148 Sanggar', 'note' => '100% lulus audit keaslian kriya',
                'icon' => 'users', 'style' => '',
            ],
            [
                'label' => 'MAHAKARYA TERBIT', 'chip' => 'QC 92.5%',
                'value' => '1.420 Karya', 'note' => 'Tersertifikasi Paspor Digital Kriya',
                'icon' => 'tag', 'style' => '',
            ],
            [
                'label' => 'KEPATUHAN SDG 8', 'chip' => 'Grade A+',
                'value' => '98.2% Sesuai', 'note' => 'Standar Upah Layak & Bahan Ramah Hayati',
                'icon' => 'leaf', 'style' => 'is-alert',
            ],
        ];

        // Antrean kurasi & validasi paspor kriya
        $curations = [
            [
                'name' => 'Songket Silungkang Motif Bungo Duo', 'studio' => 'Sanggar Siti Rahma',
                'category' => 'Wastra Tenun', 'origin' => 'Sawahlunto, Sumbar',
                'verify_icon' => 'flask', 'verify_ok' => false,
                'verify_title' => 'Uji Benang Emas Terjadwal', 'verify_detail' => 'Lab Uji Tekstil Padang',
                'wage' => 'Rp 4.750.000', 'wage_note' => '91.5% Hak Penenun',
                'action' => 'Minta Sampel', 'action_style' => 'ad-btn-soft',
            ],
            [
                'name' => 'Patung Asmat Kayu Besi Mbis', 'studio' => 'Sanggar Otsjanep',
                'category' => 'Ukiran Kayu', 'origin' => 'Asmat, Papua Selatan',
                'verify_icon' => 'check-circle', 'verify_ok' => true,
                'verify_title' => 'SVLK Legal No. 448/KLHK', 'verify_detail' => 'Kayu Ulin Terbarukan Bersertifikat',
                'wage' => 'Rp 8.500.000', 'wage_note' => '88.0% Hak Pemahat',
                'action' => 'Setujui Terbitkan', 'action_style' => 'ad-btn-terracotta',
            ],
            [
                'name' => 'Guci Terakota Abu Vulkanik', 'studio' => 'Padepokan Kasongan',
                'category' => 'Gerabah Alami', 'origin' => 'Bantul, D.I. Yogyakarta',
                'verify_icon' => 'utensils', 'verify_ok' => true,
                'verify_title' => 'Food-Grade SNI Lolos', 'verify_detail' => 'Bebas Timbal & Glazir Kimia',
                'wage' => 'Rp 1.850.000', 'wage_note' => '90.2% Hak Perajin',
                'action' => 'Setujui Terbitkan', 'action_style' => 'ad-btn-terracotta',
            ],
        ];

        // Sebaran sentra binaan ('progress' = lebar bar dalam persen)
        $regions = [
            ['name' => 'Jawa Tengah', 'studios' => 41, 'progress' => 82,
             'craft' => 'Batik Tulis & Ukir Jepara', 'badge' => '100% Upah Layak'],
            ['name' => 'Nusa Tenggara Timur', 'studios' => 32, 'progress' => 64,
             'craft' => 'Tenun Ikat Pewarna Alami Sumba & Alor', 'badge' => 'Pewarna 100% Organik'],
            ['name' => 'D.I. Yogyakarta', 'studios' => 25, 'progress' => 50,
             'craft' => 'Gerabah Kasongan & Kulit Tatah Sungging', 'badge' => '98% Lolos QC'],
            ['name' => 'Sumatera Barat', 'studios' => 18, 'progress' => 36,
             'craft' => 'Songket Silungkang & Sulaman Pandai Sikek', 'badge' => 'Warisan Budaya Hidup'],
            ['name' => 'Papua & Pegunungan', 'studios' => 14, 'progress' => 28,
             'craft' => 'Noken Serat Kayu Mahkota & Ukiran Asmat', 'badge' => '100% Hak Ulayat'],
        ];

        // Pembagian tiap Rp 100.000 pembelanjaan ('color' = warna segmen bar)
        $escrow = [
            ['percent' => 89.4, 'title' => 'Porsi Langsung Pengrajin',
             'note' => 'Transfer instan via BPD / Rekening Sanggar Desa', 'color' => '#0f4a3a'],
            ['percent' => 6.2, 'title' => 'Packaging & Cold-Chain Kriya',
             'note' => 'Besek bambu, silika alami, & asuransi kurir terlatih', 'color' => '#b4451f'],
            ['percent' => 4.4, 'title' => 'Audit Kepatuhan SDG Lapangan',
             'note' => 'Biaya inspektur laboratorium & sertifikasi SVLK', 'color' => '#2a9d7c'],
            ['percent' => 0.0, 'title' => 'Biaya Calo / Perantara',
             'note' => 'Terkunci secara protokol fair-trade', 'color' => '#8a948f'],
        ];

        // Log audit kurator lapangan ('tone' = warna titik: '', 'is-green', 'is-gray')
        $auditLogs = [
            ['tone' => '', 'time' => '14m lalu', 'title' => 'Verifikasi Pewarna Indigofera Selesai',
             'text' => 'Kurator Budi Santoso menyelesaikan uji fermentasi daun tarum di Sanggar Giriloyo, Imogiri. Kadar sintetis 0%.'],
            ['tone' => '', 'time' => '1 jam lalu', 'title' => 'Penyaluran Beasiswa ATBM',
             'text' => 'Dana apresiasi regenerasi penenun disalurkan ke 12 siswa magang sanggar di Desa Prailiu, Sumba Timur.'],
            ['tone' => 'is-green', 'time' => '3j lalu', 'title' => 'Sertifikasi SVLK Sanggar Otsjanep',
             'text' => 'Dokumen legalitas kayu ulin gelondong adat dinyatakan valid oleh auditor independen KLHK.'],
            ['tone' => 'is-gray', 'time' => 'Kemarin', 'title' => 'Peringatan QC Kemasan Bambu',
             'text' => 'Permintaan perbaikan peredam goncangan alami untuk batch tembikar Kasongan tujuan ekspor Belgia.'],
        ];

        return view('admin.dashboard', compact('stats', 'curations', 'regions', 'escrow', 'auditLogs'));
    }
}