<?php if (isset($td_appointments) && !empty($td_appointments)): ?>
  
  <div class="">
      <div class="">
          <div class="">
          <a class="hidden" id="showAppointmentList">Back to appointments</a>
                  <button type="button" class="btn btn-info" id="showBookingPages" style="float:right;">Book an appointment
                      </button>

              <div  id="appointmentList">

                  <h4><?= _l('appointment_lead_tab'); ?>
                  </h4>
                  <hr class="mbot0">
                  <?php foreach ($td_appointments as $appointment): ?>
                      <div class="todays_appointment col-2 mleft20 appointly-secondary pull-left mtop10">
                          <h3 class="text-muted mtop1">
                              <a
                                  href="<?= admin_url('appointly/appointments/view?appointment_id=' . $appointment['id']); ?>"><?= $appointment['subject']; ?></a>
                          </h3>
                          <span class="text-muted span_limited">
                              <?= _l('appointment_description'); ?>
                              <?= $appointment['description']; ?>
                          </span>
                          <h5 class="no-margin">
                              <span class="text-warning"><?= _l('appointment_scheduled_at'); ?>
                              </span>
                              <?= date("H:i A", strtotime($appointment['start_hour'])); ?>
                          </h5>
                      </div>
                  <?php endforeach; ?>
              </div>
          </div>
      </div>
  </div>
<?php else: ?>
  <div class="">
      <div class="">
          <div class="">
              <div class="">
                  <!-- <span class="label label-info label-big pull-right mtop5"><?= _d(date('Y-m-d')); ?></span> -->
                  <h4 id="no-appointment-message"><?= _l('appointment_lead_no_appointments'); ?>
                  </h4>
                  <a class="hidden mtop5" id="showAppointmentList">Back to appointments</a>
                  <button type="button" class="btn btn-info" id="showBookingPages" style="float:right;">Book an appointment
                      </button>
              </div>
          </div>
      </div>
  </div>
<?php endif; ?>

<div id="bookingPages" class="hidden">
<?php if (!empty($booking_pages)): ?>
  <table class="table dt-table table-statuses" data-order-col="0" data-order-type="asc" id = "bookingPagesTable">
      <thead>
          <tr>
              <th><?php echo _l('appointment_name'); ?></th>
              <th><?php echo _l('appointment_url'); ?></th>
          </tr>
      </thead>
      <tbody>
          <tr>
              <td><?= _l('booking_page_default_form') ?></td>
              <td><span onclick="load_booking_page('<?php echo site_url('appointly/appointments_public/form'); ?>')" target="_blank">appointly/appointments_public/form</span></td>
          </tr>
          <?php foreach ($booking_pages as $page): ?>
              <tr>
                  <td><span onclick="load_booking_page('<?php echo site_url($page['url']); ?>')" ><?php echo $page['name']; ?></span></td>
                  <td><span onclick="load_booking_page('<?php echo site_url($page['url']); ?>')" ><?php echo $page['url']; ?></span></td>
              </tr>
          <?php endforeach; ?>
      </tbody>
  </table>
  <?php render_datatable([
      _l('id'),
      [
          'th_attrs' => ['width' => '300px'],
          'name' => _l('appointment_subject')
      ],
      _l('appointment_meeting_date'),
      _l('appointment_initiated_by'),
      _l('appointment_description'),
      _l('appointment_status'),
      _l('appointment_source'),
      [
          'th_attrs' => ['width' => '120px'],
          'name' => _l('appointments_table_calendar')
      ]
  ], 'appointments'); ?>
<?php else: ?>
  <p><?php echo _l('No booking pages found.'); ?></p>
<?php endif; ?>
</div>

<div id="booking_page_form" class="hidden"></div>

<script>
    function load_booking_page(url) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function (response) {
                console.log(response);
                $('#booking_page_form').html(response);
                $('#booking_page_form').removeClass('hidden');
                $('#bookingPagesTable').addClass('hidden'); 
                $('#bookingPages').addClass('hidden');
            },
            error: function () {
                alert('Failed to fetch lead profile content.');
            }
        });
    }
</script>

<script>
    $(document).ready(function () {
        $('#showAppointmentList').click(function () {
            $('#no-appointment-message').removeClass('hidden');
            $('#appointmentList').removeClass('hidden');
            $('#bookingPages').addClass('hidden');
            $('#showAppointmentList').addClass('hidden');
            $('#showBookingPages').removeClass('hidden');
            $('#booking_page_form').addClass('hidden');
        });

        $('#showBookingPages').click(function () {
            $('#no-appointment-message').addClass('hidden');
            $('#bookingPages').removeClass('hidden');
            $('#appointmentList').addClass('hidden');
            $('#showBookingPages').addClass('hidden');
            $('#showAppointmentList').removeClass('hidden');
            $('#booking_page_form').addClass('hidden'); 
            $('#bookingPagesTable').removeClass('hidden'); 
        });
    });
</script>
