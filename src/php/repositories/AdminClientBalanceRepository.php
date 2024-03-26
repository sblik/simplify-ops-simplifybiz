<?php

/**
 * @method static AdminClientBalanceEntity|null get_one( array $filters )
 * @method static AdminClientBalanceEntity|null get_one_for_current_user()
 * @method static AdminClientBalanceEntity|null get_one_by_id( $id )
 * @method static AdminClientBalanceEntity[] get_all( array $filters = null, string $direction = 'ASC' )
 * @method static AdminClientBalanceEntity[] get_all_between( DateTime $startDate, DateTime $endDate, array $filters = null, string $direction = 'ASC' )
 * @method static int|WP_Error add( AdminClientBalanceEntity $entity )
 */
class AdminClientBalanceRepository extends SMPLFY_BaseRepository {
	public function __construct( SMPLFY_GravityFormsApiWrapper $gravityFormsApi ) {
		$this->formId     = 150;
		$this->entityType = AdminClientBalanceEntity::class;
		parent::__construct( $gravityFormsApi );
	}

	/**
	 * @param  string  $clientUserId
	 *
	 * @return AdminClientBalanceEntity|null
	 */
	public function get_one_by_client_user_id( string $clientUserId ): ?AdminClientBalanceEntity {
		if ( empty( $clientUserId ) ) {
			return null;
		}

		return $this->get_one( [ AdminClientBalanceEntity::get_field_id( 'clientUserId' ) => $clientUserId ] );
	}
}