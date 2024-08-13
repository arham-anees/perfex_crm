<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('Acquisition Channel Details'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <div class="form-group">
                            <label for="name"><?php echo _l('Name'); ?></label>
                            <p><?php echo $channel->name; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="description"><?php echo _l('Description'); ?></label>
                            <p><?php echo $channel->description; ?></p>
                        </div>
                        <div class="form-group">
                            <a href="<?php echo admin_url('leadevo/acquisition_channels/edit/' . $channel->id); ?>"
                                class="btn btn-primary">
                                <?php echo _l('Edit'); ?>
                            </a>
                            <a href="<?php echo admin_url('leadevo/acquisition_channels'); ?>" class="btn btn-default">
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