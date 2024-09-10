<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('Create New Affiliate Training Video'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                        <?php endif; ?>
                        <?php echo form_open(admin_url('affiliate_training_videos/create')); ?>
                        <div class="form-group">
                            <label for="name"><?php echo _l('Name'); ?></label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="description"><?php echo _l('Description'); ?></label>
                            <textarea id="description" name="description" class="form-control" ></textarea>
                        </div>

                        <div class="form-group">
                            <label for="video_url"><?php echo _l('Video URL'); ?></label>
                            <input type="url" id="url" name="url" class="form-control" required>
                        </div>





                        <div class="form-group">
                            <label for="video_order"><?php echo _l('video_order'); ?></label>
                            <input type="number" id="video_order" name="video_order" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary"><?php echo _l('Save'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
</body>

</html>

