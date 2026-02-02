<?php
//require __DIR__ . '/../../vendor/autoload.php';
//require __DIR__ . '/fiAppImports.php';
view('fiAppImports.php');

//use Engtuncay\Phputils8\Log\FiLog;
//FiLog::initLogger('filog');

?>
<!DOCTYPE html>
<html lang="tr">

<head>
  <?php
  ?>
  <?php
  ?>
  <? //= view('fiHead.php') 
  ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Oraksoft Code Generator</title>
  <link rel="stylesheet" href="<?= base_url('orak_modules/bootstrap/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('orak_modules/oraksoft-tw-css-lib/oraksoft-tw-lib.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/codeblock.css') ?>">
  <!--<script type="module" crossorigin src="./assets/main.js"></script>-->
  <!--<script defer src="libs/cdn.min.js"></script>-->
  <!--<script src="http://ajax.aspnetcdn.com/ajax/knockout/knockout-3.5.0.js"></script>-->
  <!--<script src="libs/knockout-latest.js"></script>-->
  <!--<script src="fiapp.js"></script>-->
  <style>
    .fibody {
      background-image: url("<?= base_url('assets/img/ocg-background.jpeg') ?>");
    }
  </style>
  <!-- <link rel="stylesheet" href="codeblock.css"> -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>

<body class="fibody">
  <!---->
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="text-white card-header bg-primary">
            <h3 class="text-center card-title">Oraksoft Code Generator</h3>
          </div>

          <div class="card-body">
            <form action="<?= base_url('codegen') ?>" method="post" enctype="multipart/form-data">
              <div class="container">
                <div class="row">
                  <div class="mb-3 col-md-4">
                    <label for="selCsharp" class="form-label">Csharp</label>
                    <select name="selCsharp" id="selCsharp" class="form-select" aria-label="Csharp Operations">
                    </select>
                    <!--          <div class="form-text">note.</div>-->
                  </div>
                  <div class="mb-3 col-md-4">
                    <label for="selTs" class="form-label">Typescript</label>
                    <select class="form-select" aria-label="Ts Operations" name="selTs" id="selTs"></select>
                  </div>
                  <div class="mb-3 col-md-4">
                    <label for="selPhp" class="form-label">Php</label>
                    <select class="form-select" aria-label="Php Operations" name="selPhp" id="selPhp">
                    </select>
                  </div>
                  <div class="mb-3 col-md-4">
                    <label for="selJava" class="form-label">Java</label>
                    <select class="form-select" aria-label="Java Operations" name="selJava" id="selJava">
                    </select>
                  </div>
                  <div class="mb-3 col-md-4">
                    <label for="selSql" class="form-label">Sql</label>
                    <select class="form-select" aria-label="Sql Operations" name="selSql" id="selSql">
                    </select>
                  </div>

                </div>
              </div>
              <div class="mb-3">
                <label for="selEntity" class="form-label">Entity</label>
                <select class="form-select" aria-label="Entity Selection" name="selEntity" id="selEntity">
                </select>
              </div>
              <div class="mb-3">
                <script type="module"></script>
                <label for="excelFile" class="form-label">DML Dosyasını Seçin:</label>
                <input type="file" class="form-control" name="excelFile" id="excelFile"
                  accept=".xlsx, .xls, .csv" required>
                <div class="form-text">Sadece .xlsx, .xls veya .csv dosyaları yükleyebilirsiniz.</div>
              </div>
              <!-- <div class="mb-3">
                <label for="txActiveEntity" class="form-label">Active Entity</label>
                <input type="text" class="form-control" id="txActiveEntity" name="txActiveEntity" placeholder="active entity">
              </div> -->
              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Generate Code</button>
                <div class="form-check mt-1">
                  <input class="form-check-input" type="checkbox" value="" id="chkEnableDb" name="chkEnableDb">
                  <label class="form-check-label" for="chkEnableDb">
                    Db Active
                  </label>
                </div>
              </div>
              <div class="d-grid">
                <button class="btn btn-info m-1" data-action="readEntityList">Read Entity List</button>
                <!-- <button class="btn btn-info m-1" data-action="readEntities">Entity Oku</button> -->
                <!-- <button class="btn btn-info m-1" data-action="test">Test</button> -->
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container mt-3 tw-h-min-1 ">
    <!--code blok -->
    <div id="divCodeBlock" class="position-relative">
      <pre class="p-3 rounded"><code id="eleCode"></code>
        </pre>
      <button class="m-2 btn btn-sm btn-outline-dark position-absolute top-0 end-0 copy-btn"
        onclick="copyCode('eleCode')">
        Copy
      </button>
    </div>
  </div>

  <script>
    function copyCode(txIdName) {
      const code = document.getElementById(txIdName).innerText;
      navigator.clipboard.writeText(code).then(() => {
        //alert("Copied!");
      });
    }

    // @flow
    //let elementById1 = document.getElementById("#txaOutput");


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

  <script type="module">
    // import {
    //   fiPostFormData,
    //   fiBsModal,
    //   testOrakSoftUi,
    // } from './orak_modules/oraksoft-ui/oraksoft-ui.js';

    import {
      actReadEntityList
    } from '<?= base_url('assets/js/home-mod.js') ?>';

    const actions = {
      readEntityList() {
        actReadEntityList();
      },
      test() {
        alert('Test clicked!');
      }
    };

    // Attach click handlers only to buttons with data-action
    document.querySelectorAll('button[data-action]').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const action = btn.dataset.action;
        if (action && actions[action]) {
          actions[action]();
        }
      });
    });
  </script>

  <script src="<?= base_url('orak_modules/bootstrap/bootstrap.min.js') ?>"></script>
  <script type="module" src="<?= base_url('assets/js/main.js') ?>"></script>
  <script src="<?= base_url('assets/js/home.js') ?>"></script>
  <script type="module" src="<?= base_url('assets/js/home-mod.js') ?>"></script>
  <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>-->

</body>

</html>



<!-- <footer>
    <div class="environment">
        <p>Page rendered in {elapsed_time} seconds using {memory_usage} MB of memory.</p>
        <p>Environment: <? //= ENVIRONMENT 
                        ?></p>
    </div>

    <div class="copyrights">
        <p>&copy; <? //= date('Y') 
                  ?> CodeIgniter Foundation. CodeIgniter is open source project released under the MIT
            open source licence.</p>
    </div>

</footer> -->

<!-- SCRIPTS -->

<!-- <script {csp-script-nonce}> -->
<!-- document.getElementById("menuToggle").addEventListener('click', toggleMenu);
    function toggleMenu() {
        var menuItems = document.getElementsByClassName('menu-item');
        for (var i = 0; i < menuItems.length; i++) {
            var menuItem = menuItems[i];
            menuItem.classList.toggle("hidden");
        }
    }
</script> -->

<!-- -->