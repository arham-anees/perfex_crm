<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <h4 class="no-margin"><?php echo _l('Prospect Details'); ?></h4>
                <hr class="hr-panel-heading" />
                <div class="prospect-details">
                    <p><strong><?php echo _l('First Name'); ?>:</strong> <?php echo $prospect->first_name ?? 'N/A'; ?></p>
                    <p><strong><?php echo _l('Last Name'); ?>:</strong> <?php echo $prospect->last_name ?? 'N/A'; ?></p>
                    <p><strong><?php echo _l('Phone'); ?>:</strong> <?php echo $prospect->phone ?? 'N/A'; ?></p>
                    <p><strong><?php echo _l('Email'); ?>:</strong> <?php echo $prospect->email ?? 'N/A'; ?></p>
                    <p><strong><?php echo _l('Industry'); ?>:</strong> <?php echo $industry_name; ?></p>
                    <p><strong><?php echo _l('Acquisition Channel'); ?>:</strong> <?php echo $acquisition_channel_name; ?></p>
                    <p><strong><?php echo _l('Type'); ?>:</strong> <?php echo $type_name; ?></p>
                    
                    <!-- Add other fields as needed -->
                    <a href="<?php echo site_url('prospects/edit/' . $prospect->id); ?>" class="btn btn-default"><?php echo _l('Edit'); ?></a>
                    <a href="<?php echo site_url('prospects'); ?>" class="btn btn-default"><?php echo _l('Back to List'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
