<?php
declare( strict_types = 1 );

test( 'simple post', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'POST' ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/post', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'POST OK' );
} );

test( 'post with parameter', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'POST' ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/post/42', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'POST ID: 42' );
} );

test( 'post 404', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'POST', 'ignore_errors' => true ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/nope', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 404 );
} );

test( 'post 405 method not allowed', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'POST', 'ignore_errors' => true ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 405 );
} );
