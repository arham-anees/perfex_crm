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
                            <p><strong><?php echo _l('Name'); ?>:</strong> <?php echo $campaign->name ?? 'N/A'; ?></p>
                        </div>

                        <div class="form-group">
                            <p><strong><?php echo _l('Description'); ?>:</strong> <?php echo $campaign->description ?? 'N/A'; ?></p>
                        </div>

                        <div class="form-group">
                            <p><strong><?php echo _l('Start Date'); ?>:</strong> 
                                <?php echo !empty($campaign->start_date) && strtotime($campaign->start_date) !== false
                                    ? date('d M Y', strtotime($campaign->start_date))
                                    : 'N/A'; ?>
                            </p>
                        </div>

                        <div class="form-group">
                            <p><strong><?php echo _l('End Date'); ?>:</strong> 
                                <?php echo !empty($campaign->end_date) && strtotime($campaign->end_date) !== false
                                    ? date('d M Y', strtotime($campaign->end_date))
                                    : 'N/A'; ?>
                            </p>
                        </div>

                        <div class="form-group">
                            <p><strong><?php echo _l('Updated At'); ?>:</strong> <?php echo $campaign->updated_at ?? 'N/A'; ?></p>
                        </div>

                        <div class="form-group">
                            <p><strong><?php echo _l('Industry ID'); ?>:</strong> <?php echo $campaign->industry_name ?? 'N/A'; ?></p>
                        </div>

                        <div class="form-group">
                            <p><strong><?php echo _l('Status'); ?>:</strong> 
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
                            <p><strong><?php echo _l('Budget'); ?>:</strong> 
                                <?php echo isset($campaign->budget) && is_numeric($campaign->budget) 
                                    ? number_format($campaign->budget, 2) 
                                    : 'N/A'; ?>
                            </p>
                        </div>

                        <div class="form-group">
                        <?php
                      $current_date = date('Y-m-d');
                      $start_date = !empty($campaign->start_date) && strtotime($campaign->start_date) !== false
                        ? date('Y-m-d', strtotime($campaign->start_date))
                        : 'N/A';

                      if (($campaign->status_id == 3) && ($start_date >= $current_date)): ?>
                        <a href="<?php echo admin_url('campaigns/edit/' . $campaign->id);   ?> " class="btn btn-primary ">Edit</a> |
                      <?php endif; ?>
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
