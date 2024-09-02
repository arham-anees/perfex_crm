<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body text-left">
                <h4><?php echo _l('Add New Prospect Alert'); ?></h4>
                <hr class="hr-panel-heading">
                <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                <?php echo form_open(site_url('prospect_alerts/create'), ['method' => 'POST']); ?>

                <div class="row">
                    <div class="form-group">
                        <label for="name"><?php echo _l('Name'); ?></label>
                        <input type="text" name="name" class="form-control" id="name"
                            value="<?php echo set_value('name'); ?>">
                    </div>

    

                    <div class="form-group">
                        <label for="industry"><?php echo _l('Prospect Industry'); ?></label>
                        <select name="industry_id" class="selectpicker" data-width="100%"
                            data-none-selected-text="<?php echo _l('Select Prospect Industry'); ?>">
                            <option value=""><?php echo _l('Select Prospect Industry'); ?></option>
                            <?php foreach ($industries as $industry): ?>
                                <option value="<?php echo $industry->id; ?>" <?php echo set_select('industry_id', $industry->id); ?>>
                                    <?php echo $industry->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="acquisition_channel"><?php echo _l('Acquisition Channel'); ?></label>
                        <select name="acquisition_channel_id" class="selectpicker" data-width="100%"
                            data-none-selected-text="<?php echo _l('Select Acquisition Channel'); ?>">
                            <option value=""><?php echo _l('Select Acquisition Channel'); ?></option>
                            <?php foreach ($acquisition_channels as $channel): ?>
                                <option value="<?php echo $channel->id; ?>" <?php echo set_select('acquisition_channel_id', $channel->id); ?>>
                                    <?php echo $channel->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?php echo _l('Verification Methods'); ?></label><br>

                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" id="verified_whatsapp" name="verified_whatsapp" value="1" <?php echo set_checkbox('verified_whatsapp', '1'); ?>>
                            <label for="verified_whatsapp">
                                <?php echo _l('Verified via WhatsApp'); ?>
                            </label>
                        </div>

                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" id="verified_sms" name="verified_sms" value="1" <?php echo set_checkbox('verified_sms', '1'); ?>>
                            <label for="verified_sms">
                                <?php echo _l('Verified via SMS'); ?>
                            </label>
                        </div>

                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" id="verified_staff" name="verified_staff" value="1" <?php echo set_checkbox('verified_staff', '1'); ?>>
                            <label for="verified_staff">
                                <?php echo _l('Verified by Staff'); ?>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="opt_1_prospect_type" class="control-label clearfix">
                            <?php echo _l('leadevo_alerts_type'); ?>
                        </label>
                        <div class="radio radio-primary radio-inline">
                            <input type="radio" id="opt_1_prospect_type" name="is_exclusive" value="1" checked>
                            <label for="opt_1_prospect_type">
                                <?php echo _l('leadevo_prospect_alert_exclusive') ?>
                            </label>
                        </div>
                        <div class="radio radio-primary radio-inline">
                            <input type="radio" id="opt_2_prospect_type" name="is_exclusive" value="0">
                            <label for="opt_2_prospect_type">
                                <?php echo _l('leadevo_prospect_alert_non_exclusive'); ?>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email"><?php echo _l('Email'); ?></label>
                        <input type="text" name="email" class="form-control" id="email"
                            value="<?php echo set_value('email'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="phone"><?php echo _l('Phone'); ?></label>
                        <input type="text" name="phone" class="form-control" id="phone"
                            value="<?php echo set_value('phone'); ?>">
                    </div>
                </div>

                <div class="form-group text-left">
                    <button type="submit" class="btn btn-primary"><?php echo _l('Save'); ?></button>
                    <a href="<?php echo site_url('prospect_alerts'); ?>"
                        class="btn btn-info"><?php echo _l('Cancel'); ?></a>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
