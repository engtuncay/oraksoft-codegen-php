<?php
require 'fiAppImports.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orak Soft Code Generator</title>
    <link rel="stylesheet" href="libs/bootstrap.min.css">
    <!--    <script type="module" crossorigin src="./assets/main.js"></script>-->
    <!--    <script defer src="libs/cdn.min.js"></script>-->
    <!--<script src="http://ajax.aspnetcdn.com/ajax/knockout/knockout-3.5.0.js"></script>-->
    <script src="libs/knockout-latest.js"></script>
    <!--<script src="fiapp.js"></script>-->
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

                    <form @submit.prevent="validateForm" action="upload.php" method="post"
                          enctype="multipart/form-data">

                        <div class="container">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="selectFi" class="form-label">Csharp:</label>
                                    <select class="form-select" aria-label="Default select example" name="selectFi"
                                            id="selectFi">
                                        <option value="-1" selected>Seçiniz</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                    <!--          <div class="form-text">note.</div>-->
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="selectFi" class="form-label">Typescript:</label>
                                    <select class="form-select" aria-label="Default select example" name="selectFi2"
                                            id="selectFi2">
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

<!--<h2>Renk Seçin</h2>-->
<!---->
<!-- Select elementi -->
<!--<select data-bind="options: colors, value: selectedColor, optionsText: 'name', optionsValue: 'code'">-->
<!--</select>-->
<!---->
<!-- Seçilen rengi göster -->
<!--<p>Seçilen Renk: <span data-bind="text: selectedColor"></span></p>-->


<script>
    function AppViewModel() {
        var self = this;

        // Renk seçenekleri
        self.colors = [
            {name: "Kırmızı", code: "#FF0000"},
            {name: "Yeşil", code: "#00FF00"},
            {name: "Mavi", code: "#0000FF"},
            {name: "Sarı", code: "#FFFF00"}
        ];

        // Seçilen renk
        self.selectedColor = ko.observable(self.colors[0].code); // Varsayılan olarak ilk renk seçili

        // Seçilen renk değeri değiştikçe burada bir şeyler yapılabilir.
    }

    ko.applyBindings(new AppViewModel());
</script>
<script src="libs/bootstrap.min.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>-->
<!--<script type="module" src="./assets/main.js"></script>-->
</body>
</html>