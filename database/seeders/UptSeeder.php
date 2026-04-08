<?php

namespace Database\Seeders;

use App\Models\Upt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UptSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Upt::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $uptList = [
            // ── Kantor Pusat & Balai Nasional (10xx) ─────────────────────────────────
            ['kode' => '1000', 'nama' => 'Kantor Pusat', 'nama_satpel' => 'Kantor Pusat', 'wilayah' => 'Bogor'],
            ['kode' => '1001', 'nama' => 'Balai Besar Uji Standar Karantina Hewan, Ikan, dan Tumbuhan', 'alias' => 'BBUSKHIT', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi DKI Jakarta'],
            ['kode' => '1002', 'nama' => 'Balai Uji Terap Teknik dan Metode Karantina Hewan, Ikan, dan Tumbuhan', 'alias' => 'BUTTMKHIT', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi DKI Jakarta'],

            // ── Aceh (11xx) ───────────────────────────────────────────────────────────
            ['kode' => '1100', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Aceh', 'alias' => 'BKHIT Aceh', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Nanggroe Aceh Darussalam'],
            ['kode' => '1101', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Aceh', 'nama_satpel' => 'Bandara Sultan Iskandar Muda', 'wilayah' => 'Aceh Besar'],
            ['kode' => '1102', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Aceh', 'nama_satpel' => 'Pelabuhan Laut Sabang', 'wilayah' => 'Provinsi Nanggroe Aceh Darussalam'],
            ['kode' => '1103', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Aceh', 'nama_satpel' => 'Pelabuhan Laut Lhokseumawe', 'wilayah' => 'Provinsi Nanggroe Aceh Darussalam'],
            ['kode' => '1104', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Aceh', 'nama_satpel' => 'Pelabuhan Laut Meulaboh', 'wilayah' => 'Provinsi Nanggroe Aceh Darussalam'],
            ['kode' => '1105', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Aceh', 'nama_satpel' => 'Pelabuhan Laut Sinabang', 'wilayah' => 'Provinsi Nanggroe Aceh Darussalam'],
            ['kode' => '1106', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Aceh', 'nama_satpel' => 'Pelabuhan Laut Kuala Langsa', 'wilayah' => 'Provinsi Nanggroe Aceh Darussalam'],
            ['kode' => '1107', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Aceh', 'nama_satpel' => 'Pelabuhan Laut Simeuleu', 'wilayah' => 'Provinsi Nanggroe Aceh Darussalam'],

            // ── Sumatera Utara (12xx) ─────────────────────────────────────────────────
            ['kode' => '1200', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Sumatera Utara', 'alias' => 'BBKHIT Sumut', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Sumatera Utara'],
            ['kode' => '1201', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Sumatera Utara', 'nama_satpel' => 'Bandara Kualanamu', 'wilayah' => 'Provinsi Sumatera Utara'],
            ['kode' => '1202', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Sumatera Utara', 'nama_satpel' => 'Bandara Sisingamangaraja', 'wilayah' => 'Provinsi Sumatera Utara'],
            ['kode' => '1203', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Sumatera Utara', 'nama_satpel' => 'Pelabuhan Belawan', 'wilayah' => 'Provinsi Sumatera Utara'],
            ['kode' => '1204', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Sumatera Utara', 'nama_satpel' => 'Pelabuhan Tanjung Balai Asahan', 'wilayah' => 'Provinsi Sumatera Utara'],
            ['kode' => '1205', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Sumatera Utara', 'nama_satpel' => 'Pelabuhan Laut Sibolga', 'wilayah' => 'Provinsi Sumatera Utara'],

            // ── Sumatera Barat (13xx) ─────────────────────────────────────────────────
            ['kode' => '1300', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sumatera Barat', 'alias' => 'BKHIT Sumbar', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Sumatera Barat'],
            ['kode' => '1301', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sumatera Barat', 'nama_satpel' => 'Bandara Minangkabau', 'wilayah' => 'Provinsi Sumatera Barat'],
            ['kode' => '1302', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sumatera Barat', 'nama_satpel' => 'Pelabuhan Laut Mentawai', 'wilayah' => 'Provinsi Sumatera Barat'],

            // ── Riau (14xx) ───────────────────────────────────────────────────────────
            ['kode' => '1400', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Riau', 'alias' => 'BKHIT Riau', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Riau'],
            ['kode' => '1401', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Riau', 'nama_satpel' => 'Bandara Sultan Syarif Kasim II', 'wilayah' => 'Provinsi Riau'],
            ['kode' => '1402', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Riau', 'nama_satpel' => 'Pelabuhan Laut Dumai', 'wilayah' => 'Provinsi Riau'],
            ['kode' => '1403', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Riau', 'nama_satpel' => 'Pelabuhan Laut Bengkalis', 'wilayah' => 'Provinsi Riau'],
            ['kode' => '1404', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Riau', 'nama_satpel' => 'Pelabuhan Laut Tembilahan', 'wilayah' => 'Provinsi Riau'],
            ['kode' => '1405', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Riau', 'nama_satpel' => 'Pelabuhan Laut Selat Panjang', 'wilayah' => 'Provinsi Riau'],
            ['kode' => '1406', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Riau', 'nama_satpel' => 'Pelabuhan Laut Sungai Guntung', 'wilayah' => 'Provinsi Riau'],
            ['kode' => '1407', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Riau', 'nama_satpel' => 'Pelabuhan Laut Tanjung Buton', 'wilayah' => 'Provinsi Riau'],

            // ── Jambi (15xx) ──────────────────────────────────────────────────────────
            ['kode' => '1500', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jambi', 'alias' => 'BKHIT Jambi', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Jambi'],
            ['kode' => '1501', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jambi', 'nama_satpel' => 'Bandara Sultan Thaha', 'wilayah' => 'Provinsi Jambi'],
            ['kode' => '1502', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jambi', 'nama_satpel' => 'Pelabuhan Laut Kuala Tungkal', 'wilayah' => 'Provinsi Jambi'],

            // ── Sumatera Selatan (16xx) ───────────────────────────────────────────────
            ['kode' => '1600', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sumatera Selatan', 'alias' => 'BKHIT Sumsel', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Sumatera Selatan'],
            ['kode' => '1601', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sumatera Selatan', 'nama_satpel' => 'Bandara Sultan Mahmud Badaruddin II Palembang', 'wilayah' => 'Provinsi Sumatera Selatan'],
            ['kode' => '1602', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sumatera Selatan', 'nama_satpel' => 'Pelabuhan Tanjung Api-api', 'wilayah' => 'Provinsi Sumatera Selatan'],

            // ── Bengkulu (17xx) ───────────────────────────────────────────────────────
            ['kode' => '1700', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Bengkulu', 'alias' => 'BKHIT Bengkulu', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Bengkulu'],
            ['kode' => '1701', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Bengkulu', 'nama_satpel' => 'Bandara Fatmawati', 'wilayah' => 'Provinsi Bengkulu'],

            // ── Lampung (18xx) ────────────────────────────────────────────────────────
            ['kode' => '1800', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Lampung', 'alias' => 'BKHIT Lampung', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Lampung'],
            ['kode' => '1801', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Lampung', 'nama_satpel' => 'Bandara Raden Inten II', 'wilayah' => 'Provinsi Lampung'],
            ['kode' => '1802', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Lampung', 'nama_satpel' => 'Pelabuhan Penyeberangan Bakauheni', 'wilayah' => 'Provinsi Lampung'],

            // ── Kepulauan Bangka Belitung (19xx) ──────────────────────────────────────
            ['kode' => '1900', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Bangka Belitung', 'alias' => 'BKHIT Babel', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Kepulauan Bangka Belitung'],
            ['kode' => '1901', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Bangka Belitung', 'nama_satpel' => 'Bandara H.A.S Hanandjoeddin', 'wilayah' => 'Provinsi Kepulauan Bangka Belitung'],
            ['kode' => '1902', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Bangka Belitung', 'nama_satpel' => 'Bandara Depati Amir', 'wilayah' => 'Provinsi Kepulauan Bangka Belitung'],
            ['kode' => '1903', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Bangka Belitung', 'nama_satpel' => 'Pelabuhan Muntok', 'wilayah' => 'Provinsi Kepulauan Bangka Belitung'],
            ['kode' => '1904', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Bangka Belitung', 'nama_satpel' => 'Pelabuhan Laut Pangkal Balam', 'wilayah' => 'Provinsi Kepulauan Bangka Belitung'],

            // ── Kepulauan Riau (21xx) ─────────────────────────────────────────────────
            ['kode' => '2100', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Riau', 'alias' => 'BKHIT Kepri', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Kepulauan Riau'],
            ['kode' => '2101', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Riau', 'nama_satpel' => 'Bandara Hang Nadim', 'wilayah' => 'Provinsi Kepulauan Riau'],
            ['kode' => '2102', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Riau', 'nama_satpel' => 'Bandara Raja Haji Fisabilillah', 'wilayah' => 'Provinsi Kepulauan Riau'],
            ['kode' => '2103', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Riau', 'nama_satpel' => 'Pelabuhan Laut Telaga Punggur', 'wilayah' => 'Provinsi Kepulauan Riau'],
            ['kode' => '2104', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Riau', 'nama_satpel' => 'Pelabuhan Tanjung Uban', 'wilayah' => 'Provinsi Kepulauan Riau'],
            ['kode' => '2105', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Riau', 'nama_satpel' => 'Pelabuhan Laut Kijang', 'wilayah' => 'Provinsi Kepulauan Riau'],
            ['kode' => '2106', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Riau', 'nama_satpel' => 'Pelabuhan Tanjung Balai Karimun', 'wilayah' => 'Provinsi Kepulauan Riau'],
            ['kode' => '2107', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Riau', 'nama_satpel' => 'Pelabuhan Tanjung Batu', 'wilayah' => 'Provinsi Kepulauan Riau'],
            ['kode' => '2108', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Riau', 'nama_satpel' => 'Pelabuhan Laut Moro', 'wilayah' => 'Provinsi Kepulauan Riau'],
            ['kode' => '2109', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kepulauan Riau', 'nama_satpel' => 'Pelabuhan Laut Natuna', 'wilayah' => 'Provinsi Kepulauan Riau'],

            // ── DKI Jakarta (31xx) ────────────────────────────────────────────────────
            ['kode' => '3100', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan DKI Jakarta', 'alias' => 'BBKHIT DKI Jakarta', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi DKI Jakarta'],
            ['kode' => '3101', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan DKI Jakarta', 'nama_satpel' => 'Pelabuhan Laut Tanjung Priok', 'wilayah' => 'Provinsi DKI Jakarta'],
            ['kode' => '3102', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan DKI Jakarta', 'nama_satpel' => 'Pelabuhan Sunda Kelapa', 'wilayah' => 'Pelabuhan Sunda Kelapa'],
            ['kode' => '3103', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan DKI Jakarta', 'nama_satpel' => 'Bandara Halim Perdana Kusuma', 'wilayah' => 'Provinsi DKI Jakarta'],

            // ── Jawa Barat (32xx) ─────────────────────────────────────────────────────
            ['kode' => '3200', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Barat', 'alias' => 'BKHIT Jabar', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Jawa Barat'],
            ['kode' => '3201', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Barat', 'nama_satpel' => 'Bandara Kertajati', 'wilayah' => 'Provinsi Jawa Barat'],
            ['kode' => '3202', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Barat', 'nama_satpel' => 'Pelabuhan Laut Cirebon', 'wilayah' => 'Provinsi Jawa Barat'],

            // ── Jawa Tengah (33xx) ────────────────────────────────────────────────────
            ['kode' => '3300', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Tengah', 'alias' => 'BKHIT Jateng', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Jawa Tengah'],
            ['kode' => '3301', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Tengah', 'nama_satpel' => 'Bandara Adi Sumarmo', 'wilayah' => 'Provinsi Jawa Tengah'],
            ['kode' => '3302', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Tengah', 'nama_satpel' => 'Bandara Ahmad Yani', 'wilayah' => 'Provinsi Jawa Tengah'],
            ['kode' => '3303', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Tengah', 'nama_satpel' => 'Pelabuhan Laut Tanjung Intan', 'wilayah' => 'Provinsi Jawa Tengah'],
            ['kode' => '3304', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Tengah', 'nama_satpel' => 'Pelabuhan Laut Tegal', 'wilayah' => 'Provinsi Jawa Tengah'],

            // ── DI Yogyakarta (34xx) ──────────────────────────────────────────────────
            ['kode' => '3400', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Daerah Istimewa Yogyakarta', 'alias' => 'BKHIT DIY', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi DI Yogyakarta'],
            ['kode' => '3401', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Daerah Istimewa Yogyakarta', 'nama_satpel' => 'Bandara Yogyakarta International Airport (YIA)', 'wilayah' => 'Provinsi DI Yogyakarta'],
            ['kode' => '3402', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Daerah Istimewa Yogyakarta', 'nama_satpel' => 'Bandara Adi Sucipto', 'wilayah' => 'Provinsi DI Yogyakarta'],

            // ── Jawa Timur (35xx) ─────────────────────────────────────────────────────
            ['kode' => '3500', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Timur', 'alias' => 'BKHIT Jatim', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Jawa Timur'],
            ['kode' => '3501', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Timur', 'nama_satpel' => 'Bandara Juanda', 'wilayah' => 'Provinsi Jawa Timur'],
            ['kode' => '3502', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Timur', 'nama_satpel' => 'Pelabuhan Penyeberangan Ketapang', 'wilayah' => 'Provinsi Jawa Timur'],
            ['kode' => '3503', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Timur', 'nama_satpel' => 'Bandara Abdul Rahman Saleh', 'wilayah' => 'Provinsi Jawa Timur'],
            ['kode' => '3504', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Timur', 'nama_satpel' => 'Pelabuhan Laut Tanjung Perak', 'wilayah' => 'Provinsi Jawa Timur'],
            ['kode' => '3505', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Timur', 'nama_satpel' => 'Pelabuhan Pulau Kangean', 'wilayah' => 'Provinsi Jawa Timur'],
            ['kode' => '3506', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Jawa Timur', 'nama_satpel' => 'Bangkalan', 'wilayah' => 'Provinsi Jawa Timur'],

            // ── Banten (36xx) ─────────────────────────────────────────────────────────
            ['kode' => '3600', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Banten', 'alias' => 'BKHIT Banten', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Banten'],
            ['kode' => '3601', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Banten', 'nama_satpel' => 'Bandara Soekarno Hatta', 'wilayah' => 'Provinsi Banten'],
            ['kode' => '3602', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Banten', 'nama_satpel' => 'Pelabuhan Penyeberangan Merak', 'wilayah' => 'Provinsi Banten'],

            // ── Bali (51xx) ───────────────────────────────────────────────────────────
            ['kode' => '5100', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Bali', 'alias' => 'BBKHIT Bali', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Bali'],
            ['kode' => '5101', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Bali', 'nama_satpel' => 'Bandara I Gusti Ngurah Rai', 'wilayah' => 'Provinsi Bali'],
            ['kode' => '5102', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Bali', 'nama_satpel' => 'Pelabuhan Laut Celukan Bawang', 'wilayah' => 'Provinsi Bali'],
            ['kode' => '5103', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Bali', 'nama_satpel' => 'Pelabuhan Penyeberangan Padang Baai', 'wilayah' => 'Provinsi Bali'],
            ['kode' => '5104', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Bali', 'nama_satpel' => 'Pelabuhan Penyeberangan Gilimanuk', 'wilayah' => 'Provinsi Bali'],

            // ── Nusa Tenggara Barat (52xx) ────────────────────────────────────────────
            ['kode' => '5200', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Barat', 'alias' => 'BKHIT NTB', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Nusa Tenggara Barat'],
            ['kode' => '5201', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Barat', 'nama_satpel' => 'Bandara Internasional Lombok', 'wilayah' => 'Provinsi Nusa Tenggara Barat'],
            ['kode' => '5202', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Barat', 'nama_satpel' => 'Pelabuhan Laut Lembar', 'wilayah' => 'Provinsi Nusa Tenggara Barat'],
            ['kode' => '5203', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Barat', 'nama_satpel' => 'Pelabuhan Laut Bima', 'wilayah' => 'Provinsi Nusa Tenggara Barat'],
            ['kode' => '5204', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Barat', 'nama_satpel' => 'Pelabuhan Penyeberangan Sape', 'wilayah' => 'Provinsi Nusa Tenggara Barat'],
            ['kode' => '5205', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Barat', 'nama_satpel' => 'Pelabuhan Laut Badas', 'wilayah' => 'Provinsi Nusa Tenggara Barat'],
            ['kode' => '5206', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Barat', 'nama_satpel' => 'Pelabuhan Laut Kayangan', 'wilayah' => 'Provinsi Nusa Tenggara Barat'],
            ['kode' => '5207', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Barat', 'nama_satpel' => 'Pelabuhan Laut Poto Tano', 'wilayah' => 'Provinsi Nusa Tenggara Barat'],

            // ── Nusa Tenggara Timur (53xx) ────────────────────────────────────────────
            ['kode' => '5300', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'alias' => 'BKHIT NTT', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],
            ['kode' => '5301', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'nama_satpel' => 'Bandara El Tari', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],
            ['kode' => '5302', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'nama_satpel' => 'Pelabuhan Laut Ende', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],
            ['kode' => '5303', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'nama_satpel' => 'Pelabuhan Laut Reo', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],
            ['kode' => '5304', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'nama_satpel' => 'Pelabuhan Laut Waingapu', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],
            ['kode' => '5305', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'nama_satpel' => 'Pelabuhan Laut Sabu', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],
            ['kode' => '5306', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'nama_satpel' => 'Pelabuhan Laut Rote', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],
            ['kode' => '5307', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'nama_satpel' => 'Pelabuhan Laut Atapupu', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],
            ['kode' => '5308', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'nama_satpel' => 'Pelabuhan Laut Alor', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],
            ['kode' => '5309', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'nama_satpel' => 'Pelabuhan Laut Waikelo', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],
            ['kode' => '5310', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'nama_satpel' => 'Pelabuhan Laut Labuan Bajo', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],
            ['kode' => '5311', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'nama_satpel' => 'Pelabuhan Laut Maumere', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],
            ['kode' => '5312', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'nama_satpel' => 'PLBN Wini', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],
            ['kode' => '5313', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'nama_satpel' => 'PLBN Motaain', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],
            ['kode' => '5314', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Nusa Tenggara Timur', 'nama_satpel' => 'PLBN Motamasin', 'wilayah' => 'Provinsi Nusa Tenggara Timur'],

            // ── Kalimantan Barat (61xx) ───────────────────────────────────────────────
            ['kode' => '6100', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Barat', 'alias' => 'BKHIT Kalbar', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Kalimantan Barat'],
            ['kode' => '6101', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Barat', 'nama_satpel' => 'Bandara Supadio', 'wilayah' => 'Provinsi Kalimantan Barat'],
            ['kode' => '6102', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Barat', 'nama_satpel' => 'Bandara Rahadi Usman', 'wilayah' => 'Provinsi Kalimantan Barat'],
            ['kode' => '6103', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Barat', 'nama_satpel' => 'PLBN Entikong', 'wilayah' => 'Provinsi Kalimantan Barat'],
            ['kode' => '6104', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Barat', 'nama_satpel' => 'PLBN Aruk', 'wilayah' => 'Provinsi Kalimantan Barat'],
            ['kode' => '6105', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Barat', 'nama_satpel' => 'PLBN Nanga Badau', 'wilayah' => 'Provinsi Kalimantan Barat'],
            ['kode' => '6106', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Barat', 'nama_satpel' => 'PLBN Jagoi Babang', 'wilayah' => 'Provinsi Kalimantan Barat'],

            // ── Kalimantan Tengah (62xx) ──────────────────────────────────────────────
            ['kode' => '6200', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Tengah', 'alias' => 'BKHIT Kalteng', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Kalimantan Tengah'],
            ['kode' => '6201', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Tengah', 'nama_satpel' => 'Bandara Tjilik Riwut', 'wilayah' => 'Provinsi Kalimantan Tengah'],
            ['kode' => '6202', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Tengah', 'nama_satpel' => 'Pelabuhan Laut Pangkalan Bun', 'wilayah' => 'Provinsi Kalimantan Tengah'],
            ['kode' => '6203', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Tengah', 'nama_satpel' => 'Pelabuhan Laut Sampit', 'wilayah' => 'Provinsi Kalimantan Tengah'],

            // ── Kalimantan Selatan (63xx) ─────────────────────────────────────────────
            ['kode' => '6300', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Selatan', 'alias' => 'BKHIT Kalsel', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Kalimantan Selatan'],
            ['kode' => '6301', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Selatan', 'nama_satpel' => 'Bandara Syamsudin Noor', 'wilayah' => 'Provinsi Kalimantan Selatan'],
            ['kode' => '6302', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Selatan', 'nama_satpel' => 'Pelabuhan Laut Batu Licin', 'wilayah' => 'Provinsi Kalimantan Selatan'],
            ['kode' => '6303', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Selatan', 'nama_satpel' => 'Pelabuhan Laut Kotabaru', 'wilayah' => 'Provinsi Kalimantan Selatan'],

            // ── Kalimantan Timur (64xx) ───────────────────────────────────────────────
            ['kode' => '6400', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Timur', 'alias' => 'BBKHIT Kaltim', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Kalimantan Timur'],
            ['kode' => '6401', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Timur', 'nama_satpel' => 'Bandara Sultan Aji Muhammad Sulaiman', 'wilayah' => 'Provinsi Kalimantan Timur'],
            ['kode' => '6402', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Timur', 'nama_satpel' => 'Pelabuhan Sungai Samarinda', 'wilayah' => 'Provinsi Kalimantan Timur'],
            ['kode' => '6403', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Timur', 'nama_satpel' => 'Pelabuhan Laut Kariangau', 'wilayah' => 'Provinsi Kalimantan Timur'],
            ['kode' => '6404', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Timur', 'nama_satpel' => 'Pelabuhan Laut Semayang', 'wilayah' => 'Provinsi Kalimantan Timur'],
            ['kode' => '6405', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Timur', 'nama_satpel' => 'Pelabuhan Laut Lok Tuan Bontang', 'wilayah' => 'Provinsi Kalimantan Timur'],
            ['kode' => '6406', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Timur', 'nama_satpel' => 'Bandara APT Pranoto', 'wilayah' => 'Provinsi Kalimantan Timur'],
            ['kode' => '6407', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Timur', 'nama_satpel' => 'Pelabuhan Laut Berau', 'wilayah' => 'Provinsi Kalimantan Timur'],

            // ── Kalimantan Utara (65xx) ───────────────────────────────────────────────
            ['kode' => '6500', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Utara', 'alias' => 'BKHIT Kaltara', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Kalimantan Utara'],
            ['kode' => '6501', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Utara', 'nama_satpel' => 'Bandara Juwata Tarakan', 'wilayah' => 'Provinsi Kalimantan Utara'],
            ['kode' => '6502', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Utara', 'nama_satpel' => 'Pelabuhan Laut Tanjung Selor', 'wilayah' => 'Provinsi Kalimantan Utara'],
            ['kode' => '6503', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Utara', 'nama_satpel' => 'PLBN Sebatik', 'wilayah' => 'Provinsi Kalimantan Utara'],
            ['kode' => '6504', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Kalimantan Utara', 'nama_satpel' => 'PLBN Nunukan', 'wilayah' => 'Provinsi Kalimantan Utara'],

            // ── Sulawesi Utara (71xx) ─────────────────────────────────────────────────
            ['kode' => '7100', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Utara', 'alias' => 'BKHIT Sulut', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Sulawesi Utara'],
            ['kode' => '7101', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Utara', 'nama_satpel' => 'Bandara Sam Ratulangi', 'wilayah' => 'Provinsi Sulawesi Utara'],
            ['kode' => '7102', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Utara', 'nama_satpel' => 'Pelabuhan Laut Bitung', 'wilayah' => 'Provinsi Sulawesi Utara'],
            ['kode' => '7103', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Utara', 'nama_satpel' => 'Pelabuhan Laut Tahuna', 'wilayah' => 'Provinsi Sulawesi Utara'],

            // ── Sulawesi Tengah (72xx) ────────────────────────────────────────────────
            ['kode' => '7200', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Tengah', 'alias' => 'BKHIT Sulteng', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Sulawesi Tengah'],
            ['kode' => '7201', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Tengah', 'nama_satpel' => 'Bandara Mutiara Sis Al-Jufri', 'wilayah' => 'Provinsi Sulawesi Tengah'],
            ['kode' => '7202', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Tengah', 'nama_satpel' => 'Pelabuhan Pantoloan', 'wilayah' => 'Provinsi Sulawesi Tengah'],
            ['kode' => '7203', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Tengah', 'nama_satpel' => 'Pelabuhan Laut Luwuk Banggai', 'wilayah' => 'Provinsi Sulawesi Tengah'],
            ['kode' => '7204', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Tengah', 'nama_satpel' => 'Pelabuhan Laut Toli-toli', 'wilayah' => 'Provinsi Sulawesi Tengah'],

            // ── Sulawesi Selatan (73xx) ───────────────────────────────────────────────
            ['kode' => '7300', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Selatan', 'alias' => 'BBKHIT Sulsel', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Sulawesi Selatan'],
            ['kode' => '7301', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Selatan', 'nama_satpel' => 'Bandara Sultan Hasanudin', 'wilayah' => 'Provinsi Sulawesi Selatan'],
            ['kode' => '7302', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Selatan', 'nama_satpel' => 'Pelabuhan Laut Pare-Pare', 'wilayah' => 'Provinsi Sulawesi Selatan'],
            ['kode' => '7303', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Selatan', 'nama_satpel' => 'Pelabuhan Laut Jeneponto', 'wilayah' => 'Provinsi Sulawesi Selatan'],
            ['kode' => '7304', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Selatan', 'nama_satpel' => 'Pelabuhan Laut Bajoe', 'wilayah' => 'Provinsi Sulawesi Selatan'],

            // ── Sulawesi Tenggara (74xx) ──────────────────────────────────────────────
            ['kode' => '7400', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Tenggara', 'alias' => 'BKHIT Sultra', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Sulawesi Tenggara'],
            ['kode' => '7401', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Tenggara', 'nama_satpel' => 'Bandara Halu Oleo', 'wilayah' => 'Provinsi Sulawesi Tenggara'],
            ['kode' => '7402', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Tenggara', 'nama_satpel' => 'Bandara Betoambari', 'wilayah' => 'Provinsi Sulawesi Tenggara'],
            ['kode' => '7403', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Tenggara', 'nama_satpel' => 'Pelabuhan Laut Kendari', 'wilayah' => 'Provinsi Sulawesi Tenggara'],
            ['kode' => '7404', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Tenggara', 'nama_satpel' => 'Pelabuhan Laut Kolaka', 'wilayah' => 'Provinsi Sulawesi Tenggara'],
            ['kode' => '7405', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Tenggara', 'nama_satpel' => 'Pelabuhan Laut Raha Muna', 'wilayah' => 'Provinsi Sulawesi Tenggara'],
            ['kode' => '7406', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Tenggara', 'nama_satpel' => 'Pelabuhan Laut Wanci Wakatobi', 'wilayah' => 'Provinsi Sulawesi Tenggara'],

            // ── Gorontalo (75xx) ──────────────────────────────────────────────────────
            ['kode' => '7500', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Gorontalo', 'alias' => 'BKHIT Gorontalo', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Gorontalo'],
            ['kode' => '7501', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Gorontalo', 'nama_satpel' => 'Bandara Jalaluddin', 'wilayah' => 'Provinsi Gorontalo'],

            // ── Sulawesi Barat (76xx) ─────────────────────────────────────────────────
            ['kode' => '7600', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Barat', 'alias' => 'BKHIT Sulbar', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Sulawesi Barat'],
            ['kode' => '7601', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Barat', 'nama_satpel' => 'Bandara Tampa Padang', 'wilayah' => 'Provinsi Sulawesi Barat'],
            ['kode' => '7602', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Barat', 'nama_satpel' => 'Pelabuhan Laut Pasang Kayu', 'wilayah' => 'Provinsi Sulawesi Barat'],
            ['kode' => '7603', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Sulawesi Barat', 'nama_satpel' => 'Pelabuhan Laut Majene', 'wilayah' => 'Provinsi Sulawesi Barat'],

            // ── Maluku (81xx) ─────────────────────────────────────────────────────────
            ['kode' => '8100', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Maluku', 'alias' => 'BKHIT Maluku', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Maluku'],
            ['kode' => '8101', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Maluku', 'nama_satpel' => 'Bandara Pattimura', 'wilayah' => 'Provinsi Maluku'],
            ['kode' => '8102', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Maluku', 'nama_satpel' => 'Pelabuhan Laut Tual', 'wilayah' => 'Provinsi Maluku'],
            ['kode' => '8103', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Maluku', 'nama_satpel' => 'Pelabuhan Laut Kobisadar', 'wilayah' => 'Provinsi Maluku'],
            ['kode' => '8104', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Maluku', 'nama_satpel' => 'Pelabuhan Laut Namlea', 'wilayah' => 'Provinsi Maluku'],
            ['kode' => '8105', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Maluku', 'nama_satpel' => 'Pelabuhan Laut Dobo', 'wilayah' => 'Kabupaten Kepulauan Aru'],

            // ── Maluku Utara (82xx) ───────────────────────────────────────────────────
            ['kode' => '8200', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Maluku Utara', 'alias' => 'BKHIT Malut', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Maluku Utara'],
            ['kode' => '8201', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Maluku Utara', 'nama_satpel' => 'Bandara Sultan Babullah', 'wilayah' => 'Provinsi Maluku Utara'],
            ['kode' => '8202', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Maluku Utara', 'nama_satpel' => 'Pelabuhan Laut Tobelo', 'wilayah' => 'Provinsi Maluku Utara'],
            ['kode' => '8203', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Maluku Utara', 'nama_satpel' => 'Pelabuhan Laut Bacan', 'wilayah' => 'Provinsi Maluku Utara'],
            ['kode' => '8204', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Maluku Utara', 'nama_satpel' => 'Pelabuhan Laut Sanana', 'wilayah' => 'Provinsi Maluku Utara'],
            ['kode' => '8205', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Maluku Utara', 'nama_satpel' => 'Pelabuhan Laut Morotai', 'wilayah' => 'Provinsi Maluku Utara'],

            // ── Papua (91xx) ──────────────────────────────────────────────────────────
            ['kode' => '9100', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Papua', 'alias' => 'BBKHIT Papua', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Papua'],
            ['kode' => '9101', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Papua', 'nama_satpel' => 'Bandara Sentani', 'wilayah' => 'Provinsi Papua'],
            ['kode' => '9102', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Papua', 'nama_satpel' => 'Pelabuhan Laut Jayapura', 'wilayah' => 'Provinsi Papua'],
            ['kode' => '9103', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Papua', 'nama_satpel' => 'Pelabuhan Laut Biak', 'wilayah' => 'Provinsi Papua'],
            ['kode' => '9104', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Papua', 'nama_satpel' => 'PLBN Skouw', 'wilayah' => 'Provinsi Papua'],
            ['kode' => '9105', 'nama' => 'Balai Besar Karantina Hewan, Ikan, dan Tumbuhan Papua', 'nama_satpel' => 'Pelabuhan Laut Serui', 'wilayah' => 'Provinsi Papua'],

            // ── Papua Barat (92xx) ────────────────────────────────────────────────────
            ['kode' => '9200', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Barat', 'alias' => 'BKHIT Papua Barat', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Papua Barat'],
            ['kode' => '9201', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Barat', 'nama_satpel' => 'Pelabuhan Laut Bintuni', 'wilayah' => 'Provinsi Papua Barat'],
            ['kode' => '9202', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Barat', 'nama_satpel' => 'Pelabuhan Laut Wasior', 'wilayah' => 'Provinsi Papua Barat'],
            ['kode' => '9203', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Barat', 'nama_satpel' => 'Pelabuhan Laut Kaimana', 'wilayah' => 'Provinsi Papua Barat'],
            ['kode' => '9204', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Barat', 'nama_satpel' => 'Pelabuhan Laut Fakfak', 'wilayah' => 'Provinsi Papua Barat'],

            // ── Papua Selatan (93xx) ──────────────────────────────────────────────────
            ['kode' => '9300', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Selatan', 'alias' => 'BKHIT Papua Selatan', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Papua Selatan'],
            ['kode' => '9301', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Selatan', 'nama_satpel' => 'Bandara Mopah', 'wilayah' => 'Provinsi Papua Selatan'],
            ['kode' => '9302', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Selatan', 'nama_satpel' => 'Pelabuhan Sungai Bade', 'wilayah' => 'Provinsi Papua Selatan'],
            ['kode' => '9303', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Selatan', 'nama_satpel' => 'PLBN Sota', 'wilayah' => 'Provinsi Papua Selatan'],
            ['kode' => '9304', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Selatan', 'nama_satpel' => 'PLBN Yetetkun Boven Digoel', 'wilayah' => 'Provinsi Papua Selatan'],

            // ── Papua Tengah (94xx) ───────────────────────────────────────────────────
            ['kode' => '9400', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Tengah', 'alias' => 'BKHIT Papua Tengah', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Papua Tengah'],
            ['kode' => '9401', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Tengah', 'nama_satpel' => 'Pelabuhan Laut Nabire', 'wilayah' => 'Provinsi Papua Tengah'],

            // ── Papua Pegunungan (95xx) ───────────────────────────────────────────────
            ['kode' => '9500', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Pegunungan', 'alias' => 'BKHIT Papua Pegunungan', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Papua Pegunungan'],

            // ── Papua Barat Daya (96xx) ───────────────────────────────────────────────
            ['kode' => '9600', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Barat Daya', 'alias' => 'BKHIT Papua Barat Daya', 'nama_satpel' => 'UPT Induk', 'wilayah' => 'Provinsi Papua Barat Daya'],
            ['kode' => '9601', 'nama' => 'Balai Karantina Hewan, Ikan, dan Tumbuhan Papua Barat Daya', 'nama_satpel' => 'Satpel Uji Coba', 'wilayah' => 'Provinsi Papua Barat Daya'],
        ];

        foreach (array_chunk($uptList, 50) as $chunk) {
            $normalized = array_map(fn($row) => array_merge(['alias' => null], $row), $chunk);
            Upt::insert($normalized);
        }
    }
}
