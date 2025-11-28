<?php
declare( strict_types = 1 );

header( 'Content-Type: text/plain' );
$name = $_ENV['__FRAME']['name'] ?? 'none';

echo "Hello $name!\n";