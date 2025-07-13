<!DOCTYPE html>
<html lang="tr">
<head>
    <?php //require __DIR__ . '/fiHead.php' ?>
    <?php 
    // [[./fiHead.php]] 
    ?>    
    <?= view('fiHead.php')?>
    <title>Orak Soft Code Generator</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/codeblock.css')?>">
</head>
<body class="fibody">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="text-white card-header bg-success">
                    <h3 class="text-center card-title">Code Generation Results</h3>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($fdrData) && $fdrData->getMessage()): ?>
                        <div class="alert alert-warning">
                            <?= htmlspecialchars($fdrData->getMessage()) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($arrDtoCodeGenPack) && !empty($arrDtoCodeGenPack)): ?>
                        <h4>Generated Code:</h4>
                        <?php foreach ($arrDtoCodeGenPack as $index => $dtoCodeGen): ?>
                            <div class="mb-4">
                                <h5>Code Block <?= $index + 1 ?> (ID: <?= htmlspecialchars($dtoCodeGen->getDcgId()) ?>)</h5>
                                <div class="code-container">
                                    <button class="btn btn-sm btn-outline-secondary float-end copy-btn" 
                                            onclick="copyToClipboard('code-<?= $index ?>')">
                                        Copy
                                    </button>
                                    <pre id="code-<?= $index ?>" class="code-block"><code><?= htmlspecialchars($dtoCodeGen->getSbCodeGen()->toString()) ?></code></pre>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (isset($sbTxCodeGen) && $sbTxCodeGen->length() > 0): ?>
                        <div class="mb-4">
                            <h5>Additional Generated Code:</h5>
                            <div class="code-container">
                                <button class="btn btn-sm btn-outline-secondary float-end copy-btn" 
                                        onclick="copyToClipboard('additional-code')">
                                    Copy
                                </button>
                                <pre id="additional-code" class="code-block"><code><?= htmlspecialchars($sbTxCodeGen->toString()) ?></code></pre>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($txCodeGenExtra) && !empty($txCodeGenExtra)): ?>
                        <div class="mb-4">
                            <h5>Extra Data:</h5>
                            <pre class="code-block"><?= htmlspecialchars($txCodeGenExtra) ?></pre>
                        </div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="<?= base_url('/') ?>" class="btn btn-primary">Generate New Code</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
<script src="<?=base_url('libs/bootstrap.min.js')?>"></script>
<!--<script type="module" src="main.mjs"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>-->
<!--<script type="module" src="./assets/main.js"></script>-->
</body>
</html>