<?php

namespace App\Support;

final class PillarCatalog
{
    /**
     * @return list<array{legacy_slug: string, slug: string, name: string, icon: string, description: string, programs: list<string>}>
     */
    public static function all(): array
    {
        return [
            [
                'legacy_slug' => 'pendidikan',
                'slug' => 'nu-care-cerdas',
                'name' => 'NU Care Cerdas',
                'icon' => 'cerdas',
                'description' => 'Program untuk meningkatkan kualitas sumber daya manusia melalui penyediaan beasiswa, pelatihan, dan penguatan fasilitas pendidikan, dari sekolah dasar, menengah, hingga perguruan tinggi. Program ini menjamin akses pendidikan berkualitas dan membuka kesempatan belajar, terutama bagi siswa yatim, duafa, dan berprestasi.',
                'programs' => [
                    'Beasiswa Santri Tidak Mampu',
                    'Beasiswa Hafidzul Qur\'an',
                    'Beasiswa Santri Berprestasi',
                ],
            ],
            [
                'legacy_slug' => 'ekonomi',
                'slug' => 'nu-care-berdaya',
                'name' => 'NU Care Berdaya',
                'icon' => 'berdaya',
                'description' => 'Program untuk mendorong kemandirian dan meningkatkan pendapatan, kesejahteraan masyarakat, serta semangat kewirausahaan melalui kegiatan ekonomi dan pembentukan usaha.',
                'programs' => [
                    'Santunan Anak Yatim',
                    'Santunan Dhuafa',
                    'Santunan Muallaf',
                    'Bantuan Disabilitas',
                    'Sedekah Subuh',
                    'Sanitasi (Masjid & Pondok Pesantren)',
                    'Jum\'at Berkah',
                    'Pendampingan UMKM',
                    'Bedah Rumah',
                ],
            ],
            [
                'legacy_slug' => 'kesehatan',
                'slug' => 'nu-care-sehat',
                'name' => 'NU Care Sehat',
                'icon' => 'sehat',
                'description' => 'Program untuk meningkatkan layanan di bidang kesehatan, khususnya di kalangan keluarga kurang mampu, melalui tindakan kuratif maupun kegiatan preventif.',
                'programs' => [
                    'Pendampingan Orang Sakit',
                ],
            ],
            [
                'legacy_slug' => 'dakwah-kemanusiaan',
                'slug' => 'nu-care-damai',
                'name' => 'NU Care Damai',
                'icon' => 'damai',
                'description' => 'Program untuk meningkatkan layanan sosial dengan semangat dakwah Islam Ahlussunnah Wal Jama\'ah dan misi kemanusiaan, baik dalam bentuk bantuan kebencanaan maupun bantuan sosial lain yang dilakukan secara sistemik bersama mitra internal dan eksternal NU.',
                'programs' => [
                    'Pemeriksaan Kesehatan Gratis',
                    'Sunatan Massal Gratis',
                    'Donor darah',
                ],
            ],
            [
                'legacy_slug' => 'lingkungan',
                'slug' => 'nu-care-hijau',
                'name' => 'NU Care Hijau',
                'icon' => 'hijau',
                'description' => 'Program untuk memelihara lingkungan dan sumber daya alam serta pemanfaatannya secara bijaksana, dan mendorong keberlanjutan alam sebagai sumber penghidupan masyarakat.',
                'programs' => [
                    'Tanggap Bencana',
                ],
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function icons(): array
    {
        return [
            'cerdas' => 'Topi wisuda',
            'berdaya' => 'Usaha',
            'sehat' => 'Kesehatan',
            'damai' => 'Kepedulian',
            'hijau' => 'Daun',
        ];
    }
}
