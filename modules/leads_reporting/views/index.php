<?php defined('BASEPATH') or exit('No direct script access allowed');
$avg_sale_cycle = is_null($average_sales_cycle_length[0]['avg_sales_cycle'])?0:$average_sales_cycle_length[0]['avg_sales_cycle'];
$satisfaction_score = isset($satisfaction_score_row[0]['satisfaction_score']) ? $satisfaction_score_row[0]['satisfaction_score'] : 0;
$follow_up_rate = isset($follow_up_rate[0])?$follow_up_rate:[];
$total_follow_ups=0;
$total_leads=0;
foreach ($follow_up_rate as $result) {
   $total_follow_ups += $result['follow_ups'];
   $total_leads += 1; // Each row represents one lead
}

// Calculate the average follow-up rate
$average_follow_up_rate = ($total_leads > 0) ? $total_follow_ups / $total_leads : 0;


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
                        <div class="tw-flex tw-flex-wrap tw-flex-col lg:tw-flex-row tw-w-full tw-gap-3 lg:tw-gap-6" id="status-summary-container">
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
                        <div class="col-md-4">
                           <div class="panel_s">
                              <div class="panel-body
                                    tw-text-left">
                                 <h5 class="no-margin tw-text-left tw-font-semibold font"><?= _l('leads_avg_sale_cycle')?></h5>
                                 <h1 class="bold" id="avg_sale_cycle"><?= round($avg_sale_cycle, 2) ?></h1>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="panel_s">
                              <div class="panel-body
                                    tw-text-left">
                                 <h5 class="no-margin tw-text-left tw-font-semibold font"><?= _l('leads_avg_satisfaction_score')?></h5>
                                 <h1 class="bold" ><span id="satisfaction_score"><?= round($satisfaction_score, 2) ?></span><span style="font-size:12px">/5</span></h1>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="panel_s">
                              <div class="panel-body
                                    tw-text-left">
                                 <h5 class="no-margin tw-text-left tw-font-semibold font"><?= _l('leads_avg_follow_up')?></h5>
                                 <h1 class="bold" id="average_follow_up_rate"><?= round($average_follow_up_rate, 2) ?></h1>
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
                           <div class="col-md-12">
                              <div class="panel_s">
                                 <div class="panel-body">
                                    <canvas id="follow_up" width="900" height="200"></canvas>
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
                        <table class="table dt-table table-leads-report" id="agent-effectiveness-table" data-order-col="0" data-order-type="asc">
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
                        
                        <table class="table dt-table table-leads-report" id="lead-source-effectiveness-table" data-order-col="0" data-order-type="asc">
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
            const currentDate = new Date();
            const pastDate = new Date(currentDate);
            pastDate.setDate(currentDate.getDate() - 30);
            document.getElementsByName('end_date')[0].value=formatDate(currentDate);
            document.getElementsByName('start_date')[0].value=formatDate(pastDate);
            document.getElementsByName('last_action_date')[0].value='';
            document.getElementsByName('attendees[]')[0].value='';
            document.getElementsByName('selected_statuses[]')[0].value='';
            document.getElementsByName('selected_sources[]')[0].value='';
            document.getElementById('filter-form').submit();
        });
        function formatDate(dateTime){
         const year = dateTime.getFullYear();
         const month = String(dateTime.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed, so add 1
         const date = String(dateTime.getDate()).padStart(2, '0');
         return `${year}-${month}-${date}`;
        }
</script>



<script>
   let leadsPerAgentChart = null;

   function drawLeadsPerAgentChart(data) {
      // Extract agent names, lead counts, and created lead counts from the new data
      const agents = data.map(item => item.agent ? item.agent : 'No Staff');
      const leadCounts = data.map(item => item.lead_count ? item.lead_count : 0);
      const createdLeadCounts = data.map(item => item.leads_created_count ? item.leads_created_count : 0);

      const ctx = document.getElementById('leads_created_assigned').getContext('2d');

      // Destroy the existing chart if it exists
      if (leadsPerAgentChart) {
         leadsPerAgentChart.destroy();
      }

      // Create a new chart
      leadsPerAgentChart = new Chart(ctx, {
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
                     text: 'Leads Assigned and Created by Agents'
                  }
               }
         }
      });
   }
</script>



<script>
   let conversionAttritionChart = null;

   function drawConversionAttritionChart(attritionRateData) {
      // Extract agent names, attrition rates, and conversion rates from the new data
      const agents = attritionRateData.map(item => item.agent_name ? item.agent_name : 'No Staff');
      const attritionRates = attritionRateData.map(item => item.attrition_rate ? item.attrition_rate : 0);
      const conversionRates = attritionRateData.map(item => item.conversion_rate ? item.conversion_rate : 0);

      const ctx = document.getElementById('leads_conversion_attrition').getContext('2d');

      // Destroy the existing chart if it exists
      if (conversionAttritionChart) {
         conversionAttritionChart.destroy();
      }

      // Create a new chart
      conversionAttritionChart = new Chart(ctx, {
         type: 'bar',
         data: {
               labels: agents,
               datasets: [
                  {
                     label: 'Attrition Rate',
                     data: attritionRates,
                     backgroundColor: 'rgba(255, 99, 132, 0.2)',
                     borderColor: 'rgba(255, 99, 132, 1)',
                     borderWidth: 1
                  },
                  {
                     label: 'Conversion Rate',
                     data: conversionRates,
                     backgroundColor: 'rgba(75, 192, 192, 0.2)',
                     borderColor: 'rgba(75, 192, 192, 1)',
                     borderWidth: 1
                  }
               ]
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
                     text: 'Leads Conversion and Attrition Rates by Agent'
                  }
               }
         }
      });
   }

     
</script>



<script>
   // start of donut chart
   let myBarChart = null;
   function drawBarChart(averageValueData) {
      // Extract agent names and average values from the new data
      const agentNames = averageValueData.map(item => item.agent_name ? item.agent_name : 'No Staff');
      const averageValues = averageValueData.map(item => item.average_value_won ? item.average_value_won : 0);

      const ctx = document.getElementById('leads_value_won').getContext('2d');

      // Destroy the existing chart if it exists
      if (myBarChart) {
         myBarChart.destroy();
      }

      // Create a new chart
      myBarChart = new Chart(ctx, {
         type: 'bar', // Changed to 'bar'
         data: {
               labels: agentNames,
               datasets: [{
                  label: 'Average Value Won',
                  data: averageValues,
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
   }
   // end of donut chart
</script>


<script>

   let timeChart = null;

   function drawTimeChart(timeTakenData) {
      // Extract agent names and time to convert from the new data
      const agentNames = timeTakenData.map(item => item.agent_name ? item.agent_name : 'No Staff');
      const timeToConvert = timeTakenData.map(item => item.avg_conversion_time ? item.avg_conversion_time : 0);

      const ctx = document.getElementById('timeChart').getContext('2d');

      // Destroy the existing chart if it exists
      if (timeChart) {
         timeChart.destroy();
      }

      // Create a new chart
      timeChart = new Chart(ctx, {
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
   }
// end of time taken to convert lead
</script>

<script>
   let followUpChart = null;
   function drawFollowUpChart(followUpData) {
    // Extract agent names and follow-up times from the new data
    const agentNames = followUpData.map(item => item.agent_name ? item.agent_name : 'No Staff');
    const followUpTimes = followUpData.map(item => item.follow_ups ? item.follow_ups : 0);

    const ctx = document.getElementById('follow_up').getContext('2d');

    // Destroy the existing chart if it exists
    if (followUpChart) {
        followUpChart.destroy();
    }

    // Create a new chart
    followUpChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: agentNames,
            datasets: [{
                label: 'Follow ups',
                data: followUpTimes,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
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
   }
    // end of follow up rate
</script>

<script>
   function regenerateLeadSourceEffectivenessTable(data) {
      const tableBody = document.querySelector('#lead-source-effectiveness-table tbody');
      tableBody.innerHTML = ''; // Clear existing rows

      data.forEach(source => {
         const row = document.createElement('tr');
         
         row.innerHTML = `
               <td>${source.source_name || 'No Data'}</td>
               <td>${source.avg_conversion_time !== null ? round(source.avg_conversion_time, 2) : '-'}</td>
               <td>${source.conversion_rate !== null ? round(source.conversion_rate, 2) + '%' : '-'}</td>
               <td>${source.total_leads !== null ? source.total_leads : '-'}</td>
               <td>${source.quotes_sent !== null ? source.quotes_sent : '-'}</td>
               <td>${source.quotes_signed !== null ? source.quotes_signed : '-'}</td>
         `;

         tableBody.appendChild(row);
      });
      _reinitDataTable('lead-source-effectiveness-table');
   }
   function regenerateAgentEffectivenessTable(data) {
      const tableBody = document.querySelector('#agent-effectiveness-table tbody');
      tableBody.innerHTML = ''; // Clear existing rows

      data.forEach(source => {
         const row = document.createElement('tr');
         
         row.innerHTML = `
               <td>${source.agent_name || 'No Staff'}</td>
               <td>${source.avg_conversion_time !== null ? round(source.avg_conversion_time, 2) : '-'}</td>
               <td>${source.conversion_rate !== null ? round(source.conversion_rate, 2) + '%' : '-'}</td>
               <td>${source.total_leads !== null ? source.total_leads : '-'}</td>
               <td>${source.total_appointments !== null ? source.total_appointments : '-'}</td>
               <td>${source.appointments_missed !== null ? source.appointments_missed : '-'}</td>
               <td>${source.quotes_sent !== null ? source.quotes_sent : '-'}</td>
               <td>${source.quotes_signed !== null ? source.quotes_signed : '-'}</td>
         `;

         tableBody.appendChild(row);
      });
      _reinitDataTable('agent-effectiveness-table');
   }

   function regenerateStatusSummary(summary) {
      const container = document.getElementById('status-summary-container');
      container.innerHTML = ''; // Clear existing content

      summary.forEach(status => {
         const div = document.createElement('div');
         div.className = 'lg:tw-border-r lg:tw-border-solid lg:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center last:tw-border-r-0';

         const span1 = document.createElement('span');
         span1.className = 'tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg';
         
         if (status.percent !== undefined) {
               span1.innerHTML = `<span data-toggle="tooltip" data-title="${status.total}">${status.percent}%</span>`;
         } else {
               span1.textContent = status.total;
         }

         const span2 = document.createElement('span');
         span2.style.color = status.color;
         span2.className = (status.junk !== undefined || status.lost !== undefined) ? 'text-danger' : '';
         span2.textContent = status.name;

         div.appendChild(span1);
         div.appendChild(span2);
         container.appendChild(div);
      });
   }
   function round(value, decimals) {
      return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
   }
   function _reinitDataTable(id){
      if ($.fn.DataTable.isDataTable('#'+id)) {
        $('#'+id).DataTable().destroy();
    }
    initDataTableInline($('#'+id));
   }
</script>

<script>
   <?php $follow_up_json = json_encode($follow_up_rate); ?>
   <?php $agent_effectiveness_json = json_encode($agent_effectiveness);?>
   <?php $average_value_json = json_encode($average_value_of_won_prospects);?>
   <?php $attrition_rate_json = json_encode($attrition_rate);?>
   <?php $leads_per_agent_json = json_encode($leads_per_agent);?>
         


   const followUpData = <?php echo $follow_up_json; ?>;
   const timeTakenData = <?php echo $agent_effectiveness_json; ?>;
   const averageValueData = <?php echo $average_value_json; ?>;
   const attritionRateData  = <?php echo $attrition_rate_json; ?>;
   const data  = <?php echo $leads_per_agent_json; ?>;

   drawFollowUpChart(followUpData);
   drawTimeChart(timeTakenData);
   drawBarChart(averageValueData);
   drawConversionAttritionChart(attritionRateData);
   drawLeadsPerAgentChart(data);

   // Fetch data from the new endpoint and redraw the chart
   function fetchDataAndDrawChart() {
      const formElement = document.forms['filter-form'];
      const queryString =window.location.search;
      if(queryString=='') getFormValuesAsQueryString(formElement);
      $.ajax({
         url: '<?= admin_url('leads_reporting/leadsreport/report')?>'+queryString ,
         method: 'GET',
         dataType: 'json',
         success: function(response) {
            let followUpData = response.follow_up_rate;
            let timeTakenData = response.agent_effectiveness;
            let averageValueData = response.average_value_of_won_prospects;
            let attritionRateData  = response.attrition_rate;
            let data  = response.leads_per_agent;
            drawFollowUpChart(followUpData);
            drawTimeChart(timeTakenData);
            drawBarChart(averageValueData);
            drawConversionAttritionChart(attritionRateData);
            drawLeadsPerAgentChart(data);

            const satisfactionScore = response.satisfaction_score_row && response.satisfaction_score_row.length > 0 ?round(response.satisfaction_score_row[0]['satisfaction_score'],2):0;
            const avgSaleCycle = response.average_sales_cycle_length && response.average_sales_cycle_length.length > 0 ?round(response.average_sales_cycle_length[0]['avg_sales_cycle'],2):0;
            let followUpRate = followUpData.length ? followUpData : [];

            let totalFollowUps = 0;
            let totalLeads = 0;

            // Calculate total follow-ups and total leads
            followUpRate.forEach(result => {
               totalFollowUps += parseInt(result.follow_ups);
               totalLeads += 1; // Each row represents one lead
            });

            // Calculate the average follow-up rate
            const averageFollowUpRate = round((totalLeads > 0) ? totalFollowUps / totalLeads : 0, 2);

            setValueById('avg_sale_cycle', isNaN(avgSaleCycle)?0:avgSaleCycle);
            setValueById('satisfaction_score', isNaN(satisfactionScore)?0:satisfactionScore);
            setValueById('average_follow_up_rate', averageFollowUpRate);

            regenerateLeadSourceEffectivenessTable(response.lead_source_effectiveness);
            regenerateAgentEffectivenessTable(response.agent_effectiveness);
            regenerateStatusSummary(response.summary);
         },
         error: function(xhr, status, error) {
               console.error('Error fetching data:', error);
         }
      });
   }

   function setValueById(id, text){
      document.getElementById(id).textContent = text;
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
   function setAutoSubmit() {
         // Clear the existing interval if already set
         if (intervalId) {
            clearInterval(intervalId);
         }
         // Set the interval to submit the form every 60 seconds (60000 milliseconds)
         intervalId = setInterval(fetchDataAndDrawChart, 60000);
   }


   setAutoSubmit();
</script>
</body>

</html>