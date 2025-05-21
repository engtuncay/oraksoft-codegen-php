<?php
require 'fiAppImports.php';

use Engtuncay\Phputils8\Meta\Fdr;
use Engtuncay\Phputils8\Log\FiLog;

FiLog::initLogger('filog');

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <?php require 'fiHead.php'; ?>
    <!-- <link rel="stylesheet" href="codeblock.css"> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body class="fibody">
<!---->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title text-center">Orak Soft Code Generator</h3>
                </div>

                <div class="card-body">
                    <form action="codegen.php" method="post" enctype="multipart/form-data">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="selCsharp" class="form-label">Csharp:</label>
                                    <select name="selCsharp" id="selCsharp" class="form-select" aria-label="Csharp Operations" >
                                    </select>
                                    <!--          <div class="form-text">note.</div>-->
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="selTs" class="form-label">Typescript:</label>
                                    <select class="form-select" aria-label="Ts Operations" name="selTs" id="selTs"></select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="selPhp" class="form-label">Php:</label>
                                    <select class="form-select" aria-label="Php Operations" name="selPhp" id="selPhp">
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="selJava" class="form-label">Java:</label>
                                    <select class="form-select" aria-label="Java Operations" name="selJava" id="selJava">
                                    </select>
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
                            <button type="submit" class="btn btn-primary">Generate Code</button>

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

    // function copyCode() {
    //     const code = document.getElementById("code-snippet").innerText;
    //     navigator.clipboard.writeText(code).then(() => {
    //         //alert("Copied!");
    //     });
    // }

    // @flow
    // let elementById1 = document.getElementById("#txaOutput");

    // Butonun tıklanmasıyla fonksiyonu çalıştır
    //document.getElementById("copy-btn").addEventListener("click", copyCode);

    //document.getElementById("#txaOutput").textContent = fkb.toString();
    // for (const fkbElement of fkb) {
    //     console.log(fkbElement);
    //     document.getElementById("#txaOutput").textContent = fkbElement;
    // }
    //console.log(fkb);

    // function AppViewModel() {
    //     var self = this;
    //
    //     // Renk seçenekleri
    //     self.colors = [
    //         {name: "Kırmızı", code: "#FF0000"},
    //         {name: "Yeşil", code: "#00FF00"},
    //         {name: "Mavi", code: "#0000FF"},
    //         {name: "Sarı", code: "#FFFF00"}
    //     ];
    //
    //     // Seçilen renk
    //     self.selectedColor = ko.observable(self.colors[0].code); // Varsayılan olarak ilk renk seçili
    //
    //     // Seçilen renk değeri değiştikçe burada bir şeyler yapılabilir.
    // }

    // ko.applyBindings(new AppViewModel());
</script>
<script type="module" src="main.js"></script>
<script src="libs/bootstrap.min.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>-->
<!--<script type="module" src="./assets/main.js"></script>-->
</body>
</html>