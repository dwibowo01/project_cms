<?php

// Static boilerplate text transcribed verbatim from public/Form/FORM CONFIRM ORDER.docx,
// used when rendering the Confirm Order PDF export.
return [

    'notes' => [
        'Harap Confirm Order ini di fax atau email kembali ke kami sebagai bukti kesepakatan.',
        'Dimohon kepada Costumer di atas, agar segera memproses Surat Keterangan Tidak Dipungut (SKTD) dan Rencana Kebutuhan Impor dan Perolehan (RKIP) apabila tidak ingin dipungut PPN 11%.',
    ],

    'payment_terms' => [
        'Pembayaran Pertama sebesar Rp. :amount (:terbilang) dari Penawaran Harga Awal akan dibayarkan setelah terbit SPK dan kapal sudah naik dock.',
        'Pembayaran berupa Pelunasan, Seluruh Biaya Docking dikurangi Pembayaran Pertama akan dibayarkan oleh Pihak Pertama dengan termin pembayaran selama 2 (dua) bulan, dibayarkan setiap bulan nya pada tanggal akhir bulan berjalan.',
    ],

    // Syarat dan Ketentuan Umum: title + numbered items per section, numbered
    // exactly as the original document's multilevel list (1, 1.1, 1.2, 2, 2.1, ...).
    'terms' => [
        1 => [
            'title' => 'Ruang Lingkup Pekerjaan',
            'items' => [
                '1.1' => 'Galangan Caputra setuju dan mengikatkan diri untuk melaksanakan pekerjaan dengan perintah/instruksi dari Pelanggan sesuai dengan dokumen penawaran docking dari Galangan Caputra yang telah disetujui oleh Pelanggan.',
                '1.2' => 'Dokumen penawaran dari Galangan Caputra merupakan lampiran dari Perjanjian dan merupakan satu kesatuan dan bagian yang tidak terpisahkan.',
                '1.3' => 'Material yang digunakan oleh Galangan Caputra adalah material yang memenuhi standart klasifikasi untuk penggunaan marine yang disetujui oleh Pelanggan.',
            ],
        ],
        2 => [
            'title' => 'Jangka Waktu Pekerjaan',
            'items' => [
                '2.1' => 'Galangan Caputra menyanggupi menyelesaikan pekerjaan tersebut sesuai dengan jadwal pekerjaan pada penawaran docking setelah kapal berada di atas area docking diluar jadwal penambahan jumlah kuantiti pekerjaan setelah survey dilapangan.',
                '2.2' => 'Penjabaran jadwal adalah menjadi satu kesatuan, apabila terjadi keterlambatan yang disebabkan oleh pihak lain yang menjadi bagian pekerjaan yang dilakukan sendiri oleh Pelanggan maka Galangan Caputra diperbolehkan menyesuaikan jadwal perkerjaan terkait perubahan dari pihak lain tersebut.',
                '2.3' => 'Apabila pada waktu pelaksanaan pekerjaan ternyata ditemukan suatu alat/barang dari kapal yang menurut Galangan Caputra secara teknis tidak mungkin diperbaiki/dipergunakan lagi, maka Galangan Caputra diwajibkan untuk memberitahukan hal tersebut kepada Pelanggan dengan mengajukan perhitungan biaya untuk penggantian alat/barang yang bersangkutan.',
            ],
        ],
        3 => [
            'title' => 'Denda',
            'items' => [
                '3.1' => 'Apabila Galangan Caputra tidak dapat menyelesaikan pekerjaan sesuai dengan jangka waktu yang disebutkan di pasal 6.1 diatas, maka Galangan Caputra akan dikenakan penalty sebesar (satu permil) / perhari maksimal 10 hari kalender.',
                '3.2' => 'Apabila terjadi keterlambatan pembayaran oleh Pelanggan, maka Pelanggan akan dikenakan penalty sebesar (satu permil) / perhari maksimal 10 hari kalender.',
            ],
        ],
        4 => [
            'title' => 'Pengawasan Pekerjaan',
            'items' => [
                '4.1' => 'Untuk memungkinkan pengawasan yang sebaik-baiknya atas pelaksanaan pekerjaan perbaikan/pengedokan kapal selama berlangsungnya pekerjaan tersebut, Pelanggan harus menunjuk seorang Pengawas/Surveyor yang harus selalu berada ditempat pekerjaan dan diberi kuasa penuh untuk memberikan keputusan yang mengikat baik mengenai volume maupun harga dari tiap item pekerjaan.',
                '4.2' => 'Galangan Caputra akan memberikan bantuan / ijin kepada Pelanggan dan atau pengawas dari Pihak Pertama/Surveyor yang akan mengawasi pelaksanaan perbaikan-perbaikan yang tersebut sebagaimana ketentuan artikel 4.1.',
                '4.3' => 'Pelanggan dan Pengawas Pelanggan (Owner Surveyor) serta awak-awak kapal harus mentaati peraturan yang berlaku di Galangan Caputra.',
            ],
        ],
        5 => [
            'title' => 'Jangka Waktu Pekerjaan',
            'items' => [
                '5.1' => 'Penyerahan akan dilakukan oleh Galangan Caputra kepada Pelanggan setelah pembayaran dilakukan dan Berita Acara ditandatangani Galangan Caputra.',
                '5.2' => 'Selambat-lambatnya 4 (empat) hari setelah pekerjaan selesai sebagaimana dibuktikan dengan Berita Acara Serah Terima Pekerjaan yang ditandatangani oleh Para Pihak, Pelanggan harus membawa kapal keluar dari area Galangan Caputra.',
            ],
        ],
        6 => [
            'title' => 'Hak Milik dan Resiko',
            'items' => [
                '6.1' => 'Para Pihak sepakat bahwa hak milik atas kapal tetap pada Pelanggan. Selama Kapal melakukan docking sampai dengan saat serah terima. Galangan Caputra menjamin Asuransi berupa jaminan apabila terjadi insiden atau kerusakan pada kapal yang disebabkan oleh kesalahan pihak Galangan Caputra baik selama proses docking maupun dalam masa Garansi akan sepenuhnya ditanggung oleh pihak Galangan Caputra.',
                '6.2' => 'Segala kegiatan/pekerjaan di luar Perjanjian beserta dokumen yang menyertainya dilarang melibatkan pekerja dan menggunakan fasilitas dari Galangan Caputra.',
                '6.3' => 'Pelanggan dilarang untuk melakukan kegiatan keluar dan masuk barang-barang yang tidak memiliki perizinan/ legalitas yang resmi terutama barang-barang yang bersifat B3 (Bahan Berbahaya dan Beracun) di area Galangan Caputra.',
                '6.4' => 'Segala risiko yang muncul akibat dari tidak dipatuhinya artikel 6.3), maka akan menjadi tanggung jawab penuh dari Pelanggan.',
            ],
        ],
        7 => [
            'title' => 'Garansi',
            'items' => [
                '7.1' => 'Galangan Caputra hanya memberi garansi pada kapal terbatas pada kerusakan atas kesalahan pekerjaan yang telah dilakukan oleh Galangan Caputra dan bukan karena kesalahan operasional. Masa garansi berlaku hingga 30 (tiga puluh) hari kalender setelah Kapal meninggalkan area Galangan Caputra.',
            ],
        ],
        8 => [
            'title' => 'Force Majeure',
            'items' => [
                '8.1' => 'Yang dimaksud dengan force majeure adalah suatu keadaan yang terjadi diluar kehendak dan kemampuan Para Pihak dan tidak diperkirakan sebelumnya, sehingga kewajiban yang ditentukan dalam Berita Acara Kesepakatan ini menjadi tidak dapat dipenuhi.',
                '8.2' => 'Yang dapat digolongkan sebagai force mejure dalam Berita Acara Kesepakatan termasuk tapi tidak terbatas pada bencana alam, bencana non alam, bencana social, demonstrasi/pemogokan, kebakaran, dan/atau, ganguan industri lainnya sebagaimana dinyatakan melalui keputusan Pemerintah.',
                '8.3' => 'Dalam hal terjadi force mejure, Pihak yang terkena force mejure memberitahukan tentang terjadinya force mejure kepada Pihak yang lain secara tertulis dalam waktu paling lambat 14 (empat belas) hari kalender sejak terjadinya force mejure, dengan menyertakan salinan pernyataan force mejure yang dikeluarkan oleh Pihak/Instansi yang berwenang sesuai ketentuan peraturan perundangan-undangan.',
                '8.4' => 'Jika ketentuan pada artikel 8.3 tidak dipenuhi, maka pengajuan force mejure ditolak.',
                '8.5' => 'Tidak termasuk force mejure adalah hal-hal merugikan yang disebabkan oleh perbuatan atau kelalaian Para Pihak.',
                '8.6' => 'Setelah terjadinya force mejure, Para Pihak dapat melakukan kesepakatan yang dituangkan dalam perubahan Perjanjian (Addendum).',
            ],
        ],
        9 => [
            'title' => 'Material Bekas/Skrap',
            'items' => [
                '9.1' => 'Seluruh material bekas/skrap yang timbul saat docking/reparasi kapal tersebut adalah milik Galangan Caputra.',
            ],
        ],
        10 => [
            'title' => 'Pengalihan',
            'items' => [
                '10.1' => 'Para Pihak tidak boleh mengalihkan hal-hal yang diatur dalam Perjanjian ini kepada Pihak Ketiga tanpa adanya persetujuan terlebih dahulu dari Pihak lainnya.',
            ],
        ],
        11 => [
            'title' => 'Domisili Hukum dan Penyelesaian Perselisihan',
            'items' => [
                '11.1' => 'Perjanjian ini akan diberlakukan, diterjemahkan dan diterapkan, dan hubungan antara para Pihak akan mengacu kepada Hukum Republik Indonesia, tanpa memandang pilihan hukumnya.',
                '11.2' => 'Jika terjadi perselisihan sehubungan dengan Perjanjian ini, para Pihak sepakat untuk mencari jalan keluar secara musyawarah.',
                '11.3' => 'Jika ada perselisihan yang tidak dapat diselesaikan oleh Para Pihak, maka Para Pihak dalam hal ini setuju dan sepakat untuk menyerahkan kepada pengadilan. Oleh karena itu, kedua belah pihak memilih tempat kedudukan yang umum dan tetap di Kantor Pengadilan Negeri Jakarta Pusat.',
            ],
        ],
        12 => [
            'title' => 'Ketentuan Lain-Lain',
            'items' => [
                '12.1' => 'Perjanjian ini tidak dapat diubah, diganti, atau dilepaskan kecuali berdasarkan persetujuan tertulis yang ditandatangani oleh Para Pihak atau kuasanya yang sah.',
                '12.2' => 'Para pihak sepakat bahwa hal-hal yang belum diatur dalam perjanjian akan diatur secara tertulis dalam suatu Perjanjian tambahan (Addendum) dan Addendum tersebut merupakan satu kesatuan serta merupakan bagian yang tidak terpisahkan dari Perjanjian.',
                '12.3' => 'Para Pihak sepakat untuk mengenyampingkan ketentuan pasal 1266 dan 1267 Kitab Undang-Undang Hukum Perdata apabila terjadi pembatalan Perjanjian.',
                '12.4' => 'Sebelum kapal naik dock, Pelanggan harus menunjukkan Surat Hasil Pemeriksaan Kesehatan yang telah diterbitkan oleh Kantor Kesehatan Pelabuhan (KKP) kepada pihak Galangan Caputra sebagai informasi bahwa awak kapal yang berada di kapal milik Pelanggan telah memenuhi persyaratan kesehatan.',
                '12.5' => 'Sebelum kapal memasuki area Galangan Caputra, Pelanggan wajib menginformasikan kepada pihak agen yang telah ditunjuk oleh pelanggan untuk melakukan kepengurusan dokumen Pemberitahuan Kedatangan Kapal (PKK) dan selanjutnya nomor PKK tersebut diinformasikan kepada pihak Galangan agar dapat dibuatkan Rencana Penambatan Kapal dan Rencana Operasi (RPK-RO) pada Sistem Aplikasi Inaportnet milik Kementerian Perhubungan.',
            ],
        ],
    ],

];
