<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4><?php echo _l('Prospect Status Details'); ?></h4>
                        <table class="table table-striped">
                            <tr>
                                <th><?php echo _l('Status Name'); ?></th>
                                <td><?php echo $status->name; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo _l('Description'); ?></th>
                                <td><?php echo $status->description; ?></td>
                            </tr>
                        </table>
                        <a href="<?php echo admin_url('leadevo/prospect_status/edit/' . $status->id); ?>"
                            class="btn btn-primary">
                            <?php echo _l('Edit'); ?>
                        </a>
                        <a href="<?php echo admin_url('leadEvo/prospect_status'); ?>"
                            class="btn btn-default"><?php echo _l('Back to List'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>

</html>