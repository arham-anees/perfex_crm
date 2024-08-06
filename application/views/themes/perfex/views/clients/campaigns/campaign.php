<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row main_row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <div class="_buttons">
                    <a data-toggle="modal" data-target="#createCampaignModal"
                        class="btn btn-primary pull-left display-block mleft10">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('New Campaign'); ?>
                    </a>
                    <div class="clearfix"></div>
                </div>
                <hr class="hr-panel-heading" />

                <?php if (!empty($campaigns)): ?>
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
                            <?php foreach ($campaigns as $campaign): ?>
                                <tr>
                                    <td><?php echo $campaign->name; ?></td>
                                    <td><?php echo $campaign->description; ?></td>
                                    <td><?php echo $campaign->is_active ? 'Yes' : 'No'; ?></td>
                                    <td>
                                        <a href="<?php echo admin_url('leadevo/campaigns/view/' . $campaign->id); ?>"
                                            class="btn btn-default btn-icon">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="<?php echo admin_url('leadevo/campaigns/edit/' . $campaign->id); ?>"
                                            class="btn btn-default btn-icon">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a href="<?php echo admin_url('leadevo/campaigns/delete/' . $campaign->id); ?>"
                                            class="btn btn-danger btn-icon"
                                            onclick="return confirm('Are you sure you want to delete this campaign ?');">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p><?php echo _l('No campaigns found.'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="createCampaignModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="padding: 20px;">




        </div>