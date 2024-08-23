<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .row{
        width:100%;
        height: 100%;
    margin-left: 16%;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <h4 class="no-margin"><?php echo _l('Edit Information Point'); ?></h4>
                <hr class="hr-panel-heading" />
                <?php echo form_open('', array('id' => 'information-form')); ?>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="info_key"><?php echo _l('info_key'); ?></label>
                            <input type="text" class="form-control" name="info_key" value="<?php echo $informationpoint->info_key??'N/A'; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="info"><?php echo _l('info'); ?></label>
                            <input type="text" class="form-control" name="info" value="<?php echo $informationpoint->info??'N/A'; ?>" required>
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
</div>




<?php init_tail(); ?>

<script>
    $(document).ready(function() {
        $('#information-form').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting the traditional way

            var formData = $(this).serialize(); // Serialize the form data

            $.ajax({
                url: '<?php echo admin_url("information_point/edit/") . $informationpoint->id; ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        window.location.href = '<?php echo admin_url("information_point"); ?>';
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>
