<?php

/**
 * @method static RecalculateClientBalanceEntity|null get_one( array $filters )
 * @method static RecalculateClientBalanceEntity|null get_one_for_current_user()
 * @method static RecalculateClientBalanceEntity|null get_one_by_id( $id )
 * @method static RecalculateClientBalanceEntity[] get_all( array $filters = null, string $direction = 'ASC' )
 * @method static RecalculateClientBalanceEntity[] get_all_between( DateTime $startDate, DateTime $endDate, array $filters = null, string $direction = 'ASC' )
 * @method static int|WP_Error add( RecalculateClientBalanceEntity $entity )
 */
class RecalculateClientBalanceRepository extends SMPLFY_BaseRepository {
	public function __construct( SMPLFY_GravityFormsApiWrapper $gravityFormsApi ) {
		$this->formId     = 163;
		$this->entityType = RecalculateClientBalanceEntity::class;
		parent::__construct( $gravityFormsApi );
	}
}