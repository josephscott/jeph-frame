<?php
declare( strict_types = 1 );

namespace JEPH;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\cachedDispatcher;
use function FastRoute\simpleDispatcher;
use function http_response_code;
use function is_array;
use function is_callable;
use function is_string;
use function rawurldecode;
use function strtok;
use function strtoupper;
use function substr;

class Frame {
	private const METHODS = [ 'GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD' ];

	private array $routes = [];

	private ?string $cache_file = null;

	private string $request_method;

	private string $request_uri;

	public function __construct() {
		$this->request_method = strtoupper( $_SERVER['REQUEST_METHOD'] );
		$this->request_uri = strtok( rawurldecode( $_SERVER['REQUEST_URI'] ), '?' );
	}

	public function set_cache_file( string $path ): void {
		$this->cache_file = $path;
	}

	public function get(
		string $path,
		string|callable|array $callback
	): void {
		$this->routes[] = [
			'path' => $path,
			'callback' => $callback,
			'method' => 'GET',
		];
	}

	public function post(
		string $path,
		string|callable|array $callback
	): void {
		$this->routes[] = [
			'path' => $path,
			'callback' => $callback,
			'method' => 'POST',
		];
	}

	public function put(
		string $path,
		string|callable|array $callback
	): void {
		$this->routes[] = [
			'path' => $path,
			'callback' => $callback,
			'method' => 'PUT',
		];
	}

	public function delete(
		string $path,
		string|callable|array $callback
	): void {
		$this->routes[] = [
			'path' => $path,
			'callback' => $callback,
			'method' => 'DELETE',
		];
	}

	public function patch(
		string $path,
		string|callable|array $callback
	): void {
		$this->routes[] = [
			'path' => $path,
			'callback' => $callback,
			'method' => 'PATCH',
		];
	}

	public function options(
		string $path,
		string|callable|array $callback
	): void {
		$this->routes[] = [
			'path' => $path,
			'callback' => $callback,
			'method' => 'OPTIONS',
		];
	}

	public function head(
		string $path,
		string|callable|array $callback
	): void {
		$this->routes[] = [
			'path' => $path,
			'callback' => $callback,
			'method' => 'HEAD',
		];
	}

	public function any(
		string $path,
		string|callable|array $callback
	): void {
		foreach ( self::METHODS as $method ) {
			$this->routes[] = [
				'path' => $path,
				'callback' => $callback,
				'method' => $method,
			];
		}
	}

	public function call_handler(
		string|callable|array $handler,
		array $vars = []
	): void {
		if ( is_string( $handler ) ) {
			( function( string $__file, array $_frame ) {
				require $__file;
			} )( $handler, $vars );
			return;
		}

		if ( is_array( $handler ) ) {
			$class = $handler[0];
			$class = new $class();
			$method = $this->request_method;
			$class->$method( $vars );
			return;
		}

		if ( is_callable( $handler ) ) {
			$handler( $vars );
		}
	}

	public function run(): void {
		$route_definition = function( RouteCollector $collector ) {
			foreach ( $this->routes as $route ) {
				$collector->addRoute(
					$route['method'],
					$route['path'],
					$route['callback']
				);
			}
		};

		if ( $this->cache_file !== null ) {
			$dispatcher = cachedDispatcher(
				$route_definition,
				[ 'cacheFile' => $this->cache_file ]
			);
		} else {
			$dispatcher = simpleDispatcher( $route_definition );
		}

		$match = $dispatcher->dispatch(
			$this->request_method,
			$this->request_uri
		);
		switch ( $match[0] ) {
			case Dispatcher::NOT_FOUND:
				// Catch URLs that include a trailing slash and
				// redirect without it
				if ( substr( $this->request_uri, -1 ) === '/' ) {
					http_response_code( 302 );
					header(
						'Location: ' . substr( $this->request_uri, 0, -1 )
					);
					exit;
				}

				http_response_code( 404 );
				echo '404 Not Found';
				break;
			case Dispatcher::METHOD_NOT_ALLOWED:
				http_response_code( 405 );
				echo '405 Method Not Allowed';
				break;
			case Dispatcher::FOUND:
				$this->call_handler( $match[1], $match[2] );
				break;
		}

	}
}
