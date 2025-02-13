<?php
// RouteHandler'ı dahil et
require 'FiAppImports.php';
require 'RouteHandler.php';

// Veritabanı bağlantısı
//$dsn = 'mysql:host=localhost;dbname=simple_api;charset=utf8';
//$username = 'your_username'; // MySQL kullanıcı adı
//$password = 'your_password'; // MySQL şifresi

//try {
//  $pdo = new PDO($dsn, $username, $password);
//  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//} catch (PDOException $e) {
//  http_response_code(500);
//  echo json_encode(['error' => 'Veritabanı bağlantı hatası: ' . $e->getMessage()]);
//  exit;
//}

$routeHandler = new RouteHandler();

// Get all tasks
$routeHandler->add('GET', 'tasks', function () { //use ($pdo)
  $tasks = ["todo1","todo2"];
//  $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
//  $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($tasks);
});

// Get a specific task by ID
//$routeHandler->add('GET', 'tasks/{id}', function ($params) use ($pdo) {
//  $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
//  $stmt->execute([$params['id']]);
//  $task = $stmt->fetch(PDO::FETCH_ASSOC);
//
//  if ($task) {
//    echo json_encode($task);
//  } else {
//    http_response_code(404);
//    echo json_encode(['error' => 'Görev bulunamadı']);
//  }
//});

// Create a new task
$routeHandler->add('POST', 'tasks', function () { //use ($pdo)
  $data = json_decode(file_get_contents('php://input'), true);

  $tasks = ["todo1","todo2", $data];
//  $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
//  $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($tasks);
//  if (!isset($data['title'])) {
//    http_response_code(400);
//    echo json_encode(['error' => 'Başlık gerekli']);
//    exit;
//  }
//
//  $stmt = $pdo->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
//  $stmt->execute([$data['title'], $data['description'] ?? '']);
//  echo json_encode(['message' => 'Görev oluşturuldu', 'id' => $pdo->lastInsertId()]);
  //echo json_encode([""]);
});

// Update a task by ID
//$routeHandler->add('PUT', 'tasks/{id}', function ($params) use ($pdo) {
//  $data = json_decode(file_get_contents('php://input'), true);
//
//  if (!isset($data['title'])) {
//    http_response_code(400);
//    echo json_encode(['error' => 'Başlık gerekli']);
//    exit;
//  }
//
//  $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, is_completed = ? WHERE id = ?");
//  $stmt->execute([$data['title'], $data['description'], $data['is_completed'], $params['id']]);
//  echo json_encode(['message' => 'Görev güncellendi']);
//});
//
//// Delete a task by ID
//$routeHandler->add('DELETE', 'tasks/{id}', function ($params) use ($pdo) {
//  $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
//  $stmt->execute([$params['id']]);
//  echo json_encode(['message' => 'Görev silindi']);
//});

// İşlemi başlat
$routeHandler->handle();