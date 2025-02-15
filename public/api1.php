<?php
//require 'fiAppImports.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orak Soft Code Generator</title>
    <script src="fiapp.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/knockout/knockout-3.5.0.js"></script>
    <style>
        /*.fibody {*/
        /*    background-image: url("./img/ocg-background.jpeg");*/
        /*}*/
    </style>
</head>
<body class="fibody">
<h1 data-bind="text: message"></h1>
<button data-bind="click: updateMessage">Mesajı Güncelle</button>

<script type="module">
    // import * as ko from "knockout";
    class AppViewModel {
        message = ko.observable("Merhaba, Knockout.js ve Vite!");

        updateMessage() {
            this.message("Güncellendi!");
        }
    }

    const vm = new AppViewModel();
    ko.applyBindings(vm);
</script>

</body>
</html>