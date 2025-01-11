<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        /* General Styling */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #333;
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
            align-items: center;
        }

        .navbar .brand {
            color: white;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            padding: 10px 15px;
        }

        .navbar .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .navbar .nav-links .dropdown {
            position: relative;
        }

        .navbar .nav-links a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
        }

        .navbar .nav-links a:hover {
            background-color: #575757;
            border-radius: 5px;
        }

        .navbar .dropdown-content {
            display: none;
            position: absolute;
            background-color: #575757;
            min-width: 200px;
            z-index: 1000;
            top: 100%;
            left: 0;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }

        .navbar .dropdown-content a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
        }

        .navbar .dropdown-content a:hover {
            background-color: #444;
        }

        .navbar .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Map Container Styling */
        #map {
            height: 600px;
        }
    </style>
</head>
<body>    <!-- Navbar -->
    <!-- Navbar -->
    <div class="navbar">
        <a href="./" class="brand">SIG BANGKA BELITUNG</a>
        <div class="nav-links">
            <!-- Dropdown Peta Tematik -->
            <div class="dropdown">
                <a href="#">Peta Tematik</a>
                <div class="dropdown-content">
                <a href="{{ route('umur-harapan') }}" class="navigate-button">Umur Harapan Hidup</a>
                <a href="{{ route('tingkat-partisipasi') }}" class="navigate-button">Tingkat Partisipasi Angkatan Kerja</a>
                <a href="{{ route('tingkat-pengangguran') }}">Tingkat Pengangguran</a>
        <a href="{{ route('gempa') }}" class="navigate-button">Gempa</a>
                </div>
            </div>
            <div class="dropdown">
                <a href="#">Kabupaten/Kota</a>
                <div class="dropdown-content">
                    <a href="{{ route('map') }}" class="navigate-button">Peta</a>
                </div>
            </div>
            <a href="/admin">Admin</a>
        </div>
    </div>

    <!-- Page Title -->
    <div style="text-align: center; margin: 20px 0;">
        <h1>PETA Bangka Belitung</h1>
        <h3>Data Non-Spasial</h3>
        <h4>Sumber data: BPS Provinsi Bangka Belitung</h4>
    </div>

    <!-- Map Container -->
    <div id="map"></div>

    <script>
        // Initialize the map
        var map = L.map('map').setView([-2.5, 107.0], 8);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var dataDaerah = @json($data_daerah); // Ambil data dari controller

        // Sort and rank data
        let rankHarapanHidup = [...dataDaerah].sort((a, b) => b.umur_harapan_hidup - a.umur_harapan_hidup);
        let rankPartisipasiKerja = [...dataDaerah].sort((a, b) => b.tingkat_partisipasi_angkatan_kerja - a.tingkat_partisipasi_angkatan_kerja);
        let rankPengangguran = [...dataDaerah].sort((a, b) => a.tingkat_pengangguran_terbuka - b.tingkat_pengangguran_terbuka);

        // Add ranking to data
        dataDaerah.forEach(item => {
            item.rankHarapanHidup = rankHarapanHidup.findIndex(d => d.kota === item.kota) + 1;
            item.rankPartisipasiKerja = rankPartisipasiKerja.findIndex(d => d.kota === item.kota) + 1;
            item.rankPengangguran = rankPengangguran.findIndex(d => d.kota === item.kota) + 1;
        });

        // Add markers
        dataDaerah.forEach(function(item) {
            var marker = L.marker([item.lat, item.long]).addTo(map);
            marker.bindPopup(`
                <b>${item.kota}</b><br>
                <b>Ranking:</b><br>
                - Umur Harapan Hidup: ${item.rankHarapanHidup}<br>
                - Partisipasi Kerja: ${item.rankPartisipasiKerja}<br>
                - Pengangguran: ${item.rankPengangguran}<br><br>
                <b>Data:</b><br>
                Umur Harapan Hidup: ${item.umur_harapan_hidup || 'N/A'}<br>
                Tingkat Partisipasi Kerja: ${item.tingkat_partisipasi_angkatan_kerja || 'N/A'}<br>
                Tingkat Pengangguran: ${item.tingkat_pengangguran_terbuka || 'N/A'}
            `);
        });
    </script>
     <div style="text-align: justify;">
        <p>Pemetaan data non-spasial dalam Sistem Informasi Geografis (SIG) Provinsi Kepulauan Bangka Belitung yang mencakup Tingkat Partisipasi Angkatan Kerja (TPAK), Tingkat Pengangguran Terbuka, Indeks Pembangunan Literasi, Umur Harapan Hidup, Jumlah Pernikahan, dan Rasio Pernikahan menunjukkan hubungan yang sangat linear dan saling berinteraksi satu sama lain. Secara runtun, hubungan antar peta tematik ini dapat dijelaskan sebagai berikut:</p>
        
        <h3 style="font-size: 18px; margin-top: 20px;">1. Tingkat Partisipasi Angkatan Kerja dan Tingkat Pengangguran Terbuka</h3>
        <ul style="margin-top: 10px; margin-left: 20px;">
            <li>Tingkat Partisipasi Angkatan Kerja (TPAK) mengukur proporsi penduduk yang aktif dalam pasar kerja. Semakin tinggi tingkat partisipasi angkatan kerja, umumnya akan berdampak pada penurunan tingkat pengangguran, karena lebih banyak orang yang terlibat dalam kegiatan ekonomi.</li>
            <li>Tingkat Pengangguran Terbuka cenderung berbanding terbalik dengan TPAK. Jika tingkat partisipasi angkatan kerja rendah, maka pengangguran terbuka akan lebih tinggi karena lebih banyak penduduk yang tidak aktif mencari pekerjaan. Sebaliknya, jika TPAK tinggi, meskipun jumlah pengangguran meningkat, namun akan lebih banyak pula peluang untuk memperoleh pekerjaan.</li>
        </ul>
        
        <h3 style="font-size: 18px; margin-top: 20px;">2. Tingkat Pengangguran Terbuka dan Indeks Pembangunan Literasi</h3>
        <ul style="margin-top: 10px; margin-left: 20px;">
            <li>Indeks Pembangunan Literasi berperan besar dalam menentukan kualitas sumber daya manusia di suatu daerah. Pendidikan dan keterampilan yang lebih tinggi akan meningkatkan daya saing tenaga kerja, mengurangi pengangguran, dan memperkecil kesenjangan antara pekerjaan yang tersedia dan kemampuan angkatan kerja.</li>
            <li>Daerah dengan tingkat literasi yang rendah biasanya akan memiliki tingkat pengangguran yang lebih tinggi, karena keterampilan yang kurang memadai untuk memasuki pasar kerja yang semakin kompetitif. Sebaliknya, daerah dengan literasi tinggi akan mencetak tenaga kerja yang lebih terampil dan siap pakai, yang pada gilirannya akan menurunkan angka pengangguran.</li>
        </ul>
        
        <h3 style="font-size: 18px; margin-top: 20px;">3. Indeks Pembangunan Literasi dan Umur Harapan Hidup</h3>
        <ul style="margin-top: 10px; margin-left: 20px;">
            <li>Indeks Pembangunan Literasi juga berhubungan langsung dengan Umur Harapan Hidup. Daerah dengan tingkat literasi yang tinggi umumnya memiliki kualitas hidup yang lebih baik, termasuk di bidang kesehatan. Akses yang lebih baik terhadap informasi kesehatan, pemahaman tentang pola hidup sehat, dan kemampuan untuk memanfaatkan layanan kesehatan dapat meningkatkan umur harapan hidup.</li>
            <li>Sebaliknya, daerah dengan literasi rendah mungkin menghadapi tantangan dalam hal pemahaman tentang kesehatan, yang dapat berdampak pada tingkat kematian yang lebih tinggi dan umur harapan hidup yang lebih pendek.</li>
        </ul>
        
        <h3 style="font-size: 18px; margin-top: 20px;">4. Umur Harapan Hidup dan Jumlah Pernikahan</h3>
        <ul style="margin-top: 10px; margin-left: 20px;">
            <li>Umur Harapan Hidup yang lebih tinggi seringkali berhubungan dengan stabilitas sosial dan ekonomi, yang pada gilirannya dapat mempengaruhi pola pernikahan. Di daerah dengan umur harapan hidup yang tinggi, masyarakat cenderung memiliki sistem dukungan sosial yang lebih baik, dan jumlah pernikahan bisa lebih stabil.</li>
            <li>Sebaliknya, daerah dengan umur harapan hidup rendah mungkin akan menghadapi masalah kesehatan yang lebih tinggi, yang dapat memengaruhi struktur keluarga dan jumlah pernikahan, karena ketidakstabilan hidup yang mempengaruhi pola perilaku sosial.</li>
        </ul>
        
        <h3 style="font-size: 18px; margin-top: 20px;">5. Jumlah Pernikahan dan Rasio Pernikahan</h3>
        <ul style="margin-top: 10px; margin-left: 20px;">
            <li>Jumlah Pernikahan dan Rasio Pernikahan memiliki hubungan yang erat dalam menggambarkan dinamika sosial dalam masyarakat. Rasio pernikahan yang tinggi biasanya mencerminkan norma sosial dan budaya yang mendukung pernikahan pada usia muda. Di sisi lain, angka pernikahan yang tinggi juga menunjukkan stabilitas sosial dan kecenderungan untuk membentuk keluarga.</li>
            <li>Sebaliknya, rasio pernikahan yang rendah bisa menjadi indikator adanya masalah sosial, seperti ketidakstabilan ekonomi, tingkat pendidikan yang lebih tinggi, atau masalah sosial lainnya yang membuat individu cenderung menunda atau menghindari pernikahan.</li>
        </ul>
        
        <p>Secara keseluruhan, pemetaan data non-spasial ini memberikan gambaran menyeluruh tentang faktor-faktor sosial dan ekonomi yang saling mempengaruhi dan berinteraksi, serta memberikan dasar yang kuat untuk perencanaan kebijakan pembangunan yang lebih terintegrasi dan berbasis pada data yang saling terkait.</p>
    </div>
</body>
</html>
