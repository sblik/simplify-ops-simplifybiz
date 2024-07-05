<?php

function bootstrap_ops_simplify_plugin() {
	require_dependencies();

	DependencyFactory::create_plugin_dependencies();
}

function require_dependencies() {
	$require = new SMPLFY_Require( BS_NAME_PLUGIN_DIR );

	try {
		$require->file( 'php/includes/enqueue_scripts.php' );

		$require->directory( 'php/utilities' );
		$require->directory( 'php/entities' );
		$require->directory( 'php/repositories' );
		$require->directory( 'php/helpers' );
		$require->directory( 'php/includes' );
		$require->directory( 'php/usecases' );
		$require->directory( 'php/api/handlers' );
		$require->directory( 'php/api/controllers' );
		$require->directory( 'php/adapters' );

		$require->file( 'php/DependencyFactory.php' );
		$require->file( 'php/api/ControllerFactory.php' );

	} catch ( Exception $e ) {
		error_log( $e->getMessage() );
	}

}
