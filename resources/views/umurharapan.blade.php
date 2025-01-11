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

    <h1 style="text-align: center;">Peta Umur Harapan Hidup</h1>
    <div id="map"></div>

    <script>
        // Initialize the map
        var map = L.map('map').setView([-2.2, 106.2], 8); // Set view awal ke wilayah Bangka Belitung
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Load GeoJSON data from Laravel endpoint
        fetch('/all-geojson-with-data') // Endpoint Laravel
            .then(response => response.json())
            .then(geojsonData => {
                // Add GeoJSON to the map
                L.geoJSON(geojsonData, {
                    style: function(feature) {
                        return {
                            color: '#3388ff',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.7,
                            fillColor: getColor(feature.properties.umur_harapan_hidup) // Custom color based on value
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        // Bind popup with data from GeoJSON
                        layer.bindPopup(`
                            <b>${feature.properties.name}</b><br>
                            Umur Harapan Hidup: ${feature.properties.umur_harapan_hidup || 'N/A'}<br>
                            Tingkat Partisipasi Angkatan Kerja: ${feature.properties.tingkat_partisipasi_angkatan_kerja || 'N/A'}<br>
                            Tingkat Pengangguran Terbuka: ${feature.properties.tingkat_pengangguran_terbuka || 'N/A'}
                        `);
                    }
                }).addTo(map);
            });

        // Function to get color based on umur_harapan_hidup value
        function getColor(value) {
    return value > 72.79 ? '#023858' :
           value > 72.25 ? '#045a8d' :
           value > 71.82 ? '#0570b0' :
           value > 71.57 ? '#3690c0' :
           value > 71.22 ? '#74a9cf' :
           value > 70.22 ? '#a6bddb' :
                           '#d0d1e6';
}

        // Fungsi untuk membuat legenda
        var legend = L.control({ position: "bottomright" }); // Letak legenda di kanan bawah

        legend.onAdd = function (map) {
    var div = L.DomUtil.create("div", "legend");
    div.innerHTML += "<h4>Legenda</h4>";
    div.innerHTML += '<i style="background: #023858"></i> > 72.79<br>';
    div.innerHTML += '<i style="background: #045a8d"></i> 72.25 - 72.79<br>';
    div.innerHTML += '<i style="background: #0570b0"></i> 71.82 - 72.25<br>';
    div.innerHTML += '<i style="background: #3690c0"></i> 71.57 - 71.82<br>';
    div.innerHTML += '<i style="background: #74a9cf"></i> 71.22 - 71.57<br>';
    div.innerHTML += '<i style="background: #a6bddb"></i> 70.22 - 71.22<br>';
    div.innerHTML += '<i style="background: #d0d1e6"></i> 68.98 - 70.22<br>';
    return div;
};

        // Tambahkan legenda ke peta
        legend.addTo(map);
    </script>

<div class="content">
        <p>
        Peta di atas merupakan peta Umur Harapan Hidup di Provinsi Kepulauan Bangka Belitung. Peta ini menggunakan skala 1:2.000.000 dengan proyeksi Mercator dan datum WGS 84 / Pseudo-Mercator. Wilayah pada peta dibagi menjadi beberapa kabupaten/kota, di mana angka harapan hidup digambarkan dengan gradasi warna yang menunjukkan nilai umur harapan hidup di setiap wilayah.
	Legenda peta menunjukkan enam kategori umur harapan hidup berdasarkan rentang nilai. Kategori terendah (68,98–70,22) diwakili oleh warna putih, sedangkan kategori tertinggi (72,79–73,96) ditandai dengan warna biru tua. Kota Pangkal Pinang memiliki angka harapan hidup tertinggi, sementara angka terendah terlihat di wilayah seperti Kabupaten Bangka Selatan.
	Peta ini berguna untuk menganalisis tingkat kesejahteraan masyarakat di setiap wilayah Provinsi Kepulauan Bangka Belitung. Informasi ini dapat dimanfaatkan untuk perencanaan pembangunan, seperti perbaikan fasilitas kesehatan dan peningkatan kualitas hidup di daerah dengan angka harapan hidup yang lebih rendah.
</p>
    </div>
</body>
</html>
