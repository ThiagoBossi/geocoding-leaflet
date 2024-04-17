<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <style>
        #map 
        { 
            height: 500px;
        }
    </style>

    <title>Geocoding | Leaflet</title>
</head>
<body>
    <div class="wrapper p-3">
        <div class="row g-2">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="my-auto">Geocoding</h2>
                </div>
            </div>
        </div>
        <div class="row g-2 mt-1">
            <div class="card">
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md">
                            <input type="text" class="form-control w-100" id="input-endereco" placeholder="Informe o Endereço"/>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" id="btn-buscar">Buscar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-2 mt-1">
            <div class="card">
                <div class="card-body">
                    <div id="map"></div>
                </div>
            </div>
        </div>
        <div class="row g-2 mt-1">
            <div class="card">
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md">
                            <button class="btn btn-secondary w-100 disabled" id="btn-anterior">Anterior</button>
                        </div>
                        <div class="col-md">
                            <button class="btn btn-secondary w-100 disabled" id="btn-proximo">Próximo</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            var map = L.map('map').setView([-20.9521444, -48.4795091], 17, { animation: true });
            var camada = L.tileLayer('http://{s}.google.com/vt?lyrs=s,h&x={x}&y={y}&z={z}',{
                maxZoom: 20,
                subdomains:['mt0','mt1','mt2','mt3']
            }).addTo(map);
            
            var resultado;
            var quantidadeResultados = 0;
            var atual = 0;

            $("#btn-buscar").on("click", function() {
                var endereco = $("#input-endereco").val();

                $.ajax({
                    url: "https://geocode.maps.co/search?q=" + endereco + "&api_key=",
                    type: "get",
                    success: function(result) {
                        if (result.length > 0) {
                            map.eachLayer((layer) => {
                                if(layer['_latlng']!=undefined)
                                    layer.remove();
                            });

                            quantidadeResultados = result.length;
                            resultado = result;

                            map.panTo(new L.LatLng(result[0]["lat"], result[0]["lon"]));

                            result.forEach(element => {
                                L.marker(new L.LatLng(element["lat"], element["lon"])).addTo(map);
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "O endereço informado não foi encontrado.",
                            });
                        }

                        if (result.length > 1) {
                            $("#btn-proximo").removeClass("disabled");
                        }
                    }
                })
            });

            $("#btn-proximo").on("click", function() {
                atual++;

                if (atual == (quantidadeResultados - 1)) 
                { 
                    $("#btn-proximo").addClass("disabled");
                }

                 $("#btn-anterior").removeClass("disabled");

                map.panTo(new L.LatLng(resultado[atual]["lat"], resultado[atual]["lon"]));
            });

            $("#btn-anterior").on("click", function() {
                atual--;

                if (atual == 0) {
                    $("#btn-proximo").removeClass("disabled");
                    $("#btn-anterior").addClass("disabled");
                }

                map.panTo(new L.LatLng(resultado[atual]["lat"], resultado[atual]["lon"]));
            });
        });
    </script>
</body>
</html>