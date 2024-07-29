<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
  <div class="col-md-12">
    <div class="panel_s">
     <div class="panel-body">
      <div class="horizontal-tabs mb-5">
        <ul class="nav nav-tabs nav-tabs-horizontal mb-10">
      <?php
      $i = 0;
      foreach($tab as $gr){ ?> 
        <li<?php if($i == 0){echo " class='active'"; } ?>>
        <a href="<?php echo admin_url('affiliate/wallet?group='.$gr); ?>" data-group="<?php echo new_html_entity_decode($gr); ?>">
          <?php echo _l($gr); ?>
          </a>
        </li>
        <?php $i++; 
      } ?>
      </ul>
      </div>
      <?php $this->load->view($tabs['view']); ?>
    </div>
  </div>
</div>
</div>
</div>
<?php init_tail(); ?>