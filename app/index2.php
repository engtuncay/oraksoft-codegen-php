<?php
require 'fiAppImports.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orak Soft Code Generator</title>
  <!--    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">-->
  <script type="module" crossorigin src="./assets/main.js"></script>
  <!--<script src="http://ajax.aspnetcdn.com/ajax/knockout/knockout-3.5.0.js"></script>-->
  <!--<link rel="stylesheet" href="assets/index.css" />-->
  <!--  <script-->
  <!--    src="https://code.jquery.com/jquery-3.7.1.min.js"-->
  <!--    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="-->
  <!--    crossorigin="anonymous"></script>-->
  <script src="fiapp.js"></script>
  <style>
      .fibody {
          background-image: url("./img/ocg-background.jpeg");
      }
  </style>
</head>
<body class="fibody">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h3 class="card-title text-center">Orak Soft Code Generator</h3>
        </div>

        <div class="card-body">

          <form action="upload.php" method="post" enctype="multipart/form-data">

            <div class="container">
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="selectFi" class="form-label">Typescript:</label>
                  <select class="form-select" aria-label="Default select example" name="selectFi" id="selectFi">
                    <option value="-1" selected>Seçiniz</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                  <!--          <div class="form-text">note.</div>-->
                </div>
                <div class="col-md-4 mb-3">
                  <label for="selectFi" class="form-label">Typescript:</label>
                  <select class="form-select" aria-label="Default select example" name="selectFi2" id="selectFi2">
                    <option value="-1" selected>Seçiniz</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                  <!--          <div class="form-text">note.</div>-->
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="excelFile" class="form-label">Excel Dosyası Seçin:</label>
              <input type="file" class="form-control" name="excelFile" id="excelFile"
                     accept=".xlsx, .xls, .csv" required>
              <div class="form-text">Sadece .xlsx, .xls veya .csv dosyaları yükleyebilirsiniz.</div>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Yükle</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<h1 data-bind="text: message"></h1>
<button data-bind="click: updateMessage">Mesajı Güncelle</button>

<!--<h2>Merhaba <span data-bind="text: name"></span>!</h2>-->
<!--<input type="text" data-bind="value: name" />-->
<!--<button data-bind="click: changeName">Change Name</button>-->

<script>
    // function AppViewModel() {
    //     this.name = ko.observable("Ahmet");
    //     this.changeName = () => {
    //         this.name("Ali");
    //     };
    // }
    //
    // ko.applyBindings(new AppViewModel());
</script>

<script type="module" src="./assets/main.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>-->
<!--<script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>-->
</body>
</html>
