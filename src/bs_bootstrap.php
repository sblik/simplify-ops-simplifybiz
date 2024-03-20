<?php

function bootstrap_ops_simplify_plugin() {
	require_dependencies();

	DependencyFactory::create_plugin_dependencies();
}

function require_dependencies() {
	require_file( 'php/includes/enqueue_scripts.php' );

	require_directory( 'php/entities' );
	require_directory( 'php/classes' );
	require_directory( 'php/repositories' );
	require_directory( 'php/helpers' );
	require_directory( 'php/includes' );
	require_directory( 'php/triggers' );
	require_directory( 'php/usecases' );
	require_directory( 'php/adapters' );

	require_file( 'php/DependencyFactory.php' );

}
