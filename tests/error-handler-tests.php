<?php
declare( strict_types = 1 );

test( 'custom 404 handler receives uri', function() {
	$context = stream_context_create( [ 'http' => [ 'ignore_errors' => true ] ] );
	$response = file_get_contents( 'http://127.0.0.1:9191/does-not-exist', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 404 );
	expect( $response )->toContain( '404 Not Found' );
	expect( $response )->toContain( '/does-not-exist' );
} );

test( 'custom 405 handler receives uri and allowed methods', function() {
	$context = stream_context_create( [
		'http' => [
			'method' => 'DELETE',
			'ignore_errors' => true,
		],
	] );
	$response = file_get_contents( 'http://127.0.0.1:9191/', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 405 );
	expect( $response )->toContain( '405 Method Not Allowed' );
	expect( $response )->toContain( '/' );
	expect( $response )->toContain( 'GET' );
} );

test( 'custom 405 handler shows multiple allowed methods', function() {
	$context = stream_context_create( [
		'http' => [
			'method' => 'PATCH',
			'ignore_errors' => true,
		],
	] );
	$response = file_get_contents( 'http://127.0.0.1:9191/post', false, $context );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 405 );
	expect( $response )->toContain( 'POST' );
} );
