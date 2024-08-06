<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body
                    text-left">
                <h4><?php echo _l('Create Prospect'); ?></h4>
                <hr class="hr-panel-heading" />
                <div class="form-group
                        text-left">
                    <label for="prospect_name"><?php echo _l('prospect_name'); ?></label>
                    <input type="text" name="prospect_name" class="form-control" id="prospect_name"
                        value="<?php echo set_value('prospect_name'); ?>">
                </div>
                <div class="form-group
                        text-left">
                    <label for="status"><?php echo _l('Status'); ?></label>
                    <select name="status" class="selectpicker" data-width="100%"
                        data-none-selected-text="<?php echo _l('Select Status'); ?>">
                        <option value=""><?php echo _l('Select Status'); ?></option>
                        <option value="active"><?php echo _l('Active'); ?></option>
                        <option value="inactive"><?php echo _l('Inactive'); ?></option>
                    </select>
                </div>
                <div class="form-group
                        text-left">
                    <label for="type"><?php echo _l('Type'); ?></label>
                    <select name="type" class="selectpicker" data-width="100%"
                        data-none-selected-text="<?php echo _l('Select Type'); ?>">
                        <option value=""><?php echo _l('Select Type'); ?></option>
                        <?php foreach ($prospect_types as $type): ?>
                            <option value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group
                        text-left">
                    <label for="category"><?php echo _l('Category'); ?></label>
                    <select name="category" class="selectpicker" data-width="100%"
                        data-none-selected-text="<?php echo _l('Select Category'); ?>">
                        <option value=""><?php echo _l('Select Category'); ?></option>
                        <?php foreach ($prospect_categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group
                        text-left">
                    <label for="industry"><?php echo _l('Industry'); ?></label>
                    <select name="industry" class="selectpicker" data-width="100%"
                        data-none-selected-text="<?php echo _l('Select Industry'); ?>">
                        <option value=""><?php echo _l('Select Industry'); ?></option>
                        <?php foreach ($industries as $industry): ?>
                            <option value="<?php echo $industry['id']; ?>"><?php echo $industry['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group
                        text-left">
                    <label for="acquisition_channel"><?php echo _l('Acquisition Channel'); ?></label>
                    <select name="acquisition_channel" class="selectpicker" data-width="100%"
                        data-none-selected-text="<?php echo _l('Select Acquisition Channel'); ?>">
                        <option value=""><?php echo _l('Select Acquisition Channel'); ?></option>
                        <?php foreach ($acquisition_channels as $channel): ?>
                            <option value="<?php echo $channel['id']; ?>"><?php echo $channel['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group
                        text-left">
                    <button type="submit" class="btn btn-primary"><?php echo _l('Save'); ?></button>
                    <a href="<?php echo admin_url('leadevo/client/prospect'); ?>"
                        class="btn btn-default"><?php echo _l('Cancel'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>