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
  <!-- <link rel="stylesheet" href="<? //= base_url('orak_modules/oraksoft-tw-css-lib/oraksoft-tw-lib.css') 
                                    ?>"> -->
  <link rel="stylesheet" href="<?= base_url('css/codeblock.css') ?>">
  <link rel="stylesheet" href="<?= base_url('output.css') ?>">
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->

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
  <div id="fim" class="container mx-auto mt-2 px-4">
    <!--fold-level-5-->
    <section id="fi1" class="flex justify-center">
      <div id="fi11" class="w-full">
        <div class="border rounded-lg shadow-lg">
          <!--card-header -->
          <div class="bg-blue-500 text-white rounded-t-lg p-4">
            <h3 class="text-center text-2xl font-bold">Oraksoft Code Generator</h3>
          </div>
          <!--card-body -->
          <div class="p-6">

            <div id="form">
              <form action="#" method="post" enctype="multipart/form-data">

                <div id="divSelBoxs" class="">
                  <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    <div class="mb-3">
                      <label for="selCsharp" class="block text-sm font-medium mb-2">Csharp</label>
                      <select name="selCsharp" id="selCsharp" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" aria-label="Csharp Operations">
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="selPhp" class="block text-sm font-medium mb-2">Php</label>
                      <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" aria-label="Php Operations" name="selPhp" id="selPhp">
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="selJava" class="block text-sm font-medium mb-2">Java</label>
                      <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" aria-label="Java Operations" name="selJava" id="selJava">
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="selTs" class="block text-sm font-medium mb-2">Typescript</label>
                      <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" aria-label="Ts Operations" name="selTs" id="selTs"></select>
                    </div>
                    <div class="mb-3">
                      <label for="selJs" class="block text-sm font-medium mb-2">Javascript</label>
                      <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" aria-label="Javascript Operations" name="selJs" id="selJs">
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="selSql" class="block text-sm font-medium mb-2">Ms-Sql</label>
                      <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" aria-label="Sql Operations" name="selSql" id="selSql">
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="selMysql" class="block text-sm font-medium mb-2">MySQL</label>
                      <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" aria-label="MySQL Operations" name="selMysql" id="selMysql">
                      </select>
                    </div>

                  </div>
                </div>

                <div class="mb-4">
                  <label for="selEntity" class="block text-sm font-medium mb-2">Entity</label>
                  <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" aria-label="Entity Selection" name="selEntity" id="selEntity">
                  </select>
                </div>

                <div class="mb-4">
                  <label for="excelFile" class="block text-sm font-medium mb-2">DML (Data Model) File:</label>
                  <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" name="excelFile" id="excelFile" accept=".xlsx, .xls, .csv" required>
                  <div class="text-xs text-gray-600 mt-1">Only .xlsx, .xls veya .csv file types</div>
                </div>

                <div class="flex flex-wrap gap-2">
                  <button class="px-4 py-2 bg-cyan-500 text-white rounded hover:bg-cyan-600 transition" data-action="actReadEntityList">Read Entity List</button>
                  <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition" data-action="actGenCode">Generate Code</button>
                  <button class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition" data-action="actExecCommand">Exec Command</button>
                  <button class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition" data-action="actExecSql">Exec Sql</button>
                </div>

                <!-- Db Active - Password Panel -->
                <div id="dbActivePanel" class="mt-6 pt-6 border-t border-gray-300">
                  <div class="space-y-4">
                    <div class="flex flex-wrap gap-4 items-end">
                      <div class="flex items-center gap-2">
                        <label for="selDbProfile" class="whitespace-nowrap text-sm font-medium">Db Profile</label>
                        <select class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" aria-label="Db Profiles" name="selDbProfile" id="selDbProfile">
                          <?php
                          $arrDbProfiles = $fkbData->getFkcVal(FkcOcgApp::fapDbProfiles());
                          ?>
                          <?php foreach ((array)$arrDbProfiles as $profile): ?>
                            <option value="<?= htmlspecialchars($profile, ENT_QUOTES) ?>"><?= htmlspecialchars($profile) ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="flex items-center gap-2">
                        <input class="w-4 h-4 text-blue-500 rounded focus:ring-2 focus:ring-blue-500" type="checkbox" value="" id="chkEnableDb" name="chkEnableDb">
                        <label class="text-sm font-medium" for="chkEnableDb">
                          Db Active
                        </label>
                      </div>
                      <div class="">
                        <label for="txDbPsw" class="sr-only">Password</label>
                        <input type="password" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="txDbPsw" name="txDbPsw" placeholder="Password">
                      </div>
                    </div>

                    <div class="space-y-4">
                      <div class="">
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="txCustomCmd" name="txCustomCmd" placeholder="Custom Command">
                      </div>

                      <div class="">
                        <div>
                          <h6 class="font-semibold text-sm mb-2">Example Commands</h6>
                        </div>
                        <div>
                          <pre class="bg-gray-100 p-3 rounded text-xs overflow-auto">--cmd dml --table TABLENAME --prefix pref
--cmd cuid --count 30
--cmd uid --count 50
--cmd sfid --count 50
</pre>
                        </div>
                      </div>

                      <div class="">
                        <div>
                          <h6 class="font-semibold text-sm mb-2">Command Messages</h6>
                        </div>
                        <div>
                          <pre id="elePreCommMess" class="bg-gray-100 p-3 rounded text-xs overflow-auto"></pre>
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
    <section id="fi2" class="mt-6">
      <div id="fi21" class="relative">
        <div id="fi211" class="bg-black rounded-lg">
          <code class="p-4 rounded-lg block text-white font-mono text-sm overflow-x-auto" id="divCodeBlock">
          </code>
          <button class="absolute top-4 right-4 px-3 py-1 bg-gray-700 hover:bg-gray-800 text-white text-sm rounded copy-btn transition"
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
    // } from './orak_modules/orak-util-js/orak-util-js.js';

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

  <script type="module" src="<?= base_url('js/main.js') ?>"></script>
  <script type="module" src="<?= base_url('js/viewHome.js') ?>"></script>

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