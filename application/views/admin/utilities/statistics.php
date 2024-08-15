<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12 tw-mb-6">
                <h4><?php echo _l('Statistics'); ?></h4>
            </div>

            <!-- Graphs Section -->
            <div class="col-md-12">
                <div class="row">
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                    <div class="container" style="max-width: 80%; margin: 0 auto;">
                        <h4 style="text-align: center;">Marketplace Statistics</h4>
                        <canvas id="marketplaceChart" style="margin-bottom: 30px;"></canvas>

                        <h4 style="text-align: center;">Campaign Statistics</h4>
                        <canvas id="campaignChart" style="margin-bottom: 30px;"></canvas>

                        <h4 style="text-align: center;">Industry Monitoring</h4>
                        <canvas id="industryChart" style="margin-bottom: 30px;"></canvas>

                        <h4 style="text-align: center;">Prospect Verification</h4>
                        <canvas id="prospectChart"></canvas>
                    </div>

                    <script>
                        // Ensure data is defined
                        var marketplaceData = <?php echo json_encode($marketplace_stats); ?> || [];
                        var campaignData = <?php echo json_encode($campaign_stats); ?> || [];
                        var industryData = <?php echo json_encode($industry_stats); ?> || [];
                        var prospectData = <?php echo json_encode($prospect_stats); ?> || [];

                        // Data for Marketplace Chart
                        var marketplaceLabels = [
                            'Exclusive for Sale Today', 'Exclusive for Sale Yesterday',
                            'Non-Exclusive for Sale Today', 'Non-Exclusive for Sale Yesterday',
                            'Exclusive Sold Today', 'Exclusive Sold Yesterday',
                            'Non-Exclusive Sold Today', 'Non-Exclusive Sold Yesterday',
                            'Exclusive Avg Time (days)', 'Non-Exclusive Avg Time (days)',
                            'Exclusive Avg Price', 'Non-Exclusive Avg Price',
                            'Reported Today', 'Reported Yesterday'
                        ];
                        var marketplaceValues = marketplaceData.length ? [
                            marketplaceData[0].exclusive_for_sale_today ?? 0,
                            marketplaceData[0].exclusive_for_sale_yesterday ?? 0,
                            marketplaceData[0].non_exclusive_for_sale_today ?? 0,
                            marketplaceData[0].non_exclusive_for_sale_yesterday ?? 0,
                            marketplaceData[0].exclusive_sold_today ?? 0,
                            marketplaceData[0].exclusive_sold_yesterday ?? 0,
                            marketplaceData[0].non_exclusive_sold_today ?? 0,
                            marketplaceData[0].non_exclusive_sold_yesterday ?? 0,
                            marketplaceData[0].exclusive_avg_time ?? 0,
                            marketplaceData[0].non_exclusive_avg_time ?? 0,
                            marketplaceData[0].exclusive_avg_price ?? 0,
                            marketplaceData[0].non_exclusive_avg_price ?? 0,
                            marketplaceData[0].reported_today ?? 0,
                            marketplaceData[0].reported_yesterday ?? 0
                        ] : [];

                        var ctxMarketplace = document.getElementById('marketplaceChart').getContext('2d');
                        var marketplaceChart = new Chart(ctxMarketplace, {
                            type: 'bar',
                            data: {
                                labels: marketplaceLabels,
                                datasets: [{
                                    label: 'Marketplace Statistics',
                                    data: marketplaceValues,
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });

                        // Data for Campaign Chart
                        var campaignLabels = [
                            'Open Today', 'Open Yesterday',
                            'Closed Today', 'Closed Yesterday',
                            'To Deliver Today', 'Exclusive Delivered Today',
                            'Non-Exclusive Delivered Today'
                        ];
                        var campaignValues = campaignData.length ? [
                            campaignData[0].open_today ?? 0,
                            campaignData[0].open_yesterday ?? 0,
                            campaignData[0].closed_today ?? 0,
                            campaignData[0].closed_yesterday ?? 0,
                            campaignData[0].to_deliver_today ?? 0,
                            campaignData[0].exclusive_delivered_today ?? 0,
                            campaignData[0].non_exclusive_delivered_today ?? 0
                        ] : [];

                        var ctxCampaign = document.getElementById('campaignChart').getContext('2d');
                        var campaignChart = new Chart(ctxCampaign, {
                            type: 'line',
                            data: {
                                labels: campaignLabels,
                                datasets: [{
                                    label: 'Campaign Statistics',
                                    data: campaignValues,
                                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                    borderColor: 'rgba(153, 102, 255, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });

                        // Data for Industry Chart
                        var industryLabels = ['Received', 'Exclusive Sold', 'Non-Exclusive Sold'];
                        var industryValues = industryData.length ? [
                            industryData[0].received ?? 0,
                            industryData[0].exclusive_sold ?? 0,
                            industryData[0].non_exclusive_sold ?? 0
                        ] : [];

                        var ctxIndustry = document.getElementById('industryChart').getContext('2d');
                        var industryChart = new Chart(ctxIndustry, {
                            type: 'bar',
                            data: {
                                labels: industryLabels,
                                datasets: [{
                                    label: 'Industry Monitoring',
                                    data: industryValues,
                                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                                    borderColor: 'rgba(255, 159, 64, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });

                        // Data for Prospect Chart
                        var prospectLabels = [
                            'To Be Verified by SMS', 'To Be Verified by WhatsApp',
                            'To Be Verified by Staff', 'Verified by SMS',
                            'Verified by WhatsApp', 'Verified by Staff'
                        ];
                        var prospectValues = prospectData.length ? [
                            prospectData[0].to_be_verified_by_sms ?? 0,
                            prospectData[0].to_be_verified_by_whatsapp ?? 0,
                            prospectData[0].to_be_verified_by_staff ?? 0,
                            prospectData[0].verified_by_sms ?? 0,
                            prospectData[0].verified_by_whatsapp ?? 0,
                            prospectData[0].verified_by_staff ?? 0
                        ] : [];

                        var ctxProspect = document.getElementById('prospectChart').getContext('2d');
                        var prospectChart = new Chart(ctxProspect, {
                            type: 'bar',
                            data: {
                                labels: prospectLabels,
                                datasets: [{
                                    label: 'Prospect Verification',
                                    data: prospectValues,
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
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

<?php init_tail(); ?>
