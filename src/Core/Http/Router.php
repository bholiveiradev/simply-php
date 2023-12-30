<?php

declare(strict_types=1);

namespace App\Core\Http;

use Closure;

class Router
{
    private static array    $routes = [];
    private static array    $middlewares = [];
    private static ?string  $prefix = null;

    private static function addRoute(string $method, string $uri, mixed $handler, array $middlewares): void
    {
        self::$routes[] = [
            'method' => $method,
            'uri' => self::$prefix . rtrim($uri, '/') . '/',
            'handler' => $handler,
            'middlewares' => array_merge(self::$middlewares, $middlewares),
        ];
    }

    private static function addMiddlewares(array $middlewares): void
    {
        self::$middlewares = array_merge(self::$middlewares, $middlewares);
    }

    public static function middlewares(array $middlewares = []): self
    {
        self::addMiddlewares($middlewares);
        return new static;
    }
    
    public static function get(string $path, mixed $handler, array $middlewares = []): void
    {
        self::addRoute('GET', $path, $handler, $middlewares);
    }

    public static function post(string $path, mixed $handler, array $middlewares = []): void
    {
        self::addRoute('POST', $path, $handler, $middlewares);
    }

    public static function put(string $path, mixed $handler, array $middlewares = []): void
    {
        self::addRoute('PUT', $path, $handler, $middlewares);
    }

    public static function delete(string $path, mixed $handler, array $middlewares = []): void
    {
        self::addRoute('DELETE', $path, $handler, $middlewares);
    }

    public static function group(string $prefix, Closure $callback, array $middlewares = []): void
    {
        self::addMiddlewares($middlewares);
        self::$prefix = self::$prefix . $prefix;
        $callback(self::class);
    }

    public static function routes(): array
    {
        return self::$routes;
    }
}