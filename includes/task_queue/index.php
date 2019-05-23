<?php
    get_header('taskqueue');
?>
<div class="container">
<?php do_action( 'woocommerce_account_navigation' ); ?>
</div>
<?php
    $open_tickets = apply_filters('get_opened_tickets', '');
    $close_tickets = apply_filters('get_closed_tickets', '');
    // print_r($open_tickets);exit();
    if(!is_array($open_tickets))
    {
        echo $open_tickets;
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
                        <h3 class="title-green">Task Queue Tickets</h3>
                        </div>
                        <div data-editable data-name="text-10">
                        <h4>Opened Tickets</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6">
                        <button type="button" id="add_task_ticket" class="spark-btn wow None round animated btn btn-light" style="float: right;padding: 10px;">Add new Ticket</button>
                    </div>
                </div>
            </div>
            <div class="container" style="margin-top: 10px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                        <table id="opened-tickets" class="table table-striped" cellspacing="0" width="100%">
                            <thead class="table-custom-dark">
                                <th style="display:none;">SortOrder</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php if(!empty($open_tickets)) { ?>
                                    <?php foreach($open_tickets as $key=>$open) { ?>
                                        <tr data-ticket_id="<?php echo $open['display_id']; ?>">
                                            <td style="display:none;"><?php echo array_shift($open['custom_field']); ?></td>
                                            <td><i class="fa fa-arrows-alt-v"></i>&nbsp;&nbsp;&nbsp;<a style="text-decoration: none;" href="<?php echo admin_url('admin-ajax.php?action=view_task_ticket&ticket_id='.$open['display_id']); ?>"><?php echo $open['subject']; ?></a></td>
                                            <td><?php echo $open['status_name']; ?></td>
                                            <td>
                                                <button type="button" class="spark-btn wow None round animated btn-danger task-ticket-delete" style="padding: 10px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;&nbsp;
                                                <button type="button" class="spark-btn wow None round animated btn-success task-ticket-close" style="padding: 10px;"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;Close</button>
                                            </td>
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
<?php }
    if(!is_array($close_tickets))
    {
        echo $close_tickets;
    }
    else
    {
?>
<div class="row" style="margin-top: 60px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div data-editable data-name="text-10">
                <h4>Closed Tickets</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                <table id="closed-tickets" class="table table-striped" cellspacing="0" width="100%">
                    <thead class="table-custom-dark">
                        <th>Subject</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        <?php if(!empty($close_tickets)) { ?>
                            <?php foreach($close_tickets as $key=>$close) { ?>
                                <tr>
                                    <td><?php echo $close['subject']; ?></td>
                                    <td><?php echo $close['status_name']; ?></td>
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
<?php get_footer('taskqueue');
?>
<script>
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    var $ = jQuery.noConflict();
    jQuery(document).ready(function() {
        $(".woocommerce-MyAccount-navigation-link").removeClass('is-active');
        $(".woocommerce-MyAccount-navigation-link").each(function() {
            if($(this).find('a').html().indexOf('Task') != -1)
            {
                $(this).addClass('is-active');
            }
        });
        // jQuery( "#opened-tickets tbody" ).sortable( {
        //     update: function( event, ui ) {
        //     jQuery(this).children().each(function(index) {
        //             jQuery(this).find('td').last().html(index + 1)
        //     });
        // }
        // });
        var table = jQuery("#opened-tickets").DataTable({
            rowReorder: {
                selector: 'tr'
            },
            paging: false,
            "columns": [
                { "orderable": false },
                { "orderable": false },
                { "orderable": false },
                { "orderable": false }
            ]
        });
        table.on( 'row-reorder', function ( e, diff, edit ) {
            table.column('0').order('asc').draw();
            setTimeout(
                function() {
                    var idArray = new Array();
                    var sortArray = new Array();
                    $("#opened-tickets tbody tr").each(function() {
                        var ticketId = $(this).attr('data-ticket_id');
                        var sortOrder = $(this).find('td:first-child').html();
                        idArray.push(ticketId);
                        sortArray.push(sortOrder);
                    });

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'update_order',
                            ticket_ids: idArray,
                            sortorders: sortArray
                        },
                        success: function(res) {
                            console.log(res);
                        }
                    });
                }, 
            1000);
        });

        jQuery("#closed-tickets").dataTable();

        $('#add_task_ticket').on('click', function(){
            location.href = ajaxurl + '?action=add_task_ticket';
        });

        $(".task-ticket-delete").on('click', function() {
            if(!confirm("Are you sure want to delete this ticket?")) {
                return;
            }
            var ticketId = $(this).parents("tr").data('ticket_id');
            var row = $(this).parents("tr");
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'delete_task_ticket',
                    ticket_id: ticketId
                },
                success: function() {
                    alert("Ticket deleted successfully");
                    table.row(row).remove().draw(); 
                }
            })
        });

        $(".task-ticket-close").on('click', function() {
            if(!confirm("Are you sure want to close the ticket?")){
                return;
            }
            var ticketId = $(this).parents("tr").data('ticket_id');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'close_task_ticket',
                    ticket_id: ticketId
                },
                success: function() {
                    alert("Ticket closed successfully.");
                    document.location.reload('true');
                }
            })
        });
    });
</script>