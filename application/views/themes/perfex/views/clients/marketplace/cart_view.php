<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
   .btn-primary-custom {
    display: inline-block;
    padding: 6px 12px; 
    font-size: 14px; 
    color: #fff;
    background-color: #2563EB; 
    border: none;
    border-radius: 4px;
    text-align: center;
    text-decoration: none;
}

</style>

<?php if (is_client_logged_in()) { ?>
    <div class="container">
        <h1>Cart Details</h1>

        <div class="cart-details">
            <?php if ($cart_prospects): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Desired Amount</th>
                            <th>Action</th>
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
                                <td>
    <a href="<?php echo site_url('cart/checkout/'); ?>" class="btn-primary-custom">Checkout</a>
</td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>
<?php } else { ?>
    <p>You need to be logged in to view this page.</p>
<?php } ?>
