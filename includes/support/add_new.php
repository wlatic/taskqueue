<?php
get_header('taskqueue');
?>
<div class="container">
<?php do_action( 'woocommerce_account_navigation' ); ?>
</div>
<div class="row">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div data-editable data-name="title-10">
                <h3 class="title-green">Add new ticket</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <form action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <input type="hidden" name="action" id="action" value="new_support_ticket">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-4 control-label">Subject</label>
                    <div class="col-md-12">
                        <input type="text" id="subject" name="subject" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Description</label>
                    <div class="col-md-12">
                        <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Source</label>
                    <div class="col-md-12">
                        <select name="source" id="source" class="form-control">
                            <option value="1">Email</option>
                            <option value="2">Portal</option>
                            <option value="3">Phone</option>
                            <option value="7">Chat</option>
                            <option value="8">Mobihelp</option>
                            <option value="9">Feedback Widget</option>
                            <option value="10">Outbound Email	</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Priority</label>
                    <div class="col-md-12">
                        <select name="priority" id="priority" class="form-control">
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                            <option value="4">Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4">Attachment</label>
                    <div class="col-md-12">
                        <input type="file" id="attachment" name="attachment" class="form-control no-style">
                    </div>
                </div>
            </div>
            <div class="form-actions col-md-12">
            <a class="btn btn-rounded btn-sm btn-primary" href="<?php echo get_page_link(get_page_by_title('Support Tickets')); ?>" title="Back">Back to list</a>
                <button type="submit" class="btn btn-rounded btn-sm btn-success" style="float:right;margin-bottom: 20px;">Save</button>
            </div>
        </form>
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