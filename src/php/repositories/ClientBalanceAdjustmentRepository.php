<?php

/**
 * @method static ClientBalanceAdjustmentEntity|null get_one( array $filters )
 * @method static ClientBalanceAdjustmentEntity|null get_one_for_current_user()
 * @method static ClientBalanceAdjustmentEntity|null get_one_by_id( $userId )
 * @method static ClientBalanceAdjustmentEntity[] get_all( array $filters = null, string $direction = 'ASC' )
 * @method static ClientBalanceAdjustmentEntity[] get_all_between( DateTime $startDate, DateTime $endDate, array $filters = null, string $direction = 'ASC' )
 * @method static int|WP_Error add( ClientBalanceAdjustmentEntity $entity )
 */
class ClientBalanceAdjustmentRepository extends SMPLFY_BaseRepository {
	public function __construct( SMPLFY_GravityFormsApiWrapper $gravityFormsApi ) {
		$this->formId     = 151;
		$this->entityType = ClientBalanceAdjustmentEntity::class;
		parent::__construct( $gravityFormsApi );
	}
}