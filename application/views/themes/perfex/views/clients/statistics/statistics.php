<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="statistics-page">
            <div class="panel_s">
                <div class="panel-body">
                    <h4 class="no-margin"><?php echo _l('Statistics'); ?></h4>
                    <hr class="hr-panel-heading" />

                    <div class="">
                        <!-- <div class="row">
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
                        </div> -->
                        <div class="row">

                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="panel_s">
                                    <div class="panel-body text-left">
                                        <h5 class="no-margin">Open Campaigns</h5>
                                        <h1 class="bold"><?php echo $campaign_stats[0]->open_today; ?></h1>
                                        <span class="vs-label">vs Yesterday</span>
                                        <span
                                            class="vs-value <?php echo $campaign_stats[0]->open_yesterday <= $campaign_stats[0]->open_today ? 'text-success' : 'text-danger'; ?>"><?php echo $campaign_stats[0]->open_yesterday; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="panel_s">
                                    <div class="panel-body text-left">
                                        <h5 class="no-margin">Closed Campaigns</h5>
                                        <h1 class="bold"><?php echo $campaign_stats[0]->closed_today; ?></h1>
                                        <span class="vs-label">vs Yesterday</span>
                                        <span
                                            class="vs-value <?php echo $campaign_stats[0]->closed_yesterday <= $campaign_stats[0]->closed_today ? 'text-success' : 'text-danger'; ?>"><?php echo $campaign_stats[0]->closed_yesterday; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="panel_s">
                                    <div class="panel-body text-left">
                                        <h5 class="no-margin">Exclusive Delivered</h5>
                                        <h1 class="bold"><?php echo $campaign_stats[0]->exclusive_delivered_today; ?>
                                        </h1>
                                        <span class="vs-label">vs Yesterday</span>
                                        <span
                                            class="vs-value <?php echo $campaign_stats[0]->exclusive_delivered_yesterday <= $campaign_stats[0]->exclusive_delivered_today ? 'text-success' : 'text-danger'; ?>"><?php echo $campaign_stats[0]->exclusive_delivered_yesterday; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="panel_s">
                                    <div class="panel-body text-left">
                                        <h5 class="no-margin">Non-Exclusive Delivered</h5>
                                        <h1 class="bold">
                                            <?php echo $campaign_stats[0]->non_exclusive_delivered_today; ?>
                                        </h1>
                                        <span class="vs-label">vs Yesterday</span>
                                        <span
                                            class="vs-value <?php echo $campaign_stats[0]->non_exclusive_delivered_yesterday <= $campaign_stats[0]->non_exclusive_delivered_today ? 'text-success' : 'text-danger'; ?>"><?php echo $campaign_stats[0]->non_exclusive_delivered_yesterday; ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="panel_s">
                                    <div class="panel-body text-left">
                                        <h5 class="no-margin">Exclusive Avg Price</h5>
                                        <h1 class="bold">
                                            <?php echo $campaign_stats[0]->avg_price_exclusive; ?>
                                        </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="panel_s">
                                    <div class="panel-body text-left">
                                        <h5 class="no-margin">Non Exclusive Avg Price</h5>
                                        <h1 class="bold">
                                            <?php echo $campaign_stats[0]->avg_price_non_exclusive; ?>
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    h5,
    h1 {
        line-height: 14px;
    }

    .vs-label,
    .vs-value {
        font-size: 12px
    }
</style>