<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
$statuses = get_statuses();
?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
               <?php if (!empty($statuses)): ?>
                    <ul>
                        <?php foreach ($statuses as $status): ?>
                            <li class="row" style="border-bottom:1px solid grey">
                        <div class="col-lg-10">    <?php echo $status['name']; ?></div>
                        <div class="col-lg-2">
                        <form method="POST" action="statuses/delete">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="status_id" value="<?php echo $status['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                        </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No statuses found.</p>
                <?php endif; ?>

                <form method="POST" action="statuses/create">
                    <!-- Include CSRF Token -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    
                    <input type="text" class="form-control" name="name" placeholder="Enter Name" id="appointment_status_name" required />
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