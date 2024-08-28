<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
$periods = [
    ['value' => 1, 'label' => 'Today'],
    ['value' => 2, 'label' => 'Yesterday'],
    ['value' => 3, 'label' => 'This Week'],
    ['value' => 4, 'label' => 'Last Week'],
    ['value' => 5, 'label' => 'This Month'],
    ['value' => 6, 'label' => 'Last Month'],
    ['value' => 7, 'label' => 'This year'],
    ['value' => 8, 'label' => 'Last Year'],
    ['value' => -1, 'label' => 'Custom']
];
$select_period = $filter['period'] ?? 1;
$start_date = $filter['start_date'] ?? '';
$end_date = $filter['end_date'] ?? '';
$selected_sources = $filter['selected_sources'] ?? [];
$selected_clients = $filter['selected_clients'] ?? [];
?>

<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 tw-mb-6">
                        <h4><?php echo _l('Statistics'); ?></h4>
                    </div>
                    <form method="GET" action="statistics" id="filter-form">
                        <div class="col-md-12 no-padding">
                            <div class="col-md-4 ">
                                <div class="form-group">
                                    <?php echo render_select('period', $periods, ['value', 'label'], 'leadevo_stats_period_dropdown', $select_period, [], [], '', '', false); ?>
                                </div>
                            </div>
                            <div class="col-md-4" <?= $select_period != -1 ? 'style="display: none;"' : '' ?>>
                                <?php echo render_datetime_input('start_date', 'leadevo_stats_start_date', $start_date ?? '', [], [], '', 'appointment-date'); ?>
                            </div>
                            <div class="col-md-4 " <?= $select_period != -1 ? 'style="display: none;"' : '' ?>>
                                <?php echo render_datetime_input('end_date', 'leadevo_stats_end_date', $end_date ?? '', [], [], '', 'appointment-date'); ?>
                            </div>

                            <div class="col-md-4 ">
                                <?php if (isset($clients)): ?>
                                    <div class="form-group">
                                        <?php echo render_select('selected_clients[]', $clients, ['userid', ['userid', 'company']], 'leadevo_stats_clients_dropdown', $selected_clients, ['multiple' => true], [], '', '', false); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4">
                                <?php if (isset($sources)): ?>
                                    <div class="form-group">
                                        <?php echo render_select('selected_sources[]', $sources, ['id', 'name'], 'leadevo_stats_source_dropdown', $selected_sources, ['multiple' => true], [], '', '', false); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary"><?= _l('leads_apply_filter') ?></button>
                                <button type="button" class="btn  btn-danger"
                                    id="clearButton"><?= _l('leads_clear_filter') ?></button>
                            </div>
                        </div>
                        <!-- <button type="submit" class="btn btn-outline-primary">Filter</button> -->
                    </form>
                    <!-- Graphs Section -->
                    <div class="col-md-12">
                        <div class="row">
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                            <div class="container" style="max-width: 80%; margin: 0 auto;">
                                <h4 style="text-align: center;">Marketplace Statistics</h4>
                                <canvas id="marketplaceChart" style="margin-bottom: 30px;"></canvas>

                                <h4 style="text-align: center;">Campaign Statistics</h4>
                                <canvas id="campaignChart" style="margin-bottom: 30px;"></canvas>

                                <h4 style="text-align: center;">Prospect Verification</h4>
                                <canvas id="prospectChart"></canvas>
                            </div>


                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="panel_s">
                            <div class="panel-body">
                                <div class="_buttons">
                                    <h4 class="no-margin">Prospects Received</h4>
                                </div>
                                <div class="clearfix"></div>
                                <hr class="hr-panel-heading" />
                                <table class="table table-leads-report" id="stats-received-table" data-order-col="0"
                                    data-order-type="asc">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('Name'); ?></th>
                                            <th><?php echo _l('total_leads'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($industry_stats_received) && is_array($industry_stats_received)) { ?>
                                            <?php foreach ($industry_stats_received as $source): ?>
                                                <tr>
                                                    <td><?php echo $source->name; ?></td>
                                                    <td><?php echo $source->total_prospects; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel_s">
                            <div class="panel-body">
                                <div class="_buttons">
                                    <h4 class="no-margin">Exclusive Prospects</h4>
                                </div>
                                <div class="clearfix"></div>
                                <hr class="hr-panel-heading" />
                                <table class="table table-leads-report" id="stats-exclusive-table" data-order-col="0"
                                    data-order-type="asc">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('Name'); ?></th>
                                            <th><?php echo _l('total_leads'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($industry_stats_exclusive) && is_array($industry_stats_exclusive)) { ?>
                                            <?php foreach ($industry_stats_exclusive as $source): ?>
                                                <tr>
                                                    <td><?php echo $source->name; ?></td>
                                                    <td><?php echo $source->total_prospects; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel_s">
                            <div class="panel-body">
                                <div class="_buttons">
                                    <h4 class="no-margin">Non-Exclusive Prospects</h4>
                                </div>
                                <div class="clearfix"></div>
                                <hr class="hr-panel-heading" />
                                <table class="table table-leads-report" id="stats-non-exclusive-table"
                                    data-order-col="0" data-order-type="asc">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('Name'); ?></th>
                                            <th><?php echo _l('total_leads'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($industry_stats_non_exclusive) && is_array($industry_stats_non_exclusive)) { ?>
                                            <?php foreach ($industry_stats_non_exclusive as $source): ?>
                                                <tr>
                                                    <td><?php echo $source->name; ?></td>
                                                    <td><?php echo $source->total_prospects; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>


<script>
    function refreshData() {
        const formElement = document.forms['filter-form'];
        const queryString = window.location.search;
        if (queryString == '') getFormValuesAsQueryString(formElement);
        $.ajax({
            url: 'prospect_statistics' + queryString,  // Replace with your actual URL
            method: 'GET',             // Specify the HTTP method
            dataType: 'json',          // Expected data type from the server
            success: function (response) {

                // Ensure data is defined
                var marketplaceData = <?php echo json_encode($marketplace_stats); ?> || [];
                var campaignData = <?php echo json_encode($campaign_stats); ?> || [];
                var industryData = <?php echo json_encode($industry_stats); ?> || [];
                var prospectData = <?php echo json_encode($prospect_stats); ?> || [];
                regenerateTable('stats-received-table', response.data.industry_stats_received);
                regenerateTable('stats-exclusive-table', response.data.industry_stats_exclusive);
                regenerateTable('stats-non-exclusive-table', response.data.industry_stats_non_exclusive);
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
                    type: 'bar',
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
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.error('Error:', textStatus, errorThrown);
            },
            complete: function (jqXHR, textStatus) {
            }
        });
    }

    var form = document.getElementById('filter-form');
    var intervalId;
    function getFormValuesAsQueryString(form) {
        // Function to read query string from URL
        const params = {};
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);

        urlParams.forEach((value, key) => {
            params[key] = value;
        });

        return params;

    }

    function setValueById(id, text) {
        document.getElementById(id).textContent = text;
    }
    function setAutoSubmit() {
        // Clear the existing interval if already set
        if (intervalId) {
            clearInterval(intervalId);
        }
        // Set the interval to submit the form every 60 seconds (60000 milliseconds)
        intervalId = setInterval(refreshData, 60000);
    }
    setAutoSubmit();
    refreshData();
    $('select#period').on('change', function () {
        if ($('select#period').val() == -1) {

            $('[app-field-wrapper=start_date]').parent().show();
            $('[app-field-wrapper=end_date]').parent().show();
        }
        else {
            $('[app-field-wrapper=start_date]').parent().hide();
            $('[app-field-wrapper=end_date]').parent().hide();
        }
    })
    setTimeout(() => {
        _reinitDataTable('stats-received-table');
        _reinitDataTable('stats-exclusive-table')
        _reinitDataTable('stats-non-exclusive-table');
    }, 200);

    function regenerateTable(id, data) {
        const tableBody = document.querySelector('#' + id + ' tbody');
        tableBody.innerHTML = ''; // Clear existing rows

        data.forEach(source => {
            const row = document.createElement('tr');

            row.innerHTML = `
               <td>${source.name || '-'}</td>
               <td>${source.total_prospects || '-'}</td>
         `;

            tableBody.appendChild(row);
        });
        _reinitDataTable(id);
    }
    function _reinitDataTable(id) {
        if ($.fn.DataTable.isDataTable('#' + id)) {
            $('#' + id).DataTable().destroy();
        }
        initDataTableInline($('#' + id));
        $('#' + id).DataTable().order([1, 'desc']).draw()
    }
</script>