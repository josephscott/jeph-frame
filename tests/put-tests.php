<?php
declare( strict_types = 1 );

test( 'simple put', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'PUT' ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/put', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'PUT OK' );
} );

test( 'put with parameter', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'PUT' ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/put/99', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'PUT ID: 99' );
} );

test( 'put 404', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'PUT', 'ignore_errors' => true ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/nope', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 404 );
} );

test( 'put 405 method not allowed', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'PUT', 'ignore_errors' => true ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 405 );
} );
