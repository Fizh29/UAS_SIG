<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIG BANGKA BELITUNG</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
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
            display: inline-block;
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

        .content {
            padding: 20px;
            background-color: #f9f9f9;
            line-height: 1.6;
            border-bottom: 1px solid #ccc;
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
                height: 400px;
            }
        }
    </style>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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

    <!-- Article Section -->
    <div class="content">
        <h2>Peta Interaktif Bangka Belitung</h2>
        <p>
            Provinsi Kepulauan Bangka Belitung adalah daerah dengan potensi besar tetapi menghadapi tantangan seperti akses informasi yang terbatas. 
            Sistem Informasi Geografis (SIG) membantu memetakan data tematik seperti tingkat partisipasi angkatan kerja, tingkat pengangguran terbuka, 
            dan umur harapan hidup. Dengan SIG, kebijakan berbasis data dapat dirancang untuk meningkatkan kesejahteraan masyarakat di Bangka Belitung.
        </p>
    </div>

    <!-- Map Container -->
    <div id="map"></div>

    <script>
        // Initialize the map
        var map = L.map('map').setView([-2.5, 107.0], 8);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap contributors'
        }).addTo(map);

        var provinces = @json($list_provinsi);
        provinces.forEach(function(province) {
            var marker = L.marker([province.latitude, province.longitude]).addTo(map);
            marker.bindPopup(`
                <b>${province.name}</b><br>
                Latitude: ${province.latitude}<br>
                Longitude: ${province.longitude}
            `);
        });
    </script>
</body>
</html>
