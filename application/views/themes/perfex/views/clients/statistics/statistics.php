<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="statistics-page">
            <div class="panel_s">
                <div class="panel-body">
                    <h4 class="no-margin"><?php echo _l('Statistics'); ?></h4>
                    <hr class="hr-panel-heading" />

                    <div class="container" style="max-width: 80%; margin: 0 auto;">
                        <h4 style="text-align: center;">Client Dashboard Statistics</h4>
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

                        <!-- Campaign Stats Chart -->
                        <h4 style="text-align: center; margin-top: 50px;">Client Campaigns Statistics</h4>
                        <canvas id="campaignChart" style="margin-bottom: 30px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data for Campaign Chart
    var campaignData = <?php echo json_encode($campaign_stats); ?>;
    var campaignLabels = [
        'Open Today', 
        'Open Yesterday', 
        'Closed Today', 
        'Closed Yesterday', 
        'To Deliver Today', 
        'Exclusive Delivered Today', 
        'Non-exclusive Delivered Today'
    ];

    var campaignValuesToday = [
        campaignData[0].open_today,
        campaignData[0].closed_today,
        campaignData[0].to_deliver_today,
        campaignData[0].exclusive_delivered_today,
        campaignData[0].non_exclusive_delivered_today
    ];

    var campaignValuesYesterday = [
        campaignData[0].open_yesterday,
        campaignData[0].closed_yesterday,
        campaignData[0].exclusive_delivered_yesterday,
        campaignData[0].non_exclusive_delivered_yesterday
    ];

    var ctxCampaign = document.getElementById('campaignChart').getContext('2d');
    var campaignChart = new Chart(ctxCampaign, {
        type: 'bar',
        data: {
            labels: campaignLabels,
            datasets: [
                {
                    label: 'Today',
                    data: campaignValuesToday,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Yesterday',
                    data: campaignValuesYesterday,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    $('#proposals').DataTable({
        "search": true
    });
</script>
