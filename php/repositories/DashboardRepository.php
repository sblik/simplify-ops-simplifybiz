<?php

/**
 * A repository for performing CRUD operations on the dashboard form
 *
 * @method static DashboardEntity|null get_one( $fieldId, $value )
 * @method static DashboardEntity|null get_one_for_current_user()
 * @method static DashboardEntity|null get_one_by_id( $userId )
 * @method static DashboardEntity[]|null get_all( $fieldId, $value, string $direction = 'ASC' )
 * @method static int|WP_Error add( DashboardEntity $entity )
 */
class DashboardRepository extends BS_BaseRepository {
	protected static $entityType = DashboardEntity::class;
	protected static int $formId = FormIds::FORM_ID_34_PORTAL_DASHBOARD;

	/**
	 * Get the dashboard where the stored coupon code matches the provided code
	 *
	 * @param  string  $couponCodeUsed
	 *
	 * @return DashboardEntity|null
	 */
	public static function get_by_stored_coupon_code( string $couponCodeUsed ): ?DashboardEntity {
		return self::get_one( DashboardEntity::get_form_id( 'storedCouponCode' ), $couponCodeUsed );
	}

	/**
	 * Get the dashboard for the provided user id
	 *
	 * @param  string  $userId
	 *
	 * @return DashboardEntity|null
	 */
	public static function get_one_for_user( string $userId ): ?DashboardEntity {
		return self::get_one( FormFields::CREATED_BY, $userId );
	}
}