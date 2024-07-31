<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
// load the subjects data from the controller
$subjects = $this->Appointments_subject_model->get_all();
?>
<style>
   .btn-action-wrapper {
      display: flex;
      gap: 10px;
      text-align: left;
   }

   .btn-edit-subject,
   .btn-delete-subject {
      background: none;
      border: none;
      cursor: pointer;
      padding: 0;
      /* color: #5bc0de; */
   }

   .btn-edit-subject:hover,
   .btn-delete-subject:hover {
      color: #31b0d5;
   }

   /* .btn-delete-subject {
      color: #d9534f;
   } */

   .btn-delete-subject:hover {
      color: #c9302c;
   }
</style>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="tw-mb-2 sm:tw-mb-4">
               <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#subject_modal">
                  <i class="fa-regular fa-plus tw-mr-1"></i>
                  <?php echo _l('appointment_add_subject'); ?>
               </a>
            </div>
            <!-- Render the subjects table -->
            <div class="panel_s">
               <div class="panel-body panel-table-full">
                  <?php if (!empty($subjects)) : ?>
                     <table class="table dt-table table-subjects" data-order-col="0" data-order-type="asc">
                        <thead>
                           <tr>
                              <th><?php echo _l('subject_booking_page_name_field'); ?></th>
                              <th><?php echo _l('appointment_subject'); ?></th>
                              <th><?php echo _l('appointment_options'); ?></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($subjects as $subject) : ?>
                              <tr>
                                 <td><?php echo $subject['name']; ?></td>
                                 <td><?php echo $subject['subject']; ?></td>
                                 <td class="text-right">
                                    <div class="btn-action-wrapper">
                                       <button type="button" class="btn-edit-subject" data-id="<?php echo $subject['id']; ?>" data-name="<?php echo $subject['subject']; ?>" data-booking="<?php echo $subject['booking_page_id']; ?>" data-toggle="modal" data-target="#edit_subject_modal">
                                          <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                       </button>
                                       <form method=" POST" action="<?php echo admin_url('appointly/subjects/delete'); ?>">
                                          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                          <input type="hidden" name="subject_id" value="<?php echo $subject['id']; ?>">
                                          <button type="submit" class="btn-delete-subject"><i class="fa-regular fa-trash-can fa-lg"></i></button>
                                       </form>
                                    </div>
                                 </td>
                              </tr>
                           <?php endforeach; ?>
                        </tbody>
                     </table>
                  <?php else : ?>
                     <p><?php echo _l('No subjects found.'); ?></p>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Create subject Modal -->
<div class="modal fade" id="subject_modal" tabindex="-1" role="dialog" aria-labelledby="subject_modal_label" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="subject_modal_label">Add New Subject</h4>
         </div>
         <div class="modal-body">
            <form method="POST" action="<?php echo base_url('admin/appointly/subjects/create'); ?>">
               <!-- Include CSRF Token -->
               <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
               <div class="form-group">
                  <label for="subject_name">Subject Name</label>
                  <input type="text" class="form-control" name="subject" placeholder="Enter Subject" id="subject_name" required />
               </div>
               <?php
               if(isset($booking_pages)){
                echo render_select(
                    'booking_page_id',
                    $booking_pages,
                    ['id', ['name']],
                    'subject_booking_page_name_field',
                    '',
                    [],
                    [],
                    'mtop15',
                    false
                );
               }
               else {
                  echo 'Please add a booking page first';
               }
               
               ?>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Add</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<!-- Edit Subject Modal -->
<div class="modal fade" id="edit_subject_modal" tabindex="-1" role="dialog" aria-labelledby="edit_subject_modal_label" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="edit_subject_modal_label">Edit Subject</h4>
         </div>
         <div class="modal-body">
           
         <form method="POST" action="<?php echo base_url('admin/appointly/subjects/update'); ?>">
               <!-- Include CSRF Token -->
               <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
               <input type="hidden" name="subject_id" id="edit_subject_id" >
               <div class="form-group">
                  <label for="edit_subject_name">Subject Name</label>
                  <input type="text" class="form-control" name="subject" placeholder="Enter Subject" id="edit_subject_name" required />
               </div>
               <div id="">
               <?php
               if(isset($booking_pages)){
                  echo render_select(
                     'booking_page_id',
                     $booking_pages,
                     ['id', ['name']],
                     'subject_booking_page_name_field',
                     '',
                     [],
                     [],
                     'mtop15',
                     false
                  );
               }
               else {
                  echo 'Please add a booking page first';
               }
               
               ?>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
         </form>
         </div>
      </div>
   </div>
</div>


<?php init_tail(); ?>

<script>
   $(function() {
      // Confirmation dialog for delete
      $(document).on('click', '.btn-delete-subject', function(e) {
         e.preventDefault();
         var form = $(this).closest('form');
         if (confirm('Are you sure you want to delete this subject?')) {
            form.submit();
         }
      });
      // Update subject modal with the selected subject data
      $(document).on('click', '.btn-edit-subject', function() {
         var id = $(this).data('id');
         var name = $(this).data('name');
         var booking_page_id = $(this).data('booking');
         $('#edit_subject_id').val(id);
         $('#edit_subject_name').val(name);

         $('#booking_page_id').val(booking_page_id)
         $('.filter-option-inner-inner').text($('#booking_page_id option[value='+booking_page_id+']').text())
         });
   });
</script>
</body>

</html>