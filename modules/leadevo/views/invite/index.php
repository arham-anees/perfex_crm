<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open(admin_url('invite/send_invitation'), ['id' => 'invite-friend-form']); ?>
                            <?php echo render_input('name', 'Name', '', 'text'); ?>
                            <?php echo render_input('email', 'Email', '', 'email'); ?>
                            <input type="submit" value="Invite" class="btn btn-primary"/>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require ('modules/leadevo/assets/js/invite.php'); ?>
<?php init_tail(); ?>
