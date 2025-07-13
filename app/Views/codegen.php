<!DOCTYPE html>
<html lang="tr">
<head>
    <?php require __DIR__ . '/fiHead.php'; ?>
    <title>Orak Soft Code Generator</title>
    <link rel="stylesheet" href="codeblock.css">
</head>
<body class="fibody">
<h1>Codegen</h1>
<pre><?php echo htmlspecialchars(print_r($data ?? [], true)); ?></pre>
<!--page scripts-->
<script src="<?=base_url('libs/bootstrap.min.js')?>"></script>
<!--<script type="module" src="main.mjs"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>-->
<!--<script type="module" src="./assets/main.js"></script>-->
</body>
</html>