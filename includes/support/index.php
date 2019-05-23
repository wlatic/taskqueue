<?php
    get_header('taskqueue');
?>
<div class="container">
    <?php do_action( 'woocommerce_account_navigation' ); ?>
</div>
<?php
    $tickets_data = apply_filters('get_support_tickets', '');
    $tickets = $tickets_data['ticket'];
    $temp_groups = $tickets_data['group'];

    if(count($temp_groups) == 0)
    {
        $groups = null;
    }
    else
    {
        $groups = array();
        $temp_groups = json_decode(json_encode($temp_groups), true);
        foreach($temp_groups as $item)
        {
            $groups[$item['group']['id']] = $item['group']['name'];
        }
    }
    // print_r($tickets);exit();
    if(!is_array($tickets))
    {
        echo $tickets;
    }
    else
    { ?>
        <style>
            #overlay {
                position: fixed; /* Sit on top of the page content */
                display: none; /* Hidden by default */
                width: 100%; /* Full width (cover the whole page) */
                height: 100%; /* Full height (cover the whole page) */
                top: 0; 
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0,0,0,0.5); /* Black background with opacity */
                z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
                cursor: pointer; /* Add a pointer on hover */
            }
        </style>
        <div id="overlay"></div>
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div data-editable data-name="title-10">
                            <h3 class="title-green">Support Tickets</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <form id="jak_statform">
                            <select name="ticket_filter" id="ticket_filter" class="form-control">
                                <option value="All">All</option>
                                <option value="Open">Open</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <button type="button" id="add_support_ticket" class="spark-btn wow None round animated btn btn-light" style="float: right;padding: 10px;">Add new Ticket</button>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                        <table id="tickets" class="table table-striped" cellspacing="0" width="100%">
                            <thead class="table-custom-dark">
                                <th>Subject</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Last Reply</th>
                                <th>Priority</th>
                            </thead>
                            <tbody>
                                <?php if(!empty($tickets)) { ?>
                                    <?php foreach($tickets as $key=>$open) { ?>
                                        <tr data-ticket_id="<?php echo $open['display_id']; ?>">
                                            <td><a style="text-decoration: none;" href="<?php echo admin_url('admin-ajax.php?action=view_support_ticket&ticket_id='.$open['display_id']); ?>"><?php echo $open['subject']; ?></a></td>
                                            <td><?php echo ($open['group_id']) ? $groups[$open['group_id']] : ''; ?></td>
                                            <td><?php echo $open['status_name']; ?></td>
                                            <td><?php echo ($open['updated_at'] != '') ? date_format(date_create($open['updated_at']), 'Y-m-d H:i:s') : '-'; ?></td>
                                            <td><?php echo $open['priority_name']; ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php } ?>
<?php get_footer('taskqueue'); ?>
<script>
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    var $ = jQuery.noConflict();
    jQuery(document).ready(function() {
        var flag = 0;
        $(".woocommerce-MyAccount-navigation-link").removeClass('is-active');
        $(".woocommerce-MyAccount-navigation-link").each(function() {
            if($(this).find('a').html().indexOf('Support') != -1 && flag != 1)
            {
                $(this).addClass('is-active');
		        flag = 1;
                return;
            }
        });
        var table = jQuery("#tickets").DataTable();

        $(".support-ticket-delete").on('click', function() {
            if(!confirm("Are you sure want to delete this ticket?")) {
                return;
            }
            var ticketId = $(this).parents("tr").data('ticket_id');
            var row = $(this).parents("tr");
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'delete_support_ticket',
                    ticket_id: ticketId
                },
                success: function() {
                    alert("Ticket deleted successfully");
                    table.row(row).remove().draw(); 
                }
            })
        });

        $(".support-ticket-close").on('click', function() {
            if(!confirm("Are you sure want to close the ticket?")){
                return;
            }
            var ticketId = $(this).parents("tr").data('ticket_id');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'close_support_ticket',
                    ticket_id: ticketId
                },
                success: function() {
                    alert("Ticket closed successfully.");
                    document.location.reload('true');
                }
            })
        });

        $("#add_support_ticket").live('click', function() {
            location.href = ajaxurl + '?action=add_support_ticket';
        });

        $("#ticket_filter").on('change', function() {
            if($(this).val() == 'All') {
                table.column(2).search('').draw();
            } else {
                table.column(2).search($(this).val()).draw();
            }
        });
    });
</script>