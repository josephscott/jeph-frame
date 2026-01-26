<?php
declare( strict_types = 1 );
require __DIR__ . '/../vendor/autoload.php';

$frame = new JEPH\Frame();

$frame->get( '/', function() {
	echo "Hello World!\n";
} );

$frame->get( '/hello/{name:\w+}', __DIR__ . '/routes/name.php' );

$frame->get( '/opt[/{name:\w+}]', function( $vars ) {
	$name = $vars['name'] ?? 'none';
	echo "OPT: $name\n";
} );

class Route_Bye {
	public function get( $vars ) {
		echo "Bye {$vars['name']}!\n";
	}
}

$frame->get( '/bye/{name:\w+}', [ 'Route_Bye' ] );

$frame->post( '/post', function() {
	echo "POST OK\n";
} );

$frame->post( '/post/{id:\d+}', function( $vars ) {
	echo "POST ID: {$vars['id']}\n";
} );

$frame->put( '/put', function() {
	echo "PUT OK\n";
} );

$frame->put( '/put/{id:\d+}', function( $vars ) {
	echo "PUT ID: {$vars['id']}\n";
} );

$frame->delete( '/delete', function() {
	echo "DELETE OK\n";
} );

$frame->delete( '/delete/{id:\d+}', function( $vars ) {
	echo "DELETE ID: {$vars['id']}\n";
} );

$frame->patch( '/patch', function() {
	echo "PATCH OK\n";
} );

$frame->patch( '/patch/{id:\d+}', function( $vars ) {
	echo "PATCH ID: {$vars['id']}\n";
} );

$frame->options( '/options', function() {
	echo "OPTIONS OK\n";
} );

$frame->options( '/options/{id:\d+}', function( $vars ) {
	echo "OPTIONS ID: {$vars['id']}\n";
} );

$frame->head( '/head', function() {
	header( 'X-Head-Test: ok' );
} );

$frame->head( '/head/{id:\d+}', function( $vars ) {
	header( 'X-Head-Id: ' . $vars['id'] );
} );

$frame->any( '/any', function() {
	echo "ANY OK\n";
} );

$frame->get( '/query', function() {
	echo "QUERY OK\n";
} );

$frame->get( '/encoded/{name}', function( $vars ) {
	echo "ENCODED: {$vars['name']}\n";
} );

$frame->run();
