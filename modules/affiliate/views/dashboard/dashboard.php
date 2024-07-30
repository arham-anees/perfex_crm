<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
   <div class="finance-summary">
      <div class="panel_s">
         <div class="panel-body">
            <div class="row home-summary">
                  <div class="col-md-6 col-lg-6 col-sm-6">
                     <div class="row">
                        <div class="col-md-12">
                           <p class="text-dark text-uppercase"><?php echo _l('discount'); ?></p>
                           <hr class="mtop15" />
                        </div>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="#" class="text-info mbot15 inline-block">
                              <?php echo _l('total'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo app_format_money($transaction_count['discount']['total'], $currency->name); ?>
                           <div class="progress no-margin progress-bar-mini">
                            <?php 
                              $percentage = 0;
                              if($transaction_count['discount']['total'] > 0){
                                $percentage = 100;
                              }
                              ?>
                              <div class="progress-bar progress-bar-info no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo new_html_entity_decode($transaction_count['discount']['total']); ?>" aria-valuemin="0" aria-valuemax="100" data-percent="<?php echo new_html_entity_decode($percentage); ?>">
                              </div>
                           </div>
                        </div>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="#" class="text-success inline-block mbot15">
                             <?php echo _l('paid'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo app_format_money($transaction_count['discount']['paid'], $currency->name); ?>

                           <div class="progress no-margin progress-bar-mini">
                            <?php 
                              $percentage = 0;
                              if($transaction_count['discount']['total'] > 0){
                                $percentage = $transaction_count['discount']['paid']/$transaction_count['discount']['total']*100;
                              }
                              ?>
                              <div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo new_html_entity_decode($transaction_count['discount']['paid']); ?>" aria-valuemin="0" aria-valuemax="100" data-percent="<?php echo new_html_entity_decode($percentage); ?>">
                              </div>
                           </div>
                        </div>
                        <?php $percent_data = get_invoices_percent_by_status(1); ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="#" class="text-warning mbot15 inline-block">
                              <?php echo _l('unpaid'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo app_format_money($transaction_count['discount']['unpaid'], $currency->name); ?>
                           <div class="progress no-margin progress-bar-mini">
                            <?php 
                              $percentage = 0;
                              if($transaction_count['discount']['total'] > 0){
                                $percentage = $transaction_count['discount']['unpaid']/$transaction_count['discount']['total']*100;
                              } ?>
                              <div class="progress-bar progress-bar-warning no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo new_html_entity_decode($transaction_count['discount']['unpaid']); ?>" aria-valuemin="0" aria-valuemax="100" data-percent="<?php echo new_html_entity_decode($percentage); ?>">
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg-6 col-sm-6">
                     <div class="row">
                        <div class="col-md-12 text-stats-wrapper">
                           <p class="text-dark text-uppercase"><?php echo _l('commission'); ?></p>
                           <hr class="mtop15" />
                        </div>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="#" class="text-info mbot15 inline-block">
                              <?php echo _l('total'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo app_format_money($transaction_count['commission']['total'], $currency->name); ?>
                           <div class="progress no-margin progress-bar-mini">
                            <?php 
                              $percentage = 0;
                              if($transaction_count['commission']['total'] > 0){
                                $percentage = 100;
                              }
                              ?>
                              <div class="progress-bar progress-bar-info no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo new_html_entity_decode($transaction_count['commission']['total']); ?>" aria-valuemin="0" aria-valuemax="100" data-percent="<?php echo new_html_entity_decode($percentage); ?>">
                              </div>
                           </div>
                        </div>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="#" class="text-success inline-block mbot15">
                             <?php echo _l('paid'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo app_format_money($transaction_count['commission']['paid'], $currency->name); ?>
                           <div class="progress no-margin progress-bar-mini">
                            <?php 
                              $percentage = 0;
                              if($transaction_count['commission']['total'] > 0){
                                $percentage = $transaction_count['commission']['paid']/$transaction_count['commission']['total']*100;
                              }
                              ?>
                              <div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo new_html_entity_decode($transaction_count['commission']['paid']); ?>" aria-valuemin="0" aria-valuemax="100" data-percent="<?php echo new_html_entity_decode($percentage); ?>">
                              </div>
                           </div>
                        </div>
                        <?php $percent_data = get_invoices_percent_by_status(1); ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="#" class="text-warning mbot15 inline-block">
                              <?php echo _l('unpaid'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo app_format_money($transaction_count['commission']['unpaid'], $currency->name); ?>
                           <div class="progress no-margin progress-bar-mini">
                            <?php 
                              $percentage = 0;
                              if($transaction_count['commission']['total'] > 0){
                                $percentage = $transaction_count['commission']['unpaid']/$transaction_count['commission']['total']*100;
                              }
                              ?>
                              <div class="progress-bar progress-bar-warning no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo new_html_entity_decode($transaction_count['commission']['unpaid']); ?>" aria-valuemin="0" aria-valuemax="100" data-percent="<?php echo new_html_entity_decode($percentage); ?>">
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  </div>
                  <div class="col-lg-4 col-xs-12 col-md-12 total-column">
                    <div class="panel_s">
                       <div class="panel-body">
                          <h3 class="text-muted _total">
                             <?php echo app_format_money($transaction_count['discount']['total'] + $transaction_count['commission']['total'], $currency->name); ?>
                          </h3>
                          <span class="text-info"><?php echo _l('total'); ?></span>
                       </div>
                    </div>
                 </div>
                 <div class="col-lg-4 col-xs-12 col-md-12 total-column">
                    <div class="panel_s">
                       <div class="panel-body">
                          <h3 class="text-muted _total">
                             <?php echo app_format_money($transaction_count['discount']['paid'] + $transaction_count['commission']['paid'], $currency->name); ?>
                          </h3>
                          <span class="text-success"><?php echo _l('paid'); ?></span>
                       </div>
                    </div>
                 </div>
                 <div class="col-lg-4 col-xs-12 col-md-12 total-column">
                    <div class="panel_s">
                       <div class="panel-body">
                          <h3 class="text-muted _total">
                             <?php echo app_format_money($transaction_count['discount']['unpaid'] + $transaction_count['commission']['unpaid'], $currency->name); ?>
                          </h3>
                          <span class="text-warning"><?php echo _l('unpaid'); ?></span>
                       </div>
                    </div>
                 </div>
               </div>
            </div>
          </div>
        <div class="panel_s">
          <div class="panel-body">
            <div id="transaction-chart">
              <div class="row">
                <figure class="highcharts-figure col-md-12">
                  <div id="transaction_chart"></div>
                </figure>
              </div>
            </div>
          </div>
        </div>
        <div class="panel_s">
          <div class="panel-body">
            <div id="commission-chart">
              <div class="row">
                <figure class="highcharts-figure col-md-12">
                  <div id="commission_chart"></div>
                </figure>
              </div>
            </div>
          </div>
        </div>
        <div class="panel_s">
          <div class="panel-body">
            <div id="discount-chart">
              <div class="row">
                <figure class="highcharts-figure col-md-12">
                  <div id="discount_chart"></div>
                </figure>
              </div>
            </div>
          </div>
        </div>
        <div class="panel_s">
          <div class="panel-body">
            <div id="registration-chart">
              <div class="row">
                <figure class="highcharts-figure col-md-12">
                  <div id="registration_chart"></div>
                </figure>
              </div>
            </div>
          </div>
        </div>
        <div class="panel_s">
        <div class="panel-body">
          <h4 class="no-margin font-bold"><?php echo _l('orders_not_yet_approved'); ?></h4>
          <?php echo form_hidden('order_status', 1); ?>
          <hr />
          <table class="table table-affiliate-orders">
            <thead>
              <th><?php echo _l('order_code'); ?></th>
              <th><?php echo _l('name'); ?></th>
              <th><?php echo _l('total'); ?></th>
              <th><?php echo _l('date_add'); ?></th>
              <th><?php echo _l('status'); ?></th>
              <th><?php echo _l('options'); ?></th>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
    </div>
  </div>
  </div>
</div>
<?php init_tail(); ?>
</body>
</html>
<?php require('modules/affiliate/assets/js/dashboard_js.php'); ?>