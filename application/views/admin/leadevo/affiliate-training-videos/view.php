
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .card {
    background-color: rgb(240, 240, 241);
    color: rgba(0, 0, 0, 0.87);
    transition: box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
    box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 1px -1px, rgba(0, 0, 0, 0.14) 0px 1px 1px 0px, rgba(0, 0, 0, 0.12) 0px 1px 3px 0px;
    border-radius: 20px;
    overflow: hidden;
    padding: 16px;
    margin: 10px 0;
    }
    .card-title {
        font-size: 2rem;
        font-weight: 500;
        color: rgba(0, 0, 0, 0.87);
        margin-bottom: 1rem;
    }
    .card-text {
        font-size: 1.5rem;
        color: rgba(0, 0, 0, 0.87);
        margin-bottom: 1rem;
    }

    .card-img-top {
        border-radius: 20px;
        width: 100%;
        height: 300px;
    }
    .card-body {
        padding: 16px;
    }




</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
 
                        <hr class="my-4">
                        <div class="container">
                        <div class="card p-3">

                        <div class="row">
                            <div class="col-md-6">
                                <iframe class="card-img-top" src="<?php echo htmlspecialchars($video['url']??'N/A'); ?>" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="col-md-6">
                                <h3 class="card-title">Video ID: <?php echo htmlspecialchars($video['id']??'N/A'); ?></h3>
                                <p class="card-text">Title: <?php echo htmlspecialchars($video['name']??'N/A'); ?></p>
                                <p class="card-text">Description: <?php echo htmlspecialchars($video['description']??'N/A'); ?></p>
                            </div>
                        </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                            <a href="<?php echo admin_url('affiliate_training_videos'); ?>" class="btn btn-default">
                                <?php echo _l('Back to videos'); ?>
                            </a>
                            </div>
                        </div>
                </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
</body>
</html>


