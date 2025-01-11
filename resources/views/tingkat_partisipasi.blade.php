<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        .legend {
            background: white;
            padding: 10px;
            line-height: 18px;
            color: #555;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            font-size: 12px;
        }

        .legend h4 {
            margin: 0;
            font-size: 14px;
        }

        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }

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
            align-items: center; /* Center items vertically */
        }

        .navbar .brand {
            color: white;
            font-size: 16px; /* Adjust font size to match other links */
            font-weight: bold;
            text-decoration: none;
            padding: 10px 15px; /* Add padding to match other links */
        }

        .navbar .nav-links {
            display: flex;
            gap: 20px;
            align-items: center; /* Center links vertically */
        }

        .navbar .nav-links .dropdown {
            position: relative; /* Ensure dropdown content is positioned relative to this */
        }

        .navbar .nav-links a {
            color: white;
            text-decoration: none;
            padding: 10px 15px; /* Consistent padding for all links */
            display: inline-block; /* Ensure links behave like blocks */
        }

        .navbar .nav-links a:hover {
            background-color: #575757;
            border-radius: 5px;
        }

        /* Dropdown Styling */
        .navbar .dropdown-content {
            display: none;
            position: absolute;
            background-color: #575757;
            min-width: 200px; /* Set a minimum width */
            z-index: 1000; /* Ensure dropdown appears above other elements */
            top: 100%; /* Position below the parent link */
            left: 0; /* Align with the left edge of the parent link */
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }
            .nav-links {
                flex-direction: column;
                gap: 10px;
            }
            #map {
                height: 400px; /* Adjust map height for smaller screens */
            }
        }
    </style>
</head>
<body>
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

    <h1 style="text-align: center;">Peta Tingkat Partisipasi Angkatan Kerja</h1>
    <div id="map"></div>


   
    <script>
        var map = L.map('map').setView([-2.2, 106.2], 8); // Wilayah Bangka Belitung
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Fetch GeoJSON data
        fetch('/all-geojson-with-data')
            .then(response => response.json())
            .then(geojsonData => {
                L.geoJSON(geojsonData, {
                    style: function(feature) {
                        return {
                            color: '#3388ff',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.7,
                            fillColor: getColor(feature.properties.tingkat_partisipasi_angkatan_kerja) // Custom color
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        layer.bindPopup(`
                            <b>${feature.properties.name}</b><br>
                            Tingkat Partisipasi Angkatan Kerja: ${feature.properties.tingkat_partisipasi_angkatan_kerja || 'N/A'}
                        `);
                    }
                }).addTo(map);
            });

        // Function to get color based on tingkat_partisipasi_angkatan_kerja value
        function getColor(value) {
    return value > 70.46 ? '#00441b' :
           value > 70.26 ? '#006d2c' :
           value > 70.13 ? '#238b45' :
           value > 68.55 ? '#41ab5d' :
           value > 67.82 ? '#74c476' :
           value > 66.69 ? '#a1d99b' :
                           '#c7e9c0';
}


        // Add legend
        var legend = L.control({ position: "bottomright" });
        legend.onAdd = function (map) {
    var div = L.DomUtil.create("div", "legend");
    div.innerHTML += "<h4>Legenda</h4>";
    div.innerHTML += '<i style="background: #00441b"></i> > 70.46<br>';
    div.innerHTML += '<i style="background: #006d2c"></i> 70.26 - 70.46<br>';
    div.innerHTML += '<i style="background: #238b45"></i> 70.13 - 70.26<br>';
    div.innerHTML += '<i style="background: #41ab5d"></i> 68.55 - 70.13<br>';
    div.innerHTML += '<i style="background: #74c476"></i> 67.82 - 68.55<br>';
    div.innerHTML += '<i style="background: #a1d99b"></i> 66.69 - 67.82<br>';
    div.innerHTML += '<i style="background: #c7e9c0"></i> 65.65 - 66.69<br>';
    return div;
};

        legend.addTo(map);
    </script>
     <div class="content">
        <p>
        Peta di atas menunjukkan tingkat partisipasi angkatan kerja (TPAK) di wilayah Provinsi Kepulauan Bangka Belitung, berdasarkan data terbaru yang tersedia. Peta ini menggunakan skala 1:2.000.000, yang berarti setiap 1 cm di peta mewakili 20 km di wilayah nyata, serta memanfaatkan proyeksi Mercator dengan datum WGS84, sehingga mempertahankan akurasi geografis.
	Wilayah pada peta mencakup seluruh kabupaten/kota di Provinsi Kepulauan Bangka Belitung, yang meliputi Kabupaten Bangka, Bangka Barat, Bangka Tengah, Bangka Selatan, Belitung, Belitung Timur, serta Kota Pangkalpinang. Tingkat partisipasi tenaga kerja dibagi ke dalam beberapa kategori, yang ditunjukkan melalui gradasi warna hijau pada legenda. Warna hijau terang menunjukkan TPAK yang lebih rendah (66,55–66,88%), sementara warna hijau tua menandakan TPAK yang lebih tinggi (70,26–70,48%).
	Dari peta ini, terlihat bahwa wilayah dengan tingkat partisipasi angkatan kerja tertinggi berada di Kabupaten Belitung Timur, sedangkan wilayah seperti Bangka Barat dan Bangka Tengah memiliki tingkat partisipasi yang relatif lebih rendah. Informasi ini penting untuk memahami distribusi angkatan kerja di provinsi ini, yang selanjutnya dapat digunakan untuk perencanaan kebijakan di sektor tenaga kerja, pendidikan, dan pengembangan ekonomi. Peta ini juga memberikan wawasan yang berguna bagi pemerintah dalam mengalokasikan sumber daya dan memprioritaskan wilayah untuk program peningkatan lapangan kerja.
</p>
    </div>
</body>
</html>
