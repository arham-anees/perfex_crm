<h4><?php echo _l('Settings'); ?></h4>
<hr class="hr-panel-heading">

<div class="card">
    <ul class="list-group">
        <li class="list-group-item">
            <a href="<?php echo site_url('settings/profile'); ?>" class="option-link"><?php echo _l('Profile'); ?></a>
        </li>

        <li class="list-group-item">
            <a href="<?php echo site_url('settings/billing'); ?>" class="option-link"><?php echo _l('Billing'); ?></a>
        </li>
        <li class="list-group-item">
            <a href="<?php echo site_url('settings/statistics'); ?>"
                class="option-link"><?php echo _l('Reports'); ?></a>
        </li>
    </ul>
</div>