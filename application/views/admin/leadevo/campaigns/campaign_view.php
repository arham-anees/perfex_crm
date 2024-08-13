<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('Campaign Details'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <div class="form-group">
                            <label for="name"><?php echo _l('Name'); ?></label>
                            <p><?php echo $campaign->name; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="description"><?php echo _l('Description'); ?></label>
                            <p><?php echo $campaign->description; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="start_date"><?php echo _l('Start Date'); ?></label>
                            <p><?php echo date('d M Y', strtotime($campaign->start_date)); ?></p>
                        </div>
                        <div class="form-group">
                            <label for="end_date"><?php echo _l('End Date'); ?></label>
                            <p><?php echo date('d M Y', strtotime($campaign->end_date)); ?></p>
                        </div>
                        <div class="form-group">
                            <label for="status_id"><?php echo _l('Status'); ?></label>
                            <p>
                                <?php
                                $status = $this->Campaigns_model->get_campaign_statuses();
                                $status_name = '';
                                foreach ($status as $stat) {
                                    if ($stat['id'] == $campaign->status_id) {
                                        $status_name = $stat['name'];
                                        break;
                                    }
                                }
                                echo $status_name;
                                ?>
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="budget"><?php echo _l('Budget'); ?></label>
                            <p><?php echo number_format($campaign->budget, 2); ?></p>
                        </div>

                        <div class="form-group">
                            <a href="<?php echo admin_url('campaigns/edit/' . $campaign->id); ?>"
                                class="btn btn-primary">
                                <?php echo _l('Edit'); ?>
                            </a>
                            <a href="<?php echo admin_url('campaigns'); ?>" class="btn btn-default">
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