<?php

namespace Database\Seeders;

use App\Models\ActionPlan;
use App\Models\Division;
use App\Models\Evp;
use App\Models\Meeting;
use App\Models\StrategicInitiative;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StrategicInitiativeSeeder extends Seeder
{
    /**
     * Data ini ditranskrip apa adanya dari
     * Action_Tracker_Inisiatif_Strategis_Niaga.xlsx (sheet "Action Tracker").
     * Tujuannya supaya prototype REDIS punya data nyata untuk ditunjukkan ke user,
     * bukan data dummy Lorem Ipsum.
     */
    public function run(): void
    {
        $meeting = Meeting::create([
            'judul' => 'Rapim Retail 25 Juni 2026',
            'tanggal' => '2026-06-25',
        ]);

        $initiatives = $this->data();

        foreach ($initiatives as $i => $ins) {
            $picEvp = $this->findOrCreateEvp($ins['pic_evp']);

            $initiative = StrategicInitiative::create([
                'kode' => $ins['kode'],
                'judul' => $ins['judul'],
                'pic_evp_id' => $picEvp->id,
            ]);

            foreach ($ins['evp_terkait'] as $evpNama) {
                $evp = $this->findOrCreateEvp($evpNama);
                $initiative->evpTerkait()->syncWithoutDetaching($evp->id);
            }

            // Semua 5 inisiatif contoh ini dibahas di meeting yang sama (sesuai draft UI)
            $initiative->meetings()->attach($meeting->id);

            foreach ($ins['action_plans'] as $ap) {
                $picUser = ! empty($ap['pic']) ? $this->findOrCreateUser($ap['pic']) : null;

                $plan = ActionPlan::create([
                    'strategic_initiative_id' => $initiative->id,
                    'urutan' => $ap['no'],
                    'nama_action_plan' => $ap['nama'],
                    'output' => $ap['output'] ?? null,
                    'pic_user_id' => $picUser?->id,
                    'pic_pendukung' => $ap['pic_pendukung'] ?? null,
                    'stakeholder_eksternal' => $ap['stakeholder'] ?? null,
                    'deadline' => $ap['deadline'] ?? null,
                    'bobot' => $ap['bobot'] ?? 0,
                    'progress_percent' => $ap['progress'] ?? 0,
                    'kendala' => $ap['kendala'] ?? null,
                    'dukungan_direktur' => $ap['dukungan'] ?? null,
                    'update_terakhir' => $ap['update_terakhir'] ?? null,
                    'tgl_update' => $ap['tgl_update'] ?? null,
                ]);

                foreach ($ap['divisi'] ?? [] as $kodeDivisi) {
                    $divisi = Division::firstOrCreate(['kode' => $kodeDivisi]);
                    $plan->divisiTerkait()->syncWithoutDetaching($divisi->id);
                }
            }
        }
    }

    private function findOrCreateEvp(string $nama): Evp
    {
        $kode = trim($nama);

        return Evp::firstOrCreate(['kode' => $kode], ['nama' => $kode]);
    }

    private function findOrCreateUser(string $nama): User
    {
        $nama = trim($nama);

        return User::firstOrCreate(
            ['name' => $nama],
            [
                'email' => Str::slug($nama).'@pln.co.id',
                'password' => bcrypt('password'),
                'role' => 'pic',
                'jabatan' => $nama,
            ]
        );
    }

    private function data(): array
    {
        return [
            [
                'kode' => 'INS-01',
                'judul' => 'Tuntas 720 Jam Nyala',
                'pic_evp' => 'EVP PPR',
                'evp_terkait' => ['EVP PPN', 'EVP CES'],
                'action_plans' => [
                    ['no' => 1, 'nama' => 'Monitoring data Pelanggan 720 Jam Nyala', 'output' => 'Data Pelanggan yang akan di sasar', 'pic' => 'VP PENGAMANAN PDPT', 'pic_pendukung' => 'VP LOLA DATA & SIS INFO GAN', 'deadline' => '2026-07-31', 'bobot' => 15, 'progress' => 100],
                    ['no' => 2, 'nama' => 'Desain produk untuk strategi penyelesaian pelanggan 720 jam nyala', 'output' => 'Paparan desain produk', 'pic_pendukung' => 'VP REN & STR PPN', 'deadline' => '2026-07-03', 'bobot' => 20, 'progress' => 90],
                    ['no' => 3, 'nama' => 'Penyusunan dokumen kebijakan produk dan dokumen pendukung GRC', 'output' => 'Draft Kebijakan Produk, Kajian Kelayakan Produk, Kajian Risiko dan Kajian Kepatuhan', 'pic_pendukung' => 'VP REN & STR PPN, VP KOMERS PROD AGA', 'deadline' => '2026-07-10', 'bobot' => 15, 'progress' => 30],
                    ['no' => 4, 'nama' => 'Review dokumen kebijakan produk oleh Divisi Pengulas GRC', 'output' => 'Nota Dinas kelengkapan kebijakan produk', 'pic_pendukung' => 'VP KOMERS PROD AGA', 'divisi' => ['DIV RKJ', 'DIV PKP', 'DIV MRF', 'DIV HLB'], 'deadline' => '2026-07-24', 'bobot' => 15, 'progress' => 0],
                    ['no' => 5, 'nama' => 'Sirkuler approval kebijakan produk ke Direksi terkait', 'output' => 'Dokumen kebijakan produk (Petunjuk Teknis Produk)', 'pic_pendukung' => 'VP KOMERS PROD AGA', 'divisi' => ['DIV MRF', 'DIV KEU'], 'deadline' => '2026-07-31', 'bobot' => 10, 'progress' => 0],
                    ['no' => 6, 'nama' => 'Change request di AP2T atau system terkait untuk implementasi produk', 'output' => 'SOP AP2T', 'pic_pendukung' => 'VP TEK & INKUBASI PROD AGA, VP LOLA DATA & SIS INFO GAN', 'divisi' => ['DIV MDG', 'DIV STI'], 'deadline' => '2026-08-31', 'bobot' => 15, 'progress' => 0],
                    ['no' => 7, 'nama' => 'Sosialisasi kebijakan produk secara nasional', 'output' => 'Knowledge Produk dan Marketing kit', 'pic' => 'VP PENGELOLAAN PENJUALAN', 'pic_pendukung' => 'VP KOMERS PROD AGA', 'divisi' => ['DIV OD', 'UID/UIW'], 'deadline' => '2026-08-31', 'bobot' => 10, 'progress' => 0],
                ],
            ],
            [
                'kode' => 'INS-02',
                'judul' => 'Agregator dan Orkestrator PLTS Atap',
                'pic_evp' => 'EVP PPR',
                'evp_terkait' => ['EVP PPN'],
                'action_plans' => [
                    ['no' => 1, 'nama' => 'Rapat pembahasan PLN for Business dengan Div MDG', 'output' => 'Contoh_Output 1', 'pic' => 'VP xx', 'deadline' => '2026-07-01', 'bobot' => 10, 'progress' => 100],
                    ['no' => 2, 'nama' => 'Rapat penentuan anak usaha yang menjalankan produk/jasa "PV Solution" di PLN for Business', 'output' => 'Contoh_Output 2', 'pic' => 'VP xx', 'deadline' => '2026-07-03', 'bobot' => 25, 'progress' => 0],
                    ['no' => 3, 'nama' => 'Div MDG & STI memproses mockup dan CR tampilan menu "PV Solution"', 'output' => 'Contoh_Output 3', 'pic' => 'VP xx', 'deadline' => '2026-07-17', 'bobot' => 25, 'progress' => 0],
                    ['no' => 4, 'nama' => 'Penetapan SLA untuk masing-masing produk dan jasa (Div PPR, BKI, Anak Perusahaan)', 'deadline' => '2026-07-24', 'bobot' => 10, 'progress' => 0],
                    ['no' => 5, 'nama' => 'Kesepakatan Split Revenue Aggregator dengan Anak Usaha Eksekutor (PLN Icon, Anak Perusahaan, BKI)', 'deadline' => '2026-07-24', 'bobot' => 10, 'progress' => 0],
                    ['no' => 6, 'nama' => 'Kesepakatan Split Revenue Aggregator dengan Anak Usaha Eksekutor - tahap 2', 'deadline' => '2026-07-24', 'bobot' => 10, 'progress' => 0],
                    ['no' => 7, 'nama' => 'UAT Menu "PV Solution" pada PLN For Business (Div STI, MDG)', 'deadline' => '2026-08-07', 'bobot' => 10, 'progress' => 0],
                    ['no' => 8, 'nama' => 'Launching Aplikasi PLN For Business (menu PV Solution)', 'bobot' => 0, 'progress' => 0],
                ],
            ],
            [
                'kode' => 'INS-03',
                'judul' => 'Skema Bisnis Pelanggan TM-TT',
                'pic_evp' => 'EVP PPN',
                'evp_terkait' => ['EVP PPR', 'EVP APR'],
                'action_plans' => [
                    ['no' => 1, 'nama' => 'Menyusun konsep, business model, dan ruang lingkup Power Acceleration Service (PAS)', 'output' => 'Konsep dan Business Model PAS', 'pic' => 'VP Renstra', 'deadline' => '2026-07-20', 'bobot' => 30, 'progress' => 100],
                    ['no' => 2, 'nama' => 'Menyusun mekanisme komersial (insentif BP, struktur tarif, appraisal, hibah aset, perpajakan)', 'output' => 'Skema Komersial PAS', 'pic' => 'VP Renstra', 'deadline' => '2026-07-25', 'bobot' => 40, 'progress' => 55],
                    ['no' => 3, 'nama' => 'Menyusun Peraturan Pelaksanaan, PJBTL, SLA, serta approval authority', 'output' => 'Peraturan Pelaksanaan dan Dokumen Implementasi PAS', 'deadline' => '2026-07-30', 'bobot' => 30, 'progress' => 30],
                    ['no' => 4, 'nama' => 'Menyusun standar teknis pembangunan infrastruktur oleh pelanggan, commissioning, O&M, dan asset handover', 'output' => 'Standar Teknis Implementasi PAS', 'divisi' => ['DIV OD', 'DIV MES'], 'bobot' => 0, 'progress' => 0],
                    ['no' => 5, 'nama' => 'Menyusun business process end-to-end dan integrasi sistem layanan', 'output' => 'Business Process dan Workflow PAS', 'bobot' => 0, 'progress' => 0],
                    ['no' => 6, 'nama' => 'Melaksanakan dan evaluasi pilot project pada calon pelanggan', 'output' => 'Hasil Pilot Project dan Rekomendasi Implementasi', 'pic' => 'VP Renstra', 'bobot' => 0, 'progress' => 0],
                    ['no' => 7, 'nama' => 'Mengajukan persetujuan Direksi atas implementasi PAS, finalisasi program dan sosialisasi', 'output' => 'Persetujuan Direksi dan Go Live PAS', 'pic' => 'VP Renstra', 'bobot' => 0, 'progress' => 0],
                ],
            ],
            [
                'kode' => 'INS-04',
                'judul' => 'Kerjasama Antar Wilus melalui Joint Venture',
                'pic_evp' => 'EVP APR',
                'evp_terkait' => ['EVP PPN', 'EVP NPS'],
                'action_plans' => [
                    ['no' => 1, 'nama' => 'Inventarisasi data Wilayah Usaha prioritas (pelanggan, aset, demand, stakeholder, ultimate plan, dll.)', 'output' => 'Database Wilayah Usaha', 'pic' => 'VP KI KEK dan Wilus', 'pic_pendukung' => 'VP Additional Demand', 'divisi' => ['DIV RSL', 'DIV OD', 'DIV LPT', 'DIV RST', 'UID'], 'stakeholder' => 'Dirjen Ketenagalistrikan', 'deadline' => '2026-07-10', 'bobot' => 5, 'progress' => 70, 'update_terakhir' => 'Sudah dilakukan Profiling Wilus Non PLN', 'tgl_update' => '2026-07-01'],
                    ['no' => 2, 'nama' => 'Profiling dan pemetaan potensi masing-masing Wilayah Usaha', 'output' => 'Profil Wilayah Usaha Prioritas', 'pic' => 'VP KI KEK dan Wilus', 'divisi' => ['DIV BKI', 'UID'], 'stakeholder' => 'Pengelola Wilayah Usaha', 'deadline' => '2026-07-30', 'bobot' => 5, 'progress' => 60, 'update_terakhir' => 'Sudah dilakukan Profiling Wilus Non PLN', 'tgl_update' => '2026-07-01'],
                    ['no' => 3, 'nama' => 'Identifikasi peluang sinergi antar Wilayah Usaha melalui skema JVC', 'output' => 'Daftar peluang kerja sama', 'pic' => 'VP KI KEK dan Wilus', 'divisi' => ['DIV BKI', 'DIV PFM', 'SH/AP', 'UID'], 'deadline' => '2026-07-30', 'bobot' => 5, 'progress' => 10, 'update_terakhir' => 'Sudah terdapat beberapa Wilus yang telah dibina komunikasi (contoh: Cikarang Listrindo)', 'tgl_update' => '2026-07-01'],
                    ['no' => 4, 'nama' => 'Kajian teknis sistem kelistrikan dan kebutuhan investasi', 'output' => 'Technical Assessment Report', 'pic' => 'VP KI KEK dan Wilus', 'pic_pendukung' => 'VP Additional Demand, VP Perencanaan Tarif', 'divisi' => ['DIV OD', 'DIV BKI', 'DIV RKJ'], 'stakeholder' => 'Konsultan Teknis (bila diperlukan)', 'deadline' => '2026-08-15', 'bobot' => 15, 'progress' => 0],
                    ['no' => 5, 'nama' => 'Kajian aspek bisnis, komersial, finansial, legal, dan regulasi', 'output' => 'Feasibility Study & Business Case', 'pic' => 'VP KI KEK dan Wilus', 'pic_pendukung' => 'VP Additional Demand, VP Perencanaan Tarif', 'divisi' => ['DIV BKI', 'DIV HLB', 'DIV MRF', 'DIV MRS', 'DIV RKJ'], 'deadline' => '2026-08-31', 'bobot' => 15, 'progress' => 0],
                    ['no' => 6, 'nama' => 'Penyusunan Business Model, Governance, dan skema kerja sama JVC', 'output' => 'Business Model & Governance JVC', 'pic' => 'VP KI KEK dan Wilus', 'pic_pendukung' => 'VP Additional Demand, VP Perencanaan Tarif', 'deadline' => '2026-09-10', 'bobot' => 30, 'progress' => 0],
                    ['no' => 7, 'nama' => 'Penetapan Wilayah Usaha prioritas sebagai pilot project', 'output' => 'Shortlist Pilot Project', 'pic' => 'VP KI KEK dan Wilus', 'pic_pendukung' => 'VP Additional Demand', 'deadline' => '2026-09-20', 'bobot' => 5, 'progress' => 0],
                    ['no' => 8, 'nama' => 'Penyusunan Roadmap Implementasi JVC', 'output' => 'Roadmap Implementasi', 'pic' => 'VP KI KEK dan Wilus', 'pic_pendukung' => 'VP Additional Demand', 'deadline' => '2026-09-30', 'bobot' => 10, 'progress' => 0],
                    ['no' => 9, 'nama' => 'Penyusunan rekomendasi Direksi dan Finalisasi Program', 'output' => 'Executive Recommendation & Final Report', 'pic' => 'VP KI KEK dan Wilus', 'pic_pendukung' => 'VP Additional Demand', 'stakeholder' => 'Calon Mitra JVC, SH/AP', 'deadline' => '2026-10-10', 'bobot' => 10, 'progress' => 0],
                ],
            ],
            [
                'kode' => 'INS-05',
                'judul' => 'PowerHub Data Center',
                'pic_evp' => 'EVP APR',
                'evp_terkait' => ['EVP PPR'],
                'action_plans' => [
                    ['no' => 1, 'nama' => 'Menyusun metodologi dan kriteria penilaian kandidat lokasi Power Hub', 'output' => 'Framework Assessment (Daya, Air, Tol, Konektivitas)', 'pic' => 'VP DIV APR', 'pic_pendukung' => 'VP Pengelolaan Penjualan', 'divisi' => ['DIV BKI'], 'deadline' => '2026-07-05', 'bobot' => 5, 'progress' => 80, 'update_terakhir' => 'Masih diperlukan penyesuaian Bobot Kategori, sudah dilakukan diskusi dengan DIV BKI', 'tgl_update' => '2026-07-01'],
                    ['no' => 2, 'nama' => 'Inventarisasi lokasi pembangkit PLN yang memiliki kapasitas daya dan lahan potensial', 'output' => 'Longlist kandidat lokasi Power Hub', 'pic' => 'VP DIV APR', 'pic_pendukung' => 'VP Pengelolaan Penjualan, VP Perencanaan dan Strategi Pengembangan Produk Niaga', 'divisi' => ['DIV MEB', 'DIV RSK'], 'deadline' => '2026-07-10', 'bobot' => 10, 'progress' => 60, 'kendala' => 'Data Pembangkit belum ada', 'update_terakhir' => 'Nodin permintaan Data ke RSK telah dikirimkan', 'tgl_update' => '2026-07-01'],
                    ['no' => 3, 'nama' => 'Pengumpulan data sistem kelistrikan (IBT, GI, Loading, Headroom, Reserve)', 'output' => 'Database Kesiapan Kelistrikan', 'pic' => 'VP DIV APR', 'pic_pendukung' => 'VP Pengelolaan Penjualan, VP Perencanaan dan Strategi Pengembangan Produk Niaga', 'divisi' => ['DIV MEB', 'DIV RSK'], 'deadline' => '2026-07-15', 'bobot' => 5, 'progress' => 60, 'kendala' => 'Data Pembangkit belum ada', 'update_terakhir' => 'Nodin permintaan Data ke RSK telah dikirimkan', 'tgl_update' => '2026-07-01'],
                    ['no' => 4, 'nama' => 'Pengumpulan data utilitas pendukung (Air Baku, Fiber Optic, Jalan Tol, Pelabuhan, Bandara)', 'output' => 'Database Infrastruktur Pendukung', 'pic' => 'VP DIV APR', 'deadline' => '2026-07-15', 'bobot' => 5, 'progress' => 100, 'update_terakhir' => 'Menggunakan data Open Data', 'tgl_update' => '2026-07-01'],
                    ['no' => 5, 'nama' => 'Pengembangan aplikasi/model scoring kandidat lokasi', 'output' => 'Dashboard Power Hub & Composite Score', 'pic' => 'VP DIV APR', 'divisi' => ['DIV BKI'], 'deadline' => '2026-07-15', 'bobot' => 5, 'progress' => 60, 'update_terakhir' => 'Aplikasi sederhana telah disiapkan, tinggal menyesuaikan kategori', 'tgl_update' => '2026-07-01'],
                    ['no' => 6, 'nama' => 'Penilaian dan pemeringkatan seluruh kandidat lokasi', 'output' => 'Shortlist Lokasi Prioritas Power Hub', 'pic' => 'VP DIV APR', 'bobot' => 5, 'progress' => 0],
                    ['no' => 7, 'nama' => 'Penyusunan Business Model dan Governance Power Hub', 'output' => 'Business Model, Governance, Revenue Model', 'pic' => 'VP DIV APR', 'pic_pendukung' => 'VP Pengelolaan Penjualan, VP Perencanaan dan Strategi Pengembangan Produk Niaga', 'divisi' => ['DIV BKI', 'DIV MRF', 'DIV MRS'], 'bobot' => 15, 'progress' => 0],
                    ['no' => 8, 'nama' => 'Penyusunan skema kolaborasi PLN Group', 'output' => 'Draft MoC / Collaboration Scheme', 'pic' => 'VP DIV APR', 'pic_pendukung' => 'VP Pengelolaan Penjualan, VP Perencanaan dan Strategi Pengembangan Produk Niaga', 'divisi' => ['DIV BKI', 'DIV PFM', 'SH/AP'], 'bobot' => 10, 'progress' => 0],
                    ['no' => 9, 'nama' => 'Penyusunan Investment Showcase', 'output' => 'Investment Deck, Fact Sheet, Profil Lokasi', 'pic' => 'VP DIV APR', 'pic_pendukung' => 'VP Pengelolaan Penjualan, VP Perencanaan dan Strategi Pengembangan Produk Niaga', 'divisi' => ['DIV PFM', 'SH/AP'], 'bobot' => 10, 'progress' => 0],
                    ['no' => 10, 'nama' => 'Site Visit lokasi kandidat bersama calon investor', 'output' => 'Site Visit Report', 'pic' => 'VP DIV APR', 'pic_pendukung' => 'VP Pengelolaan Penjualan', 'divisi' => ['UID'], 'bobot' => 10, 'progress' => 0],
                    ['no' => 11, 'nama' => 'Finalisasi rekomendasi lokasi Power Hub', 'output' => 'Daftar Final Lokasi Power Hub', 'pic' => 'VP DIV APR', 'pic_pendukung' => 'VP Pengelolaan Penjualan, VP Perencanaan dan Strategi Pengembangan Produk Niaga', 'divisi' => ['DIV BKI'], 'bobot' => 10, 'progress' => 0],
                    ['no' => 12, 'nama' => 'Penyampaian rekomendasi kepada Direksi', 'output' => 'Persetujuan Program Power Hub', 'pic' => 'VP DIV APR', 'pic_pendukung' => 'VP Pengelolaan Penjualan, VP Perencanaan dan Strategi Pengembangan Produk Niaga', 'bobot' => 10, 'progress' => 0],
                ],
            ],
        ];
    }
}
