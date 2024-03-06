<?php


function bootstrap_ops_simplify_plugin() {
	require_dependencies();
}


function require_dependencies() {
	// TODO: move require statements closer to where they are used

	require_file( 'includes/enqueue_scripts.php' );
	require_file( 'repositories/BS_BaseRepository.php' );
	require_file( '/entities/BS_BaseEntity.php' );

	require_directory( 'entities' );
	require_directory( 'repositories' );
	require_directory( 'css' );
	require_directory( 'helpers' );
	require_directory( 'includes' );
	require_directory( 'js' );
	require_directory( 'triggers' );
}
