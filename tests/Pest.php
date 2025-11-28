<?php
declare( strict_types = 1 );

require __DIR__ . '/../vendor/autoload.php';

function parse_http_headers( array $headers ) : array {
	$parsed = [];

	$response_code = array_shift( $headers );
	if ( preg_match( '#HTTP/([0-9\.]+)\s+([0-9]+)#', $response_code, $matches ) ) {
		$headers[] = 'http_version: ' . floatval( $matches[1] );
		$headers[] = 'response_code: ' . intval( $matches[2] );
	}

	foreach ( $headers as $header ) {
		$parts = explode( ':', $header, 2 );
		if ( count( $parts ) === 2 ) {
			$parts[1] = trim( $parts[1] );

			if ( $parts[0] === 'http_version' ) {
				$parts[1] = (float) $parts[1];
			} elseif ( $parts[0] === 'response_code' ) {
				$parts[1] = (int) $parts[1];
			}

			$parsed[strtolower( trim( $parts[0] ) )] = $parts[1];
		}
	}

	return $parsed;
}
