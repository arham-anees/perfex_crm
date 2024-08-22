<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .container {
    transform: scale(1);
    transform-origin: 0 0;
    height: 100%;
}

    .row{
        width:100%;
    margin-left: 16%;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <h4 class="no-margin"><?php echo _l('Create information point'); ?></h4>
                <hr class="hr-panel-heading" />
                <?php echo form_open('', array('id' => 'information-form')); ?>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="info_key"><?php echo _l('info_key'); ?></label>
                            <input type="text" class="form-control" name="info_key" id="info_key" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="info"><?php echo _l('info'); ?></label>
                            <input type="text" class="form-control" name="info" id="info" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><?php echo _l('Save'); ?></button>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div><script>
    $(document).ready(function() {
        $('#information-form').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting the traditional way

            var formData = $(this).serialize(); // Serialize the form data

            $.ajax({
                url: '<?php echo admin_url("information_point/create"); ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    console.log(response); // Log the response for debugging
                    if (response.status === 'success') {
                        alert(response.message);
                        window.location.href = '<?php echo admin_url("information_point"); ?>';
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log("AJAX Error: ", status, error); // Log errors if any
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>

<script>
    var $j = jQuery.noConflict();
    // Your jQuery code here, using $j instead of $
    $j(document).ready(function() {
        $j('#information-form').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting the traditional way

            var formData = $j(this).serialize(); // Serialize the form data

            $j.ajax({
                url: '<?php echo admin_url("information_point/create"); ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    console.log(response); // Log the response for debugging
                    if (response.status === 'success') {
                        alert(response.message);
                        window.location.href = '<?php echo admin_url("information_point"); ?>';
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log("AJAX Error: ", status, error); // Log errors if any
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>
