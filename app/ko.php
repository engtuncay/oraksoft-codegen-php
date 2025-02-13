<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Knockout.js Temel Örnek</title>
  <!-- Knockout.js Kütüphanesi -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-min.js"></script>
</head>
<body>
<h2>Merhaba, <span data-bind="text: name"></span>!</h2>

<input type="text" data-bind="value: name, valueUpdate: 'input'" />

<button data-bind="click: changeName">Adı 'Ali' Yap</button>

<script>
  function AppViewModel() {
    var self = this;  // this bağlamını korumak için

    // Gözlemlenebilir bir değişken oluşturuyoruz
    self.name = ko.observable("Ahmet");

    // Buton tıklandığında çalışacak fonksiyon
    self.changeName = function() {
      self.name("Ali");
    };
  }

  // Knockout'u başlatıyoruz
  ko.applyBindings(new AppViewModel());
</script>
</body>
</html>
