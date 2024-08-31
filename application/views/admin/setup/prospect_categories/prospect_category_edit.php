<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('Edit Prospect Category'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                        <?php echo form_open(admin_url('leadevo/prospect_categories/edit/' . (isset($category) ? $category->id : ''))); ?>
                        <div class="form-group">
                            <label for="name"><?php echo _l('Name'); ?></label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="<?php echo isset($category) ? htmlspecialchars($category->name) : ''; ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="description"><?php echo _l('Description'); ?></label>
                            <textarea id="description" name="description" class="form-control"
                                required><?php echo isset($category) ? htmlspecialchars($category->description) : ''; ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary"><?php echo _l('Save Changes'); ?></button>
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