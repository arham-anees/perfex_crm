<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="tw-mb-2 sm:tw-mb-4">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#status_modal">
                    <i class="fa-regular fa-plus tw-mr-1"></i>
                    Add New Status
                 </a>
            </div>
                  
                  
                  <div class="panel_s">
                     <div class="panel-body panel-table-full">
                        <table class="table table-statuses">
                           <thead>
                              <tr>
                                 <th><?php echo _l('Name'); ?></th>
                                 <th><?php echo _l('options'); ?></th>
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
<div class="modal fade" id="status_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button group="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Add New Status</h4>
            
            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button> -->
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

<?php init_tail(); ?>

<script> 
   $(function(){
   //  initDataTable('.table-statuses', 'statuses/json', [1], [1]);
      initDataTable('.table-statuses', 'statuses/json', [], [], [], {
         "fnRowCallback": function(nRow, aData, iDisplayIndex) {
            $(nRow).find('td:eq(1)').addClass('dt-column-options');
            return nRow;
         }
      });
      // Confirmation dialog for delete
      $(document).on('click', '.btn-delete-status', function(e) {
         e.preventDefault();
         var form = $(this).closest('form');
         if(confirm('Are you sure you want to delete this status?')) {
            form.submit();
         }
      });
   });
</script>
</body>
</html>
