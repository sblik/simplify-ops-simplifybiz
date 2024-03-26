<?php

/**
 * @method static AdminClientBalanceAdjustmentEntity|null get_one( array $filters )
 * @method static AdminClientBalanceAdjustmentEntity|null get_one_for_current_user()
 * @method static AdminClientBalanceAdjustmentEntity|null get_one_by_id( $id )
 * @method static AdminClientBalanceAdjustmentEntity[] get_all( array $filters = null, string $direction = 'ASC' )
 * @method static AdminClientBalanceAdjustmentEntity[] get_all_between( DateTime $startDate, DateTime $endDate, array $filters = null, string $direction = 'ASC' )
 * @method static int|WP_Error add( AdminClientBalanceAdjustmentEntity $entity )
 */
class AdminClientBalanceAdjustmentRepository extends SMPLFY_BaseRepository {
	public function __construct( SMPLFY_GravityFormsApiWrapper $gravityFormsApi ) {
		$this->formId     = 151;
		$this->entityType = AdminClientBalanceAdjustmentEntity::class;
		parent::__construct( $gravityFormsApi );
	}
}