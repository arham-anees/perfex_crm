<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <h4 class="no-margin"><?php echo _l('Prospect Alert Details'); ?></h4>
                <hr class="hr-panel-heading" />
                <div class="prospect-details">
                    <p><strong><?php echo _l('ID'); ?>:</strong> <?php echo htmlspecialchars($alert['id'] ?? 'N/A'); ?></p>
                    <p><strong><?php echo _l('Name'); ?>:</strong> <?php echo htmlspecialchars($alert['name'] ?? 'N/A'); ?></p>
                    
                    <p><strong><?php echo _l('Type'); ?>:</strong> 
                        <?php echo $alert['is_exclusive'] == 1 ? 'Exclusive' : 'Non-Exclusive'; ?>
                    </p>
                    <p><strong><?php echo _l('Email'); ?>:</strong> <?php echo htmlspecialchars($alert['email'] ?? 'N/A'); ?></p>
                    <p><strong><?php echo _l('Phone'); ?>:</strong> <?php echo htmlspecialchars($alert['phone'] ?? 'N/A'); ?></p>
                    <p><strong><?php echo _l('Status'); ?>:</strong> 
                        <?php echo $alert['status'] ? _l('Active') : _l('Inactive'); ?>
                    </p>

                    <!-- Add other fields as needed -->
                    <a href="<?php echo site_url('prospect_alerts/edit/' . $alert['id']); ?>" class="btn btn-default">
                        <?php echo _l('Edit'); ?>
                    </a>
                    <a href="<?php echo site_url('prospect_alerts'); ?>" class="btn btn-default">
                        <?php echo _l('Back to List'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
