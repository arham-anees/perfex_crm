<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row main_row">
    <div class="col-md-12">
        <h4><?php echo _l('Reported Prospect Details'); ?></h4>

        <div class="panel_s">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong><?php echo _l('Prospect ID:'); ?></strong> <?php echo htmlspecialchars($reported_prospect['prospect_id']); ?></p>
                        <p><strong><?php echo _l('Reason:'); ?></strong> <?php echo htmlspecialchars($reported_prospect['reason']); ?></p>
                        <p><strong><?php echo _l('Client ID:'); ?></strong> <?php echo htmlspecialchars($reported_prospect['client_id']); ?></p>
                        <p><strong><?php echo _l('Created At:'); ?></strong> <?php echo htmlspecialchars($reported_prospect['created_at']); ?></p>
                        <p><strong><?php echo _l('Evidence:'); ?></strong> <?php echo htmlspecialchars($reported_prospect['evidence']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <a href="<?php echo site_url('prospects/reported'); ?>" class="btn btn-default"><?php echo _l('Back to Reported Prospects'); ?></a>
    </div>
</div>
