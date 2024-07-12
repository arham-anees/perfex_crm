<?php defined('BASEPATH') or exit('No direct script access allowed'); 
?>
<?php init_head();
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="horizontal-scrollable-tabs">
                           <div class="horizontal-tabs">
                              <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                    <li role="presentation" class="active">
                                       <a href="#general" aria-controls="general" role="tab" data-toggle="tab" aria-expanded="true"><?= ucfirst(_l('general')); ?></a>
                                    </li>
                                    <li role="presentation" class="">
                                       <a href="#form" aria-controls="form" role="tab" data-toggle="tab" aria-expanded="false"><?= ucfirst(_l('form')); ?></a>
                                    </li>
                              </ul>
                           </div>
                        </div>
                        <div class="tab-content">
                           <div role="tabpanel" class="tab-pane active" id="general">
                                    <!-- Booking Page Settings -->
                                    <div class="form-group">
                                    <label for="name"><?= _l('booking_page_name_field'); ?></label>
                                    <input type="text" class="form-control"
                                       value="<?= $booking_page['name']; ?>" id="name"
                                       name="name" required>
                                       <input type="hidden" class="form-control"
                                       value=""
                                       name="error_message" required disabled>
                              </div>

                              <div class="form-group">
                                    <label for="booking_page_description"><?= _l('booking_page_description'); ?></label>
                                    <textarea class="form-control" id="booking_page_description"
                                       name="description" disabled><?= $booking_page['description']; ?></textarea>
                              </div>
                                    <!-- Only shown for system admins -->
                                    <?php if (is_admin()) {

                                    if (isset($staff_members)) {
                                       echo render_select(
                                          'appointly_responsible_person',
                                          $booking_page['staff_members'],
                                          ['staffid', ['firstname', 'lastname']],
                                          'appointment_responsible_person',
                                          $appointly_responsible_person,
                                          [],
                                          [],
                                          'mtop15'
                                       );
                                       echo '<hr />';
                                       echo render_select(
                                          'callbacks_responsible_person',
                                          $booking_page['staff_members'],
                                          ['staffid', ['firstname', 'lastname']],
                                          'callbacks_responsible_person',
                                          $callbacks_responsible_person
                                       );
                                       echo '<hr />';
                                    } else {
                                       echo '<div class="alert alert-warning mtop15">';
                                       echo '<span>' . _l('appointment_no_staff_members') . '</span>';
                                       echo '</div>';
                                    }

                                    $appointmentHours = getAppointmentHours();

                                    $savedHours = json_decode($booking_page['appointly_available_hours']);
                                    ?>
                                    <div class="form-group">
                                       <label for="appointment_hours"><?= _l('appointments_default_hours_label'); ?></label>
                                       <select class="selectpicker" name="appointly_available_hours[]" id="appointment_hours" data-width="100%" multiple="true">
                                          <?php foreach ($appointmentHours as $hour) { ?>
                                                <option value="<?= $hour['value']; ?>" <?php if ($savedHours !== null) {
                                                   if (in_array($hour['value'], $savedHours)) {
                                                      echo ' selected';
                                                   }
                                                } ?>>
                                                   <?= $hour['name']; ?>
                                                </option>
                                          <?php } ?>
                                       </select>
                                    </div>
                                    <hr />

                                    <?php
                                    $appointmentFeedbacks = getAppointmentsFeedbacks();
                                    foreach ($appointmentFeedbacks as $fb) {
                                       echo $fb['name'];
                                    }
                                    $savedFeedbacks = json_decode($booking_page['appointly_default_feedbacks']);

                                    ?>

                                    <div class="form-group">
                                       <label for="appointly_default_feedbacks"><?= _l('appointments_feedback_info'); ?></label>
                                       <select class="selectpicker" name="appointly_default_feedbacks[]" id="appointly_default_feedbacks" data-width="100%" multiple="true">
                                          <?php foreach ($appointmentFeedbacks as $feedback) { ?>

                                                <option value="<?= $feedback['value']; ?>" <?php if ($savedFeedbacks !== null) {
                                                   if (in_array($feedback['value'], $savedFeedbacks)) {
                                                      echo ' selected';
                                                   }
                                                } ?>>
                                                   <?= $feedback['name']; ?>
                                                </option>
                                          <?php } ?>
                                       </select>
                                    </div>
                                    <hr/>
                                    <h4>Google Calendar API</h4>
                                    <div class="form-group">
                                       <label for="google_client_id"><?= _l('appointments_google_calendar_client_id'); ?></label>
                                       <!-- <input type="text" class="form-control" value="<?= $booking_page['google_client_id']; ?>" id="google_client_id" name="google_client_id"> -->
                                    </div>
                                    <div class="form-group">
                                       <label for="appointly_google_client_secret"><?= _l('appointments_google_calendar_client_secret'); ?></label>
                                       <input type="text" class="form-control" value="<?= $booking_page['appointly_google_client_secret']; ?>" id="appointly_google_client_secret" name="appointly_google_client_secret">
                                    </div>
                                    <div class="alert alert-info alert-dismissible" role="alert">
                                       <?= _l('appointments_redirect_url'); ?>:
                                       <strong> <?= base_url() . 'appointly/google/auth/oauth'; ?></strong>
                                       <button type="button" class="close" data-dismiss="alert" aria-label="<?= _l('close'); ?>">
                                          <span aria-hidden="true">&times;</span>
                                       </button>
                                    </div>
                                    <hr>
                                    <h4><?= _l('appointment_outlook_api_label'); ?></h4>
                                    <div class="form-group">
                                       <label for="appointly_outlook_client_id"><?= _l('appointment_outlook_client_id'); ?></label>
                                       <input type="text" class="form-control" value="<?= $booking_page['appointly_outlook_client_id']; ?>" id="appointly_outlook_client_id" name="sappointly_outlook_client_id">
                                    </div>
                                    <div class="alert alert-info alert-dismissible" role="alert">
                                       <?= _l('appointment_redirect_url_logout'); ?>:
                                       <strong> <?= base_url() . 'admin/appointly/appointments'; ?></strong>
                                       <button type="button" class="close" data-dismiss="alert" aria-label="<?= _l('close'); ?>">
                                          <span aria-hidden="true">&times;</span>
                                       </button>
                                    </div>
                                    <hr />
                                    <div class="form-group">
                                       <label for="appointly_recaptcha" class="control-label clearfix">
                                          <?= _l('appointly_recaptcha_label'); ?>
                                       </label>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_1_appointly_recaptcha" name="appointly_appointments_recaptcha" value="1" <?= ($booking_page['appointly_appointments_recaptcha'] == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointly_recaptcha"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointly_recaptcha" name="appointly_appointments_recaptcha" value="0" <?= ($booking_page['appointly_appointments_recaptcha'] == '0') ? ' checked' : '' ?>>
                                          <label for="y_opt_2_appointly_recaptcha">
                                                <?= _l('settings_no'); ?>
                                          </label>
                                       </div>
                                    </div>
                                    <hr />

                                    <div class="form-group">
                                       <label for="appointly_busy_times_enabled" class="control-label clearfix">
                                          <?= _l('appointly_busy_times_enabled_label'); ?>
                                       </label>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_1_appointly_busy_times_enabled" name="appointly_busy_times_enabled" value="1" <?= ($booking_page['appointly_busy_times_enabled'] == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointly_busy_times_enabled"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointly_busy_times_enabled" name="appointly_busy_times_enabled" value="0" <?= ($booking_page['appointly_busy_times_enabled'] == '0') ? ' checked' : '' ?>>
                                          <label for="y_opt_2_appointly_busy_times_enabled">
                                                <?= _l('settings_no'); ?>
                                          </label>
                                       </div>
                                    </div>
                                    <hr />
                                    <div class="form-group">
                                       <label for="appointly_also_delete_in_google_calendar" class="control-label clearfix">
                                          <?= _l('appointments_delete_from_google_label'); ?>
                                       </label>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_1_appointly_also_delete_in_google_calendar" name="appointly_also_delete_in_google_calendar" value="1" <?= ($booking_page['appointly_also_delete_in_google_calendar'] == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointly_also_delete_in_google_calendar"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointly_also_delete_in_google_calendar" name="appointly_also_delete_in_google_calendar" value="0" <?= ($booking_page['appointly_also_delete_in_google_calendar'] == '0') ? ' checked' : '' ?>>
                                          <label for="y_opt_2_appointly_also_delete_in_google_calendar">
                                                <?= _l('settings_no'); ?>
                                          </label>
                                       </div>
                                    </div>
                                    <hr />

                                    <div class="form-group">
                                       <label for="appointments_disable_weekends" class="control-label clearfix">
                                          <?= _l('appointments_disable_weekends_label'); ?>
                                       </label>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_1_appointments_disable_weekends" name="appointments_disable_weekends" value="1" <?= ($booking_page['appointments_disable_weekends'] == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointments_disable_weekends"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointments_disable_weekends" name="appointments_disable_weekends" value="0" <?= ($booking_page['appointments_disable_weekends'] == '0') ? ' checked' : '' ?>>
                                          <label for="y_opt_2_appointments_disable_weekends">
                                                <?= _l('settings_no'); ?>
                                          </label>
                                       </div>
                                    </div>
                                    <hr />
                                    <div class="form-group mtop10">
                                       <label for="appointly_view_all_in_calendar" class="control-label clearfix">
                                          <?= _l('appointly_view_all_in_calendar'); ?>
                                       </label>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_1_appointly_view_all_in_calendar" name="appointly_view_all_in_calendar" value="1" <?= ($booking_page['appointly_view_all_in_calendar'] == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointly_view_all_in_calendar"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointly_view_all_in_calendar" name="appointly_view_all_in_calendar" value="0" <?= ($booking_page['appointly_view_all_in_calendar'] == '0') ? ' checked' : '' ?>>
                                          <label for="y_opt_2_appointly_view_all_in_calendar">
                                                <?= _l('settings_no'); ?>
                                          </label>
                                       </div>
                                       <hr>
                                    </div>
                                    <div class="form-group">
                                       <label for="appointly_client_meeting_approved_default" class="control-label clearfix">
                                          <?= _l('appointments_approve_automatically_label'); ?>
                                       </label>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_1_appointly_client_meeting_approved_default" name="appointly_client_meeting_approved_default" value="1" <?= ($booking_page['appointly_client_meeting_approved_default'] == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointly_client_meeting_approved_default"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointly_client_meeting_approved_default" name="appointly_client_meeting_approved_default" value="0" <?= ($booking_page['appointly_client_meeting_approved_default'] == '0') ? ' checked' : '' ?>>
                                          <label for="y_opt_2_appointly_client_meeting_approved_default">
                                                <?= _l('settings_no'); ?>
                                          </label>
                                       </div>
                                    </div>
                                    <hr />

                                    <div class="form-group">
                                       <label for="appointments_show_past_times" class="control-label clearfix">
                                          <?= _l('appointments_buffer_hours_label'); ?>
                                       </label>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_1_appointments_show_past_times" name="appointments_show_past_times" value="1" <?= ($booking_page['appointments_show_past_times'] == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointments_show_past_times"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointments_show_past_times" name="appointments_show_past_times" value="0" <?= ($booking_page['appointments_show_past_times'] == '0') ? ' checked' : '' ?>>
                                          <label for="y_opt_2_appointments_show_past_times">
                                                <?= _l('settings_no'); ?>
                                          </label>
                                       </div>
                                    </div>
                                    <hr />

                                    <!-- End if is admin -->
                              <?php } ?>

                              <?php if (staff_can('edit', 'settings')) { ?>
                                    <div class="form-group">
                                       <label for="appointly_show_clients_schedule_button" class="control-label clearfix">
                                          <?= _l('appointly_allow_non_logged_clients_appointment'); ?>
                                       </label>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_1_appointly_show_clients_schedule_button" name="appointly_show_clients_schedule_button" value="1" <?= ($booking_page['appointly_show_clients_schedule_button'] == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointly_show_clients_schedule_button"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointly_show_clients_schedule_button" name="appointly_show_clients_schedule_button" value="0" <?= ($booking_page['appointly_show_clients_schedule_button'] == '0') ? ' checked' : '' ?>>
                                          <label for="y_opt_2_appointly_show_clients_schedule_button">
                                                <?= _l('settings_no'); ?>
                                          </label>
                                       </div>
                                    </div>
                                    <hr />

                                    <div class="form-group">
                                       <label for="appointly_tab_on_clients_page" class="control-label clearfix">
                                          <?= _l('appointly_show_appointments_menu_item_in_clients_area'); ?>
                                       </label>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_1_appointly_tab_on_clients_page" name="appointly_tab_on_clients_page" value="1" <?= ($booking_page['appointly_tab_on_clients_page'] == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointly_tab_on_clients_page"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointly_tab_on_clients_page" name="appointly_tab_on_clients_page" value="0" <?= ($booking_page['appointly_tab_on_clients_page'] == '0') ? ' checked' : '' ?>>
                                          <label for="y_opt_2_appointly_tab_on_clients_page">
                                                <?= _l('settings_no'); ?>
                                          </label>
                                       </div>
                                    </div>
                                    <hr />

                              <?php } ?>
                              <div class="mtop10">
                                    <span class="label label-info"><strong><?= get_appointly_version(); ?></strong></span>
                              </div>
                           </div>
                           <?php if (is_admin()) { ?>
                           <div role="tabpanel" class="tab-pane" id="form">
                              <div class="form-group mtop10">
                                    <label for="callbacks_mode_enabled" class="control-label clearfix">
                                       <?= _l('callbacks_enable_on_external_form'); ?>
                                    </label>
                                    <div class="radio radio-primary radio-inline">
                                       <input type="radio" id="y_opt_1_callbacks_mode_enabled" name="callbacks_mode_enabled" value="1" <?= ($booking_page['callbacks_mode_enabled'] == '1') ? ' checked' : '' ?>>
                                       <label for="y_opt_1_callbacks_mode_enabled"><?= _l('settings_yes'); ?></label>
                                    </div>
                                    <div class="radio radio-primary radio-inline">
                                       <input type="radio" id="y_opt_2_callbacks_mode_enabled" name="callbacks_mode_enabled" value="0" <?= ($booking_page['callbacks_mode_enabled'] == '0') ? ' checked' : '' ?>>
                                       <label for="y_opt_2_callbacks_mode_enabled">
                                          <?= _l('settings_no'); ?>
                                       </label>
                                    </div>
                                    <hr>
                                    <?php } ?>
                              </div>
                              <h4 class="bold">Form Info</h4>
                              <p><b>Form url:</b>
                                    <span class="label label-default">
                                          <a href="<?= site_url( $booking_page['url']); ?>">
                                             <?= site_url($booking_page['url']); ?>
                                          </a>
                                       </span>
                              </p>
                              <p><b>Form Subjects:</b>
                                    <span class="label ">
                                          <a href="<?= admin_url('appointly/subjects'); ?>" >
                                                List of Subjects
                                          </a>
                                       </span>
                              </p>
                              <hr />

                              <h4 class="bold">Embed form</h4>
                              <p><?= _l('form_integration_code_help'); ?></p>
                              <textarea class="form-control" rows="1"><iframe width="600" height="850" src="<?= site_url('appointly/' . $booking_page['url']); ?>" frameborder="0" allowfullscreen></iframe></textarea>
                              <p class="bold mtop15">When placing the iframe snippet code consider the following:</p>
                              <p class="<?php if (strpos(site_url(), 'http://') !== false) {
                                    echo 'bold text-success';
                              } ?>">1. If the protocol of your installation is http use a http page inside the iframe.</p>
                              <p class="<?php if (strpos(site_url(), 'https://') !== false) {
                                    echo 'bold text-success';
                              } ?>">2. If the protocol of your installation is https use a https page inside the iframe.</p>
                              <p>None SSL installation will need to place the link in non ssl eq. landing page and backwards.</p>
                              <hr />
                              <h4 class="bold">Change form container column (Bootstrap)</h4>
                              <p>
                                       <span class="label label-default">
                                          <a href="<?= site_url( $booking_page['url'].'?col=col-md-8'); ?>" target="_blank">
                                                <?= site_url($booking_page['url'].'?col=col-md-8'); ?>
                                          </a>
                                       </span>
                              </p>
                              <p>
                                       <span class="label label-default">
                                          <a href="<?= site_url( $booking_page['url'].'?col=col-md-8+col-md-offset-2'); ?>" target="_blank"><?= site_url( $booking_page['url'].'?col=col-md-8+col-md-offset-2'); ?></a>
                                       </span>
                              </p>
                              <p>
                                       <span class="label label-default">
                                          <a href="<?= site_url( $booking_page['url'] .'?col=col-md-5'); ?>" target="_blank">
                                                <?= site_url( $booking_page['url'].'?col=col-md-5'); ?>
                                          </a>
                                       </span>
                              </p>
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