<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4><?php echo _l('View Prospect Category'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php if (isset($category)): ?>
                            <div class="form-group">
                                <label for="name"><?php echo _l('Category Name'); ?></label>
                                <p><?php echo htmlspecialchars($category->name); ?></p>
                            </div>
                            <div class="form-group">
                                <label for="description"><?php echo _l('Description'); ?></label>
                                <p><?php echo htmlspecialchars($category->description); ?></p>
                            </div>
                            <div class="form-group">
                                <label for="is_active"><?php echo _l('Active'); ?></label>
                                <p><?php echo $category->is_active ? _l('Yes') : _l('No'); ?></p>
                            </div>
                        <?php else: ?>
                            <p><?php echo _l('No category found.'); ?></p>
                        <?php endif; ?>
                        <a href="<?php echo admin_url('leadevo/prospectcategories/edit/' . $category->id); ?>" class="btn btn-primary">
                                <?php echo _l('Edit'); ?>
                            </a>
                        <a href="<?php echo admin_url('leadevo/prospectcategories'); ?>" class="btn btn-default""><?php echo _l('Back to List'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
