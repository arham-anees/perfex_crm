<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="panel_s">
        <div class="panel-body text-left">
            <h4><?php echo _l('Settings'); ?></h4>
            <hr class="hr-panel-heading">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="list-group list-group-flush">
                            <a class="list-group-item <?php echo (isset($active_tab) && $active_tab == 'profile') ? 'active' : ''; ?>"><?php echo _l('Profile'); ?></a>
                            <a class="list-group-item <?php echo (isset($active_tab) && $active_tab == 'billing') ? 'active' : ''; ?>"><?php echo _l('Billing'); ?></a>
                            <a class="list-group-item <?php echo (isset($active_tab) && $active_tab == 'prospect_status') ? 'active' : ''; ?>"><?php echo _l('Prospect status'); ?></a>
                            <a class="list-group-item <?php echo (isset($active_tab) && $active_tab == 'report') ? 'active' : ''; ?>"><?php echo _l('Report'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php
                    if (isset($active_tab) && in_array($active_tab, array('profile', 'billing', 'prospect_status', 'report'))) {
                        $this->load->view('themes/perfex/views/' . $active_tab);
                    } else {
                        echo '<div class="alert alert-warning">' . _l('no_data_found') . '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

