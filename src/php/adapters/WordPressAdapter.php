<?php

/**
 * Adapter for handling WordPress events
 */
class WordPressAdapter {

	private AddUserContactMethod $addUserContactMethod;
	private MenuLoaded $menuLoaded;
	private UserLogin $userLogin;
    private AdminDashboard $adminDashboard;
    private BillableHoursNotification $billableHoursNotification;

	public function __construct( AddUserContactMethod $addUserContactMethod, MenuLoaded $menuLoaded, UserLogin $userLogin, AdminDashboard $adminDashboard, BillableHoursNotification $billableHoursNotification ) {
		$this->addUserContactMethod = $addUserContactMethod;
		$this->menuLoaded           = $menuLoaded;
		$this->userLogin           = $userLogin;
        $this->adminDashboard       = $adminDashboard;
        $this->billableHoursNotification = $billableHoursNotification;

		$this->register_filters();
	}

	/**
	 * @return void
	 */
	public function register_filters() {
        add_filter('login_redirect', [$this->userLogin, 'handle_redirect'], 10, 3);
		add_filter( 'user_contactmethods', [ $this->addUserContactMethod, 'add_organization' ], 10, 2 );
		add_filter( 'wp_nav_menu_objects', [ $this->menuLoaded, 'add_link_to_clients_balance' ] );

        add_action( 'admin_menu', [$this, 'register_menu'] );
        add_action( 'smplfy_send_billable_hours_report', [$this->billableHoursNotification, 'send']);
	}

    /**
     * Register the admin menu page
     */
    public function register_menu(): void
    {
        add_menu_page(
            'Billable Hours',               // Page title
            'Billable Hours',               // Menu title
            'manage_options',               // Capability required
            'billable-hours-dashboard',     // Menu slug
            [$this->adminDashboard, 'render_dashboard'],    // Callback function
            'dashicons-chart-bar',          // Icon
            30                              // Position
        );
    }
}