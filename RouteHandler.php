<?php

class RouteHandler
{
  private $routes = [];

  // Route ekleme
  public function add($method, $path, $callback)
  {
    $this->routes[] = ['method' => $method, 'path' => $path, 'callback' => $callback];
  }

  // Route'a göre işlemi çalıştırma
  public function handle()
  {
    $method = $_SERVER['REQUEST_METHOD'];
    $path = trim($_SERVER['PATH_INFO'], '/');

    foreach ($this->routes as $route) {
      if ($route['method'] === $method && $this->match($path, $route['path'], $params)) {
        call_user_func($route['callback'], $params);
        return;
      }
    }

    // 404 - Route bulunamadı
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
  }

  // URL'yi kontrol etme ve parametreleri çıkarma
  private function match($path, $routePath, &$params)
  {
    $pathParts = explode('/', $path);
    $routeParts = explode('/', $routePath);

    if (count($pathParts) != count($routeParts)) {
      return false;
    }

    $params = [];
    foreach ($routeParts as $index => $part) {
      if (preg_match('/\{(\w+)\}/', $part, $matches)) {
        $params[$matches[1]] = $pathParts[$index];
      } elseif ($part !== $pathParts[$index]) {
        return false;
      }
    }

    return true;
  }
}
