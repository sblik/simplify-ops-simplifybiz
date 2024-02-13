<?php


/**
 * Adapter for MemberPress interactions
 */
class MemberPressAdapter {
	/**
	 * Create a new membership in member press
	 *
	 * @param $productId
	 * @param $user_id
	 *
	 * @return MeprTransaction
	 */
	static function create_membership_transaction( $productId, $user_id ): MeprTransaction {
		$txn             = new MeprTransaction();
		$txn->amount     = 0;
		$txn->total      = 0;
		$txn->user_id    = $user_id;
		$txn->product_id = $productId;
		$txn->status     = MeprTransaction::$complete_str;
		$txn->txn_type   = MeprTransaction::$payment_str;
		$txn->gateway    = 'manual';
		$txn->store();

		return $txn;
	}
}