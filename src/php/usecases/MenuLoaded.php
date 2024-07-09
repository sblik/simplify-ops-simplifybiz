<?php

class MenuLoaded {
	private ClientBalanceRepository $clientBalancesRepository;

	public function __construct(
		ClientBalanceRepository $clientBalancesRepository
	) {
		$this->clientBalancesRepository = $clientBalancesRepository;

	}

	function add_link_to_clients_balance( $menu_items ) {
		$userID = get_current_user_id();

		foreach ( $menu_items as $menu_item ) {

			if ( $menu_item->ID == '19319' ) {
				$clientBalancesEntity = $this->clientBalancesRepository->get_one_by_client_user_id( $userID );
				if ( empty( $clientBalancesEntity ) ) {
					$menu_item->title = '';
					$menu_item->url   = '';
				} else {
					$menu_item->url = str_replace( 'entryid/', $clientBalancesEntity->id, $menu_item->url );
				}

			}

		}

		return $menu_items;
	}
}