<?php

/**
 * @method static ClientBalancesEntity|null get_one( array $filters )
 * @method static ClientBalancesEntity|null get_one_for_current_user()
 * @method static ClientBalancesEntity|null get_one_by_id( $id )
 * @method static ClientBalancesEntity[] get_all( array $filters = null, string $direction = 'ASC' )
 * @method static ClientBalancesEntity[] get_all_between( DateTime $startDate, DateTime $endDate, array $filters = null, string $direction = 'ASC' )
 * @method static int|WP_Error add( ClientBalancesEntity $entity )
 */
class ClientBalancesRepository extends SMPLFY_BaseRepository {
	public function __construct( SMPLFY_GravityFormsApiWrapper $gravityFormsApi ) {
		$this->formId     = 150;
		$this->entityType = ClientBalancesEntity::class;
		parent::__construct( $gravityFormsApi );
	}

	/**
	 * @param  string  $clientUserId
	 *
	 * @return ClientBalancesEntity|null
	 */
	public function get_one_by_client_user_id( string $clientUserId ): ?ClientBalancesEntity {
		return $this->get_one( [ ClientBalancesEntity::get_field_id( 'clientUserId' ) => $clientUserId ] );
	}
}