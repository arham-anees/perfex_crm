<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open(site_url('invite'), ['id' => 'invite-friend-form']); ?>
                        <?php echo render_input('name', 'Name', '', 'text'); ?>
                        <?php echo render_input('email', 'Email', '', 'email'); ?>
                        <input type="submit" value="Invite" class="btn btn-primary" />
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include JavaScript file using <script> tags -->
<!-- <?php require ('modules/leadevo/assets/js/invite/invite.php'); ?> -->
<?php init_tail(); ?>
