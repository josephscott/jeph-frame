<?php
declare( strict_types = 1 );

test( 'simple', function() {
	$response = file_get_contents( 'http://127.0.0.1:9191/' );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'Hello' );
} );

test( 'named parameter', function() {
	$response = file_get_contents( 'http://127.0.0.1:9191/hello/alice' );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'Hello alice' );
} );

test( 'optional parameter missing', function() {
	$response = file_get_contents( 'http://127.0.0.1:9191/opt' );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'OPT: none' );
} );

test( 'optional parameter provided', function() {
	$response = file_get_contents( 'http://127.0.0.1:9191/opt/bob' );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'OPT: bob' );
} );

test( 'class handler', function() {
	$response = file_get_contents( 'http://127.0.0.1:9191/bye/carol' );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'Bye carol' );
} );

test( '404 not found', function() {
	$context = stream_context_create( [ 'http' => [ 'ignore_errors' => true ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/nope', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 404 );
	expect( $response )->toContain( 'Not Found' );
} );
