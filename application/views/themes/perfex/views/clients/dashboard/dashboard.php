<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<div class="row">
    <div class="col-md-12 section-client-dashboard">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <!-- start of panel body -->


                        <!-- create 2 cards in same row -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="panel_s">
                                    <div class="panel-body
                                                text-left">
                                        <h5 class="no-margin tw-text-left tw-font-semibold font">
                                            <?= _l('leadevo_client_dashboard_total_campaigns') ?>
                                        </h5>
                                        <h1 class="bold"><?php echo count($campaigns); ?></h1>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel_s">
                                    <div class="panel-body
                                                text-left">
                                        <h5 class="no-margin tw-text-left tw-font-semibold font">
                                            <?= _l('leadevo_client_dashboard_total_prospects') ?>
                                        </h5>
                                        <h1 class="bold"><?php echo count($prospects); ?></h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel_s">
                                    <div class="panel-body
                                                text-left">
                                        <h5 class="no-margin tw-text-left tw-font-semibold font">
                                            <?= _l('leadevo_client_dashboard_prospects_average_cost') ?>
                                        </h5>
                                        <h1 class="bold">0</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel_s">
                                    <div class="panel-body
                                                text-left">
                                        <h5 class="no-margin tw-text-left tw-font-semibold font">
                                            <?= _l('leadevo_client_dashboard_reported_prospects') ?>
                                        </h5>

                                        <h1 class="bold"><?php echo count($reported_prospects); ?></h1>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- end of row with 2 columns for cards -->
                        <div class="row">
                            <?php
                            // Ensure $dashboard_stats is not empty
                            if (!empty($dashboard_stats)) {
                                $card_count = 0; // Counter to handle columns in rows
                                foreach ($dashboard_stats[0] as $key => $value) {
                                    // Skip keys that are not part of the statistics to display
                                    if (!in_array($key, ['prospect_amount', 'reported_today', 'delivered_today', 'delivered_yesterday', 'prospect_avg_price'])) {
                                        continue;
                                    }

                                    // Start a new row every 4 cards
                                    if ($card_count % 4 == 0) {
                                        if ($card_count > 0) {
                                            echo '</div></div>'; // Close previous row if not the first one
                                        }
                                        echo '<div class="row"><div class="col-md-12">';
                                    }
                                    ?>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="panel_s">
                                            <div class="panel-body text-left">
                                                <h5 class="no-margin"><?php echo ucfirst(str_replace('_', ' ', $key)); ?></h5>
                                                <h1 class="bold"><?php echo $value; ?></h1>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $card_count++;
                                }
                                // Close the last row if necessary
                                if ($card_count > 0) {
                                    echo '</div></div>';
                                }
                            }
                            ?>
                        </div>
                        <!-- End of panel body -->
                    </div>
                </div>
            </div>
        </div>
    </div>