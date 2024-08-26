<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4><?php echo _l('Prospect Type Details'); ?></h4>
                        <table class="table table-striped">
                            <tr>
                                <th><?php echo _l('Name'); ?></th>
                                <td><?php echo $type->name; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo _l('Description'); ?></th>
                                <td><?php echo $type->description; ?></td>
                            </tr>
                        </table>
                        <a href="<?php echo admin_url('leadevo/prospecttypes/edit/' . $type->id); ?>"
                            class="btn btn-primary">
                            <?php echo _l('Edit'); ?>
                        </a>
                        <a href="<?php echo admin_url('leadEvo/prospecttypes'); ?>"
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