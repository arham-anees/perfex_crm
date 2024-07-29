<?php init_head();?>
<div id="wrapper" class="affiliate">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
          <div class="horizontal-tabs mb-5">
              <ul class="nav nav-tabs nav-tabs-horizontal mb-10">
            <?php
            $i = 0;
            foreach($tab as $gr){ ?> 
              <li<?php if($i == 0){echo " class='active'"; } ?>>
              <a href="<?php echo admin_url('affiliate/members?group='.$gr); ?>" data-group="<?php echo new_html_entity_decode($gr); ?>">
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
</body>
</html>
<?php 
  if($group == 'member_list'){
    require 'modules/affiliate/assets/js/members/members_list_js.php';
  }
?>