<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('leadevo/prospecttypes/create'); ?>" class="btn btn-primary pull-left display-block mleft10">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('New Prospect Type'); ?>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                        <hr class="hr-panel-heading" />
                        <?php if (!empty($types)) : ?>
                            <table class="table dt-table scroll-responsive">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('Name'); ?></th>
                                        <th><?php echo _l('Description'); ?></th>
                                        <th><?php echo _l('Active'); ?></th>
                                        <th><?php echo _l('Actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($types as $type) : ?>
                                        <tr>
                                            <td><?php echo $type->name; ?></td>
                                            <td><?php echo $type->description; ?></td>
                                            <td><?php echo $type->is_active ? 'Yes' : 'No'; ?></td>
                                            <td>
                                                <a href="<?php echo admin_url('leadevo/prospecttypes/view/' . $type->id); ?>" class="btn btn-default btn-icon">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="<?php echo admin_url('leadevo/prospecttypes/edit/' . $type->id); ?>" class="btn btn-default btn-icon">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo admin_url('leadevo/prospecttypes/delete/' . $type->id); ?>" class="btn btn-danger btn-icon" onclick="return confirm('Are you sure you want to delete this prospect type?');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p><?php echo _l('No prospect types found.'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
