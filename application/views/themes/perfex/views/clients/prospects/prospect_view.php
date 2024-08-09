<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <h4 class="no-margin"><?php echo _l('Prospect Details'); ?></h4>
                <hr class="hr-panel-heading">
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th><?php echo _l('Prospect Name'); ?></th>
                            <td><?php echo htmlspecialchars($prospect->prospect_name); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo _l('Status'); ?></th>
                            <td><?php echo htmlspecialchars($prospect->status); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo _l('Type'); ?></th>
                            <td><?php echo htmlspecialchars($prospect->type); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo _l('Category'); ?></th>
                            <td><?php echo htmlspecialchars($prospect->category); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo _l('Acquisition Channel'); ?></th>
                            <td><?php echo htmlspecialchars($prospect->acquisition_channel); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo _l('Industry'); ?></th>
                            <td><?php echo htmlspecialchars($prospect->industry); ?></td>
                        </tr>
                    </table>
                </div>
                
                <a href="<?php echo site_url('prospects'); ?>" class="btn btn-default">
                    <?php echo _l('Back to List'); ?>
                </a>
            </div>
        </div>
    </div>
</div>
