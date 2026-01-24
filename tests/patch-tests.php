<?php
declare( strict_types = 1 );

test( 'simple patch', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'PATCH' ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/patch', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'PATCH OK' );
} );

test( 'patch with parameter', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'PATCH' ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/patch/55', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'PATCH ID: 55' );
} );

test( 'patch 404', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'PATCH', 'ignore_errors' => true ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/nope', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 404 );
} );

test( 'patch 405 method not allowed', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'PATCH', 'ignore_errors' => true ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 405 );
} );
