<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                        <?php echo form_open(); ?>
                        <div class="form-group">
                            <label for="name"><?php echo _l('Name'); ?></label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="<?php echo set_value('name', $type->name); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description"><?php echo _l('Description'); ?></label>
                            <textarea name="description" id="description" class="form-control"
                                required><?php echo set_value('description', $type->description); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="description" class="control-label clearfix"><?php echo _l('Status'); ?></label>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" id="is_active" name="is_active" value="1" <?= $type->is_active=='1'?'checked':''?>>
                                <label for="is_active" >Active</label>
                            </div>

                            <div class="radio radio-primary radio-inline">

                                <input type="radio" id="is_actives" name="is_active" value="" <?= $type->is_active=='0'?'checked':''?>>
                                <label for="is_actives">In Active</label>
                            </div>
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