<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body text-left">
                <h4><?php echo _l('Edit Prospect Alert'); ?></h4>
                <hr class="hr-panel-heading">
                 <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                <?php echo form_open(site_url('prospect_alerts/edit/' . $alert['id']), ['method' => 'POST']); ?>

                <div class="row">
                    <div class="form-group">
                        <label for="name"><?php echo _l('Name'); ?></label>
                        <input type="text" name="name" class="form-control" id="name"
                            value="<?php echo set_value('name', $alert['name']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="prospect_category"><?php echo _l('Prospect Category'); ?></label>
                        <select name="prospect_category_id" class="selectpicker" data-width="100%"
                            data-none-selected-text="<?php echo _l('Select Prospect Category'); ?>">
                            <option value=""><?php echo _l('Select Prospect Category'); ?></option>
                            <?php foreach ($prospect_categories as $category): ?>
                                <option value="<?php echo $category->id; ?>" <?php echo set_select('prospect_category_id', $category->id); ?>>
                                    <?php echo $category->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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
                        <label for="opt_1_prospect_type" class="control-label clearfix">
                            <?php echo _l('leadevo_alerts_type'); ?>
                        </label>
                        <div class="radio radio-primary radio-inline">
                            <input type="radio" id="opt_1_prospect_type" name="is_exclusive" value="1" <?php echo $alert['is_exclusive'] == 1 ? 'checked' : '' ?>>
                            <label for="opt_1_prospect_type">
                                <?php echo _l('leadevo_prospect_alert_exclusive') ?>
                            </label>
                        </div>
                        <div class="radio radio-primary radio-inline">
                            <input type="radio" id="opt_2_prospect_type" name="is_exclusive" value="0" <?php echo $alert['is_exclusive'] == 0 ? 'checked' : '' ?>>
                            <label for="opt_2_prospect_type">
                                <?php echo _l('leadevo_prospect_alert_non_exclusive'); ?>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email"><?php echo _l('Email'); ?></label>
                        <input type="text" name="email" class="form-control" id="email"
                            value="<?php echo set_value('email', $alert['email']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="phone"><?php echo _l('Phone'); ?></label>
                        <input type="text" name="phone" class="form-control" id="phone"
                            value="<?php echo set_value('phone', $alert['phone']); ?>">
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