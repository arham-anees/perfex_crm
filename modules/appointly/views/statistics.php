<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();

?>
<!-- Card css -->
<style>
    .stat-card {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 10px;
        text-align: start;
    }

    .stat-card h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .stat-card .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
    }

    .stat-card .stat-change {
        color: #28a745;
    }

    .stat-card .stat-percentage {
        color: #6c757d;
    }

    .chart-card {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 10px;
    }

    .chart-card h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }

    .mt-22 {
        margin-top: 23px;
    }
</style>

<div id="wrapper">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <form method="GET" action="<?php echo admin_url('/appointly/appointments/statistics') ?>">
                            <div class="col-md-12 no-padding">
                                <div class="col-md-3 no-padding">
                                    <?php echo render_datetime_input('start_date', 'appointment_statistics_start_date', $start_date, ['readonly' => "readonly"], [], '', 'appointment-date'); ?>
                                </div>
                                <div class="col-md-3 ">
                                    <?php echo render_datetime_input('end_date', 'appointment_statistics_end_date', $end_date, ['readonly' => "readonly"], [], '', 'appointment-date'); ?>
                                </div>
                                <div class="col-md-3 ">
                                    <?php if (isset($staff)) : ?>
                                        <div class="form-group">
                                            <?php echo render_select('attendees[]', $staff, ['staffid', ['firstname', 'lastname']], 'appointment_select_attendees', $attendees, ['multiple' => true], [], '', '', false); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <?php if (isset($staff)) : ?>
                                        <div class="form-group">
                                            <?php echo render_select('booking_pages[]', $booking_pages, ['id', ['name']], 'appointment_by_booking_page', $selected_booking_pages, ['multiple' => true], [], '', '', false); ?>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                                    <button type="submit" class="btn  btn-danger">Clear Filter</button>
                                </div>
                            </div>
                            <!-- <button type="submit" class="btn btn-outline-primary">Filter</button> -->
                        </form>
                        <!-- Cards for summary data (e.g., total appointments, completed appointments, etc.) -->
                        <!-- <div class="container justify-content-start no-padding">
                            <div class="row"> -->
                        <div class="col-md-4 no-padding">
                            <div class="stat-card">
                                <h4><?= _l('appointments_created_appointments') ?></h4>
                                <div class="stat-number"> <?php echo $summary[0]['total_filtered']; ?></div>
                                <div class="stat-change"><?php
                                                            $currentTotal = $summary[0]['total_filtered'];
                                                            $priorTotal = $summary2[0]['total_prior_filtered'];
                                                            $change = $currentTotal - $priorTotal;
                                                            $percentageChange = $priorTotal > 0 ? ($change / $priorTotal) * 100 : 0;
                                                            echo ($change >= 0 ? '+' : '') . $change;
                                                            ?></div>
                                <div class="stat-percentage"><?php
                                                                echo '(' . ($percentageChange >= 0 ? '+' : '') . number_format($percentageChange, 1) . '%)';
                                                                ?></div>
                                <div class="stat-period">vs prior <?php echo $prior_days ?> days</div>
                            </div>
                        </div>
                        <div class="col-md-4 no-padding">
                            <div class="stat-card">
                                <h4><?= _l('appointments_finished_appointments') ?></h4>
                                <div class="stat-number"><?php echo $summary[0]['completed_filtered']; ?></div>
                                <div class="stat-change">
                                    <?php
                                    $currentTotal = $summary[0]['completed_filtered'];
                                    $priorTotal = $summary2[0]['completed_prior_filtered'];
                                    $change = $currentTotal - $priorTotal;
                                    $percentageChange = $priorTotal > 0 ? ($change / $priorTotal) * 100 : 0;
                                    echo ($change >= 0 ? '+' : '') . $change;
                                    ?>
                                </div>
                                <div class="stat-percentage">
                                    <?php
                                    echo '(' . ($percentageChange >= 0 ? '+' : '') . number_format($percentageChange, 1) . '%)';
                                    ?>
                                </div>
                                <div class="stat-period">vs prior <?php echo $prior_days ?> days</div>
                            </div>
                        </div>
                        <!-- <div class="col-md-3 no-padding">
                            <div class="stat-card">
                                <h4>Rescheduled events</h4>
                                <div class="stat-number">0</div>
                                <div class="stat-change">+0</div>
                                <div class="stat-percentage">(+0.0%)</div>
                                <div class="stat-period">vs prior 0 days</div>
                            </div>
                        </div> -->
                        <div class="col-md-4 no-padding">
                            <div class="stat-card">
                                <h4><?= _l('appointments_cancelled_appointments') ?></h4>
                                <div class="stat-number"> <?php echo $summary[0]['cancelled_filtered']; ?></div>
                                <div class="stat-change">
                                    <?php
                                    $currentTotal = $summary[0]['cancelled_filtered'];
                                    $priorTotal = $summary2[0]['cancelled_prior_filtered'];
                                    $change = $currentTotal - $priorTotal;
                                    $percentageChange = $priorTotal > 0 ? ($change / $priorTotal) * 100 : 0;
                                    echo ($change >= 0 ? '+' : '') . $change;
                                    ?>
                                </div>
                                <div class="stat-percentage">
                                    <?php
                                    echo '(' . ($percentageChange >= 0 ? '+' : '') . number_format($percentageChange, 1) . '%)';
                                    ?>
                                </div>
                                <div class="stat-period">vs prior <?php echo $prior_days ?> days</div>
                            </div>
                        </div>
                        <!-- </div>
                        </div> -->




                        <!-- <?php if (isset($summary)) : ?>
                            <h3>Filtered by Date Range (Current)</h3>
                            <p>Total Appointments: <?php echo $summary[0]['total_filtered']; ?></p>
                            <p>Completed Appointments: <?php echo $summary[0]['completed_filtered']; ?></p>
                            <p>Cancelled Appointments: <?php echo $summary[0]['cancelled_filtered']; ?></p>
                            <p>First Date: <?php echo $summary[0]['first_date_filtered']; ?></p>
                            <p>Last Date: <?php echo $summary[0]['last_date_filtered']; ?></p>

                            <h3>Filtered by Date Range (Prior) <?php echo $prior_days ?> days</h3>
                            <p>Total Appointments: <?php echo $summary2[0]['total_prior_filtered']; ?></p>
                            <p>Completed Appointments: <?php echo $summary2[0]['completed_prior_filtered']; ?></p>
                            <p>Cancelled Appointments: <?php echo $summary2[0]['cancelled_prior_filtered']; ?></p>
                            <p>First Date: <?php echo $summary2[0]['first_date_prior_filtered']; ?></p>
                            <p>Last Date: <?php echo $summary2[0]['last_date_prior_filtered']; ?></p>
                        <?php else : ?>
                            <p>No data available.</p>
                        <?php endif; ?> -->
                        <div class="col-md-6">
                            <div class="chart-card">
                                <h3>Completed Appointments</h3>
                                <?php
                                // Example dates from the summary array
                                $firstDate = $summary[0]['first_date_filtered'];
                                $lastDate = $summary[0]['last_date_filtered'];

                                // Convert the dates to DateTime objects
                                $firstDateObj = new DateTime($firstDate);
                                $lastDateObj = new DateTime($lastDate);

                                // Format the dates
                                $firstDateFormatted = $firstDateObj->format('j F');
                                $lastDateFormatted = $lastDateObj->format('j F');
                                ?>
                                <div class="text-right text-muted">
                                    <?php echo $firstDateFormatted; ?> – <?php echo $lastDateFormatted; ?>
                                </div>

                                <div class="chart-container">
                                    <canvas id="myChart"></canvas>
                                </div>
                                <!-- <div class="text-right text-muted">1 Jun – 30 Jun</div> -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chart-card">
                                <h3>Assigned Appointments</h3>
                                <div class="text-right text-muted">
                                    <?php echo $firstDateFormatted; ?> – <?php echo $lastDateFormatted; ?>
                                </div>

                                <div class="chart-container">
                                    <canvas id="staff_completed_chart"></canvas>
                                </div>
                                <!-- <div class="text-right text-muted">1 Jun – 30 Jun</div> -->
                            </div>
                        </div>
                        <!-- <canvas id="myChart" width="400" height="200"></canvas> -->
                        <!-- <canvas id="staff_completed_chart" width="400" height="200"></canvas> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

    $labels_date = [];
    $data_date = [];
    foreach ($completed_by_date as $completed) {
        $labels_date[] = $completed->date;
        $data_date[] = $completed->completed_appointments;
    }

    $labels_staff = [];
    $data_staff = [];
    foreach ($completed_by_staff as $completed) {
        $labels_staff[] = $completed['name'];
        $data_staff[] = $completed['appointments'];
    }
    ?>
    <script>
        // Sample data passed from the controller
        var labels_date = <?php echo json_encode($labels_date); ?>;
        var data_date = <?php echo json_encode($data_date); ?>;

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line', // or 'line', 'pie', etc.
            data: {
                labels: labels_date,
                datasets: [{
                    label: '# of Completed Appointments',
                    data: data_date,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: false,
                    lineTension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Event Volume'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Sample data passed from the controller
        var labels_staff = <?php echo json_encode($labels_staff); ?>;
        var data_staff = <?php echo json_encode($data_staff); ?>;

        var ctx = document.getElementById('staff_completed_chart').getContext('2d');
        var staff_completed_chart = new Chart(ctx, {
            type: 'bar', // or 'line', 'pie', etc.
            data: {
                labels: labels_staff,
                datasets: [{
                    label: 'Appointments',
                    data: data_staff,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
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
<?php init_tail(); ?>


</body>

</html>