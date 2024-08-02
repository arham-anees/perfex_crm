<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php $ratings = ['0stars'=>10, '1stars'=>10, '2stars'=>10, '3stars'=>40, '4stars'=>20, '5stars'=>10]; ?>

<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                 Default Settings
                 <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Percent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>0 Stars</td>
                            <td><?=$ratings['0stars']?></td>
                        </tr>
                        <tr>
                            <td>1 Star</td>
                            <td><?=$ratings['1stars']?></td>
                        </tr>
                        <tr>
                            <td>2 Stars</td>
                            <td><?=$ratings['2stars']?></td>
                        </tr>
                        <tr>
                            <td>3 Stars</td>
                            <td><?=$ratings['3stars']?></td>
                        </tr>
                        <tr>
                            <td>4 Stars</td>
                            <td><?=$ratings['4stars']?></td>
                        </tr>
                        <tr>
                            <td>5 Stars</td>
                            <td><?=$ratings['5stars']?></td>
                        </tr>
                    </tbody>
                 </table>
                 <button type="button" class="btn-edit-status"  data-toggle="modal" data-target="#edit_quality_modal">
                    <i class="fa-regular fa-pen-to-square fa-lg"></i>
                </button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Edit Quality Modal -->
<div class="modal fade" id="edit_quality_modal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="editModalLabel">Edit Delivery Quality Algorithm</h4>
         </div>
         <div class="modal-body">
            <form  id="edit_delivery_quality_form" >
               <!-- Include CSRF Token -->
               <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
               <?php echo render_input('0stars',_l('leadevo_delivery_quality_0stars'), $ratings['0stars'],'number') ?> 
               <?php echo render_input('1stars',_l('leadevo_delivery_quality_1stars'), $ratings['1stars'],'number') ?> 
               <?php echo render_input('2stars',_l('leadevo_delivery_quality_2stars'), $ratings['2stars'],'number') ?> 
               <?php echo render_input('3stars',_l('leadevo_delivery_quality_3stars'), $ratings['3stars'],'number') ?> 
               <?php echo render_input('4stars',_l('leadevo_delivery_quality_4stars'), $ratings['4stars'],'number') ?> 
               <?php echo render_input('5stars',_l('leadevo_delivery_quality_5stars'), $ratings['5stars'],'number') ?> 
               <div id="edit_delivery_quality_form_error" style="color:red"></div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save Changes</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script>

    document.addEventListener('DOMContentLoaded', function() {
        $('#edit_delivery_quality_form').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            $('#edit_delivery_quality_form_error')[0].innerText='';

            var sum = 0;
            var formData = $(this).serializeArray();
            var data = {};

            $.each(formData, function(index, field) {
                if(field.name!='csrf_token_name'){
                    var value = parseFloat(field.value) || 0;
                    sum += value;
                    data[field.name] = value;
                }
            });
            if (sum === 100) {
            // Assuming admin_url is defined and accessible
            $.ajax({
                url: admin_url + 'leadevo/settings/update_delivery_quality',
                type: 'POST',
                data: data,
                success: function(response) {
                // Handle success response
                alert('Data submitted successfully!');
                },
                error: function(xhr, status, error) {
                // Handle error response
                console.error(error);
                alert('An error occurred while submitting the data.');
                }
            });
            } else {
                $('#edit_delivery_quality_form_error')[0].innerText='The sum of all quality values must be 100.';
            }
        });
    });

</script>


<?php init_tail(); ?>

