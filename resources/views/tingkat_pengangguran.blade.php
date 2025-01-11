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

    <h1 style="text-align: center;">Peta Tingkat Pengangguran Terbuka</h1>
    <div id="map"></div>


   
    <script>
        // Initialize the map
        var map = L.map('map').setView([-2.2, 106.2], 8);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Load GeoJSON data from Laravel endpoint
        fetch('/all-geojson-with-data')
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
                            fillColor: getColor(feature.properties.tingkat_pengangguran_terbuka) // Color based on value
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        layer.bindPopup(`
                            <b>${feature.properties.name}</b><br>
                            Tingkat Pengangguran Terbuka: ${feature.properties.tingkat_pengangguran_terbuka || 'N/A'}
                        `);
                    }
                }).addTo(map);
            });

        // Function to get color based on tingkat_pengangguran_terbuka value
        function getColor(value) {
            return value > 5.34 ? '#67000d' :
                   value > 4.89 ? '#a50f15' :
                   value > 4.5  ? '#cb181d' :
                   value > 4.06 ? '#ef3b2c' :
                   value > 3.67 ? '#fb6a4a' :
                   value > 2.41 ? '#fcae91' :
                                 '#fee5d9';
        }

        // Add legend
        var legend = L.control({ position: "bottomright" });

        legend.onAdd = function (map) {
            var div = L.DomUtil.create("div", "legend");
            div.innerHTML += "<h4>Legenda</h4>";
            div.innerHTML += '<i style="background: #67000d"></i> > 5.34<br>';
            div.innerHTML += '<i style="background: #a50f15"></i> 4.89 - 5.34<br>';
            div.innerHTML += '<i style="background: #cb181d"></i> 4.5 - 4.89<br>';
            div.innerHTML += '<i style="background: #ef3b2c"></i> 4.06 - 4.5<br>';
            div.innerHTML += '<i style="background: #fb6a4a"></i> 3.67 - 4.06<br>';
            div.innerHTML += '<i style="background: #fcae91"></i> 2.41 - 3.67<br>';
            div.innerHTML += '<i style="background: #fee5d9"></i> < 2.41<br>';
            return div;
        };

        legend.addTo(map);
    </script>

<div class="content">
        <p>
        Peta diatas  adalah peta tingkat pengangguran terbuka di Provinsi Kepulauan Bangka Belitung. Peta ini menggunakan skala 1:2.000.000, yang berarti setiap 1 cm pada peta mewakili 20 km di lapangan. Sistem koordinat yang digunakan adalah Proyeksi Mercator dengan datum WGS 84.
	Peta ini menampilkan tingkat pengangguran terbuka di setiap kabupaten/kota dengan menggunakan gradasi warna untuk membedakan tingkatannya. Warna terang pada peta, seperti krem, menunjukkan tingkat pengangguran yang rendah, yaitu pada kisaran 2,41 hingga 3,67 persen. Sementara itu, warna yang semakin gelap hingga merah tua mewakili tingkat pengangguran yang lebih tinggi, yaitu pada rentang 5,134 hingga 5,76 persen.
	Dari peta tersebut, terlihat bahwa wilayah dengan tingkat pengangguran tinggi ditandai dengan warna merah tua, seperti Kota Pangkal Pinang. Kabupaten lain seperti Bangka, Bangka Selatan dan Bangka Barat berada pada kategori menengah dengan warna jingga. Sedangkan wilayah seperti Kabupaten Belitung Timur memiliki tingkat pengangguran yang lebih rendah dan diwakili oleh warna kre atau kuning muda.
	Peta ini memberikan gambaran yang sangat berguna untuk memahami distribusi tingkat pengangguran di provinsi ini. Data seperti ini dapat digunakan oleh pemerintah untuk perencanaan kebijakan dan program, seperti peningkatan lapangan pekerjaan atau pengentasan pengangguran di daerah-daerah yang memiliki angka pengangguran tinggi.
</p>
    </div>
</body>
</html>
