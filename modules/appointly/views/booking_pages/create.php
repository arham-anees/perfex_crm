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
                     <?php echo form_open(admin_url('appointly/booking_pages/'. (isset($id)?'update/'.$id:'create')), ['id' => 'booking-page-form']); ?>

                     <?php if(isset($error_message) && $error_message != ''){?>
                        <div class="alert alert-danger"><?= $error_message ?></div>
                        <?php } ?>
                        <div >
                           <div role="tabpanel" class="tab-pane active" id="general">
                                    <!-- Booking Page Settings -->
                                    <div class="form-group">
                                    <label for="name"><?= _l('booking_page_name_field'); ?></label>
                                    <input type="text" class="form-control"
                                       value="<?= $name; ?>" id="name"
                                       name="name" required>
                                       <input type="hidden" class="form-control"
                                       value=""
                                       name="error_message" >
                              </div>

                              <div class="form-group">
                                    <label for="booking_page_description"><?= _l('booking_page_description'); ?></label>
                                    <textarea class="form-control" id="booking_page_description"
                                       name="description"><?= $description; ?></textarea>
                              </div>

                              <div class="form-group">
                                <label for="duration_minutes"><?= _l('appointment_duration_minutes'); ?></label>
                                <input type="int" class="form-control"
                                    value="<?= $duration_minutes ?>"
                                    name="duration_minutes" id="duration_minutes" require>
                              </div>
                              <div class="form-group">
                                <label for="simultaneous_appointments"><?= _l('appointment_simultaneous_appointments'); ?></label>
                                <input type="int" class="form-control"
                                    value="<?= $simultaneous_appointments ?>"
                                    name="simultaneous_appointments" id="simultaneous_appointments" require>
                              </div>
                                    <!-- Only shown for system admins -->
                                    <?php if (is_admin()) {

                                    if (isset($staff_members)) {
                                       echo render_select(
                                          'appointly_responsible_person',
                                          $staff_members,
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
                                          $staff_members,
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
                                    $savedHours = isset($appointly_available_hours)?json_decode($appointly_available_hours):[];
                                    ?>
                                    <div class="form-group hours">
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
                                    $savedFeedbacks = isset($appointly_default_feedbacks)?json_decode($appointly_default_feedbacks):[];

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
                                    <div class="form-group">
                                       <label for="appointly_recaptcha" class="control-label clearfix">
                                          <?= _l('appointly_recaptcha_label'); ?>
                                       </label>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_1_appointly_recaptcha" name="appointly_appointments_recaptcha" value="1" <?= ($appointly_appointments_recaptcha == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointly_recaptcha"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointly_recaptcha" name="appointly_appointments_recaptcha" value="0" <?= ($appointly_appointments_recaptcha == '0') ? ' checked' : '' ?>>
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
                                          <input type="radio" id="y_opt_1_appointly_busy_times_enabled" name="appointly_busy_times_enabled" value="1" <?= ($appointly_busy_times_enabled == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointly_busy_times_enabled"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointly_busy_times_enabled" name="appointly_busy_times_enabled" value="0" <?= ($appointly_busy_times_enabled == '0') ? ' checked' : '' ?>>
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
                                          <input type="radio" id="y_opt_1_appointly_also_delete_in_google_calendar" name="appointly_also_delete_in_google_calendar" value="1" <?= ($appointly_also_delete_in_google_calendar == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointly_also_delete_in_google_calendar"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointly_also_delete_in_google_calendar" name="appointly_also_delete_in_google_calendar" value="0" <?= ($appointly_also_delete_in_google_calendar == '0') ? ' checked' : '' ?>>
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
                                          <input type="radio" id="y_opt_1_appointments_disable_weekends" name="appointments_disable_weekends" value="1" <?= ($appointments_disable_weekends == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointments_disable_weekends"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointments_disable_weekends" name="appointments_disable_weekends" value="0" <?= ($appointments_disable_weekends == '0') ? ' checked' : '' ?>>
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
                                          <input type="radio" id="y_opt_1_appointly_view_all_in_calendar" name="appointly_view_all_in_calendar" value="1" <?= ($appointly_view_all_in_calendar == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointly_view_all_in_calendar"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointly_view_all_in_calendar" name="appointly_view_all_in_calendar" value="0" <?= ($appointly_view_all_in_calendar == '0') ? ' checked' : '' ?>>
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
                                          <input type="radio" id="y_opt_1_appointly_client_meeting_approved_default" name="appointly_client_meeting_approved_default" value="1" <?= ($appointly_client_meeting_approved_default == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointly_client_meeting_approved_default"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointly_client_meeting_approved_default" name="appointly_client_meeting_approved_default" value="0" <?= ($appointly_client_meeting_approved_default == '0') ? ' checked' : '' ?>>
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
                                          <input type="radio" id="y_opt_1_appointments_show_past_times" name="appointments_show_past_times" value="1" <?= ($appointments_show_past_times == '1') ? ' checked' : '' ?>>
                                          <label for="y_opt_1_appointments_show_past_times"><?= _l('settings_yes'); ?></label>
                                       </div>
                                       <div class="radio radio-primary radio-inline">
                                          <input type="radio" id="y_opt_2_appointments_show_past_times" name="appointments_show_past_times" value="0" <?= ($appointments_show_past_times == '0') ? ' checked' : '' ?>>
                                          <label for="y_opt_2_appointments_show_past_times">
                                                <?= _l('settings_no'); ?>
                                          </label>
                                       </div>
                                    </div>
                                    <hr />

                                    <!-- End if is admin -->
                              <?php } ?>
                              <div class="form-group mtop10">
                                    <label for="callbacks_mode_enabled" class="control-label clearfix">
                                       <?= _l('callbacks_enable_on_external_form'); ?>
                                    </label>
                                    <div class="radio radio-primary radio-inline">
                                       <input type="radio" id="y_opt_1_callbacks_mode_enabled" name="callbacks_mode_enabled" value="1" <?= ($callbacks_mode_enabled == '1') ? ' checked' : '' ?>>
                                       <label for="y_opt_1_callbacks_mode_enabled"><?= _l('settings_yes'); ?></label>
                                    </div>
                                    <div class="radio radio-primary radio-inline">
                                       <input type="radio" id="y_opt_2_callbacks_mode_enabled" name="callbacks_mode_enabled" value="0" <?= ($callbacks_mode_enabled == '0') ? ' checked' : '' ?>>
                                       <label for="y_opt_2_callbacks_mode_enabled">
                                          <?= _l('settings_no'); ?>
                                       </label>
                                    </div>
                                    <hr>
                                    <p><b>Form url:</b>
                                    <span class="label label-default">
                                          <span><?= site_url('a'); ?>
                                          <input type="text" name="url" value="<?= $url ?>" require pattern="^[a-zA-Z0-9_-]+$"/>
                              </span>
                                       </span>
                              </p>
                              <p><b>Form Subjects:</b>
                                    <span class="label label-info">
                                          <a href="<?= site_url('appointly/subjects'); ?>" >
                                                List of Subjects
                                          </a>
                                       </span>
                              </p>
                           </div>
                        </div>
                        <input type='submit' class="btn btn-primary pull-right" value='Submit'/>
                     
                <?php echo form_close(); ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <script>


document.addEventListener('DOMContentLoaded',function(){
      let error_message = '<?= $error_message ?>';
      console.log(error_message);
      if(error_message.length>0){
         // alert_float('error', error_message);
      }
    $(function() {
        appValidateForm($("#booking-page-form"), {
            name: "required",
            url: "required"
        }, apply_appointments_form_data);

        function apply_appointments_form_data(form) {
            $('input[type="submit"], button.close_btn').prop('disabled', true);
            $('input[type="submit"]').html('<i class="fa fa-refresh fa-spin fa-fw"></i>');

            var formSerializedData = $(form).serializeArray();


            var data = $(form).serialize();
            var url = form.action;

            $.post(url, data).done(function(response) {
               response = JSON.parse(response);
               if (response.success) {
            alert_float('success', "New booking page was successfully created");
            setTimeout(() => window.location.reload(), 1000);
            } else {
                  alert_float('error', "Failed to create booking page");
            }
            });
            return false;
        }
    });

    $('#duration_minutes').on('change', function() {
        var durationMinutes = $(this).val(); // Get the new value of duration_minutes
        // AJAX request to execute PHP script and fetch HTML
        $.ajax({
            url: 'generate_options', // Replace with your PHP script URL
            type: 'GET', // Use GET or POST as appropriate
            dataType: 'html', // Expected data type from server
            data: { minutes_duration: durationMinutes }, // Pass parameters if needed
            success: function(response) {
                // Update the HTML content based on the response
                $('.form-group.hours').html(response);
                // If using selectpicker, refresh it after updating options
                $('.selectpicker').selectpicker('refresh');
            },
            error: function(xhr, status, error) {
                // Handle errors if any
                console.error('AJAX Error:', error);
            }
        });
      });
});
</script>


<?php init_tail(); ?>

</body>
</html>