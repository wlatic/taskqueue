<?php
class FreshDeskSettingsPage{

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
	private $url_options;
	private $url_val;

    /**
     * Start up
     */
    public function __construct(){
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_css_js' ) );
    }


    /*
     * Add options page
     */
    public function add_plugin_page(){
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Freshdesk', 
            'manage_options', 
            'wp-freshdesk', 
            array( $this, 'create_admin_page' )
        );
    }


    /*
     * Options page callback
     */
    public function create_admin_page(){
	
        // Set class property
        $this->options = get_option( 'fd_apikey' );
		if( $this->options ){
			if( isset( $this->options['freshdesk_url'] ) ){
				if ( !preg_match( "/^[-A-Za-z\d\s]+$/", $this->options['freshdesk_url'] ) ) {
					$this->options['freshdesk_url'] = '';
					$this->url_val = '';
				} else {
					$this->url_val = $this->options['freshdesk_url'];
					$this->options['freshdesk_url'] = 'https://' . $this->options['freshdesk_url'] . '.freshdesk.com/';
				}	
			} else {
				$this->url_val = '';
				$this->options['freshdesk_url'] = '';
			}
		} else {
			$this->url_val = '';
			$this->options['freshdesk_url'] = '';
		}
		
		$this->url_options = get_option( 'fd_url' );
		$this->display_option = get_option( 'fd_display' );
        ?>
        <div class="wrap about-wrap">
            <div class="fd-heading-section">
				<h1><?php echo __( 'WP Freshdesk Settings', 'wp-freshdesk' ); ?></h1>
				<div class="fd-about-text about-text"><?php echo __( 'Now your users won\'t have to remember one more username and password! Configure your WordPress website and Freshdesk to work together to give your users Freshdesk Remote Authentication!', 'wp-freshdesk' ); ?></div>
				<div class="fd-badge"></div>
			
			<h2 class="nav-tab-wrapper">
				<a href="javascript:void(0);" id="tab-api" class="nav-tab nav-tab-active"><?php echo __( 'General Configuration', 'wp-freshdesk' ); ?></a>
				<a href="javascript:void(0);" id="tab-url" class="nav-tab"><?php echo __( 'Freshdesk SSO', 'wp-freshdesk' ); ?></a>
				<a href="javascript:void(0);" id="tab-page" class="nav-tab"><?php echo __( 'Page Setting', 'wp-freshdesk' ); ?></a>
			</h2>
			<div id="api-tab" class="fd-tabs">
				<p class="about-description"><?php echo __( 'All the settings related to connecting your freshdesk account with your WordPress are listed here.', 'wp-freshdesk' ); ?></p>
				<form method="post" action="options.php" autocomplete="off">
					<?php
						// This prints out all hidden setting fields
						settings_fields( 'my_option_group' );   
						do_settings_sections( 'my-setting-admin' );
						submit_button();?>
				</form>
			</div>
			<div id="shortcode-tab" style="display:none;" class="fd-tabs">
				<p class="about-description"><?php echo __( 'Shortcodes for displaying tickets on your page.', 'wp-freshdesk' ); ?></p>
				<table>
					<tr>
						<td><?php echo __( 'All tickets', 'wp-freshdesk' ); ?></td>
						<td><code>[fd_fetch_tickets]</code></td>
					</tr>
					<tr>
						<td><?php echo __( 'Open', 'wp-freshdesk' ); ?></td>
						<td><code>[fd_fetch_tickets filter="Open"]</code></td>
					</tr>
					<tr>
						<td><?php echo __( 'Resolved', 'wp-freshdesk' ); ?></td>
						<td><code>[fd_fetch_tickets filter="Resolved"]</code></td>
					</tr>
					<tr>
						<td><?php echo __( 'Closed', 'wp-freshdesk' ); ?></td>
						<td><code>[fd_fetch_tickets filter="Closed"]</code></td>
					</tr>
					<tr>
						<td><?php echo __( 'Pending', 'wp-freshdesk' ); ?></td>
						<td><code>[fd_fetch_tickets filter="Pending"]</code></td>
					</tr>
					<tr>
						<td><?php echo __( 'Waiting on Customer', 'wp-freshdesk' ); ?></td>
						<td><code>[fd_fetch_tickets filter="Waiting on Customer"]</code></td>
					</tr>
					<tr>
						<td><?php echo __( 'Waiting on Third Party', 'wp-freshdesk' ); ?></td>
						<td><code>[fd_fetch_tickets filter="Waiting on Third Party"]</code></td>
					</tr>
					<tr>
						<td><?php echo __( 'Create New Ticket', 'wp-freshdesk' ); ?></td>
						<td><code>[fd_new_ticket]</code></td>
					</tr>
				</table>
			</div>
			<div id="url-tab" style="display:none;" class="fd-tabs">
				<p class="about-description"><?php echo __( 'Configure Single Sign on with Freshdesk, so that users don\'t have to remember their username and password combos to get to their tickets.', 'wp-freshdesk' ); ?></p>
				<form method="post" action="options.php" id="url_form" autocomplete="off">
					<?php
						// This prints out all hidden setting fields
						settings_fields( 'url_option' );   
						do_settings_sections( 'url-admin-setting' );
						$val = '';
						if(  isset( $this->options['freshdesk_url'] ) ) {
							$val = $this->options['freshdesk_url'];
						} else {
							$val = 'https://your_domain.freshdesk.com/';
						}
					?>
					<p class="description"><?php echo __( 'Note: Remember that you can always go to: ', 'wp-freshdesk' ); ?><a href="<?php echo $val; ?>login/normal" target="_blank"><?php echo $val; ?>access/normal</a><?php echo __( ' to use the regular login in case you get unlucky and somehow lock yourself out of Freshdesk.', 'wp-freshdesk' ); ?></p>
					<?php
						submit_button();
					?>
				</form>
			</div>
			<div id="page-tab" style="display:none;" class="fd-tabs">
				<form method="post" action="options.php" id="pagename_form" autocomplete="off">
					<?php
						// This prints out all hidden setting fields
						settings_fields( 'pagename_option' );   
						do_settings_sections( 'pagename-setting' );
						submit_button();?>
				</form>
			</div>
        </div>
        <?php
    }
	
	/**
	 * Enqueue CSS and JS on WordPress admin pages of WP Freshdesk
	 */
	public function enqueue_admin_css_js( $hook ) {
		
		 // Load only on WP Freshdesk plugin pages
		if ( $hook != "settings_page_wp-freshdesk" ) {
			return;
		}
		
		//Enqueue all styles and scripts
		wp_register_script( 'fd-script', plugins_url('js/fd-script.js', __FILE__), array('jquery'), '1.1', true );
		wp_enqueue_script( 'fd-script' );
		
		wp_register_style( 'fd-style', plugins_url('css/fd-style.css', __FILE__) );
		wp_enqueue_style( 'fd-style' );
	}
	
    /*
     * Register and add settings
     */
    public function page_init(){
		
		// Register the setting tab
		register_setting(
            'my_option_group', // Option group
            'fd_apikey', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            '', // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );
		
		add_settings_field(
            'freshdesk_url', // ID
            'Base Freshdesk URL', // Title 
            array( $this, 'freshdesk_url_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );
		
		
		add_settings_field(
            'use_apikey', // ID
            'Method of Authentication', // Title 
            array( $this, 'use_apikey_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );
		

        add_settings_field(
            'freshdesk_apikey', // ID
            'API Key', // Title 
            array( $this, 'freshdesk_apikey_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );
		
		
		add_settings_field(
            'api_username', // ID
            'Username', // Title 
            array( $this, 'api_username_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );
		
		add_settings_field(
            'api_pwd', // ID
            'Password', // Title 
            array( $this, 'api_pwd_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );
		
		// Register the setting tab		
		register_setting(
            'url_option', // Option group
            'fd_url' // Option name
        );
		
		add_settings_section(
            'freshdesk_url_section', // ID
            '', // Title
            array( $this, 'print_section_info' ), // Callback
            'url-admin-setting' // Page
        );
		
		add_settings_field(
            'freshdesk_enable', // ID
            'Enable SSO', // Title 
            array( $this, 'freshdesk_enable_callback' ), // Callback
            'url-admin-setting', // Page
            'freshdesk_url_section' // Section           
        );

		
		add_settings_field(
            'freshdesk_sharedkey', // ID
            'Secret Shared Key', // Title 
            array( $this, 'freshdesk_sharedkey_callback' ), // Callback
            'url-admin-setting', // Page
            'freshdesk_url_section' // Section           
        );
		
		add_settings_field(
            'freshdesk_login_url', // ID
            'Remote Login URL', // Title 
            array( $this, 'freshdesk_loginurl_callback' ), // Callback
            'url-admin-setting', // Page
            'freshdesk_url_section' // Section           
        );
		
		add_settings_field(
            'freshdesk_logout_url', // ID
            'Remote Logout URL', // Title 
            array( $this, 'freshdesk_logouturl_callback' ), // Callback
            'url-admin-setting', // Page
            'freshdesk_url_section' // Section           
        );
		
		// Register the display setting tab		
		register_setting(
            'pagename_option', // Option group
            'fd_display' // Option name
        );
		
		add_settings_section(
            'freshdesk_display_section', // ID
            '', // Title
            array( $this, 'print_section_info' ), // Callback
            'pagename-setting' // Page
        );
		
		add_settings_field(
            'task_pagename', // ID
            'Task Queue Page', // Title 
            array( $this, 'task_page_callback' ), // Callback
            'pagename-setting', // Page
            'freshdesk_display_section' // Section           
        );
		add_settings_field(
            'support_pagename', // ID
            'Support Page', // Title 
            array( $this, 'support_page_callback' ), // Callback
            'pagename-setting', // Page
            'freshdesk_display_section' // Section           
        );
    }
	

    /*
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ){
	
        $new_input = array();
        if( isset( $input['freshdesk_apikey'] ) )
            $new_input['freshdesk_apikey'] = sanitize_text_field( $input['freshdesk_apikey'] );
			
		if( isset( $input['freshdesk_url'] ) )
            $new_input['freshdesk_url'] = $input['freshdesk_url'];
			
		if( isset( $input['freshdesk_sharedkey'] ) )
            $new_input['freshdesk_sharedkey'] = sanitize_text_field( $input['freshdesk_sharedkey'] );
			
		if( isset( $input['api_username'] ) )
            $new_input['api_username'] = sanitize_text_field( $input['api_username'] );
			
		if( isset( $input['api_pwd'] ) )
            $new_input['api_pwd'] = sanitize_text_field( $input['api_pwd'] );
			
		if( isset( $input['use_apikey'] ) )
            $new_input['use_apikey'] = sanitize_text_field( $input['use_apikey'] );
			
		if( isset( $input['no_tickets_msg'] ) )
            $new_input['no_tickets_msg'] = ( $input['no_tickets_msg'] );
        return $new_input;
    }


    /* 
     * Print the Section text
     */
    public function print_section_info(){
        //Nothing to do here
    }
	

    /*
     * Callback function for "Freshdesk API Key"
     */
    public function freshdesk_apikey_callback(){
		$val1 = $val2 = '';
		if( isset( $this->options['freshdesk_apikey'] ) ) {
			$val1 = esc_attr( $this->options['freshdesk_apikey']);
		} else {
			$val1 = '';
		}
		if( isset( $this->options['use_apikey'] ) ) {
			$val2 = ( $this->options['use_apikey'] != 'on' ) ? 'readonly="readonly"' : '';
		} else {
			$val2 = 'readonly="readonly"';
		}
		if( empty( $this->options ) ) {
			$val1 = '';
			$val2 = '';
		}
		
        printf(
            '<input autocomplete="off" type="text" id="freshdesk_apikey" name="fd_apikey[freshdesk_apikey]" value="%s" class="regular-text" %s />', $val1, $val2
        );
		printf( '<p id="timezone-description" class="description">Refer this tutorial to get your API key - <a href="http://bsf.io/freshdesk-api" target="blank">http://bsf.io/wp-freshdesk</a></p>' );
    }
		
	
	/*
     * Callback function for "Freshdesk Shared Secret Key"
     */
    public function freshdesk_sharedkey_callback(){
		$val1 = $val2 = '';
		if( isset( $this->url_options['freshdesk_sharedkey'] ) ) {
			$val1 = esc_attr( $this->url_options['freshdesk_sharedkey']);
		} else {
			$val1 = '';
		}
		if( isset( $this->url_options['freshdesk_enable'] ) ) {
			$val2 = '';
		} else {
			$val2 = 'readonly="readonly"';
		}
		if(  isset( $this->options['freshdesk_url'] ) && strlen( $this->options['freshdesk_url'] ) > 5 ) {
			$val = $this->options['freshdesk_url'];
		} else {
			$val = 'https://your_domain.freshdesk.com/';
		}
        printf(
            '<input autocomplete="off" type="text" id="freshdesk_sharedkey" name="fd_url[freshdesk_sharedkey]" value="%s" class="regular-text" %s />', $val1, $val2
        );
		printf( '<p id="timezone-description" class="description">Your shared token could be obtained on the <a target="_blank" href="%sadmin/security">Account Security page</a> in the <br> Single Sign-On >> "Simple SSO" section.</p>', $val
		);
    }
	
		
	 /*
     * Callback function for "Freshdesk Admin Username"
     */
    public function use_apikey_callback(){
		$on = ( isset( $this->options['use_apikey'] ) &&  $this->options['use_apikey'] == 'on' ) ? 'selected="selected"' : '';
		$off = ( isset( $this->options['use_apikey'] ) && $this->options['use_apikey'] != 'on' ) ? 'selected="selected"' : '';
        printf(
			'<select id="use_apikey" name="fd_apikey[use_apikey]">
				<option value="on" %s>API Key</option>
				<option value="off" %s>Username/Password</option>
			</select>', $on, $off
        );
    }
	
	
	/*
     * Callback function for "Freshdesk Admin Username"
     */
    public function api_username_callback(){
		$val1 = $val2 = '';
		if( !isset( $this->options['use_apikey'] ) || $this->options['use_apikey'] != 'on' ) {
			if( isset( $this->options['api_username'] ) ) {
				$val1 = esc_attr( $this->options['api_username'] );
				$val2 = '';
			}
		} else {
			if( isset( $this->options['api_username'] ) ) {
				$val1 = esc_attr( $this->options['api_username'] );
			}
			$val2 = 'readonly="readonly"';
		}
		if( empty( $this->options ) ) {
			$val1 = '';
			$val2 = 'readonly="readonly"';
		}
        printf(
            '<input type="text" autocomplete="off" placeholder="Username" id="api_username" name="fd_apikey[api_username]" value="%s" class="regular-text" %s>', $val1, $val2
        );
    }
	
	
	/*
     * Callback function for "Freshdesk Admin Password"
     */
    public function api_pwd_callback(){
		$val1 = $val2 = '';
		if( !isset( $this->options['use_apikey'] ) || $this->options['use_apikey'] != 'on' ) {
			if( isset( $this->options['api_pwd'] ) ) {
				$val1 = esc_attr( $this->options['api_pwd'] );
				$val2 = '';
			}
			
		} else {
			if( isset( $this->options['api_pwd'] ) ) {
				$val1 = esc_attr( $this->options['api_pwd'] );
			}
			$val2 = 'readonly="readonly"';
		}
		if( empty( $this->options ) ) {
			$val1 = '';
			$val2 = 'readonly="readonly"';
		}
        printf(
            '<input type="password" autocomplete="off" placeholder="Password" id="api_pwd" name="fd_apikey[api_pwd]" class="regular-text" value="%s" %s>', $val1, $val2
        );
    }
	
		
	/* 
     * Callback function for "Freshdesk URL"
     */
    public function freshdesk_url_callback(){
		$val = '';
		if( isset( $this->options['freshdesk_url'] ) ) {
			$val = $this->url_val;
		} else {
			$val = '';
		}
        printf(
            'https://<input type="text" autocomplete="off" id="freshdesk_url" name="fd_apikey[freshdesk_url]" value="%s" class="regular-text" placeholder="Ex: your_domain_name" />.freshdesk.com/', $val
        );
		printf( '<p class="description">Enter only <strong>test</strong> if your site domain is <strong>https://test.freshdesk.com/</strong></p><p id="timezone-description" class="description">(This is the base Freshdesk support URL.)</p>' );
    }
		
	
	/* 
     * Callback function for "Login URL" for SSO
     */
    public function freshdesk_loginurl_callback(){
        printf(
            '<code>' . site_url() . '/wp-login.php?action=fd-remote-login' . '</code><p class="description">Paste this URL as <strong>\'Remote Login URL\'</strong> in your Freshdesk settings page</p>'
        );
    }
	
	
	/*
     * Callback function for "Logout URL" for SSO
     */
    public function freshdesk_logouturl_callback(){
        printf(
            '<code>' . site_url() . '/wp-login.php?action=fd-remote-logout' . '</code><p class="description">Paste this URL as <strong>\'Remote Logout URL\'</strong> in your Freshdesk settings page</p>'
        );
    }
	
	
	/*
     * Callback function for "Enable SSO" checkbox
     */
    public function freshdesk_enable_callback(){
		$val = '';
		if( isset( $this->url_options['freshdesk_enable'] ) ){
			$val = ( $this->url_options['freshdesk_enable'] == 'on' ) ? 'checked="checked"' : '';
		} else {
			$val = '';
		}
		if( $val == '' ) {
			$class = ' fd-use-apikey-no';
			$yesno = 'No';
		} else {
			$class = ' fd-use-apikey-yes';
			$yesno = 'Yes';
		}
        printf(
            	'<div id="fd-wrapper">
					<div id="fd-main">
						<div class="fd-container">
							<div class="fd-settings">
								<div class="fd-row">
									<div class="fd-switch">
										<input id="freshdesk_enable" class="fd-toggle fd-toggle-round" type="checkbox" name="fd_url[freshdesk_enable]" %s>
										<label for="freshdesk_enable"><p id="freshdesk_enable-p" class="fd-use-apikey-yesno %s">%s</p></label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>', $val, $class, $yesno
        );
    }
	
	
	/*
     * Callback function for no ticket message text box
     */
	public function task_page_callback(){
		$val = '';
		if( isset( $this->display_option['task_pagename'] ) ){
			$val = ( $this->display_option['task_pagename'] != '' ) ? htmlentities( $this->display_option['task_pagename'] ) : '';
		}
        printf(
			'<input type="text" id="task_pagename" name="fd_display[task_pagename]" class="regular-text" value="%s" />', $val
        );
	}
	
	public function support_page_callback(){
		$val = '';
		if( isset( $this->display_option['support_pagename'] ) ){
			$val = ( $this->display_option['support_pagename'] != '' ) ? htmlentities( $this->display_option['support_pagename'] ) : '';
		}
        printf(
			'<input type="text" id="support_pagename" name="fd_display[support_pagename]" class="regular-text" value="%s" />', $val
        );
	}
}

if( is_admin() )
    new FreshDeskSettingsPage();



?>