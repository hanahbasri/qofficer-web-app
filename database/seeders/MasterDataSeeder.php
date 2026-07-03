<?php

namespace Database\Seeders;

use App\Models\MasterTarget;
use App\Models\MasterTemuan;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Master Target (uraian target uji per jenis karantina) ─────
        $targets = [
            // Hewan (H)
            ['nama' => 'Anthrax',                                  'jenis_karantina' => 'H'],
            ['nama' => 'Brucellosis',                              'jenis_karantina' => 'H'],
            ['nama' => 'PMK (Penyakit Mulut dan Kuku)',            'jenis_karantina' => 'H'],
            ['nama' => 'Rabies',                                   'jenis_karantina' => 'H'],
            ['nama' => 'AI (Avian Influenza)',                     'jenis_karantina' => 'H'],
            ['nama' => 'ND (Newcastle Disease)',                   'jenis_karantina' => 'H'],
            ['nama' => 'IBR (Infectious Bovine Rhinotracheitis)',  'jenis_karantina' => 'H'],
            ['nama' => 'BVD (Bovine Viral Diarrhea)',              'jenis_karantina' => 'H'],
            ['nama' => 'Scabies',                                  'jenis_karantina' => 'H'],
            ['nama' => 'Helmintiasis',                             'jenis_karantina' => 'H'],
            ['nama' => 'Hog Cholera (Classical Swine Fever)',      'jenis_karantina' => 'H'],
            ['nama' => 'Negatif/Nihil',                            'jenis_karantina' => 'H'],
            // Tumbuhan (T)
            ['nama' => 'Bactrocera sp. (Lalat Buah)',              'jenis_karantina' => 'T'],
            ['nama' => 'Ralstonia solanacearum',                   'jenis_karantina' => 'T'],
            ['nama' => 'Phytophthora sp.',                         'jenis_karantina' => 'T'],
            ['nama' => 'Meloidogyne sp. (Nematoda Puru Akar)',     'jenis_karantina' => 'T'],
            ['nama' => 'Bemisia tabaci',                           'jenis_karantina' => 'T'],
            ['nama' => 'Tomato Yellow Leaf Curl Virus (TYLCV)',    'jenis_karantina' => 'T'],
            ['nama' => 'Nilaparvata lugens (Wereng Coklat)',       'jenis_karantina' => 'T'],
            ['nama' => 'Fusarium oxysporum',                       'jenis_karantina' => 'T'],
            ['nama' => 'Xanthomonas oryzae pv. oryzae',           'jenis_karantina' => 'T'],
            ['nama' => 'Negatif/Nihil',                            'jenis_karantina' => 'T'],
            // Ikan (I)
            ['nama' => 'White Spot Syndrome Virus (WSSV)',         'jenis_karantina' => 'I'],
            ['nama' => 'Koi Herpes Virus (KHV)',                   'jenis_karantina' => 'I'],
            ['nama' => 'VNN (Viral Nervous Necrosis)',             'jenis_karantina' => 'I'],
            ['nama' => 'Gyrodactylus salaris',                     'jenis_karantina' => 'I'],
            ['nama' => 'Aeromonas hydrophila',                     'jenis_karantina' => 'I'],
            ['nama' => 'Tilapia Lake Virus (TiLV)',                'jenis_karantina' => 'I'],
            ['nama' => 'EUS (Epizootic Ulcerative Syndrome)',      'jenis_karantina' => 'I'],
            ['nama' => 'Negatif/Nihil',                            'jenis_karantina' => 'I'],
        ];

        foreach ($targets as $t) {
            MasterTarget::updateOrCreate(
                ['nama' => $t['nama'], 'jenis_karantina' => $t['jenis_karantina']],
                array_merge($t, ['is_active' => true]),
            );
        }

        // ── Master Temuan ─────────────────────────────────────────────
        $temuan = [
            // Hewan (H)
            ['nama' => 'Negatif/Nihil',                                   'jenis_karantina' => 'H'],
            ['nama' => 'Positif PMK (Penyakit Mulut dan Kuku)',           'jenis_karantina' => 'H'],
            ['nama' => 'Positif Brucellosis',                             'jenis_karantina' => 'H'],
            ['nama' => 'Positif Rabies',                                  'jenis_karantina' => 'H'],
            ['nama' => 'Positif AI (Avian Influenza)',                    'jenis_karantina' => 'H'],
            ['nama' => 'Positif ND (Newcastle Disease)',                  'jenis_karantina' => 'H'],
            ['nama' => 'Positif IBR',                                     'jenis_karantina' => 'H'],
            ['nama' => 'Positif Anthrax',                                 'jenis_karantina' => 'H'],
            ['nama' => 'Positif Scabies',                                 'jenis_karantina' => 'H'],
            ['nama' => 'Dokumen Tidak Lengkap',                           'jenis_karantina' => 'H'],
            // Tumbuhan (T)
            ['nama' => 'Negatif/Nihil',                                   'jenis_karantina' => 'T'],
            ['nama' => 'Positif Bactrocera sp. (Lalat Buah)',             'jenis_karantina' => 'T'],
            ['nama' => 'Positif Ralstonia solanacearum',                  'jenis_karantina' => 'T'],
            ['nama' => 'Positif Phytophthora sp.',                        'jenis_karantina' => 'T'],
            ['nama' => 'Positif Meloidogyne sp.',                         'jenis_karantina' => 'T'],
            ['nama' => 'Positif TYLCV',                                   'jenis_karantina' => 'T'],
            ['nama' => 'Positif Fusarium oxysporum',                      'jenis_karantina' => 'T'],
            ['nama' => 'Dokumen Tidak Lengkap',                           'jenis_karantina' => 'T'],
            // Ikan (I)
            ['nama' => 'Negatif/Nihil',                                   'jenis_karantina' => 'I'],
            ['nama' => 'Positif WSSV (White Spot Syndrome Virus)',        'jenis_karantina' => 'I'],
            ['nama' => 'Positif KHV (Koi Herpes Virus)',                  'jenis_karantina' => 'I'],
            ['nama' => 'Positif VNN (Viral Nervous Necrosis)',            'jenis_karantina' => 'I'],
            ['nama' => 'Positif Aeromonas hydrophila',                    'jenis_karantina' => 'I'],
            ['nama' => 'Positif TiLV (Tilapia Lake Virus)',               'jenis_karantina' => 'I'],
            ['nama' => 'Dokumen Tidak Lengkap',                           'jenis_karantina' => 'I'],
        ];

        foreach ($temuan as $t) {
            MasterTemuan::updateOrCreate(
                ['nama' => $t['nama'], 'jenis_karantina' => $t['jenis_karantina']],
                array_merge($t, ['is_active' => true]),
            );
        }
    }
}
