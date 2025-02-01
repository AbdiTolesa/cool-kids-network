<?php
$GLOBALS['wp_tests_options'] = array(
	'active_plugins' => array( 'cool-kids-network/cool-kids-network.php' ),
);

echo 'Welcome to the Cool Kids Network Test Suite' . PHP_EOL;
echo 'Version: 1.0' . PHP_EOL . PHP_EOL;

if ( ! defined( 'SCRIPT_DEBUG' ) ) {
	define( 'SCRIPT_DEBUG', false );
}

if ( file_exists( __DIR__ . '/../vendor/autoload.php' ) ) {
	include __DIR__ . '/../vendor/autoload.php';
}

if ( false !== getenv( 'WP_DEVELOP_DIR' ) ) {
	require getenv( 'WP_DEVELOP_DIR' ) . 'tests/phpunit/includes/bootstrap.php';
} else {
	require '../../../../tests/phpunit/includes/bootstrap.php';
}
