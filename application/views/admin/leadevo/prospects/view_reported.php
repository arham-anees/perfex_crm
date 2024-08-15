<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><?php echo _l('Prospect ID:'); ?></strong>
                                    <?php echo htmlspecialchars($reported_prospect['prospect_id']); ?></p>
                                <p><strong><?php echo _l('Reason:'); ?></strong>
                                    <?php echo htmlspecialchars($reported_prospect['reason_name']); ?></p>
                                <p><strong><?php echo _l('Created At:'); ?></strong>
                                    <?php echo htmlspecialchars($reported_prospect['created_at']); ?></p>
                                <p><strong><?php echo _l('Evidence:'); ?></strong>
                                    <?php echo htmlspecialchars($reported_prospect['evidence']); ?></p>


                                <a href="<?php echo admin_url('prospects/reported'); ?>"
                                    class="btn btn-default"><?php echo _l('Back to Reported Prospects'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
</body>

</html>