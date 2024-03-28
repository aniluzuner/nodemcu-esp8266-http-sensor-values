<!DOCTYPE html>
<html lang="tr">
<head>
  <title>Sensörler</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <style>
    ::-webkit-scrollbar {
      width: 20px;
    }

    ::-webkit-scrollbar-track {
      background: #303030;
    }
    
    ::-webkit-scrollbar-thumb {
      background: #121212; 
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #151515; 
    }
  </style>
</head>
<body class="bg-black text-white" style="--bs-text-opacity: .9;">
  <script>
    function hareketleri_yaz(data){
      var satirlar = data.split("\n");

      var tablo = "";
      for (var i = satirlar.length - 2; i >= 0; i--){
        var sutunlar = satirlar[i].split("-");
        tablo += "<tr><td>" + sutunlar[0] + "</td><td>" + sutunlar[1] + "</td></tr>";
      }

      document.getElementById("hareketler").innerHTML = tablo;
    }

    function sicaklik_nem(data){
      var sicaklik = Number(data.split("-")[0]).toFixed(1);
      var nem = data.split("-")[1];

      document.getElementById("sicaklik").innerHTML = sicaklik + " °C";
      document.getElementById("nem").innerHTML = "%" + nem;
    }

    function verigetir(){
      fetch('records.txt', {cache: 'no-store'})
        .then(response => response.text())
        .then(data => hareketleri_yaz(data))
        .catch(error => console.error('Hata:', error));

      fetch('dht11.txt', {cache: 'no-store'})
        .then(response => response.text())
        .then(data => sicaklik_nem(data))
        .catch(error => console.error('Hata:', error));
    }
    setInterval(verigetir, 2000);
  </script>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-8 vh-100 p-3 pe-md-0">
        <div class="col-12 h-100 p-3 pe-md-0">
          <div class="w-100 h-100 rounded-3 bg-dark p-3 overflow-y-auto">
            <h1 class="lh-1 ms-1">Hareket Sensörü Kayıtları</h1>
            <table class="table table-dark table-hover rounded-3 overflow-hidden">
              <thead>
                <tr>
                  <th scope="col" class="fs-3">Saat</th>
                  <th scope="col" class="fs-3">Tarih</th>
                </tr>
              </thead>
              <tbody id="hareketler" class="fs-3">
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4 vh-100 p-3">
        <div class="col-12 h-50 p-3">
          <div class="w-100 h-100 rounded-3 d-flex align-items-center justify-content-center bg-dark">
            <div>
              <h1 class="text-center display-1">Sıcaklık</h1>
              <h2 class="text-center display-1 fw-bold" id="sicaklik"></h2>
            </div>
          </div>
        </div>
        <div class="col-12 h-50 p-3">
          <div class="w-100 h-100 rounded-3 d-flex align-items-center justify-content-center bg-dark">
            <div>
              <h1 class="text-center display-1">Nem</h1>
              <h2 class="text-center display-1 fw-bold" id="nem"></h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>