<?php


// Veritabanı bağlantısı
$dsn = 'mysql:host=localhost;dbname=your_database;charset=utf8';
$username = 'your_username';
$password = 'your_password';

try {
  $pdo = new PDO($dsn, $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Veritabanından şehirleri çek
  $stmt = $pdo->query("SELECT id, city_name FROM cities");
  $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // JSON formatında döndür
  header('Content-Type: application/json');
  echo json_encode($cities);

} catch (PDOException $e) {
  echo json_encode(['error' => $e->getMessage()]);
}

