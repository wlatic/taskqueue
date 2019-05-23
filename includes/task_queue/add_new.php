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
            <input type="hidden" name="action" id="action" value="new_task_ticket">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-4 control-label">Subject</label>
                    <div class="col-md-12">
                        <input type="text" id="subject" name="subject" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Description</label>
                    <div class="col-md-12">
                        <textarea class="form-control" id="description" name="description" rows="5"></textarea>
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
            <a class="btn btn-rounded btn-sm btn-primary" href="<?php echo get_page_link(get_page_by_title('Task Queue Tickets')); ?>" title="Back">Back to list</a>
                <button type="submit" class="btn btn-rounded btn-sm btn-success" style="float:right;margin-bottom: 20px;">Save</button>
            </div>
        </form>
    </div>
</div>
<?php
get_footer('taskqueue');
?>