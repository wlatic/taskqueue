<?php
// defined('ABSPATH') or die();

if(!class_exists('SupportTickets'))
{
    class SupportTickets
    {
        private $freshdeskUrl;
        private $opt;
        private $options;
        private $display_option;

        function __construct()
        {
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

        public function init()
        {
            add_filter('get_support_tickets', array($this, 'get_support_tickets'));
            
            $this->enqueue_scripts();
            
            load_template(__DIR__ . '/support/index.php');
        }

        public function add_support_ticket()
        {
            $class = new SupportTickets();
            $class->enqueue_scripts();
            load_template(__DIR__ . '/support/add_new.php');
        }

        function enqueue_scripts()
        {
            wp_register_style('fontawesome', plugins_url('task_queue/css/fontawesome.css', __FILE__));
            wp_enqueue_style('fontawesome');
            wp_register_style('style9', plugins_url('task_queue/css/styles9.css', __FILE__));
            wp_enqueue_style('style9');
            wp_register_style('dropzone', plugins_url('task_queue/css/dropzone.css', __FILE__));
            wp_enqueue_style('dropzone');
            wp_register_style('bootstrap', plugins_url('task_queue/css/bootstrap1.css', __FILE__));
            wp_enqueue_style('bootstrap');
            wp_register_style('material_icons', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons');
            wp_enqueue_style('material_icons');
            wp_register_style('dataTables.bootstrap4.min', plugins_url('task_queue/css/dataTables.bootstrap4.min.css', __FILE__));
            wp_enqueue_style('dataTables.bootstrap4.min');
            wp_register_style('rowReorder.bootstrap4.min', plugins_url('task_queue/css/rowReorder.bootstrap4.min.css', __FILE__));
            wp_enqueue_style('rowReorder.bootstrap4.min');
            wp_register_style('summernote-bs4style', plugins_url('task_queue/css/summernote-bs4.css', __FILE__));
            wp_enqueue_style('summernote-bs4style');
            wp_register_script('jquery', plugins_url('task_queue/js/jquery-3.3.1.js', __FILE__));
            wp_enqueue_script('jquery');
            wp_register_script('popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js');
            wp_enqueue_script('popper');
            wp_register_script('standard', plugins_url('task_queue/js/standard.js', __FILE__));
            wp_enqueue_script('standard');
            // wp_register_script('contact', plugins_url('task_queue/js/contact.js', __FILE__));
            // wp_enqueue_script('contact');
            wp_register_script('jquery.dataTables.min', plugins_url('task_queue/js/jquery.dataTables.min.js', __FILE__));
            wp_enqueue_script('jquery.dataTables.min');
            wp_register_script('dataTables.rowReorder.min', plugins_url('task_queue/js/dataTables.rowReorder.min.js', __FILE__));
            wp_enqueue_script('dataTables.rowReorder.min');
            wp_register_script('dataTables.bootstrap4.min', plugins_url('task_queue/js/dataTables.bootstrap4.min.js', __FILE__));
            wp_enqueue_script('dataTables.bootstrap4.min');
            wp_register_script('summernote-bs4', plugins_url('task_queue/js/summernote-bs4.js', __FILE__));
            wp_enqueue_script('summernote-bs4');
            
        }

        public function get_support_tickets()
        {
            return array('ticket' => $this->fetch_tickets('all_tickets'), 'group' => $this->get_groups());
        }

        public function view_support_ticket($ticket_id)
        {
            $class = new SupportTickets();
            $class->enqueue_scripts();
            $url = $class->freshdeskUrl."api/v2/tickets/$ticket_id?include=requester";
            if (isset($class->opt['use_apikey'])) {
                $api_key = ( $class->opt['freshdesk_apikey'] != '' ) ? $class->opt['freshdesk_apikey'] : '';
                $password = "";
            } else {
                $api_key = ( $class->opt['api_username'] != '' ) ? $class->opt['api_username'] : '';
                $password = ( $class->opt['api_pwd'] != '' ) ? $class->opt['api_pwd'] : '';
            }

            $ch = curl_init($url);

            // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headers = substr($server_output, 0, $header_size);
            $response = substr($server_output, $header_size);
            curl_close();
            $ticket_data = $response;

            $url = $class->freshdeskUrl."api/v2/tickets/$ticket_id/conversations";
            if (isset($class->opt['use_apikey'])) {
                $api_key = ( $class->opt['freshdesk_apikey'] != '' ) ? $class->opt['freshdesk_apikey'] : '';
                $password = "";
            } else {
                $api_key = ( $class->opt['api_username'] != '' ) ? $class->opt['api_username'] : '';
                $password = ( $class->opt['api_pwd'] != '' ) ? $class->opt['api_pwd'] : '';
            }

            $ch = curl_init($url);

            // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headers = substr($server_output, 0, $header_size);
            $response = substr($server_output, $header_size);
            curl_close();
            $conversation_data = $response;

            $data = array('ticket_data' => json_decode($ticket_data, true), 'conversation_data' => json_decode($conversation_data, true));
            set_query_var('data', $data);
            
            // $class->enqueue_scripts();
            load_template(__DIR__ . '/support/view_ticket.php');
        }

        public function add_support_reply($ticket_id, $reply)
        {
            global $current_user;
            $class = new SupportTickets();
            
            $conversation = array(
                    'body' => $reply
            );
            $url = $class->freshdeskUrl."api/v2/tickets/$ticket_id/reply";
            if (isset($class->opt['use_apikey'])) {
                $api_key = ( $class->opt['freshdesk_apikey'] != '' ) ? $class->opt['freshdesk_apikey'] : '';
                $password = "";
            } else {
                $api_key = ( $class->opt['api_username'] != '' ) ? $class->opt['api_username'] : '';
                $password = ( $class->opt['api_pwd'] != '' ) ? $class->opt['api_pwd'] : '';
            }

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $conversation);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headers = substr($server_output, 0, $header_size);
            $response = substr($server_output, $header_size);
            curl_close();

            if(wp_redirect(get_page_link(get_page_by_title('Support Tickets'))))
            {
                exit();
            }
        }

        public function close_ticket($ticket_id)
        {
            global $current_user;
            $class = new SupportTickets();
            
            $status = array(
                'status' => 5
            );
            $url = $class->freshdeskUrl."api/v2/tickets/$ticket_id";
            if (isset($class->opt['use_apikey'])) {
                $api_key = ( $class->opt['freshdesk_apikey'] != '' ) ? $class->opt['freshdesk_apikey'] : '';
                $password = "";
            } else {
                $api_key = ( $class->opt['api_username'] != '' ) ? $class->opt['api_username'] : '';
                $password = ( $class->opt['api_pwd'] != '' ) ? $class->opt['api_pwd'] : '';
            }

            $ch = curl_init($url);
            $header[] = "Content-type: application/json";

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($status));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headers = substr($server_output, 0, $header_size);
            $response = substr($server_output, $header_size);
            curl_close();

            // echo $response;
            if(wp_redirect(get_page_link(get_page_by_title('Support Tickets'))))
            {
                exit();
            }
        }

        public function delete_support_ticket($ticket_id)
        {
            $class = new SupportTickets();

            $url = $class->freshdeskUrl."api/v2/tickets/$ticket_id";
            if (isset($class->opt['use_apikey'])) {
                $api_key = ( $class->opt['freshdesk_apikey'] != '' ) ? $class->opt['freshdesk_apikey'] : '';
                $password = "";
            } else {
                $api_key = ( $class->opt['api_username'] != '' ) ? $class->opt['api_username'] : '';
                $password = ( $class->opt['api_pwd'] != '' ) ? $class->opt['api_pwd'] : '';
            }

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headers = substr($server_output, 0, $header_size);
            $response = substr($server_output, $header_size);
            curl_close();

            echo $response;
        }

        public function new_support_ticket($ticket_data, $attachment)
        {
            global $current_user;
            $class = new SupportTickets();
            // echo "Location: " . get_page_link(get_page_by_title('Support Tickets'));exit();
            // $groups = $class->get_groups();
            // $groups = json_decode(json_encode($groups), true);
            // $task_group_id = $class->get_task_group_id($groups);
            // print_r($attachment);exit();
            $upload_files = array();
            if($attachment != null)
            {
                // foreach($attachment as $file)
                // {
                    $upload_files = curl_file_create($attachment['tmp_name'], $attachment['type'], $attachment['name']);
                // }
            }

            $ticket = array(
                    'email' => $current_user->data->user_email,
                    'subject' => $ticket_data['subject'],
                    'description' => $ticket_data['description'],
                    'priority' => $ticket_data['priority'],
                    'source' => $ticket_data['source'],
                    'status' => 2
            );

            if($attachment != null)
            {
                $ticket['attachments[]'] = $upload_files;
            }
            $url = $class->freshdeskUrl."api/v2/tickets";
            if (isset($class->opt['use_apikey'])) {
                $api_key = ( $class->opt['freshdesk_apikey'] != '' ) ? $class->opt['freshdesk_apikey'] : '';
                $password = "";
            } else {
                $api_key = ( $class->opt['api_username'] != '' ) ? $class->opt['api_username'] : '';
                $password = ( $class->opt['api_pwd'] != '' ) ? $class->opt['api_pwd'] : '';
            }

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $ticket);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headers = substr($server_output, 0, $header_size);
            $response = substr($server_output, $header_size);
            curl_close();
            if(wp_redirect(get_page_link(get_page_by_title('Support Tickets'))))
            {
                exit();
            }
        }

        public function get_max_sortorder()
        {
            
            $max_sortorder = 0;
            $ticket_data = $this->fetch_tickets('Open');
            if(count($ticket_data) == 0)
            {
                return 0;
            }
            foreach($ticket_data as $ticket)
            {
                if(($sortorder = array_shift($ticket['custom_field'])) > $max_sortorder)
                {
                    $max_sortorder = $sortorder;
                }
            }
            return $max_sortorder;
        }

        /*
        * Function Name: fetch_tickets
        * Function Description: Fetched all tickets from Freshdesk for current logged in user.
        */
        
        function fetch_tickets($status = '', $comparison = '')
        {
            $result = '';
            if (is_user_logged_in()) {
                global $current_user;
                
                if (( isset($this->opt['freshdesk_apikey']) && $this->opt['freshdesk_apikey'] != '' ) || !isset($this->opt['use_apikey'])) {
                    
                    $tickets = $this->get_tickets($current_user->data->user_email, $current_user->roles);
                    $filteredTickets = false;
                    if (isset($tickets)) {
                        $tickets = json_decode(json_encode($tickets), true);
                        if (isset($status) && $status != '') {
                            $filteredTickets = $this->filter_tickets($tickets, $status, $comparison);
                            return $filteredTickets;
                        }
                        else
                        {
                            $filteredTickets = $this->filter_tickets($tickets);
                            return $filteredTickets;
                        }
                    } else {
                        $filteredTickets = false;
                    }
                    
                    if (!isset($tickets->require_login) && $tickets != '' && !isset($tickets->errors) && !empty($tickets)) {
                        if (isset($status)) {
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
                                    $dropdown = ( isset($status) && $status != '' ) ? 'No tickets for <strong>"' . strtoupper(str_replace('_', ' ', $status)) . '"</strong> category' : '';
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

            if($result != '')
            {
                return $result;
            }

            return $filteredTickets;
        }
        public function update_order($ticket_ids, $sortorders)
        {
            $class = new SupportTickets();
            if (isset($class->opt['use_apikey'])) {
                $apikey = ( $class->opt['freshdesk_apikey'] != '' ) ? $class->opt['freshdesk_apikey'] : '';
                $password = "";
            } else {
                $apikey = ( $class->opt['api_username'] != '' ) ? $class->opt['api_username'] : '';
                $password = ( $class->opt['api_pwd'] != '' ) ? $class->opt['api_pwd'] : '';
            }
            for($i = 0; $i < count($ticket_ids); $i++)
            {
                $ticket_id = $ticket_ids[$i];
                $url = $class->freshdeskUrl."api/v2/tickets/$ticket_id";
                
                $ch = curl_init($url);
                $ticket_data = array("custom_fields" => array("cf_sortorder" => $sortorders[$i] + 0));
                $header[] = "Content-type: application/json";
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_USERPWD, "$apikey:$password");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ticket_data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $server_output = curl_exec($ch);
                $info = curl_getinfo($ch);
                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $headers = substr($server_output, 0, $header_size);
                $response = substr($server_output, $header_size);

                echo $response;
                curl_close($ch);
            }
            echo 'success';
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
                $url = $this->freshdeskUrl . 'helpdesk/tickets.json?filter_name=' . $filterName;
                
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
         * Function Name: get_groups
         * Function Description: API call to Freshdesk to get all groups list
         */
        public function get_groups()
        {
            if (isset($this->opt['use_apikey'])) {
                $apikey = ( $this->opt['freshdesk_apikey'] != '' ) ? $this->opt['freshdesk_apikey'] : '';
                $password = "";
            } else {
                $apikey = ( $this->opt['api_username'] != '' ) ? $this->opt['api_username'] : '';
                $password = ( $this->opt['api_pwd'] != '' ) ? $this->opt['api_pwd'] : '';
            }
            $url = $this->freshdeskUrl . 'groups.json';

            $auth = base64_encode($apikey . ':' . $password);
                
                $args = array(
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Authorization' => "Basic $auth"
                    ),
                    'body' => array()
                );
                // echo $url;exit();
                $response = wp_remote_get($url, $args);

                // test for wp errors.
                if (is_wp_error($response)) {
                    return array();
                    exit;
                }

                $body = wp_remote_retrieve_body($response);
                $groups = json_decode($body);
                return $groups;
            
        }

        /*
         * Function Name: filter_tickets
         * Function Description: Filters the tickets according to ticket_status
         */
        
        public function filter_tickets($tickets = '', $status = '', $comparison = '')
        {
            $filtered_tickets = array();
            $groups = $this->get_groups();
            if(isset($groups))
            {
                $groups = json_decode(json_encode($groups), true);
            }
            else
            {
                return array();
            }
            
            $task_group_id = $this->get_task_group_id($groups);
            if($task_group_id == '')
            {
                return array();
            }

            if(!isset($tickets['errors']))
            {
                if ($status == 'all_tickets' || $status == 'all_tickets') {
                    foreach ($tickets as $t) {
                        if($comparison == '!=')
                        {
                            if ($t['group_id'] != $task_group_id) {
                                $filtered_tickets[] = $t;
                            }
                        }
                        else
                        {
                            if ($t['group_id'] != $task_group_id) {
                                $filtered_tickets[] = $t;
                            }
                        }
                    }
                    return $filtered_tickets;
                } else if($status == '') {
                    foreach ($tickets as $t) {
                        if($comparison == '!=')
                        {
                            if ($t['group_id'] != $task_group_id) {
                                $filtered_tickets[] = $t;
                            }
                        }
                        else
                        {
                            if ($t['group_id'] != $task_group_id) {
                                $filtered_tickets[] = $t;
                            }
                        }
                    }
                    return $filtered_tickets;
                } else {
                    return $tickets;
                }
            }
            return array();
        }

        public function get_task_group_id($groups)
        {
            if(count($groups) == 0)
            {
                return '';
            }

            foreach($groups as $group)
            {
                if($group['group']['name'] == 'Task Queue')
                {
                    return $group['group']['id'];
                }
            }

            return '';
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

    // $class = new SupportTickets;
    // $class->init();
}