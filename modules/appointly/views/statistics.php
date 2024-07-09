<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
?><div id="wrapper">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="content">
   <div class="row">
      <div class="col-md-12">
         <div class="panel_s">
            <div class="panel-body">

           <?php if (isset($summary)): ?>
                <h3>Filtered by Date Range (Current)</h3>
                <p>Total Appointments: <?php echo $summary[0]['total_filtered']; ?></p>
                <p>Completed Appointments: <?php echo $summary[0]['completed_filtered']; ?></p>
                <p>Cancelled Appointments: <?php echo $summary[0]['cancelled_filtered']; ?></p>
                <p>First Date: <?php echo $summary[0]['first_date_filtered']; ?></p>
                <p>Last Date: <?php echo $summary[0]['last_date_filtered']; ?></p>
                
                <h3>Filtered by Date Range (Prior)</h3>
                <p>Total Appointments: <?php echo $summary2[0]['total_prior_filtered']; ?></p>
                <p>Completed Appointments: <?php echo $summary2[0]['completed_prior_filtered']; ?></p>
                <p>Cancelled Appointments: <?php echo $summary2[0]['cancelled_prior_filtered']; ?></p>
                <p>First Date: <?php echo $summary2[0]['first_date_prior_filtered']; ?></p>
                <p>Last Date: <?php echo $summary2[0]['last_date_prior_filtered']; ?></p>
            <?php else: ?>
                <p>No data available.</p>
            <?php endif; ?>

            <canvas id="myChart" width="400" height="200"></canvas>
            </div>
         </div>
      </div>
   </div>
</div>
<?php
function generate_sample_data($num_points = 6) {
    $labels = [];
    $data = [];

    // Generate labels (e.g., months)
    for ($i = 0; $i < $num_points; $i++) {
        $labels[] = date('F', strtotime("-$i months")); // Generate labels for past months
    }

    // Generate random data or predefined data
    for ($i = 0; $i < $num_points; $i++) {
        $data[] = rand(5, 20); // Generate random data between 5 and 20
    }

    return [
        'labels' => array_reverse($labels), // Reverse labels to show in chronological order
        'data' => array_reverse($data)     // Reverse data to match labels
    ];
}

// Usage example
$sample_data = generate_sample_data();
$labels = $sample_data['labels'];
$data = $sample_data['data'];

// Print sample data and labels
echo "Labels: " . json_encode($labels) . "<br>";
echo "Data: " . json_encode($data) . "<br>";
?>
<script>
    // Sample data passed from the controller
    var labels = <?php echo json_encode($labels); ?>;
    var data = <?php echo json_encode($data); ?>;

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar', // or 'line', 'pie', etc.
        data: {
            labels: labels,
            datasets: [{
                label: '# of Appointments',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
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
<?php init_tail(); ?>


</body>
</html>