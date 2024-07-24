<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <!-- start of panel body -->
                        <div class="_buttons">
                            <a href="<?php echo admin_url('leadevo/campaigns/create'); ?>" class="btn btn-primary pull-left display-block mleft10 ">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('New Campaign'); ?>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <!-- create a row with 2 columns for cards -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-body">
                                    <!-- create 2 cards in same row -->
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="panel_s">
                                                <div class="panel-body
                                                text-center">
                                                    <h4><?php echo _l('Total Campaigns'); ?></h4>
                                                    <h1 class="bold"><?php echo count($campaigns); ?></h1>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="panel_s">
                                                <div class="panel-body
                                                text-center">
                                                    <h4><?php echo _l('total_prospects'); ?></h4>
                                                    <h1 class="bold"><?php echo count($prospects); ?></h1>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="panel_s">
                                                <div class="panel-body
                                                text-center">
                                                    <h4><?php echo _l('average_prospects'); ?></h4>
                                                    <h1 class="bold">0</h1>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="panel_s">
                                                <div class="panel-body
                                                text-center">
                                                    <h4><?php echo _l('reported_prospects'); ?></h4>
                                                    <h1 class="bold">0</h1>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>    
                            </div>
                        </div>
                        <!-- end of row with 2 columns for cards -->


                        
                            

                      


                        <!-- End of panel body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
