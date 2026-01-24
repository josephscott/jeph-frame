<?php
declare( strict_types = 1 );

test( 'simple options', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'OPTIONS' ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/options', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'OPTIONS OK' );
} );

test( 'options with parameter', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'OPTIONS' ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/options/33', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'OPTIONS ID: 33' );
} );

test( 'options 404', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'OPTIONS', 'ignore_errors' => true ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/nope', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 404 );
} );

test( 'options 405 method not allowed', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'OPTIONS', 'ignore_errors' => true ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 405 );
} );
