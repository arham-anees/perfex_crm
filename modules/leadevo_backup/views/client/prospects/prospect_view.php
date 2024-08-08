<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>


<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body
                    text-left">
                        <h4><?php echo _l('Prospect Details'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <div class="form-group
                        text-left">
                            <label for="first_name"><?php echo _l('prospect_name'); ?></label>
                            <p><?php echo $prospect['prospect_name']; ?></p>
                        </div>
                        <div class="form-group
                        text-left">
                            <label for="status"><?php echo _l('Status'); ?></label>
                            <p><?php echo $prospect['status']; ?></p>
                        </div>
                        <div class="form-group
                        text-left">
                            <label for="type"><?php echo _l('Type'); ?></label>
                            <p><?php echo $prospect['type']; ?></p>
                        </div>
                        <div class="form-group
                        text-left">
                            <label for="category"><?php echo _l('Category'); ?></label>
                            <p><?php echo $prospect['category']; ?></p>
                        </div>
                        <div class="form-group
                        text-left">
                            <label for="acquisition_channel"><?php echo _l('Acquisition Channel'); ?></label>
                            <p><?php echo $prospect['acquisition_channel']; ?></p>
                        </div>
                        <div class="form-group
                        text-left">
                            <label for="industry"><?php echo _l('Industry'); ?></label>
                            <p><?php echo $prospect['industry']; ?></p>
                        </div>
                        <div class="form-group
                        text-left">
                            <a href="<?php echo admin_url('leadevo/client/prospect/edit/' . $prospect['id']); ?>" class="btn btn-primary">
                                <?php echo _l('Edit'); ?>
                            </a>
                            <a href="<?php echo admin_url('leadevo/client/prospect'); ?>" class="btn btn-default">
                                <?php echo _l('Back'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php init_tail(); ?>
</body>

</html>
