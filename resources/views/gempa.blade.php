<!DOCTYPE html>
<html lang="en">
<head>
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
     <!-- Make sure you put this AFTER Leaflet's CSS -->
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
        <div style= "text-align: center">
            <h1> Informasi Gempa Terkini di Indonesia </h1>
            <h3>Sumber data : BMKG </h3>
        </div>
        <div id="map"></div>
        <script>
            var map = L.map('map').setView([-0.3155398750904368, 117.1371634207888], 5);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{ maxZoom: 5,
              attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            let datas = {!! file_get_contents("https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.json")!!};
            // console.log(datas);
            let gempas = datas.Infogempa.gempa;
            let number = 1; 
            gempas.forEach(gempa => {
                let koordinat = gempa.Coordinates.split(",");
                let _lat = koordinat[0];
                let _long = koordinat[1];

                let marker = L.marker([_lat, _long]).addTo(map);
                marker.bindPopup(number + ") Waktu : "+ gempa.Tanggal + ":" + gempa.Jam + "<br>" + " Wilayah " + gempa.Wilayah + " Kedalaman : " + gempa.Kedalaman + "," + gempa.Magnitude + " SR " + "<br>" + " Potensi " + gempa.Potensi );
                number ++ ;
                
            });
        </script>
    </body>
</html>