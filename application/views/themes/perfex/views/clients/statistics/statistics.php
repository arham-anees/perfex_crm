<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <h4 class="no-margin"><?php echo _l('Statistics'); ?></h4>
                <hr class="hr-panel-heading" />

                <!-- Graphs Section -->
                <div class="row">
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                    <div class="container" style="max-width: 80%; margin: 0 auto;">
                        <h4 style="text-align: center;">Client Dashboard Statistics</h4>
                        <canvas id="dashboardChart" style="margin-bottom: 30px;"></canvas>

                        <h4 style="text-align: center;">Client Campaigns Statistics</h4>
                        <canvas id="campaignChart"></canvas>
                    </div>

                    <script>
                        // Data for Dashboard Chart
                        var dashboardData = <?php echo json_encode($dashboard_stats); ?>;
                        
                        // Prepare labels and data
                        var dashboardLabels = ['Prospect Amount', 'Reported Today', 'Delivered Today', 'Delivered Yesterday', 'Prospect Avg Price'];
                        var dashboardValues = [
                            dashboardData[0].prospect_amount,
                            dashboardData[0].reported_today,
                            dashboardData[0].delivered_today,
                            dashboardData[0].delivered_yesterday,
                            dashboardData[0].prospect_avg_price
                        ];

                        var ctxDashboard = document.getElementById('dashboardChart').getContext('2d');
                        var dashboardChart = new Chart(ctxDashboard, {
                            type: 'bar',
                            data: {
                                labels: dashboardLabels,
                                datasets: [{
                                    label: 'Client Dashboard Statistics',
                                    data: dashboardValues,
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });

                        // Data for Campaign Chart
                        var campaignData = <?php echo json_encode($campaign_stats); ?>;
                        
                        var campaignLabels = ['Open Today', 'Open Yesterday', 'Closed Today', 'Closed Yesterday', 'To Deliver Today', 'Exclusive Delivered Today', 'Non-exclusive Delivered Today'];
                        var campaignValues = [
                            campaignData[0].open_today,
                            campaignData[0].open_yesterday,
                            campaignData[0].closed_today,
                            campaignData[0].closed_yesterday,
                            campaignData[0].to_deliver_today,
                            campaignData[0].exclusive_delivered_today,
                            campaignData[0].non_exclusive_delivered_today
                        ];

                        var ctxCampaign = document.getElementById('campaignChart').getContext('2d');
                        var campaignChart = new Chart(ctxCampaign, {
                            type: 'line',
                            data: {
                                labels: campaignLabels,
                                datasets: [{
                                    label: 'Client Campaigns Statistics',
                                    data: campaignValues,
                                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                    borderColor: 'rgba(153, 102, 255, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    </script>
                </div>


            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">  
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

<?php init_tail(); ?>
<script>
    $('#proposals').DataTable({
        "search": true
    });
</script>
