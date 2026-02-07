<?php
namespace App\Filters;

use Codegen\OcgConfigs\OcgConfig;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Engtuncay\Phputils8\FiApps\FiAppConfig;

class GlobalFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Her istekte çalışacak kod buraya yazılır
        // Örnek: loglama
        // UBOM AppConfig ayarlama
        log_message('info', 'GlobalFilter (before) was executed.');
        $ospConfig = new OcgConfig();
        FiAppConfig::$fiConfig = $ospConfig;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // İstekten sonra çalışacak kod (opsiyonel)
    }
}
