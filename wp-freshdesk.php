<?php
/**
* Plugin Name: WP Custom Freshdesk
* Plugin URI:
* Description: With this plugin, your users will be able to see and control their support tickets on your site. Also your users will be able to manage their Task Queue Tickets.
* Version: 1.0
* Author: Xianglan K.
* Author URI: https://www.httpsimple.com/
* License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

//Block direct access to plugin files.
defined('ABSPATH') or die();

add_action('wp_ajax_add_task_ticket', 'add_task_ticket', 0);
add_action('wp_ajax_nopriv_add_task_ticket', 'add_task_ticket', 0);
function add_task_ticket()
{
    require_once(__DIR__ . '/includes/task_queue_tickets.php');
    if(!class_exists('TaskQueueTickets'))
    {
        $task_class = new TaskQueueTickets;
        $task_class->add_task_ticket();
    } else {
        TaskQueueTickets::add_task_ticket();
    }
}

add_action('wp_ajax_add_support_ticket', 'add_support_ticket', 0);
add_action('wp_ajax_nopriv_add_support_ticket', 'add_support_ticket', 0);
function add_support_ticket()
{
    require_once(__DIR__ . '/includes/support_tickets.php');
    if(!class_exists('SupportTickets'))
    {
        $support_class = new supportTickets;
        $support_class->add_support_ticket();
    } else {
        supportTickets::add_support_ticket();
    }
}

add_action('wp_ajax_update_order', 'update_order', 0, 2);
add_action('wp_ajax_nopriv_update_order', 'update_order', 0, 2);
function update_order()
{
    $ticket_ids = $_REQUEST['ticket_ids'];
    $sortorders = $_REQUEST['sortorders'];
    require_once(__DIR__ . '/includes/task_queue_tickets.php');
    if(!class_exists('TaskQueueTickets'))
    {
        $task_class = new TaskQueueTickets;
        $task_class->update_order($ticket_ids, $sortorders);
    } else {
        TaskQueueTickets::update_order($ticket_ids, $sortorders);
    }
}

add_action('wp_ajax_new_task_ticket', 'new_task_ticket', 0, 2);
add_action('wp_ajax_nopriv_new_task_ticket', 'new_task_ticket', 0, 2);
function new_task_ticket()
{
    if($_FILES['attachment']['name'] != '')
    {
        $files = $_FILES['attachment'];
    }
    else
    {
        $files = null;
    }
    $ticket_data = $_POST;
    require_once(__DIR__ . '/includes/task_queue_tickets.php');
    if(!class_exists('TaskQueueTickets'))
    {
        $task_class = new TaskQueueTickets;
        $task_class->new_task_ticket($ticket_data, $files);
    } else {
        TaskQueueTickets::new_task_ticket($ticket_data, $files);
    }
}

add_action('wp_ajax_new_support_ticket', 'new_support_ticket', 0, 4);
add_action('wp_ajax_nopriv_new_support_ticket', 'new_support_ticket', 0, 4);
function new_support_ticket()
{
    if($_FILES['attachment']['name'] != '')
    {
        $files = $_FILES['attachment'];
    }
    else
    {
        $files = null;
    }
    $ticket_data = $_POST;
    require_once(__DIR__ . '/includes/support_tickets.php');
    if(!class_exists('SupportTickets'))
    {
        $task_class = new SupportTickets;
        $task_class->new_support_ticket($ticket_data, $files);
    } else {
        SupportTickets::new_support_ticket($ticket_data, $files);
    }
}

add_action('wp_ajax_delete_task_ticket', 'delete_task_ticket', 0);
add_action('wp_ajax_nopriv_delete_task_ticket', 'delete_task_ticket', 0);
function delete_task_ticket()
{
    $ticket_id = $_POST['ticket_id'];
    require_once(__DIR__ . '/includes/task_queue_tickets.php');
    if(!class_exists('TaskQueueTickets'))
    {
        $task_class = new TaskQueueTickets;
        $task_class->delete_task_ticket($ticket_id);
    } else {
        TaskQueueTickets::delete_task_ticket($ticket_id);
    }
}

add_action('wp_ajax_view_task_ticket', 'view_task_ticket', 0);
add_action('wp_ajax_nopriv_view_task_ticket', 'view_task_ticket', 0);
function view_task_ticket()
{
    $ticket_id = $_REQUEST['ticket_id'];
    require_once(__DIR__ . '/includes/task_queue_tickets.php');
    if(!class_exists('TaskQueueTickets'))
    {
        $task_class = new TaskQueueTickets;
        $task_class->view_task_ticket($ticket_id);
    } else {
        TaskQueueTickets::view_task_ticket($ticket_id);
    }
}

add_action('wp_ajax_view_support_ticket', 'view_support_ticket', 0);
add_action('wp_ajax_nopriv_view_support_ticket', 'view_support_ticket', 0);
function view_support_ticket()
{
    $ticket_id = $_REQUEST['ticket_id'];
    require_once(__DIR__ . '/includes/support_tickets.php');
    if(!class_exists('SupportTickets'))
    {
        $support_class = new SupportTickets;
        $support_class->view_support_ticket($ticket_id);
    } else {
        SupportTickets::view_support_ticket($ticket_id);
    }
}

add_action('wp_ajax_add_reply', 'add_reply', 0, 2);
add_action('wp_ajax_nopriv_add_reply', 'add_reply', 0, 2);
function add_reply()
{
    $ticket_id = $_REQUEST['ticket_id'];
    $reply = $_REQUEST['conversation_body'];
    require_once(__DIR__ . '/includes/task_queue_tickets.php');
    if(!class_exists('TaskQueueTickets'))
    {
        $task_class = new TaskQueueTickets;
        $task_class->add_reply($ticket_id, $reply);
    } else {
        TaskQueueTickets::add_reply($ticket_id, $reply);
    }
}

add_action('wp_ajax_add_support_reply', 'add_support_reply', 0, 2);
add_action('wp_ajax_nopriv_add_support_reply', 'add_support_reply', 0, 2);
function add_support_reply()
{
    $ticket_id = $_REQUEST['ticket_id'];
    $reply = $_REQUEST['conversation_body'];
    require_once(__DIR__ . '/includes/support_tickets.php');
    if(!class_exists('SupportTickets'))
    {
        $support_class = new SupportTickets;
        $support_class->add_support_reply($ticket_id, $reply);
    } else {
        SupportTickets::add_support_reply($ticket_id, $reply);
    }
}

add_action('wp_ajax_close_task_ticket', 'close_ticket', 0, 1);
add_action('wp_ajax_nopriv_close_task_ticket', 'close_ticket', 0, 1);
add_filter ( 'woocommerce_account_menu_items', 'add_ticket_pages' );
add_filter( 'woocommerce_get_endpoint_url', 'page_urls', 10, 4 );
function close_ticket()
{
    $ticket_id = $_REQUEST['ticket_id'];
    require_once(__DIR__ . '/includes/task_queue_tickets.php');
    if(!class_exists('TaskQueueTickets'))
    {
        $task_class = new TaskQueueTickets;
        $task_class->close_ticket($ticket_id);
    } else {
        TaskQueueTickets::close_ticket($ticket_id);
    }
}

function add_ticket_pages($menu_links)
{
    $pagename_option = get_option('fd_display');
    
    $task = array('task-queue' => (isset($pagename_option['task_pagename']) && $pagename_option['task_pagename'] != '') ? $pagename_option['task_pagename'] : 'TaskQueue Tickets');
    $support = array('support' => (isset($pagename_option['support_pagename']) && $pagename_option['support_pagename'] != '') ? $pagename_option['support_pagename'] : 'Support Tickets');

    $menu_links = array_slice($menu_links, 0, 2, true) + $task + array_slice($menu_links, 2, 3, true) + $support + array_slice($menu_links, 5, null, true);

    return $menu_links;
}


function page_urls( $url, $endpoint, $value, $permalink ){

    if( $endpoint === 'task-queue' ) {

        // ok, here is the place for your custom URL, it could be external
        $url = site_url('task-queue');

    }
    else if($endpoint === 'support') {
        $url = site_url('support');
    }
    return $url;

}

if (!class_exists("FreshDeskAPI")) {
    class FreshDeskAPI
    {
    
        //Class Variables
        private $freshdeskUrl;
        private $opt;
        private $options;
        private $display_option;
    
        /*
         * Function Name: __construct
         * Function Description: Constructor
         */
        
        function __construct()
        {
        
            add_action('init', array( $this, 'init' ));
            add_action('plugins_loaded', array( $this, 'fd_load_textdomain' ));
            $this->create_pages();
            
            include_once('admin-settings.php');
            // include_once('include/support_tickets.php');
            add_filter( 'template_include', array( __CLASS__, 'template_loader' ) );
            
            $this->options = get_option('fd_url');
            $this->opt = get_option('fd_apikey');
            $this->display_option = get_option('fd_display');
            
            if (isset($this->opt['freshdesk_url'])) {
                if (preg_match("/^[-A-Za-z\d\s]+$/", $this->opt['freshdesk_url'])) {
                    $this->freshdeskUrl = 'https://' . $this->opt['freshdesk_url'] . '.freshdesk.com/';
                } else {
                    $this->freshdeskUrl = '';
                }
            } else {
                $this->freshdeskUrl = '';
            }
        }
        
        public static function template_loader($template) {
            if ( is_embed() ) {
                return $template;
            }

            $class=new FreshDeskAPI;
            $default_file = $class->check_page();

            if($default_file != '')
            {
                $template = __DIR__ . '/includes/' . $default_file;
                require_once($template);
                if($default_file == 'task_queue_tickets.php')
                {
                    $class = new TaskQueueTickets;
                    $class->init();
                    return $template;
                }
                else if($default_file == 'support_tickets.php')
                {
                    $class = new SupportTickets;
                    $class->init();
                    return $template;
                }
            }

            return $template;
        }

        public function check_page()
        {
            $task_page_id = apply_filters( 'freshdesk_get_taskqueue_page_id', get_option( 'freshdesk_taskqueue_page_id' ));
            $support_page_id = apply_filters( 'freshdesk_get_support_page_id', get_option( 'freshdesk_support_page_id' ));
            if(is_page($task_page_id))
            {
                return 'task_queue_tickets.php';
            }
            else if(is_page($support_page_id))
            {
                return 'support_tickets.php';
            }

            return '';
        }
        
        /**
         * Load plugin textdomain.
         *
         * @since 1.0.0
         */
        function fd_load_textdomain()
        {
            load_plugin_textdomain('wp-freshdesk', false, plugin_basename(dirname(__FILE__)) . '/languages');
        }

        /*
         * Function Name: create_pages
         * Function Description: Create task queue ticket page and support ticket page.
         */
        function create_pages()
        {
            $pages = apply_filters(
                'freshdesk_create_pages',
                array(
                    'taskqueue'      => array(
                        'name'    => _x( 'task-queue', 'Page slug', 'freshdesk' ),
                        'title'   => _x( 'Task Queue Tickets', 'Page title', 'freshdesk' ),
                        'content' => '',
                    ),
                    'support'      => array(
                        'name'    => _x( 'support', 'Page slug', 'freshdesk' ),
                        'title'   => _x( 'Support Tickets', 'Page title', 'freshdesk' ),
                        'content' => '',
                    ))
                );
    
            foreach ( $pages as $key => $page ) {
                $this->create_freshdesk_page( esc_sql( $page['name'] ), 'freshdesk_' . $key . '_page_id', $page['title'], $page['content'], '');
            }

            return true;
        }

        function create_freshdesk_page($slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0 )
        {
            global $wpdb;
            $option_value = get_option( $option );

            if ( $option_value > 0 ) {
                $page_object = get_post( $option_value );

                if ( $page_object && 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array( 'pending', 'trash', 'future', 'auto-draft' ), true ) ) {
                    // Valid page is already in place.
                    return $page_object->ID;
                }
            }

            if ( strlen( $page_content ) > 0 ) {
                // Search for an existing page with the specified page content (typically a shortcode).
                $shortcode = str_replace( array( '<!-- wp:shortcode -->', '<!-- /wp:shortcode -->' ), '', $page_content );
                $valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$shortcode}%" ) );
            } else {
                // Search for an existing page with the specified page slug.
                $valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
            }

            $valid_page_found = apply_filters( 'woocommerce_create_page_id', $valid_page_found, $slug, $page_content );

            if ( $valid_page_found ) {
                if ( $option ) {
                    update_option( $option, $valid_page_found );
                }
                return $valid_page_found;
            }

            // Search for a matching valid trashed page.
            if ( strlen( $page_content ) > 0 ) {
                // Search for an existing page with the specified page content (typically a shortcode).
                $trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
            } else {
                // Search for an existing page with the specified page slug.
                $trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
            }

            if ( $trashed_page_found ) {
                $page_id   = $trashed_page_found;
                $page_data = array(
                    'ID'          => $page_id,
                    'post_status' => 'publish',
                );
                wp_update_post( $page_data );
            } else {
                $page_data = array(
                    'post_status'    => 'publish',
                    'post_type'      => 'page',
                    'post_author'    => 1,
                    'post_name'      => $slug,
                    'post_title'     => $page_title,
                    'post_content'   => $page_content,
                    'post_parent'    => $post_parent,
                    'comment_status' => 'closed',
                );
                $page_id   = wp_insert_post( $page_data );
            }

            if ( $option ) {
                update_option( $option, $page_id );
            }

            return $page_id;
        }
        
        /*
         * Function Name: init
         * Function Description: Initialization
         */
        public function init()
        {
            if (is_user_logged_in()) {
                // This is a login request.
                if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'fd-remote-login') {
                    // Don't waste time if remote auth is turned off.
                    if (!isset($this->options['freshdesk_enable']) && $this->options['freshdesk_enable'] != 'on' && !isset($this->options['freshdesk_sharedkey']) && $this->options['freshdesk_sharedkey'] != '') {
                        __('Remote authentication is not configured yet.', 'wp-freshdesk');
                        die();
                    }
                    // Filter freshdesk_return_to
                    $return_to = apply_filters('freshdesk_return_to', $_REQUEST['host_url']) ;
    
                    // If the current user is logged in
                    if (is_user_logged_in()) {
                        global $current_user;
                        wp_get_current_user();
    
                        // Pick the most appropriate name for the current user.
                        if ($current_user->user_firstname != '' && $current_user->user_lastname != '') {
                            $name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
                        } else {
                            $name = $current_user->display_name;
                        }
    
                        // Gather more info from the user, incl. external ID
                        $email = $current_user->user_email;
    
                        // The token is the remote "Shared Secret" under Admin - Security - Enable Single Sign On
                        $token = $this->options['freshdesk_sharedkey'];
    
                        // Current timestamp.
                        $timestamp = time();

                        // Generate the hash as per http://www.freshdesk.com/api/remote-authentication

                        $to_be_hashed = $name . $token . $email . $timestamp;
                        $hash = hash_hmac('md5', $to_be_hashed, $token);

    
                        // Create the SSO redirect URL and fire the redirect.
                        $sso_url = trailingslashit($this->freshdeskUrl) . 'login/sso/?action=fd-remote-login&return_to=' . urlencode('https://' . $return_to . '/') . '&name=' . urlencode($name) . '&email=' . urlencode($email) . '&hash=' . urlencode($hash) . '&timestamp=' . $timestamp;
    
                        //Hook before redirecting logged in user.
                        do_action('freshdesk_logged_in_redirect_before');
    
                        wp_redirect($sso_url);
    
                        // No further output.
                        die();
                    } else {
                        //Hook before redirecting user to login form
                        do_action('freshdesk_logged_in_redirect_before');
    
                        // If the current user is not logged in we ask him to visit the login form
                        // first, authenticate and specify the current URL again as the return
                        // to address. Hopefully WordPress will understand this.
                        wp_redirect(wp_login_url(wp_login_url() . '?action=fd-remote-login&&return_to=' . urlencode($return_to)));
                        die();
                    }
                }
    
                // Is this a logout request? Errors from Freshdesk are handled here too.
                if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'fd-remote-logout') {
                    // Error processing and info messages are done here.
                    $kind = isset($_REQUEST['kind']) ? $_REQUEST['kind'] : 'info';
                    $message = isset($_REQUEST['message']) ? $_REQUEST['message'] : 'nothing';
    
                    // Depending on the message kind
                    if ($kind == 'info') {
                        // When the kind is an info, it probably means that the logout
                        // was successful, thus, logout of WordPress too.
                        wp_redirect(htmlspecialchars_decode(wp_logout_url()));
                        die();
                    } elseif ($kind == 'error') {
                        // If there was an error...
                        ?>
                        <p><?php __('Remote authentication failed: ', 'wp-freshdesk'); ?><?php echo $message; ?>.</p>
                        <ul>
                            <li><a href="<?php echo $this->freshdeskUrl; ?>"><?php __('Try again', 'wp-freshdesk'); ?></a></li>
                            <li><a href="<?php echo wp_logout_url(); ?>"><?php printf(__('Log out of %s', 'wp-freshdesk'), get_bloginfo('name')); ?></a></li>
                            <li><a href="<?php echo admin_url(); ?>"><?php printf(__('Return to %s dashboard', 'wp-freshdesk'), get_bloginfo('name')); ?></a></li>
                        </ul>
                        <?php
                    }
    
                    // No further output.
                    die();
                }
            }
        }
        
        /*
         * Function Name: fetch_tickets
         * Function Description: Fetched all tickets from Freshdesk for current logged in user.
         */
        
        public function fetch_tickets($atts)
        {
            $result = '
			<div class="fd-tickets-outter">
				<ul>';
            
            if (is_user_logged_in()) {
                global $current_user;
                $fd_filter_dropdown = ( isset($_GET["fd-filter_dropdown"]) ) ? esc_attr($_GET["fd-filter_dropdown"]) : '';
                if (( isset($this->opt['freshdesk_apikey']) && $this->opt['freshdesk_apikey'] != '' ) || !isset($this->opt['use_apikey'])) {
                    if (isset($atts['filter']) && trim($atts['filter']) != '') {
                        switch (trim(ucwords(strtolower($atts['filter'])))) {
                            case 'Open':
                                $fd_filter_dropdown = 'Open';
                                break;
                            case 'Closed':
                                $fd_filter_dropdown = 'Closed';
                                break;
                            case 'Resolved':
                                $fd_filter_dropdown = 'Resolved';
                                break;
                            case 'Waiting On Third Party':
                                $fd_filter_dropdown = 'Waiting on Third Party';
                                break;
                            case 'Waiting On Customer':
                                $fd_filter_dropdown = 'Waiting on Customer';
                                break;
                            case 'Pending':
                                $fd_filter_dropdown = 'Pending';
                                break;
                            default:
                                break;
                        }
                    }
                    
                    if (!isset($fd_filter_dropdown) || $fd_filter_dropdown == '') {
                        $fd_filter_dropdown = 'Open';
                    }
                    
                    $tickets = $this->get_tickets($current_user->data->user_email, $current_user->roles);
                    $filteredTickets = false;
                    $search_txt = ( isset($_GET['search_txt']) ) ? esc_attr($_GET['search_txt']) : '';
                    if (isset($tickets)) {
                        $tickets = json_decode(json_encode($tickets), true);
                        if (isset($fd_filter_dropdown) && $fd_filter_dropdown != '') {
                            $filteredTickets = ( $fd_filter_dropdown != 'all_tickets' ) ? $this->filter_tickets($tickets, $fd_filter_dropdown) : $tickets ;
                        }
                        if (isset($search_txt) && $search_txt != '') {
                            $filteredTickets = ( trim($search_txt) != '' ) ? $this->search_tickets($filteredTickets, $search_txt) : $tickets ;
                        }
                    } else {
                        $filteredTickets = false;
                    }
                    
                    
                    
                    $result .= '
								<li class="fd-filter-tickets">
									<form method="get" action="" id="fd-filter_form" name="fd-filter_form">
										<div class="fd-filter-dropdown fd-filter">
											<select id="fd-filter_dropdown" name="fd-filter_dropdown">
												<option value="all_tickets" ';
                    if (isset($fd_filter_dropdown)) {
                        $result .= ( $fd_filter_dropdown == "all_tickets" ) ? 'selected="selected"' : '';
                    }
                                    $result .= '>' . __('All Tickets', 'wp-freshdesk') . '</option>
												<option value="Open" ';
                    if (isset($fd_filter_dropdown)) {
                        $result .= ( $fd_filter_dropdown == "Open" ) ? 'selected="selected"' : '';
                    }
                                    $result .= '>' . __('Open', 'wp-freshdesk') . '</option>
												<option value="Pending" ';
                    if (isset($fd_filter_dropdown)) {
                        $result .= ( $fd_filter_dropdown == "Pending" ) ? 'selected="selected"' : '';
                    }
                                    $result .= '>' . __('Pending', 'wp-freshdesk') . '</option>
												<option value="Resolved" ';
                    if (isset($fd_filter_dropdown)) {
                        $result .= ( $fd_filter_dropdown == "Resolved" ) ? 'selected="selected"' : '';
                    }
                                    $result .= '>' . __('Resolved', 'wp-freshdesk') . '</option>
												<option value="Closed" ';
                    if (isset($fd_filter_dropdown)) {
                        $result .= ( $fd_filter_dropdown == "Closed" ) ? 'selected="selected"' : '';
                    }
                                    $result .= '>' . __('Closed', 'wp-freshdesk') . '</option>
												<option value="Waiting on Customer" ';
                    if (isset($fd_filter_dropdown)) {
                        $result .= ( $fd_filter_dropdown == "Waiting on Customer" ) ? 'selected="selected"' : '';
                    }
                                    $result .= '>' . __('Waiting on Customer', 'wp-freshdesk') . '</option>
												<option value="Waiting on Third Party" ';
                    if (isset($fd_filter_dropdown)) {
                        $result .= ( $fd_filter_dropdown == "Waiting on Third Party" ) ? 'selected="selected"' : '';
                    }
                                    $txt = ( isset($search_txt) ) ? $search_txt : '';
                                    $result .= '>' . __('Waiting on Third Party', 'wp-freshdesk') . '</option>
											</select>
										</div>
										<div class="fd-search-box fd-filter">
											<input type="text" value="' . $txt . '" id="search_txt" name="search_txt" placeholder="' . __('Search...', 'wp-freshdesk') . '"/>
										</div>
										<div class="fd-filter">
											<input type="submit" value="Search" id="filter_tickets"/>
										</div>
										<div class="fd-filter">
											<input type="button" value="Reset" id="reset_filter">
										</div>
										<div class="clear"></div>
									</form>
								</li>';
                    
                    if (!isset($tickets->require_login) && $tickets != '' && !isset($tickets->errors) && !empty($tickets)) {
                        if (isset($search_txt) || isset($fd_filter_dropdown)) {
                            if (!isset($filteredTickets->require_login) && $filteredTickets != '' && !isset($filteredTickets->errors) && !empty($filteredTickets)) {
                                $result .= $this->get_html($filteredTickets);
                            } else {
                                if (isset($filteredTickets->require_login)) {
                                    $msg = __('Invalid Credentials', 'wp-freshdesk');
                                } elseif (isset($filteredTickets->errors)) {
                                    if (isset($filteredTickets->errors->no_email)) {
                                        $msg = ( isset($this->display_option['invalid_user_msg']) && $this->display_option['invalid_user_msg'] != '' ) ? $this->display_option['invalid_user_msg'] : __('Invalid User', 'wp-freshdesk');
                                    } else {
                                        $msg = __('Invalid Freshdesk URL', 'wp-freshdesk');
                                    }
                                } elseif (empty($filteredTickets)) {
                                    $keyword = ( isset($search_txt) && $search_txt != '' ) ? 'keyword <strong>"' . $search_txt . '"</strong>.' : '';
                                    $dropdown = ( isset($fd_filter_dropdown) && $fd_filter_dropdown != '' ) ? 'No tickets for <strong>"' . strtoupper(str_replace('_', ' ', $fd_filter_dropdown)) . '"</strong> category' : '';
                                    $str = $dropdown;
                                    $str .= ( $keyword != '' ) ? ' & ' . $keyword : '';
                                    $msg = '<p> ' . $str . '</p><div class="fd-more-ticket">Could not find what you are searching for? Click <a href="' . $this->freshdeskUrl . 'support/tickets" target="_blank">here</a> to check all your old tickets.</div>';
                                } else {
                                    $msg = __('Error!', 'wp-freshdesk');
                                }
                                $result .= '<li>
												<div class="fd-message">' . $msg . '</div>
											</li>';
                            }
                        } else {
                            $result .= $this->get_html($tickets);
                        }
                    } else {
                        if (isset($tickets->require_login)) {
                            $msg = __('Invalid Credentials', 'wp-freshdesk');
                        } elseif (isset($tickets->errors)) {
                            if (isset($tickets->errors->no_email)) {
                                $msg = ( isset($this->display_option['invalid_user_msg']) && $this->display_option['invalid_user_msg'] != '' ) ? $this->display_option['invalid_user_msg'] : __('Invalid User', 'wp-freshdesk');
                            } else {
                                $msg = __('Invalid Freshdesk URL', 'wp-freshdesk');
                            }
                        } elseif (empty($tickets)) {
                            $msg = ( isset($this->display_option['no_tickets_msg']) && $this->display_option['no_tickets_msg'] != '' ) ? $this->display_option['no_tickets_msg'] : __('No tickets', 'wp-freshdesk');
                        } else {
                            $msg = __('Error!', 'wp-freshdesk');
                        }
                        $result .= '<li>
										<div class="fd-message">' . $msg . '</div>
									</li>';
                    }
                } else {
                    $result .= '
						<li>
							<div class="fd-message">Please configure settings for <strong>Freshdesk API</strong> from <a href="' . admin_url('/options-general.php?page=wp-freshdesk') . '" target="_blank">admin panel</a></div>
						</li>
					';
                }
            } else {
                $result .= '
					<li>
						<div class="fd-message"><a href="' . wp_login_url() . '" title="Login">Login</a> to view your tickets!</div>
					</li>
				';
            }
            
            $result .=
                '</ul>
			</div>';
            return $result;
        }
        
        
        /*
         * Function Name: new_ticket
         * Function Description: Create a new ticket button html
         */
        
        function new_ticket()
        {
            return '<form action="' . $this->freshdeskUrl . 'support/tickets/new/" target="_blank"><input type="submit" value="New Ticket" id="new_ticket"></form>';
        }
        
        
        /*
         * Function Name: get_tickets
         * Function Description: API call to Freshdesk to get all tickets of the user(email)
         */
        
        public function get_tickets($uemail = '', $roles = array())
        {
            if (!empty($uemail)) {
                $filterName = 'all_tickets';
                if (isset($this->opt['use_apikey'])) {
                    $apikey = ( $this->opt['freshdesk_apikey'] != '' ) ? $this->opt['freshdesk_apikey'] : '';
                    $password = "";
                } else {
                    $apikey = ( $this->opt['api_username'] != '' ) ? $this->opt['api_username'] : '';
                    $password = ( $this->opt['api_pwd'] != '' ) ? $this->opt['api_pwd'] : '';
                }
                
                $filter = ( !in_array('administrator', $roles) ) ? '&email=' . $uemail : '';
                $url = $this->freshdeskUrl . 'helpdesk/tickets.json?filter_name=' . $filterName . $filter;
                
                $auth = base64_encode($apikey . ':' . $password);
                
                $args = array(
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Authorization' => "Basic $auth"
                    ),
                    'body' => array()
                );

                $response = wp_remote_get($url, $args);

                // test for wp errors.
                if (is_wp_error($response)) {
                    return array();
                    exit;
                }

                $body = wp_remote_retrieve_body($response);
                $tickets = json_decode($body);
                return $tickets;
            } else {
                return false;
            }
        }
        
        
        /*
         * Function Name: get_html
         * Function Description: Returns HTML string of the tickets
         */
        
        public function get_html($tickets = '')
        {
            global $current_user;
            $html = '';
            $tickets = json_decode(json_encode($tickets), false);
            $append = ( count($tickets) > 1 ) ? 's' : '';
            $html .=
            '<li>
				<div class="fd-message">' . count($tickets) . ' ticket' . $append . ' found (Opened with: ' . $current_user->data->user_email . ')</div>
			</li>';
            
            foreach ($tickets as $d) {
                $class = ( $d->status_name == "Closed" ) ? 'status-closed' : '';
                $diff = ( strtotime(date_i18n('Y-m-d H:i:s')) - strtotime(date_i18n('Y-m-d H:i:s', false, 'gmt')) );
                $date = date_i18n('j M\' Y, g:i A', strtotime($d->updated_at) + $diff);
                $description = ( strlen($d->description) > 125 ) ? strip_tags(substr($d->description, 0, 125)) . '...' : strip_tags($d->description);
                $time_elapsed = $this->timeAgo(date_i18n('Y-m-d H:i:s', strtotime($d->updated_at) + $diff));
                $html .= '
				<li class="group ' . $class . '">
					<a href="' . $this->freshdeskUrl . 'helpdesk/tickets/' . $d->display_id . '" target="_blank">
						<span class="ticket-data">
							<span class="ticket-title">' . strip_tags($d->subject) . ' <span class="ticket-id">#' . $d->display_id . '</span></span>
							<span class="ticket-excerpt">' . $description . '</span>
						</span>
						<span class="ticket-meta">
							<span class="ticket-status ' . $class . '">' . strip_tags($d->status_name) . '</span>
							<span class="ticket-time"><abbr title="Last Updated on - ' . $date . '" class="timeago comment-time ticket-updated-at">' . $time_elapsed . '</abbr></span>
						</span>
					</a>
				</li>';
            }
            return $html;
        }
        
        
        /*
         * Function Name: filter_tickets
         * Function Description: Filters the tickets according to ticket_status
         */
        
        public function filter_tickets($tickets = '', $status = '')
        {
            $filtered_tickets = array();
            if ($status != 'all_tickets') {
                foreach ($tickets as $t) {
                    if ($t['status_name'] == $status) {
                        $filtered_tickets[] = $t;
                    }
                }
                return $filtered_tickets;
            } else {
                return $tickets;
            }
        }
        
        
        /*
         * Function Name: search_tickets
         * Function Description: Searches the tickets according to input text
         */
        
        public function search_tickets($tickets, $txt = '')
        {
            $filtered_tickets = array();
            foreach ($tickets as $t) {
                if (stristr($t['subject'], trim($txt)) || stristr($t['description'], trim($txt)) || stristr($t['id'], trim($txt))) {
                    $filtered_tickets[] = $t;
                }
            }
            return $filtered_tickets;
        }
        
        
        /*
         * Function Name: timeAgo
         * Function Description: returns input php time to "mins/hours/months/weeks/years ago" format.
         */

        function timeAgo($time_ago)
        {
            $time_ago = strtotime($time_ago);
            $cur_time = strtotime(date_i18n('Y-m-d H:i:s'));
            $time_elapsed = $cur_time - $time_ago;
            $seconds = $time_elapsed ;
            $minutes = round($time_elapsed / 60);
            $hours = round($time_elapsed / 3600);
            $days = round($time_elapsed / 86400);
            $weeks = round($time_elapsed / 604800);
            $months = round($time_elapsed / 2600640);
            $years = round($time_elapsed / 31207680);
            // Seconds
            if ($seconds <= 60) {
                return "just now";
            }
            //Minutes
            elseif ($minutes <= 60) {
                if ($minutes == 1) {
                    return "one minute ago";
                } else {
                    return "$minutes minutes ago";
                }
            }
            //Hours
            elseif ($hours <= 24) {
                if ($hours == 1) {
                    return "an hour ago";
                } else {
                    return "$hours hrs ago";
                }
            }
            //Days
            elseif ($days <= 7) {
                if ($days == 1) {
                    return "yesterday";
                } else {
                    return "$days days ago";
                }
            }
            //Weeks
            elseif ($weeks <= 4.3) {
                if ($weeks == 1) {
                    return "a week ago";
                } else {
                    return "$weeks weeks ago";
                }
            }
            //Months
            elseif ($months <= 12) {
                if ($months == 1) {
                    return "a month ago";
                } else {
                    return "$months months ago";
                }
            }
            //Years
            else {
                if ($years == 1) {
                    return "one year ago";
                } else {
                    return "$years years ago";
                }
            }
        }
    }
} //end of class


/* Register the activation function and redirect to Setting page. */
register_activation_hook(__FILE__, 'fd_plugin_activate');
add_action('admin_init', 'fd_plugin_redirect');

/*
 * Function Name: fd_plugin_redirect
 * Function Description:
 */
 
function fd_plugin_redirect()
{
    if (get_option('fd_do_activation_redirect', false)) {
        delete_option('fd_do_activation_redirect');
        $activate_multi = isset($_GET['activate-multi']) ? esc_attr($_GET['activate-multi']) : '';
        if (!$activate_multi) {
            wp_redirect('options-general.php?page=wp-freshdesk');
        }
    }
}

/*
 * Function Name: fd_plugin_activate
 * Function Description:
 */

function fd_plugin_activate()
{
    add_option('fd_do_activation_redirect', true);
    $activate_multi = isset($_GET['activate-multi']) ? esc_attr($_GET['activate-multi']) : '';
    if (!$activate_multi) {
        wp_redirect('options-general.php?page=wp-freshdesk');
    }
}

new FreshDeskAPI();
?>
