<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open(); ?>
                        <div class="form-group">
                            <label for="name"><?php echo _l('Name'); ?></label>
                            <input type="text" name="name" id="name" class="form-control" value="<?php echo set_value('name', $type->name); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description"><?php echo _l('Description'); ?></label>
                            <textarea name="description" id="description" class="form-control" required><?php echo set_value('description', $type->description); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="is_active"><?php echo _l('Active'); ?></label>
                            <select name="is_active" id="is_active" class="form-control" required>
                                <option value="1" <?php echo set_select('is_active', '1', $type->is_active); ?>><?php echo _l('Yes'); ?></option>
                                <option value="0" <?php echo set_select('is_active', '0', !$type->is_active); ?>><?php echo _l('No'); ?></option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo _l('Save'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
