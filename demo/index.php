<?php
declare( strict_types = 1 );
require __DIR__ . '/../vendor/autoload.php';

$frame = new JEPH\Frame();

$frame->get( '/', function() {
	echo "Hello World!\n";
} );

$frame->get( '/hello/{name:\w+}', __DIR__ . '/routes/name.php' );

class Route_Bye {
	public function get( $vars ) {
		echo "Bye {$vars['name']}!\n";
	}
}

$frame->get( '/bye/{name:\w+}', [ 'Route_Bye' ] );

$frame->run();
