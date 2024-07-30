<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
	<h3 id="greeting" class="no-mtop"></h3>
		<div class="panel_s">
			<div class="panel-body">
				<div class="row">
      		<div class="col-md-12">
				<h3 class="text-success projects-summary-heading no-mtop mbot15"><?php echo _l('transactions'); ?></h3>
         <hr>
      </div>
      <div class="col-lg-6 col-xs-12 col-md-12 total-column">
      <div class="panel_s">
         <div class="panel-body">
            <h3 class="text-muted _total">
               <?php
               $this->load->model('currencies_model');
               $currency = $this->currencies_model->get_base_currency(); 
                echo app_format_money(affiliate_sum_transaction(get_affiliate_user_id()), $currency->name); ?>
            </h3>
            <span class="text-warning"><?php echo _l('total'); ?></span>
         </div>
      </div>
   </div>
      <div class="col-lg-6 col-xs-12 col-md-12 total-column">
        <div class="panel_s">
           <div class="panel-body">
              <h3 class="text-muted _total">
                 <?php echo app_format_money(affiliate_sum_transaction(get_affiliate_user_id(), true), $currency->name); ?>
              </h3>
              <span class="text-success"><?php echo _l('this_month'); ?></span>
           </div>
        </div>
      </div>
      <div id="dashboard-commission-chart">
        <div class="row">
          <figure class="highcharts-figure col-md-12">
            <div id="commission_chart"></div>
          </figure>
        </div>
      </div>
     </div>
	</div>
</div>
</div>
