<?php
declare( strict_types = 1 );

test( 'any method with GET', function() {
	$response = file_get_contents( 'http://127.0.0.1:9191/any' );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'ANY OK' );
} );

test( 'any method with POST', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'POST' ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/any', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'ANY OK' );
} );

test( 'any method with DELETE', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'DELETE' ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/any', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'ANY OK' );
} );

test( 'trailing slash redirect', function() {
	$context = stream_context_create( [ 'http' => [ 'follow_location' => false, 'ignore_errors' => true ] ] );
	file_get_contents( 'http://127.0.0.1:9191/any/', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 302 );
	expect( $headers['location'] )->toBe( '/any' );
} );

test( 'query string ignored', function() {
	$response = file_get_contents( 'http://127.0.0.1:9191/query?foo=bar&baz=123' );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'QUERY OK' );
} );

test( 'url encoded parameter', function() {
	$response = file_get_contents( 'http://127.0.0.1:9191/encoded/hello%20world' );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'ENCODED: hello world' );
} );
