<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-md-12 section-client-dashboard">
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
                                                text-left">
                                                    <h5 class="no-margin tw-text-left tw-font-semibold font"><?= _l('total_campaigns')?></h5>
                                                     <h1 class="bold"><?php echo count($campaigns); ?></h1>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="panel_s">
                                                <div class="panel-body
                                                text-left">
                                                     <h5 class="no-margin tw-text-left tw-font-semibold font"><?= _l('total_prospects')?></h5>
                                                    <h1 class="bold"><?php echo count($prospects); ?></h1>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="panel_s">
                                                <div class="panel-body
                                                text-left">
                                                     <h5 class="no-margin tw-text-left tw-font-semibold font"><?= _l('prospects_average_cost')?></h5>
                                                    <h1 class="bold">0</h1>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="panel_s">
                                                <div class="panel-body
                                                text-left">
                                                <h5 class="no-margin tw-text-left tw-font-semibold font"><?= _l('reported_prospects')?></h5>

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
