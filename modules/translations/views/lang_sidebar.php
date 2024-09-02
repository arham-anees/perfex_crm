<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
        <?php
        foreach ($this->app->get_available_languages() as $language) {
            ?>
            <li class="settings-group-<?php echo html_escape($language); ?><?php if($selected_lang == $language) echo html_escape(' active'); ?>">
                <a href="<?php echo admin_url('translations?lang=' . $language); ?>" data-group="<?php echo html_escape($language); ?>">
                    <?php echo html_escape(ucfirst($language)); ?>
                </a>
            </li>
        <?php } ?>
    </ul>