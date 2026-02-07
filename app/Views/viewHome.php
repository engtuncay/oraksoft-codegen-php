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

  <style>
    .fibody {
      background-image: url("<?= base_url('assets/img/ocg-background.jpeg') ?>");
    }
  </style>

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
                    <!--<div class="form-text">note.</div>-->
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
                    <label for="selTs" class="form-label">Typescript</label>
                    <select class="form-select" aria-label="Ts Operations" name="selTs" id="selTs"></select>
                  </div>
                  <div class="mb-3 col-md-4">
                    <label for="selJs" class="form-label">Javascript</label>
                    <select class="form-select" aria-label="Javascript Operations" name="selJs" id="selJs">
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
                <label for="excelFile" class="form-label">DML (Data Model) File:</label>
                <input type="file" class="form-control" name="excelFile" id="excelFile"
                  accept=".xlsx, .xls, .csv" required>
                <div class="form-text">Only .xlsx, .xls veya .csv file types</div>
              </div>
              <!-- <div class="mb-3">
                <label for="txActiveEntity" class="form-label">Active Entity</label>
                <input type="text" class="form-control" id="txActiveEntity" name="txActiveEntity" placeholder="active entity">
              </div> -->
              
              <div class="d-grid">
                <button class="btn btn-info m-1 " data-action="actReadEntityList">Read Entity List</button>
                <button type="submit" class="btn btn-primary m-1" data-action="actGenCode">Generate Code</button>
              </div>
              
              <!-- Db Active - Password Panel -->
              <div class="container p-0" id="dbActivePanel">
              <div class="row g-3 m-1 align-items-center">
                <div class="col-auto">
                  <input class="form-check-input" type="checkbox" value="" id="chkEnableDb" name="chkEnableDb">
                  <label class="form-check-label" for="chkEnableDb">
                    Db Active
                  </label>
                </div>
                <div class="col-auto ">
                  <label for="inputPassword2" class="visually-hidden">Password</label>
                  <input type="password" class="form-control" id="inputPassword2" placeholder="Password">
                </div>
                
              </div>
              </div>

          </div>
          <div class="d-grid">

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
    <div id="" class="position-relative bg-black">
      <!-- <pre class="p-3 rounded"> -->
      <code class="p-3 rounded" id="divCodeBlock">
      </code>
      <!-- </pre> -->
      <button class="m-2 btn btn-sm btn-outline-dark position-absolute top-0 end-0 copy-btn"
        onclick="copyCode('divCodeBlock')">
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
  </script>

  <script type="module">
    // import {
    //   fiPostFormData,
    //   fiBsModal,
    //   testOrakSoftUi,
    // } from './orak_modules/oraksoft-ui/oraksoft-ui.js';

    import {
      actReadEntityList,
      actGenCode,
      viewHomeInit
    } from '<?= base_url('assets/js/viewHome.js') ?>';

    viewHomeInit();

    const actions = {
      actGenCode() {
        //alert('Generate Code clicked!');
        actGenCode();
      },
      actReadEntityList() {
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
  <script type="module" src="<?= base_url('assets/js/viewHome.js') ?>"></script>
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