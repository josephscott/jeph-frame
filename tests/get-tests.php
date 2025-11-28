<?php
declare( strict_types = 1 );

test( 'simple', function() {
	$response = file_get_contents( 'http://127.0.0.1:9191/' );
	$headers = parse_http_headers( $http_response_header );

	expect( $headers['response_code'] )->toBe( 200 );
	expect( $response )->toContain( 'Hello' );
} );
