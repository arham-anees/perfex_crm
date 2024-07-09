<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="tw-mb-2 sm:tw-mb-4">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#subject_modal">
                    <i class="fa-regular fa-plus tw-mr-1"></i>
                    Add New Subject
                 </a>
            </div>
                  
                  
                  <div class="panel_s">
                     <div class="panel-body panel-table-full">
                        <table class="table table-subjects">
                           <thead>
                              <tr>
                                 <th><?php echo _l('Subject'); ?></th>
                                 <th><?php echo _l('Options'); ?></th>
                              </tr>
                           </thead>
                           <tbody>
                              <!-- Data will be populated by DataTable -->
                           </tbody>
                        </table>
                     </div>
                  </div>
              
         </div>
      </div>
   </div>
</div>

<!-- Modal -->
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
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Add</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<?php init_tail(); ?>

<script> 
   $(function(){
      initDataTable('.table-subjects', '<?php echo base_url('admin/appointly/subjects/json'); ?>', [], [], [], {
         "fnRowCallback": function(nRow, aData, iDisplayIndex) {
            $(nRow).find('td:eq(1)').addClass('dt-column-options');
            return nRow;
         }
      });

      // Confirmation dialog for delete
      $(document).on('click', '.btn-delete-subject', function(e) {
         e.preventDefault();
         var form = $(this).closest('form');
         if(confirm('Are you sure you want to delete this subject?')) {
            form.submit();
         }
      });
   });
</script>
</body>
</html>
