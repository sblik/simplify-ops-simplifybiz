<?php

class CRYO_Serializable {
	/**
	 * @return string
	 */
	public function to_json(): string {
		return json_encode( get_object_vars( $this ) );
	}

	/**
	 * @return string
	 */
	public function to_json_string(): string {
		return print_r( json_encode( get_object_vars( $this ) ), true );
	}

	/**
	 * @return string
	 */
	public function to_json_array(): string {
		return json_encode( array( get_object_vars( $this ) ) );
	}
}