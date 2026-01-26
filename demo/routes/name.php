<?php
declare( strict_types = 1 );

header( 'Content-Type: text/plain' );
$name = $_frame['name'] ?? 'none';

echo "Hello $name!\n";
