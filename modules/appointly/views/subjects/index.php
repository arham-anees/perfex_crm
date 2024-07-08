<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
$subjects = get_subjects();
?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
               <?php if (!empty($subjects)): ?>
                    <ul>
                        <?php foreach ($subjects as $subject): ?>
                            <li class="row" style="border-bottom:1px solid grey">
                        <div class="col-lg-10">    <?php echo $subject['subject']; ?></div>
                        <div class="col-lg-2">
                        <form method="POST" action="subjects/delete">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="subject_id" value="<?php echo $subject['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                        </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No subjects found.</p>
                <?php endif; ?>

                <form method="POST" action="subjects/create">
                    <!-- Include CSRF Token -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    
                    <input type="text" class="form-control" name="subject" placeholder="Enter subject" id="appointment_subject" required />
                    <button type="submit">Add</button>
                </form>

               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>
</body>
</html>