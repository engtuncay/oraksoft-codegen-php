<?php
//require __DIR__ . '/../../vendor/autoload.php';
//require __DIR__ . '/fiAppImports.php';

use Codegen\FiMetas\App\FkcOcgApp;
use Engtuncay\Phputils8\FiDtos\FiKeybean;

view('fiAppImports.php');

//use Engtuncay\Phputils8\Log\FiLog;
//FiLog::initLogger('filog');
// view fonksiyonu ile gönderilen assoc.array keyleri değişken olarak view içerisinde kullanılabilir.
//FkcOcgApp::fapDbProfiles()

//$fapDbProfiles

$fkbData = new FiKeybean($data);




?>
<!DOCTYPE html>
<html lang="tr">

<head>
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
<div>
<?php
//echo print_r($fkbData->getFkcVal(FkcOcgApp::fapDbProfiles()), true);
?>
</div>
  <!--fim-main-container-->
  <div id="fim" class="container mt-2">
    <!--fold-level-5-->
    <section id="fi1" class="row justify-content-center">
      <div id="fi11" class="col-md-12">
        <div class="card">
          <!--card-header -->
          <div class="card-header text-white bg-primary">
            <h3 class="text-center card-title">Oraksoft Code Generator</h3>
          </div>
          <!--card-body -->
          <div class="card-body">

            <div id="form">
              <form action="#" method="post" enctype="multipart/form-data">

                <div id="divSelBoxs" class="">
                  <div class="row">
                    <div class="mb-3 col-md-3">
                      <label for="selCsharp" class="form-label">Csharp</label>
                      <select name="selCsharp" id="selCsharp" class="form-select" aria-label="Csharp Operations">
                      </select>
                      <!--<div class="form-text">note.</div>-->
                    </div>
                    <div class="mb-3 col-md-3">
                      <label for="selPhp" class="form-label">Php</label>
                      <select class="form-select" aria-label="Php Operations" name="selPhp" id="selPhp">
                      </select>
                    </div>
                    <div class="mb-3 col-md-3">
                      <label for="selJava" class="form-label">Java</label>
                      <select class="form-select" aria-label="Java Operations" name="selJava" id="selJava">
                      </select>
                    </div>
                    <div class="mb-3 col-md-3">
                      <label for="selTs" class="form-label">Typescript</label>
                      <select class="form-select" aria-label="Ts Operations" name="selTs" id="selTs"></select>
                    </div>
                    <div class="mb-3 col-md-3">
                      <label for="selJs" class="form-label">Javascript</label>
                      <select class="form-select" aria-label="Javascript Operations" name="selJs" id="selJs">
                      </select>
                    </div>
                    <div class="mb-3 col-md-3">
                      <label for="selSql" class="form-label">Ms-Sql</label>
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
                  <label for="excelFile" class="form-label">DML (Data Model) File:</label>
                  <input type="file" class="form-control" name="excelFile" id="excelFile" accept=".xlsx, .xls, .csv" required>
                  <div class="form-text">Only .xlsx, .xls veya .csv file types</div>
                </div>

                <div class="d-flex">
                  <button class="btn btn-info m-1 " data-action="actReadEntityList">Read Entity List</button>
                  <button class="btn btn-primary m-1" data-action="actGenCode">Generate Code</button>
                  <button class="btn btn-secondary m-1" data-action="actExecCommand">Exec Command</button>
                </div>

                <!-- Db Active - Password Panel -->
                <div id="dbActivePanel" class="">
                  <div class="row g-3 m-1 align-items-center">
                    <div class="col-auto d-flex flex-row gap-2 align-items-center">
                      <label for="selDbProfile" class="text-nowrap">Db Profile</label>
                      <select class="form-select" aria-label="Db Profiles" name="selDbProfile" id="selDbProfile">
                        <?php
                        $arrDbProfiles = $fkbData->getFkcVal(FkcOcgApp::fapDbProfiles());
                        ?>
                        <?php foreach ((array)$arrDbProfiles as $profile): ?>
                          <option value="<?= htmlspecialchars($profile, ENT_QUOTES) ?>"><?= htmlspecialchars($profile) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-auto">
                      <input class="form-check-input" type="checkbox" value="" id="chkEnableDb" name="chkEnableDb">
                      <label class="form-check-label" for="chkEnableDb">
                        Db Active
                      </label>
                    </div>
                    <div class="col-auto ">
                      <label for="txDbPsw" class="visually-hidden">Password</label>
                      <input type="password" class="form-control" id="txDbPsw" name="txDbPsw" placeholder="Password">
                    </div>

                    <div class="container p-0 mt-2">
                      <div class="row">
                        <div class=" mb-3">
                          <!-- <label for="txCustomCmd" class="form-label">Custom Command</label> -->
                          <input type="text" class="form-control" id="txCustomCmd" name="txCustomCmd" placeholder="Custom Command">
                        </div>
                      </div>
                      <div class="row">
                        <div class="">
                          <div><h6>Example Commands</h6></div>
                          <!-- <label for="txCustomCmd" class="form-label">Custom Command</label> -->
                          <div><pre class="mb-1"> --cmd dml --table TABLENAME --prefix pref</pre></div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>

              </form>
            </div>

          </div>
          <!-- end card-body -->
        </div>
        <!-- end card -->
      </div>
    </section>

    <!-- code-block -->
    <section id="fi2" class="row mt-3 tw-h-min-1">
      <div id="fi21" class="col-12 position-relative">
        <div id="fi211" class="bg-black">
          <!-- <pre class="p-3 rounded"> -->
          <code class="p-3 rounded" id="divCodeBlock" class="divCodeBlock">
          </code>
          <!-- </pre> -->
          <button class="m-2 btn btn-sm btn-outline-dark position-absolute top-0 end-0 copy-btn"
            onclick="copyCode('divCodeBlock')">
            Copy
          </button>
        </div>
      </div>
    </section>
    <!-- end-code-block -->

  </div>
  <!--end-main-container -->

  <!--script-block -->
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
      actExecCommand,
      viewHomeInit
    } from '<?= base_url('js/viewHome.js') ?>';

    viewHomeInit();

    const actions = {
      actGenCode() {
        //alert('Generate Code clicked!');
        actGenCode();
      },
      actReadEntityList() {
        actReadEntityList();
      },
      actExecCommand() {
        actExecCommand();
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
  <script type="module" src="<?= base_url('js/main.js') ?>"></script>
  <script type="module" src="<?= base_url('js/viewHome.js') ?>"></script>
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