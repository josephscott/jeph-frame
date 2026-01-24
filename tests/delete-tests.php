<?php
declare( strict_types = 1 );

test( 'simple delete', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'DELETE' ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/delete', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'DELETE OK' );
} );

test( 'delete with parameter', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'DELETE' ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/delete/77', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'DELETE ID: 77' );
} );

test( 'delete 404', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'DELETE', 'ignore_errors' => true ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/nope', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 404 );
} );

test( 'delete 405 method not allowed', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'DELETE', 'ignore_errors' => true ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 405 );
} );
