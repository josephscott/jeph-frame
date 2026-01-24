<?php
declare( strict_types = 1 );

test( 'simple head', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'HEAD' ] ] );
	file_get_contents( 'http://127.0.0.1:9191/head', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $headers['x-head-test'] )->toBe( 'ok' );
} );

test( 'head with parameter', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'HEAD' ] ] );
	file_get_contents( 'http://127.0.0.1:9191/head/22', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $headers['x-head-id'] )->toBe( '22' );
} );

test( 'head 404', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'HEAD', 'ignore_errors' => true ] ] );
	file_get_contents( 'http://127.0.0.1:9191/nope', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 404 );
} );

test( 'head 405 method not allowed', function() {
	$context = stream_context_create( [ 'http' => [ 'method' => 'HEAD', 'ignore_errors' => true ] ] );
	file_get_contents( 'http://127.0.0.1:9191/post', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 405 );
} );
