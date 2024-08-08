<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body text-left">
                        <h4><?php echo _l('Edit Prospect Alert'); ?></h4>
                        <hr class="hr-panel-heading">

                        <?php echo form_open(admin_url('leadevo/client/prospectAlert/edit/' . $prospect_alert['id']), ['method' => 'POST']); ?>
                            <div class="form-group text-left">
                                <label for="name"><?php echo _l('Name'); ?></label>
                                <input type="text" name="name" class="form-control" id="name" value="<?php echo set_value('name', $prospect_alert['alert_name']); ?>">
                            </div>
                            <div class="form-group text-left">
                                <label for="prospect_category"><?php echo _l('Prospect Category'); ?></label>
                                <select name="prospect_category_id" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('Select Prospect Category'); ?>">
                                    <option value=""><?php echo _l('Select Prospect Category'); ?></option>
                                    <?php foreach ($prospect_categories as $category) : ?>
                                        <option value="<?php echo $category['id']; ?>" <?php echo set_select('prospect_category_id', $category['id'], $prospect_alert['prospect_category_id'] == $category['id']); ?>>
                                            <?php echo $category['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group text-left">
                                <label for="email"><?php echo _l('Email'); ?></label>
                                <input type="text" name="email" class="form-control" id="email" value="<?php echo set_value('email', $prospect_alert['email']); ?>">
                            </div>
                            <div class="form-group text-left">
                                <label for="phone"><?php echo _l('Phone'); ?></label>
                                <input type="text" name="phone" class="form-control" id="phone" value="<?php echo set_value('phone', $prospect_alert['phone']); ?>">
                            </div>
                            <div class="form-group text-left">
                                <button type="submit" class="btn btn-primary"><?php echo _l('Save'); ?></button>
                                <a href="<?php echo admin_url('leadevo/client/prospectAlert'); ?>" class="btn btn-info"><?php echo _l('Cancel'); ?></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
</body>
</html>
