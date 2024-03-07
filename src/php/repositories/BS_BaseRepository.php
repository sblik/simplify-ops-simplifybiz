<?php

/**
 * This base repository serves as mechanism to perform CRUD operations on Gravity Forms entries
 * The BS_BaseRepository class has default methods for all repositories that extend it
 * @template T
 */
abstract class BS_BaseRepository {

	/**
	 * Override $entityType in repositories that extend this base repository
	 * @var class-string<T>
	 */
	protected static $entityType;

	/**
	 * The associated gravity-forms form id
	 */
	protected static int $formId;

	/**
	 * Delete entry in Gravity Forms
	 *
	 * @param mixed $entryId
	 *
	 * @return bool|WP_Error Either true for success or a WP_Error instance.
	 */
	static function delete( $entryId ): bool {
		return GFAPI::delete_entry( $entryId );
	}

	/**
	 * Updates an entire single Entry object in Gravity Forms.
	 *
	 * @param T $entity
	 *
	 */
	static function update( $entity ) {
		GFAPI::update_entry( $entity->formEntry );
	}

	/**
	 * Adds an entire single Entry object in Gravity Forms.
	 *
	 * @param T $entity
	 *
	 * @return int|WP_Error Either the new Entry ID or a WP_Error instance.
	 */
	static function add( $entity ): int {
		return GFAPI::add_entry( $entity->formEntry );
	}

	/**
	 * Get the first entry where the entry was created by the current user
	 *
	 * @return T|null
	 */
	static function get_one_for_current_user() {
		return self::get_one( FormFields::CREATED_BY, get_current_user_id() );
	}

	/**
	 * Get the first entry where the field of the given fieldId related to the $propertyName has a value that matches the given $value
	 *
	 * @param $fieldId
	 * @param $value
	 *
	 * @return T|null
	 */
	static function get_one( $fieldId, $value ) {
		try {
			$retrieved_entries = self::get( $fieldId, $value );

			if ( ! empty( $retrieved_entries ) ) {
				return $retrieved_entries[0];
			} else {
				return null;
			}

		} catch ( Exception $e ) {
			return null;
		}
	}

	/**
	 * Generic get method used by both get_one and get_all
	 *
	 * @param  $fieldId
	 * @param  $value
	 * @param string $direction
	 * @param null $paging
	 *
	 * @return T[]
	 */
	private static function get( $fieldId = null, $value = null, string $direction = 'ASC', $paging = null ): array {
		$search_criteria = array();

		if ( $fieldId ) {
			$search_criteria['field_filters'][] = array( 'key' => $fieldId, 'value' => $value );
		}

		$search_criteria['status'] = 'active';

		$sorting = array(
			'key'        => 'id',
			'direction'  => $direction,
			'is_numeric' => true,
		);

		$retrieved_entries = GFAPI::get_entries( static::$formId, $search_criteria, $sorting, $paging );

		if ( is_wp_error( $retrieved_entries ) ) {
			return array();
		}

		return self::map_to_entities( $retrieved_entries );
	}

	/**
	 * Maps form entries to entities associated with the repository
	 *
	 * @param $formEntries
	 *
	 * @return T[]
	 */
	public static function map_to_entities( $formEntries ): array {
		$entities = [];
		foreach ( $formEntries as $entry ) {
			$entities[] = new static::$entityType( $entry );
		}

		return $entities;
	}

	/**
	 * Get the first entry where the entry was created by the provided user
	 *
	 * @return T|null
	 */
	static function get_one_for_user( $userId ) {
		return self::get_one( FormFields::CREATED_BY, $userId );
	}

	/**
	 * Get the first entry where the fieldId matches the given value
	 *
	 * @param $value
	 *
	 * @return T|null
	 */
	static function get_one_by_id( $value ) {
		return self::get_one( FormFields::ID, $value );
	}

	/**
	 * Get all entries where the field of the given fieldId has a value that matches the given fieldValue
	 *
	 * @param  $fieldId
	 * @param  $value
	 * @param string $direction
	 *
	 * @return T[]
	 */
	public static function get_all( $fieldId = null, $value = null, string $direction = 'ASC' ): array {
		$paging = array( 'offset' => 0, 'page_size' => 999999999999 );

		return self::get( $fieldId, $value, $direction, $paging );
	}
}