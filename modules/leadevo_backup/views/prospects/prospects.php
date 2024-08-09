<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('leadevo/prospects/create'); ?>" class="btn btn-primary mb-3">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('New Prospect'); ?>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                        <hr class="hr-panel-heading" />
                        <?php if (!empty($prospects)) : ?>
                            <div class="table-responsive">
                                <table class="table table-bordered dt-table nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('Name'); ?></th>
                                            <th><?php echo _l('Phone'); ?></th>
                                            <th><?php echo _l('Email'); ?></th>
                                            <th><?php echo _l('Status Id'); ?></th>
                                            <th><?php echo _l('Type Id'); ?></th>
                                            <th><?php echo _l('Category Id'); ?></th>
                                            <th><?php echo _l('Acquisition Channels Id'); ?></th>
                                            <th><?php echo _l('Industry Id'); ?></th>
                                            <th><?php echo _l('Created At'); ?></th>
                                            <th><?php echo _l('Updated At'); ?></th>
                                            <th><?php echo _l('Actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($prospects as $prospect) : ?>
                                            <tr>
                                                <td><?php echo $prospect->first_name . ' ' . $prospect->last_name; ?></td>
                                                <td><?php echo $prospect->phone; ?></td>
                                                <td><?php echo $prospect->email; ?></td>
                                                <td><?php echo $prospect->status_id; ?></td>
                                                <td><?php echo $prospect->type_id; ?></td>
                                                <td><?php echo $prospect->category_id; ?></td>
                                                <td><?php echo $prospect->acquisition_channel_id; ?></td>
                                                <td><?php echo $prospect->industry_id; ?></td>
                                                <td><?php echo $prospect->created_at; ?></td>
                                                <td><?php echo $prospect->updated_at; ?></td>
                                                <td class="text-center">
                                                    <a href="<?php echo admin_url('leadevo/prospects/view/' . $prospect->id); ?>" class="btn btn-default btn-icon">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo admin_url('leadevo/prospects/edit/' . $prospect->id); ?>" class="btn btn-default btn-icon">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?php echo admin_url('leadevo/prospects/delete/' . $prospect->id); ?>" class="btn btn-danger btn-icon" onclick="return confirm('Are you sure you want to delete this prospect?');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <p><?php echo _l('No prospects found.'); ?></p>
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
