<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Get the first admin user to be the author
        $user = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->first();

        if (!$user) {
            $user = User::first();
        }

        $categories = [
            'Prestasi', 'Kegiatan Sekolah', 'Penerimaan Siswa Baru', 'Info Beasiswa', 
            'Kegiatan Ekstrakurikuler', 'Lomba', 'Pengumuman', 'Kegiatan Sosial',
            'Karya Siswa', 'Kerjasama Sekolah', 'Pembelajaran', 'Peringatan Hari Besar'
        ];

        $newsItems = [
            [
                'title' => 'SMK WIMA Juara 1 Lomba Web Design Tingkat Nasional',
                'content' => 'Siswa-siswi SMK WIMA berhasil meraih juara 1 dalam Lomba Web Design Tingkat Nasional yang diselenggarakan oleh Kementerian Pendidikan. Tim yang terdiri dari 3 siswa kelas XII berhasil mengalahkan 50 tim dari seluruh Indonesia dengan karya website interaktif tentang pendidikan berkelanjutan.',
                'category' => 'Prestasi',
                'image' => 'prestasi-web-design.jpg'
            ],
            [
                'title' => 'Pembukaan Penerimaan Peserta Didik Baru Tahun Ajaran 2025/2026',
                'content' => 'SMK WIMA membuka pendaftaran Peserta Didik Baru untuk Tahun Ajaran 2025/2026. Terbuka untuk lulusan SMP/MTs/sederajat. Kuota terbatas untuk setiap kompetensi keahlian. Dapatkan diskon uang pangkal untuk pendaftar 100 pertama.',
                'category' => 'Penerimaan Siswa Baru',
                'image' => 'ppdb-2025.jpg'
            ],
            [
                'title' => 'Kegiatan Bakti Sosial di Panti Asuhan Harapan Bangsa',
                'content' => 'Sebagai wujud kepedulian sosial, siswa-siswi SMK WIMA mengadakan bakti sosial di Panti Asuhan Harapan Bangsa. Kegiatan ini meliputi pemberian bantuan sembako, perlengkapan sekolah, dan bingkisan lebaran untuk anak-anak yatim piatu.',
                'category' => 'Kegiatan Sosial',
                'image' => 'baksos-2025.jpg'
            ],
            [
                'title' => 'Workshop Kewirausahaan untuk Siswa Kelas XI',
                'content' => 'Dalam rangka meningkatkan jiwa kewirausahaan, SMK WIMA mengadakan workshop kewirausahaan yang menghadirkan pengusaha sukses lulusan SMK. Workshop ini bertujuan untuk memberikan motivasi dan pengetahuan praktis tentang memulai bisnis sejak dini.',
                'category' => 'Kegiatan Sekolah',
                'image' => 'workshop-kewirausahaan.jpg'
            ],
            [
                'title' => 'Beasiswa Prestasi untuk 50 Siswa Berprestasi',
                'content' => 'Yayasan Pendidikan WIMA memberikan beasiswa prestasi kepada 50 siswa berprestasi di SMK WIMA. Beasiswa ini diberikan sebagai bentuk apresiasi atas prestasi akademik dan non-akademik yang telah diraih oleh para siswa.',
                'category' => 'Info Beasiswa',
                'image' => 'beasiswa-prestasi.jpg'
            ],
            [
                'title' => 'Ekstrakurikuler Robotik Raih Juara di Kompetisi Regional',
                'content' => 'Tim Robotik SMK WIMA berhasil meraih juara 2 dalam Kompetisi Robotik Regional Jawa Timur. Karya robot pemilah sampah otomatis mereka berhasil menyisihkan 30 tim dari berbagai sekolah se-Jawa Timur.',
                'category' => 'Ekstrakurikuler',
                'image' => 'robotik-juara.jpg'
            ],
            [
                'title' => 'Kunjungan Industri ke PT. Teknologi Maju Indonesia',
                'content' => 'Sebanyak 80 siswa kelas XI melakukan kunjungan industri ke PT. Teknologi Maju Indonesia untuk melihat langsung proses produksi dan manajemen industri. Kegiatan ini bertujuan untuk memberikan gambaran nyata dunia kerja kepada siswa.',
                'category' => 'Kegiatan Sekolah',
                'image' => 'kunjungan-industri.jpg'
            ],
            [
                'title' => 'Pelatihan Keterampilan Digital untuk Guru',
                'content' => 'SMK WIMA menyelenggarakan pelatihan peningkatan kompetensi guru dalam pemanfaatan teknologi digital untuk pembelajaran. Pelatihan ini diikuti oleh seluruh guru untuk meningkatkan kualitas pembelajaran berbasis teknologi.',
                'category' => 'Pembelajaran',
                'image' => 'pelatihan-guru.jpg'
            ],
            [
                'title' => 'Perayaan Hari Pendidikan Nasional 2025',
                'content' => 'SMK WIMA merayakan Hari Pendidikan Nasional dengan mengadakan berbagai lomba dan kegiatan edukatif. Tema tahun ini adalah "Bergerak Bersama, Wujudkan Merdeka Belajar".',
                'category' => 'Peringatan Hari Besar',
                'image' => 'hardiknas-2025.jpg'
            ],
            [
                'title' => 'Kerjasama dengan Perusahaan IT Terkemuka',
                'content' => 'SMK WIMA menjalin kerjasama dengan PT. Solusi Teknologi Indonesia dalam rangka peningkatan kualitas pendidikan vokasi. Kerjasama ini meliputi program magang, pelatihan guru, dan pengembangan kurikulum berbasis industri.',
                'category' => 'Kerjasama Sekolah',
                'image' => 'kerjasama-it.jpg'
            ],
            [
                'title' => 'Pameran Karya Siswa Jurusan Multimedia',
                'content' => 'Siswa-siswi jurusan Multimedia mengadakan pameran karya seni digital dan animasi. Karya yang dipamerkan merupakan hasil pembelajaran selama satu semester, meliputi desain grafis, animasi 2D/3D, dan pengembangan game sederhana.',
                'category' => 'Karya Siswa',
                'image' => 'pameran-multimedia.jpg'
            ],
            [
                'title' => 'Siswa SMK WIMA Lolos Olimpiade Sains Nasional',
                'content' => 'Tiga siswa SMK WIMA berhasil lolos ke babak final Olimpiade Sains Nasional 2025. Mereka akan mewakili provinsi di tingkat nasional untuk mata pelajaran Matematika, Fisika, dan Komputer.',
                'category' => 'Prestasi',
                'image' => 'osn-2025.jpg'
            ],
            [
                'title' => 'Peluncuran Program Kreativitas Siswa',
                'content' => 'SMK WIMA meluncurkan program pengembangan kreativitas siswa melalui berbagai kegiatan ekstrakurikuler dan kompetisi. Program ini bertujuan untuk mengasah bakat dan minat siswa di luar kegiatan akademik.',
                'category' => 'Kegiatan Sekolah',
                'image' => 'program-kreativitas.jpg'
            ],
            [
                'title' => 'Siswa Berprestasi Dapat Beasiswa ke Jepang',
                'content' => 'Siswa SMK WIMA terpilih untuk mengikuti program pertukaran pelajar ke Jepang selama 6 bulan. Program ini merupakan hasil kerjasama dengan Japan International Cooperation Agency (JICA) untuk pengembangan pendidikan vokasi.',
                'category' => 'Prestasi',
                'image' => 'beasiswa-jepang.jpg'
            ],
            [
                'title' => 'Workshop Kewirausahaan Digital untuk Alumni',
                'content' => 'SMK WIMA mengadakan workshop kewirausahaan digital khusus untuk alumni yang ingin mengembangkan bisnis online. Workshop ini menghadirkan praktisi digital marketing dan e-commerce berpengalaman.',
                'category' => 'Kegiatan Sekolah',
                'image' => 'workshop-alumni.jpg'
            ],
            [
                'title' => 'Peringatan Hari Pahlawan 2025',
                'content' => 'Dalam rangka memperingati Hari Pahlawan, SMK WIMA mengadakan upacara bendera dan berbagai lomba bertema kepahlawanan. Kegiatan ini bertujuan untuk menanamkan nilai-nilai kepahlawanan kepada generasi muda.',
                'category' => 'Peringatan Hari Besar',
                'image' => 'hari-pahlawan.jpg'
            ],
            [
                'title' => 'Siswa SMK WIMA Juara Lomba Desain Grafis',
                'content' => 'Siswa SMK WIMA meraih juara 1 Lomba Desain Grafis Tingkat Kota. Karya yang dibuat mengangkat tema "Teknologi untuk Pendidikan Inklusif" dan berhasil menyisihkan 100 peserta dari berbagai sekolah.',
                'category' => 'Prestasi',
                'image' => 'juara-desain-grafis.jpg'
            ],
            [
                'title' => 'Pelatihan Keterampilan Hidup untuk Siswa',
                'content' => 'SMK WIMA menyelenggarakan pelatihan keterampilan hidup (life skills) yang meliputi public speaking, manajemen waktu, dan pengembangan diri. Kegiatan ini bertujuan untuk mempersiapkan siswa menghadapi tantangan di dunia kerja.',
                'category' => 'Kegiatan Sekolah',
                'image' => 'pelatihan-life-skills.jpg'
            ],
            [
                'title' => 'Kegiatan Outbound untuk Meningkatkan Kerjasama Tim',
                'content' => 'Siswa kelas X mengikuti kegiatan outbound di Taman Wisata Alam untuk melatih kerjasama tim dan kepemimpinan. Kegiatan ini diharapkan dapat mempererat tali persaudaraan antar siswa.',
                'category' => 'Kegiatan Sekolah',
                'image' => 'outbound-2025.jpg'
            ],
            [
                'title' => 'Siswa SMK WIMA Raih Medali Emas di Ajang LKS',
                'content' => 'Siswa SMK WIMA berhasil meraih medali emas dalam Lomba Kompetensi Siswa (LKS) tingkat provinsi untuk kategori IT Network System Administration. Prestasi ini merupakan yang ketiga kalinya berturut-turut diraih oleh SMK WIMA.',
                'category' => 'Prestasi',
                'image' => 'lks-2025.jpg'
            ]
        ];

        foreach ($newsItems as $item) {
            $publishedAt = $faker->dateTimeBetween('-1 year', 'now');
            
            News::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'content' => $item['content'],
                'image' => $item['image'],
                'category' => $item['category'],
                'user_id' => $user->id,
                'is_published' => true,
                'published_at' => $publishedAt,
                'view_count' => $faker->numberBetween(50, 5000),
                'created_at' => $publishedAt,
                'updated_at' => $publishedAt,
            ]);
        }

        $this->command->info('Berhasil menambahkan 20 berita acak ke database');
    }
}
