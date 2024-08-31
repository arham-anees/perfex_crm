<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
    .btn-primary-custom {
        padding: 8px 12px;
        font-size: 14px;
        color: #fff;
        background-color: #2563EB;
        border: none;
        border-radius: 4px;
        text-align: center;
        text-decoration: none;
        margin-left: 80%;
    }

    h1 {
        text-align: center;
    }

    .table {
        width: 90%;
    }
</style>

<div class="row main_row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <?php if (is_client_logged_in()) { ?>
                    <h1>Cart Details</h1>

                    <div class="cart-details">
                        <?php if ($cart_prospects): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered dt-table" id="cart-table">
                                    <thead>
                                        <tr>
                                            <th><?= _l('id') ?></th>
                                            <th><?= _l('name') ?></th>
                                            <th>Last Name</th>
                                            <th><?= _l('leadevo_email') ?></th>
                                            <th><?= _l('leadevo_phone') ?></th>
                                            <th><?= _l('leadevo_price') ?></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cart_prospects as $prospect): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($prospect['prospect_id']); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['first_name']); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['email']); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['phone']); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['desired_amount']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p>Your cart is empty.</p>
                        <?php endif; ?>
                    </div>
                    <a href="<?php echo site_url('cart/checkout/'); ?>" class="btn-primary-custom">Checkout</a>
                <?php } else { ?>
                    <p>You need to be logged in to view this page.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Fetch CSRF token from meta tags
        $('.btn-primary-custom').on('click', function (e) {
            e.preventDefault(); // Prevent the default link behavior
            if (confirm('Are you sure you want to proceed with checkout?')) {
                $.ajax({
                    url: '<?php echo site_url('cart/checkout/'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {

                        'csrf_token_name': '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            // Handle success (e.g., show a message or redirect)
                            alert('Checkout successful! Invoice ID: ' + response.data);
                            // Optionally, redirect to another page
                            window.location.href = '<?php echo site_url('clients/billing'); ?>';
                        } else {
                            // Handle error
                            alert('Error: ' + (response.message || 'Something went wrong.'));
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        alert('Error occurred while processing the request.');
                    }
                });
            }
        });
    });

    $('#cart-table').dataTable();
</script>