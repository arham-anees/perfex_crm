<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
// statuses data from controller will be passed here
$statuses = $this->Leadevo_status_model->get_all();

// Render the statuses table


?>
<style>
   .btn-action-wrapper {
      display: flex;
      /* justify-content: flex-end; */
      gap: 10px;
      text-align: left;
   }

   .btn-edit-status,
   .btn-delete-status {
      background: none;
      border: none;
      cursor: pointer;
      padding: 0;
      /* color: #5bc0de; */

   }

   .btn-edit-status:hover,
   .btn-delete-status:hover {
      color: #31b0d5;
      /* Adjust hover color as needed */
   }

   .btn-delete-status:hover {
      color: #c9302c;
   }
</style>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="tw-mb-2 sm:tw-mb-4">
               <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#status_modal">
                  <i class="fa-regular fa-plus tw-mr-1"></i>
                  <?php echo _l('appointment_add_status'); ?>
               </a>
            </div>



            <!-- Render the statuses table -->
            <div class="panel_s">
               <div class="panel-body panel-table-full">
                  <?php if (!empty($statuses)) : ?>
                     <table class="table dt-table table-statuses" data-order-col="0" data-order-type="asc">
                        <thead>
                           <tr>
                              <th><?php echo _l('appointment_name'); ?></th>
                              <th><?php echo _l('appointment_options'); ?></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($statuses as $status) : ?>
                              <tr>
                                 <td><?php echo $status['name']; ?></td>
                                 <td class="text-right">

                                    <div class="btn-action-wrapper">
                                       <button type="button" class="btn-edit-status" data-id="<?php echo $status['id']; ?>" data-name="<?php echo $status['name']; ?>" data-toggle="modal" data-target="#edit_status_modal">
                                          <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                       </button>
                                       <form method="POST" action="<?php echo admin_url('appointly/statuses/delete'); ?>">
                                          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                          <input type="hidden" name="status_id" value="<?php echo $status['id']; ?>">

                                          <button type="submit" class="btn-delete-status"><i class="fa-regular fa-trash-can fa-lg"></i></button>

                                          <!-- <button type="submit" class="btn-delete-status"><i class="fa fa-trash"></i></button> -->

                                       </form>
                                    </div>
                              </tr>
                           <?php endforeach; ?>
                        </tbody>
                     </table>
                  <?php else : ?>
                     <p><?php echo _l('No statuses found.'); ?></p>
                  <?php endif; ?>
               </div>
            </div>

         </div>
      </div>
   </div>
</div>

<!--  Create status Modal -->
<div class="modal fade" id="status_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button group="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Add New Status</h4>
         </div>

         <div class="modal-body">

            <form method="POST" action="statuses/create">
               <!-- Include CSRF Token -->
               <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
               <div class="form-group">
                  <label for="appointment_status_name">Status Name</label>
                  <input type="text" class="form-control" name="name" placeholder="Enter Name" id="appointment_status_name" required />
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<!-- Edit Status Modal -->
<div class="modal fade" id="edit_status_modal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="editModalLabel">Edit Status</h4>
         </div>
         <div class="modal-body">
            <form method="POST" action="statuses/update">
               <!-- Include CSRF Token -->
               <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
               <input type="hidden" name="status_id" id="edit_status_id">
               <div class="form-group">
                  <label for="edit_appointment_status_name">Status Name</label>
                  <input type="text" class="form-control" name="name" placeholder="Enter Name" id="edit_appointment_status_name" required />
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
      //  initDataTable('.table-statuses', 'statuses/json', [1], [1]);
      // initDataTable('.table-statuses', 'statuses/json', [], [], [], {
      //    "fnRowCallback": function(nRow, aData, iDisplayIndex) {
      //       $(nRow).find('td:eq(1)').addClass('dt-column-options');
      //       return nRow;
      //    }
      // });
      // Confirmation dialog for delete
      $(document).on('click', '.btn-delete-status', function(e) {
         e.preventDefault();
         var form = $(this).closest('form');
         if (confirm('Are you sure you want to delete this status?')) {
            form.submit();
         }
      });
      // Update status modal with the selected status data
      $(document).on('click', '.btn-edit-status', function() {
         var id = $(this).data('id');
         var name = $(this).data('name');
         $('#edit_status_id').val(id);
         $('#edit_appointment_status_name').val(name);
      });

   });
</script>
</body>

</html>