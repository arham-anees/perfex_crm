<?php defined('BASEPATH') or exit('No direct script access allowed');
$avg_sale_cycle = is_null($average_sales_cycle_length[0]['avg_sales_cycle'])?0:$average_sales_cycle_length[0]['avg_sales_cycle'];

init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
               <form method="GET" action="<?php echo admin_url('/leads_reporting/leadsreport') ?>">
                            <div class="col-md-12 no-padding">
                                <div class="col-md-4">
                                    <?php echo render_datetime_input('start_date', 'appointment_statistics_start_date', $start_date, [], [], '', 'appointment-date'); ?>
                                </div>
                                <div class="col-md-4 ">
                                    <?php echo render_datetime_input('end_date', 'appointment_statistics_end_date', $end_date,[], [], '', 'appointment-date'); ?>
                                </div>
                                <div class="col-md-4 ">
                                    <?php echo render_datetime_input('last_action_date', 'appointment_statistics_last_action_date', $last_action_date, [], [], '', 'appointment-date'); ?>
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
                                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                                    <button type="submit" class="btn  btn-danger">Clear Filter</button>
                                </div>
                            </div>
                            <!-- <button type="submit" class="btn btn-outline-primary">Filter</button> -->
                        </form>
                  <div class="col-md-12">
                     <div class="row">
                        <div class="col-md-4">
                           <div class="panel_s">
                              <div class="panel-body
                                    tw-text-left">
                                 <h5 class="no-margin tw-text-left tw-font-semibold font">Average Sale Cycle</h5>
                                 <h1 class="bold"><?= round($avg_sale_cycle, 2) ?></h1>
                                 <!-- <p class="no-margin">+23(+59.2%)<br>vs prior 30 days</p> -->
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">

                     <!-- below is the code for conversion and attrsion -->
                     <div class="col-md-12">
                        <div class="row">

                           <!-- Conversion rate of prospects to customers -->
                           <div class="col-md-6">
                              <div class="panel_s">
                                 <div class="panel-body">
                                    <!-- <div class="_buttons">
                                       <h4 class="no-margin">Conversoion Rate of Prospects to Customers</h4>
                                    </div>
                                    <div class="clearfix"></div> -->
                                    <!-- <hr class="hr-panel-heading" /> -->

                                    <?php if (!empty($conversion_rate) && is_array($conversion_rate)) : ?>
                                       <table class="table dt-table table-leads-report" data-order-col="0" data-order-type="asc">
                                          <thead>
                                             <tr>
                                                <th><?php echo _l('custom_field_staff'); ?></th>
                                                <!-- <th><?php echo _l('conversion_rate'); ?></th> -->
                                                <th>Conversion Rate</th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                             <?php foreach ($conversion_rate as $source) : ?>
                                                <?php    if($source['conversion_rate']>0) {?>
                                                <tr>
                                                   <td><?php echo $source['agent_name']; ?></td>
                                                   <td><?php echo is_null($source['conversion_rate']) ? '-' : round($source['conversion_rate'], 2) . '%' ?></td>
                                                </tr>
                                                <?php }?>
                                             <?php endforeach; ?>
                                          </tbody>
                                       </table>
                                    <?php else : ?>
                                       <p>No data available</p>
                                    <?php endif; ?>
                                 </div>
                              </div>
                           </div>


                           <!-- Prospect attrition rate -->
                           <div class="col-md-6">

                              <div class="panel_s">
                                 <div class="panel-body">
                                    <!-- <div class="_buttons">
                                       <h4 class="no-margin">Prospect attrition rate</h4>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr class="hr-panel-heading" /> -->

                                    <?php if (!empty($conversion_rate) && is_array($conversion_rate)) : ?>
                                       <table class="table dt-table table-leads-report" data-order-col="0" data-order-type="asc">
                                          <thead>
                                             <tr>
                                                <th><?php echo _l('custom_field_staff'); ?></th>
                                                <!-- <th><?php echo _l('conversion_rate'); ?></th> -->
                                                <th>Attrition Rate</th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                             <?php foreach ($conversion_rate as $source) : ?>
                                                <tr>
                                                   <td><?php echo $source['agent_name']; ?></td>
                                                   <td><?php echo is_null($source['conversion_rate']) ? '-' : round(100 - $source['conversion_rate'], 2) . '%'; ?></td>
                                                </tr>
                                             <?php endforeach; ?>
                                          </tbody>
                                       </table>
                                    <?php else : ?>
                                       <p>No data available</p>
                                    <?php endif; ?>
                                 </div>
                              </div>
                           </div>

                        </div>
                     </div>



                     <div class="col-md-12">
                        <div class="row">

                           <!-- Conversion rate of prospects to customers -->
                           <div class="col-md-6">
                              <div class="panel_s">
                                 <div class="panel-body">
                                    <!-- <div class="_buttons">
                                       <h4 class="no-margin">Amount of leads assigned per agent</h4>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr class="hr-panel-heading" /> -->

                                    <?php if (!empty($leads_per_agent) && is_array($leads_per_agent)) : ?>
                                       <table class="table dt-table table-leads-report" data-order-col="0" data-order-type="asc">
                                          <thead>
                                             <tr>
                                                <th><?php echo _l('custom_field_staff'); ?></th>
                                                <!-- <th><?php echo _l('lead_count'); ?></th> -->
                                                <th>Leads Assigned</th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                             <?php foreach ($leads_per_agent as $source) : ?>
                                                <tr>
                                                   <td><?php echo $source['agent']; ?></td>
                                                   <td><?php echo $source['lead_count']; ?></td>
                                                </tr>
                                             <?php endforeach; ?>
                                          </tbody>
                                       </table>
                                    <?php else : ?>
                                       <p>No data available</p>
                                    <?php endif; ?>
                                 </div>
                              </div>
                           </div>




                           <!-- Amount of lead created per agent -->
                           <div class="col-md-6">

                              <div class="panel_s">
                                 <div class="panel-body">
                                    <!-- <div class="_buttons">
                                       <h4 class="no-margin">Amount of leads created per agent</h4>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr class="hr-panel-heading" /> -->

                                    <?php if (!empty($leads_created_per_agent) && is_array($leads_created_per_agent)) : ?>
                                       <table class="table dt-table table-leads-report" data-order-col="0" data-order-type="asc">
                                          <thead>
                                             <tr>
                                                <th><?php echo _l('custom_field_staff'); ?></th>
                                                <!-- <th><?php echo _l('total_leads'); ?></th> -->
                                                <th>Leads Created</th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                             <?php foreach ($leads_created_per_agent as $source) : ?>
                                                <tr>
                                                   <td><?php echo $source['agent']; ?></td>
                                                   <td><?php echo $source['lead_count']; ?></td>
                                                </tr>
                                             <?php endforeach; ?>
                                          </tbody>
                                       </table>
                                    <?php else : ?>
                                       <p>No data available</p>
                                    <?php endif; ?>
                                 </div>
                              </div>
                           </div>


                        </div>
                     </div>


                     <div class="col-md-12">
                        <div class="row">

                           <!-- Average Time Spent per Prospect -->
                           <div class="col-md-6">
                              <div class="panel_s">
                                 <div class="panel-body">
                                    <!-- <div class="_buttons">
                                       <h4 class="no-margin">Average Time Spent per Prospect (Hours)</h4>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr class="hr-panel-heading" /> -->

                                    <?php if (!empty($conversion_rate) && is_array($conversion_rate)) : ?>
                                       <table class="table dt-table table-leads-report" data-order-col="0" data-order-type="asc">
                                          <thead>
                                             <tr>
                                                <th><?php echo _l('custom_field_staff'); ?></th>
                                                <!-- <th><?php echo _l('avg_time'); ?></th> -->
                                                <th>Average Time(days)</th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                             <?php foreach ($conversion_rate as $source) : ?>
                                                <?php if(!is_null($source['avg_conversion_time']) && $source['avg_conversion_time']!=''){?>
                                                <tr>
                                                   <td><?php echo $source['agent_name']; ?></td>
                                                   <td><?php echo round($source['avg_conversion_time'], 2); ?></td>
                                                </tr>
                                                <?php } ?>
                                             <?php endforeach; ?>
                                          </tbody>
                                       </table>
                                    <?php else : ?>
                                       <p>No data available</p>
                                    <?php endif; ?>
                                 </div>
                              </div>
                           </div>





                           <!-- Amount of lead created per agent -->
                           <div class="col-md-6">

                              <div class="panel_s">
                                 <div class="panel-body">
                                    <!-- <div class="_buttons">
                                       <h4 class="no-margin">Average Value of Won Prospects</h4>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr class="hr-panel-heading" /> -->

                                    <?php if (!empty($average_value_of_won_prospects) && is_array($average_value_of_won_prospects)) : ?>
                                       <table class="table dt-table table-leads-report" data-order-col="0" data-order-type="asc">
                                          <thead>
                                             <tr>
                                                <th><?php echo _l('custom_field_staff'); ?></th>
                                                <th><?php echo _l('leads_dt_lead_value'); ?></th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                             <?php foreach ($average_value_of_won_prospects as $source) : ?>
                                                <tr>
                                                   <td><?php echo $source['agent_name']; ?></td>
                                                   <td><?php echo round($source['average_value_won'], 2) . ' $'; ?></td>
                                                </tr>
                                             <?php endforeach; ?>
                                          </tbody>
                                       </table>
                                    <?php else : ?>
                                       <p>No data available</p>
                                    <?php endif; ?>
                                 </div>
                              </div>
                           </div>


                        </div>
                     </div>

                     <div class="col-md-12">
                        <div class="_buttons">
                           <h4 class="no-margin">Agent Effectiveness</h4>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <?php if (!empty($agent_effectiveness) && is_array($agent_effectiveness)) : ?>
                           <table class="table dt-table table-leads-report" data-order-col="0" data-order-type="asc">
                              <thead>
                                 <tr>
                                    <th><?php echo _l('agent_name'); ?></th>
                                    <!-- <th><?php echo _l('source_name'); ?></th> -->
                                    <th><?php echo _l('avg_conversion_time'); ?></th>
                                    <th><?php echo _l('conversion_rate'); ?></th>
                                    <th><?php echo _l('total_leads'); ?></th>
                                    <th><?php echo _l('total_appointments'); ?></th>
                                    <th><?php echo _l('appointments_missed'); ?></th>
                                    <th><?php echo _l('quotes_sent'); ?></th>
                                    <th><?php echo _l('quotes_signed'); ?></th>
                                 </tr>
                              </thead>
                              <tbody>
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
                              </tbody>
                           </table>
                        <?php else : ?>
                           <p>No data available</p>
                        <?php endif; ?>
                     </div>


                     <div class="col-md-12">
                        <div class="_buttons">
                           <h4 class="no-margin">Lead Source Effectiveness</h4>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <?php if (!empty($lead_source_effectiveness) && is_array($lead_source_effectiveness)) : ?>

                           <table class="table dt-table table-leads-report" data-order-col="0" data-order-type="asc">
                              <thead>
                                 <tr>
                                    <th><?php echo _l('source_name'); ?></th>
                                    <th><?php echo _l('avg_conversion_time'); ?></th>
                                    <th><?php echo _l('conversion_rate'); ?></th>
                                    <th><?php echo _l('total_leads'); ?></th>
                                    <!-- <th><?php echo _l('total_appointments'); ?></th> -->
                                    <!-- <th><?php echo _l('appointments_missed'); ?></th> -->
                                    <th><?php echo _l('quotes_sent'); ?></th>
                                    <th><?php echo _l('quotes_signed'); ?></th>
                                 </tr>
                              </thead>
                              <tbody>
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
                              </tbody>
                           </table>
                        <?php else : ?>
                           <p>No data available</p>
                        <?php endif; ?>
                     </div>
                  </div>

               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<?php init_tail(); ?>
</body>

</html>