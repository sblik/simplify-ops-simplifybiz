<?php

/**
 * @method static ClientBalanceEntity|null get_one( array $filters )
 * @method static ClientBalanceEntity|null get_one_for_current_user()
 * @method static ClientBalanceEntity|null get_one_by_id( $id )
 * @method static ClientBalanceEntity[] get_all( array $filters = null, string $direction = 'ASC' )
 * @method static ClientBalanceEntity[] get_all_between( DateTime $startDate, DateTime $endDate, array $filters = null, string $direction = 'ASC' )
 * @method static int|WP_Error add( ClientBalanceEntity $entity )
 */
class ClientBalanceRepository extends SMPLFY_BaseRepository {
	public function __construct( SMPLFY_GravityFormsApiWrapper $gravityFormsApi ) {
		$this->formId     = 138;
		$this->entityType = ClientBalanceEntity::class;
		parent::__construct( $gravityFormsApi );
	}

	/**
	 * @param  string  $clientUserId
	 *
	 * @return ClientBalanceEntity|null
	 */
	public function get_one_by_client_user_id( string $clientUserId ): ?ClientBalanceEntity {
		if ( empty( $clientUserId ) ) {
			return null;
		}

		return $this->get_one( [ ClientBalanceEntity::get_field_id( 'clientUserId' ) => $clientUserId ] );
	}
}