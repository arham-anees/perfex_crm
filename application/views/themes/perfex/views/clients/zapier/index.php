<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<div class="row main_row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <div class="_buttons">
                    <a data-toggle="modal" href="<?= site_url('zapier/create') ?>"
                        class="btn btn-primary pull-left display-block mleft10">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('leadevo_zapier_new_webhook'); ?>
                    </a>
                    <div class="clearfix"></div>
                </div>
                <?php if (!empty($webhooks)): ?>

                    <div class="table-responsive">
                        <table class="table table-bordered dt-table nowrap" id="purchased-prospects">
                            <thead>
                                <tr>
                                    <th><?php echo _l('id'); ?></th>
                                    <th><?php echo _l('name'); ?></th>
                                    <th><?php echo _l('leadevo_description'); ?></th>
                                    <th><?php echo _l('leadevo_zapier_webhook'); ?></th>
                                    <th><?php echo _l('Status'); ?></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($webhooks as $webhook): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($webhook['id']); ?></td>
                                        <td><?php echo htmlspecialchars($webhook['name'] ?? '-'); ?>
                                            <div class="row-options">
                                            <a href="<?php echo site_url('zapier/details/' . $webhook['id']); ?>" class="">
                                                    view
                                                </a> |
                                                <a href="<?php echo site_url('zapier/edit/' . $webhook['id']); ?>" class="">
                                                    Edit
                                                </a> |
                                                <a href="<?php echo site_url('zapier/delete/' . $webhook['id']); ?>"
                                                    class="text-danger"
                                                    onclick="return confirm('Are you sure you want to delete this prospect?');">
                                                    Delete
                                                </a>
                                            </div>
                                        </td>

                                        <td><?php echo htmlspecialchars($webhook['description'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($webhook['webhook'] ?? '-'); ?></td>
                                        <td><?php echo $webhook['status'] == 1 ? 'Active' : 'Inactive'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p><?php echo _l('leadevo_zapier_no_row'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>