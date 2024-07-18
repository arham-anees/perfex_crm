<?php defined('BASEPATH') or exit('No direct script access allowed');
$avg_sale_cycle = is_null($average_sales_cycle_length[0]['avg_sales_cycle'])?0:$average_sales_cycle_length[0]['avg_sales_cycle'];
$satisfaction_score = isset($satisfaction_score_row[0]['satisfaction_score']) ? $satisfaction_score_row[0]['satisfaction_score'] : 0;


init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <form method="GET" action="<?php echo admin_url('/leads_reporting/leadsreport') ?>" id="filter-form">
                     <div class="col-md-12 no-padding">
                        <div class="col-md-4">
                           <?php echo render_datetime_input('start_date', 'leads_start_date', $start_date, [], [], '', 'appointment-date'); ?>
                        </div>
                        <div class="col-md-4 ">
                           <?php echo render_datetime_input('end_date', 'leads_end_date', $end_date,[], [], '', 'appointment-date'); ?>
                        </div>
                        <div class="col-md-4 ">
                           <?php echo render_datetime_input('last_action_date', 'leads_last_action_date', $last_action_date, [], [], '', 'appointment-date'); ?>
                        </div>
                        <div class="col-md-4 ">
                           <?php if (isset($staff)) : ?>
                                 <div class="form-group">
                                    <?php echo render_select('attendees[]', $staff, ['staffid', ['firstname', 'lastname']], 'leads_staff_dropdown', $attendees, ['multiple' => true], [], '', '', false); ?>
                                 </div>
                           <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                           <?php if (isset($statuses)) : ?>
                                 <div class="form-group">
                                    <?php echo render_select('selected_statuses[]', $statuses, ['id', ['name']], 'leads_status_dropdown', $selected_statuses, ['multiple' => true], [], '', '', false); ?>
                                 </div>
                           <?php endif; ?>
                        </div> 
                        <div class="col-md-4">
                           <?php if (isset($sources)) : ?>
                                 <div class="form-group">
                                    <?php echo render_select('selected_sources[]', $sources, ['id', ['name']], 'leads_source_dropdown', $selected_sources, ['multiple' => true], [], '', '', false); ?>
                                 </div>
                           <?php endif; ?>
                        </div> 
                     </div>

                     <div class="row">
                        <div class="col-md-12 text-right">
                           <button type="submit" class="btn btn-primary"><?= _l('leads_apply_filter')?></button>
                           <button type="button" class="btn  btn-danger" id="clearButton"><?= _l('leads_clear_filter')?></button>
                        </div>
                     </div>
                            <!-- <button type="submit" class="btn btn-outline-primary">Filter</button> -->
                  </form>

                  <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-12">
                     <div class="mbot20 leads-overview tw-mt-2 sm:tw-mt-4 tw-mb-4 sm:tw-mb-0">
                        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg">
                            <?php echo _l('leads_summary'); ?>
                        </h4>
                        <div class="tw-flex tw-flex-wrap tw-flex-col lg:tw-flex-row tw-w-full tw-gap-3 lg:tw-gap-6">
                            <?php
                           foreach ($summary as $status) { ?>
                            <div
                                class="lg:tw-border-r lg:tw-border-solid lg:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center last:tw-border-r-0">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    <?php
                                          if (isset($status['percent'])) {
                                              echo '<span data-toggle="tooltip" data-title="' . $status['total'] . '">' . $status['percent'] . '%</span>';
                                          } else {
                                              // Is regular status
                                              echo $status['total'];
                                          }
                                       ?>
                                </span>
                                <span style="color:<?php echo e($status['color']); ?>"
                                    class="<?php echo isset($status['junk']) || isset($status['lost']) ? 'text-danger' : ''; ?>">
                                    <?php echo e($status['name']); ?>
                                </span>
                            </div>
                            <?php } ?>
                        </div>

                    </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="panel_s">
                              <div class="panel-body
                                    tw-text-left">
                                 <h5 class="no-margin tw-text-left tw-font-semibold font"><?= _l('leads_avg_sale_cycle')?></h5>
                                 <h1 class="bold"><?= round($avg_sale_cycle, 2) ?></h1>
                                 <!-- <p class="no-margin">+23(+59.2%)<br>vs prior 30 days</p> -->
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="panel_s">
                              <div class="panel-body
                                    tw-text-left">
                                 <h5 class="no-margin tw-text-left tw-font-semibold font"><?= _l('leads_avg_satisfaction_score')?></h5>
                                 <h1 class="bold"><?= round($satisfaction_score, 2) ?><span style="font-size:12px">/5</span></h1>
                                 <!-- <p class="no-margin">+23(+59.2%)<br>vs prior 30 days</p> -->
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                 
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="panel_s">
                                 <div class="panel-body">
                                 <canvas id="leads_conversion_attrition" width="400" height="200"></canvas>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="panel_s">
                                 <div class="panel-body">
                                 <canvas id="leads_created_assigned" width="400" height="200"></canvas>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="panel_s">
                                 <div class="panel-body">
                                 <canvas id="leads_value_won" width="400" height="200"></canvas>
                                 </div>
                              </div>
                           </div>

                           <div class="col-md-6">
                              <div class="panel_s">
                                 <div class="panel-body">
                                    <canvas id="timeChart" width="400" height="200"></canvas>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-12">
                     <div class="panel_s">
                     <div class="panel-body">
                        <div class="_buttons">
                           <h4 class="no-margin">Agent Effectiveness</h4>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <table class="table dt-table table-leads-report" data-order-col="0" data-order-type="asc">
                           <thead>
                              <tr>
                                 <th><?php echo _l('leads_agent_name'); ?></th>
                                 <!-- <th><?php echo _l('source_name'); ?></th> -->
                                 <th><?php echo _l('leads_avg_convertion_time'); ?></th>
                                 <th><?php echo _l('leads_conversion_rate'); ?></th>
                                 <th><?php echo _l('leads_total_count'); ?></th>
                                 <th><?php echo _l('leads_total_appointments'); ?></th>
                                 <th><?php echo _l('leads_missed_appointments'); ?></th>
                                 <th><?php echo _l('leads_quotes_sent'); ?></th>
                                 <th><?php echo _l('leads_quotes_signed'); ?></th>
                              </tr>
                           </thead>
                           <tbody>
                                 <?php if (!empty($agent_effectiveness) && is_array($agent_effectiveness)) { ?>
                                 <?php foreach ($agent_effectiveness as $source) : ?>
                                    <tr>
                                       <td><?php echo $source['agent_name']; ?></td>
                                       <!-- <td><?php echo $source['source_name']; ?></td> -->
                                       <td><?php echo is_null($source['avg_conversion_time']) ? '-' : round($source['avg_conversion_time'], 2); ?></td>
                                       <td><?php echo is_null($source['conversion_rate']) ? '-' : round($source['conversion_rate'], 2) . '%' ?></td>
                                       <td><?php echo is_null($source['total_leads']) ? '-' : $source['total_leads']; ?></td>
                                       <td><?php echo is_null($source['total_appointments']) ? '-' : $source['total_appointments']; ?></td>
                                       <td><?php echo is_null($source['appointments_missed']) ? '-' : $source['appointments_missed']; ?></td>
                                       <td><?php echo is_null($source['quotes_sent']) ? '-' : $source['quotes_sent']; ?></td>
                                       <td><?php echo is_null($source['quotes_signed']) ? '-' : $source['quotes_signed']; ?></td>
                                    </tr>
                                 <?php endforeach; ?>
                                 <?php }?>
                              </tbody>
                           </table>
                     </div>  </div>
                     </div>


                     <div class="col-md-12">

                     <div class="panel_s">
                                 <div class="panel-body">
                        <div class="_buttons">
                           <h4 class="no-margin">Lead Source Effectiveness</h4>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        
                        <table class="table dt-table table-leads-report" data-order-col="0" data-order-type="asc">
                           <thead>
                              <tr>
                                 <th><?php echo _l('leads_source_name'); ?></th>
                                 <th><?php echo _l('leads_avg_convertion_time'); ?></th>
                                 <th><?php echo _l('leads_conversion_rate'); ?></th>
                                 <th><?php echo _l('leads_total_count'); ?></th>
                                 <!-- <th><?php echo _l('total_appointments'); ?></th> -->
                                 <!-- <th><?php echo _l('appointments_missed'); ?></th> -->
                                 <th><?php echo _l('leads_quotes_sent'); ?></th>
                                 <th><?php echo _l('leads_quotes_signed'); ?></th>
                              </tr>
                           </thead>
                           <tbody>
                                 <?php if (!empty($lead_source_effectiveness) && is_array($lead_source_effectiveness)) { ?>
                                 <?php foreach ($lead_source_effectiveness as $source) : ?>
                                    <tr>
                                       <td><?php echo $source['source_name']; ?></td>
                                       <td><?php echo is_null($source['avg_conversion_time']) ? '-' : round($source['avg_conversion_time'], 2); ?></td>
                                       <td><?php echo is_null($source['conversion_rate']) ? '-' : round($source['conversion_rate'], 2) . '%' ?></td>
                                       <td><?php echo is_null($source['total_leads']) ? '-' : $source['total_leads']; ?></td>
                                       <!-- <td><?php echo is_null($source['total_appointments']) ? '-' : $source['total_appointments']; ?></td> -->
                                       <!-- <td><?php echo is_null($source['appointments_missed']) ? '-' : $source['appointments_missed']; ?></td> -->
                                       <td><?php echo is_null($source['quotes_sent']) ? '-' : $source['quotes_sent']; ?></td>
                                       <td><?php echo is_null($source['quotes_signed']) ? '-' : $source['quotes_signed']; ?></td>
                                    </tr>
                                 <?php endforeach; ?>

                                 <?php }?>
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
   </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<?php init_tail(); ?>
<script>
           document.getElementById('clearButton').addEventListener('click', function() {
            document.getElementById('filter-form').reset();
            document.getElementsByName('last_action_date')[0].value='';
            document.getElementById('filter-form').submit();
        });
</script>

<script>
      //   document.addEventListener("DOMContentLoaded", function() {
            var form = document.getElementById('filter-form');
            var intervalId;

            function submitForm() {
                form.submit();
            }

            function setAutoSubmit() {
                // Clear the existing interval if already set
                if (intervalId) {
                    clearInterval(intervalId);
                }
                // Set the interval to submit the form every 60 seconds (60000 milliseconds)
                intervalId = setInterval(submitForm, 60000);
            }

            // Initialize auto-submit when the page loads
            setAutoSubmit();
      //   });
</script>

<script>
      <?php       
      $leads_per_agent_json = json_encode($leads_per_agent);
      ?>
      // Data from the variable
         const data  = <?php echo $leads_per_agent_json; ?>;


         const agents = data.map(item => item.agent ? item.agent : 'No Staff');
         const leadCounts = data.map(item => item.lead_count);
         const createdLeadCounts = data.map(item => item.leads_created_count);

         const ctx = document.getElementById('leads_created_assigned').getContext('2d');
         const leads_per_agent_chart = new Chart(ctx, {
            type: 'bar',
            data: {
               labels: agents,
               datasets: [
                     {
                        label: 'Leads Assigned',
                        data: leadCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                     },
                     {
                        label: 'Leads Created',
                        data: createdLeadCounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                     }
               ]
            },
            options: {
                  indexAxis:'y',
               scales: {
                     y: {
                        beginAtZero: true
                     }
               },
               
               responsive: true,
               plugins: {
                     legend: {
                        position: 'top',
                     },
                     title: {
                        display: true,
                        text: 'Leads Assigned and Created by Agents'
                     }
               }
            }
         });
      </script>

<script>

            
         document.addEventListener('DOMContentLoaded', function() {
            <?php       
            $attrition_rate_json = json_encode($attrition_rate);
            ?>
                  // Data from the variable
            const attrition_rate_data  = <?php echo $attrition_rate_json; ?>;


            const agents = attrition_rate_data.map(item => item.agent_name ? item.agent_name : 'No Staff');
            const leadCounts = attrition_rate_data.map(item => item.attrition_rate);
            const createdLeadCounts = attrition_rate_data.map(item => item.conversion_rate);

            const ctx = document.getElementById('leads_conversion_attrition').getContext('2d');
            const leads_per_agent_chart = new Chart(ctx, {
               type: 'bar',
               data: {
                  labels: agents,
                  datasets: [
                        {
                           label: 'Attrition Rate',
                           data: leadCounts,
                           backgroundColor: 'rgba(255, 99, 132, 0.2)', 
                           borderColor: 'rgba(255, 99, 132, 1)',
                           borderWidth: 1
                        },
                        {
                           label: 'Conversion Rate',
                           data: createdLeadCounts,
                           backgroundColor: 'rgba(75, 192, 192, 0.2)',
                           borderColor: 'rgba(75, 192, 192, 1)',
                           borderWidth: 1
                        }
                  ]
               },
               options: {
                     indexAxis:'y',
                  scales: {
                        y: {
                           beginAtZero: true
                        }
                  },
                  
                  responsive: true,
                  plugins: {
                        legend: {
                           position: 'top',
                        },
                        title: {
                           display: true,
                           text: 'Leads Assigned and Created by Agents'
                        }
                  }
               }
            });
         });
</script>



<script>
   // start of donut chart

   
   document.addEventListener('DOMContentLoaded', function() {
      <?php       
      $average_value_json = json_encode($average_value_of_won_prospects);
      ?>
            // Data from the variable
      const averageValueData = <?php echo $average_value_json; ?>;

      const agentNames = averageValueData.map(item => item.agent_name?item.agent_name:'No Staff');
      const averageValues = averageValueData.map(item => item.average_value_won);

      const ctx = document.getElementById('leads_value_won').getContext('2d');
      const myDonutChart = new Chart(ctx, {
         type: 'bar', // Changed to 'bar'
         data: {
               labels: agentNames,
               datasets: [{
                  label: 'Average Value Won',
                  data: averageValues,
                  backgroundColor: [
                     'rgba(255, 99, 132, 0.2)',
                     'rgba(54, 162, 235, 0.2)',
                     'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                     'rgba(255, 99, 132, 1)',
                     'rgba(54, 162, 235, 1)',
                     'rgba(75, 192, 192, 1)'
                  ],
                  borderWidth: 1
               }]
         },
         options: {
               responsive: true,
               scales: {
                  y: {
                     beginAtZero: true
                  }
               },
               plugins: {
                  legend: {
                     position: 'top',
                  },
                  title: {
                     display: true,
                     text: 'Average Value of Won Prospects by Agent'
                  },
                  datalabels: {
                     formatter: (value, ctx) => {
                           let sum = 0;
                           let dataArr = ctx.chart.data.datasets[0].data;
                           dataArr.map(data => {
                              sum += data;
                           });
                           let percentage = (value * 100 / sum).toFixed(2) + "%";
                           return percentage;
                     },
                     color: '#fff',
                  }
               }
         },
         plugins: [ChartDataLabels]
      });
   });
   // end of donut chart
</script>


<script>
   <?php       
   $agent_effectiveness_json = json_encode($agent_effectiveness);
   ?>
   // start of time taken to convert lead
   const timeTakenData = <?php echo $agent_effectiveness_json; ?>;

   document.addEventListener('DOMContentLoaded', function() {
    // Prepare data for Chart.js
    const agentNames = timeTakenData.map(item => item.agent_name?item.agent_name:'No Staff');
    const timeToConvert = timeTakenData.map(item => item.avg_conversion_time);

    const ctx = document.getElementById('timeChart').getContext('2d');
    const timeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: agentNames,
            datasets: [{
                label: 'Time to Convert (days)',
                data: timeToConvert,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y', // Change to horizontal bar chart
            scales: {
                x: {
                    beginAtZero: true
                }
            },
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Time Taken to Convert Leads by Agent'
                }
            }
        }
    });
});

// end of time taken to convert lead
</script>
</body>

</html>