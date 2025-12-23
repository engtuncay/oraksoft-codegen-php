<?php
use Codegen\OcdConfig\OcgLogger;
?>
<!DOCTYPE html>
<html lang="tr">

<head>
  <?php //require __DIR__ . '/fiHead.php' 
  ?>
  <?php
  // (fihead)[./fiHead.php]

  ?>
  <?= view('fiHead.php') ?>
  <title>Orak Soft Code Generator</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/codeblock.css') ?>">
</head>

<body class="fibody">

  <?php
  // Değişkenlerin tanımlı olup olmadığını kontrol et
  $arrDtoCodeGenPack = $arrDtoCodeGenPack ?? [];
  $txCodeGenExtra = $txCodeGenExtra ?? '';
  //OcgLogger::info('arrDtoCodeGenPack count:' . count($arrDtoCodeGenPack))
  ?>

  <?php foreach ($arrDtoCodeGenPack as $arrDtoCdg) { ?>
    <div class="container mt-3">
      <!--code blok -->
      <div class="position-relative">
        <pre class="p-3 rounded"><code id="<?= $arrDtoCdg->getDcgId() ?>"><?= $arrDtoCdg->getSbCodeGen()->toString(); ?></code>
        </pre>
        <button class="top-0 m-2 btn btn-sm btn-outline-light position-absolute end-0 copy-btn"
          onclick="copyCode('<?= trim($arrDtoCdg->getDcgId()) ?>')">
          Copy
        </button>
      </div>
    </div>
  <?php } ?>

  <?php if (!empty($txCodeGenExtra)) { ?>
    <div class="container mt-3">
      <!--code blok -->
      <div class="position-relative">
        <pre class="p-3 rounded">
        <code id="code-snippet-extra"><?= $txCodeGenExtra ?></code>
    </pre>
        <button class="top-0 m-2 btn btn-sm btn-outline-light position-absolute end-0 copy-btn"
          onclick="copyCodeExtra()">
          Copy
        </button>
      </div>
    </div>
  <?php } ?>

  <script>
    function copyCode() {
      const code = document.getElementById("code-snippet").innerText;
      navigator.clipboard.writeText(code).then(() => {
        //alert("Copied!");
      });
    }

    function copyCode(txIdName) {
      const code = document.getElementById(txIdName).innerText;
      navigator.clipboard.writeText(code).then(() => {
        //alert("Copied!");
      });
    }

    function copyCodeExtra() {
      const code = document.getElementById("code-snippet-extra").innerText;
      navigator.clipboard.writeText(code).then(() => {
        //alert("Copied!");
      });
    }
  </script>


  <script>
    function copyToClipboard(elementId) {
      const element = document.getElementById(elementId);
      const text = element.textContent;

      navigator.clipboard.writeText(text).then(function() {
        // Başarı mesajı göster
        const btn = element.parentElement.querySelector('.copy-btn');
        const originalText = btn.textContent;
        btn.textContent = 'Copied!';
        btn.classList.add('btn-success');
        btn.classList.remove('btn-outline-secondary');

        setTimeout(function() {
          btn.textContent = originalText;
          btn.classList.remove('btn-success');
          btn.classList.add('btn-outline-secondary');
        }, 2000);
      }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Copy failed!');
      });
    }
  </script>

  <!--page scripts-->
  <script src="<?= base_url('libs/bootstrap.min.js') ?>"></script>
  <!--<script type="module" src="main.mjs"></script>-->
  <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>-->
  <!--<script type="module" src="./assets/main.js"></script>-->
</body>

</html>