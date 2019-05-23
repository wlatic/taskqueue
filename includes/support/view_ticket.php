<?php
get_header('taskqueue');
?>
<div class="container">
<?php do_action( 'woocommerce_account_navigation' ); ?>
</div>
<?php
$ticket_data = $data['ticket_data'];
$conversation_data = $data['conversation_data'];
$priority = array('', 'Low', 'Medium', 'High', 'Urgent');
$status = array('', '', 'Open', 'Pending', 'Resolved', 'Closed');
if(!isset($ticket_data['id'])) {
?>
<script type="text/javascript">
    alert("You can't see the ticket right now");
    location.href = "<?php echo get_page_link(get_page_by_title('Support Tickets')); ?>";
</script>
<?php } else { ?>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card bg-rose">
                <div class="card-body">
                    <h3 class="card-title">
                        <a href="javascript:void(0);"><?php echo $ticket_data['subject']; ?></a>
                    </h3>
                    <p>
                        <?php echo $ticket_data['description']; ?>
                    </p>
                </div>
                <div class="card-footer">
                    <div class="author">
                        <a href="javascript:void(0);">
                            <img src="<?php echo plugins_url('wp-custom-freshdesk/includes/task_queue/author.jpg')?>" alt="Author" class="avatar img-raised" style="float: left;" />
                            <span><?php echo $ticket_data['requester']['email'] ?></span>
                        </a>
                    </div>
                    <div class="stats ml-auto">
                        <i class="material-icons">schedule</i><?php echo date_format(date_create($ticket_data['created_at']), 'Y-m-d H:i:s'); ?>
                    </div>
                </div>
            </div>
            <?php for($i = 0; $i < count($conversation_data); $i++) { ?>
                <div class="card <?php echo ($i % 2 == 0) ? 'bg-secondary' : 'bg-rose'; ?>">
                    <div class="card-body">
                        <h4 class="card-title">
                            <div id="edit-content1"><p><?php echo $conversation_data[$i]['body'] ?></p></div>
                        </h4>
                    </div>
                    <div class="card-footer ">
                        <div class="author">
                            <a href="javascript:void(0)">
                                <img src="<?php echo plugins_url('wp-custom-freshdesk/includes/task_queue/author.jpg')?>" alt="Author" class="avatar img-raised" style="float: left;" />
                                <span><?php echo $conversation_data[$i]['support_email']; ?></span>
                            </a>
                        </div>
                        <div class="stats ml-auto">
                            <i class="material-icons">schedule</i> Answered: <?php echo date_format(date_create($ticket_data['created_at']), 'Y-m-d H:i:s'); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <form action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="action" value="add_support_reply">
                <input type="hidden" name="ticket_id" id="ticket_id" value="<?php echo $ticket_data['id']; ?>">
                <div class="section">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="info-title">Reply</h4>
                        </div>
                        <div class="col-md-12">
                            <textarea name="conversation_body" id="conversation_body" style="width:100%;" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="section section-blog-info">
                    <div class="row">
                        <div class="col-md-12 ml-auto mr-auto">
                            <a class="btn btn-rounded btn-sm btn-primary" href="<?php echo get_page_link(get_page_by_title('Support Tickets')); ?>" title="Back"><i class="fa fa-chevron-left"></i> Back to list</a>
                            <button class="btn btn-rounded btn-sm btn-success" style="float:right;" type="submit" title="Reply"><i class="fa fa-reply"></i> Reply</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4">
        <div class="card card-blog">
			<div class="card-header card-header-image">
				<a href="javascript:void(0)">
					<img src="<?php echo plugins_url('wp-custom-freshdesk/includes/task_queue/blog.jpg')?>" alt="priority">
					<div class="card-title">
						ID: #<?php echo $ticket_data['id']; ?>
                    </div>
				</a>
            </div>
			<div class="card-body">
				<h4 class="info-title">Priority <small><?php echo $priority[$ticket_data['priority']]; ?></small></h4>
				<!-- <h4 class="info-title">Option <small>Bug Report <i class="fa fa-bug"></i></small></h4> -->
				<h4 class="info-title">Publisher <small><?php echo $ticket_data['requester']['email']; ?></small></h4>
    			<h4 class="info-title">Updated <small><?php echo date_format(date_create($ticket_data['updated_at']), 'Y-m-d H:i:s'); ?></small></h4>
				<h4 class="info-title">Created <small><?php echo date_format(date_create($ticket_data['created_at']), 'Y-m-d H:i:s'); ?></small></h4>
				<h4 class="info-title">Status <small><?php echo $status[$ticket_data['status']]; ?></small></h4>				
			</div>
		</div>
        <div class="card">
			<div class="card-body">
				<h4 class="card-title">
					<a href="javascript:void(0)">Attachments: <span id="upload-counter"><?php echo count($ticket_data['attachments']); ?></span></a>
				</h4>
				<div id="attach-list">
                    <?php for($i = 0; $i < count($ticket_data['attachments']); $i++) { ?>
                        <p>
                            <a data-toggle="lightbox" href="<?php echo $ticket_data['attachments'][$i]['attachment_url']; ?>"><?php echo $ticket_data['attachments'][$i]['name']; ?></a>
                        </p>
                    <?php } ?>
                </div>
				</div>
			</div>
        </div>
    </div>
</div>
<?php
get_footer('taskqueue');
?>
<script>
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
        $('#conversation_body').summernote({
            height: 200,
        });
    });
</script>
<?php } ?>