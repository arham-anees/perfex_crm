<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <div></div>
            <h4 class="modal-title w-100">Reject Prospect report</h4>
            <h6 class="modal-title w-100">Prospect reported cannot be replaced</h6>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            <?php echo form_open(admin_url('prospects/reject_prospect_report'), ['id' => 'reject-prospect-report-form']); ?>
            <input type="hidden" name="id" />
            <input type="hidden" name="campaign_id" />

            <p><?= _l('leadevo_report_reject_prospect_message') ?></p>
                
                <!-- Description Input -->
                <div class="form-group">
                    <label for="reject_description"><?= _l('leadevo_fake_description_label') ?></label>
                    <textarea name="reject_description" id="reject_description" class="form-control" rows="4" required placeholder="<?= _l('leadevo_reject_prospect_description_placeholder') ?>"></textarea>
                </div>

                <!-- Submit Button -->
                <input type="submit" value="<?php echo _l('leadevo_report_reject_prospect_button'); ?>"
                    class="btn btn-primary" />

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

