<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
    .fa-cart-shopping {
        font-size: 25px;
        margin-top: 5px;
    }

    .navbar-nav .fa-cart-shopping {
        font-size: 25px;
        margin-top: 5px;
        position: relative;
    }

    /* Badge styling */
    .navbar-nav .badge {
        position: absolute;
        top: 2px;
        right: -8px;
        background-color: #ff0000;
        color: #ffffff;
        font-size: 11px;
        padding: 4px 6px;
        border-radius: 50%;
        font-weight: bold;
        display: inline-block;
    }


    .dropdown-menu .table {
        margin: 0;
        border-collapse: collapse;
    }

    .dropdown-menu .table th,
    .dropdown-menu .table td {
        padding: 10px;
        text-align: left;
    }

    .dropdown-menu .table thead {
        background-color: #f8f9fa;
    }

    .dropdown-menu .table tbody tr:nth-child(odd) {
        background-color: #f1f1f1;
    }

    .dropdown-menu .table tbody tr:hover {
        background-color: #e2e6ea;
    }

    .text-center {
        font-size: 14px;
        padding: 10px;
    }
    .cart{
        text-align: center;
    }
    .primary{
        text-align: center;
    }
    .see_all{
        text-align: center !important;
    }
 


</style>
<?php if (is_client_logged_in()) { ?>
    <div id="header">
        <div class="hide-menu tw-ml-1"><i class="fa fa-align-left"></i></div>

        <nav>
            <div class="tw-flex tw-justify-between">
                <div class="tw-flex tw-flex-1 sm:tw-flex-initial">

                    <?php if (is_client_logged_in()) { ?>
                        <div id="top_search"
                            class="tw-inline-flex tw-relative dropdown sm:tw-ml-1.5 sm:tw-mr-3 tw-max-w-xl tw-flex-auto"
                            data-toggle="tooltip" data-placement="bottom" data-title="<?php echo _l('search_by_tags'); ?>">
                            <input type="search" id="search_input"
                                class="tw-px-4 tw-ml-1 tw-mt-2.5 focus:!tw-ring-0 tw-w-full !tw-placeholder-neutral-400 !tw-shadow-none tw-text-neutral-800 focus:!tw-placeholder-neutral-600 hover:!tw-placeholder-neutral-600 sm:tw-w-[400px] tw-h-[40px] tw-bg-neutral-300/30 hover:tw-bg-neutral-300/50 !tw-border-0"
                                placeholder="<?php echo _l('top_search_placeholder'); ?>" autocomplete="off">
                            <div id="top_search_button" class="tw-absolute rtl:tw-left-0 -tw-mt-2 tw-top-1.5 ltr:tw-right-1">
                                <button class="tw-outline-none tw-border-0 tw-text-neutral-600">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                            <div id="search_results">
                            </div>
                            <ul class="dropdown-menu search-results animated fadeIn search-history" id="search-history">
                            </ul>
                        <?php } ?>
                        </div>
                        <ul class="nav navbar-nav visible-md visible-lg">
                            <?php
                            $quickActions = collect($this->app->get_quick_actions_links())->reject(function ($action) {
                                return isset($action['permission']) && staff_cant('create', $action['permission']);
                            });
                            ?>
                            <?php if ($quickActions->isNotEmpty()) { ?>
                                <li class="icon tw-relative ltr:tw-mr-1.5 rtl:tw-ml-1.5" title="<?php echo _l('quick_create'); ?>"
                                    data-toggle="tooltip" data-placement="bottom">
                                    <a href="#" class="!tw-px-0 tw-group !tw-text-white" data-toggle="dropdown">
                                        <span
                                            class="tw-rounded-full tw-bg-primary-600 tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-7 tw-w-7 -tw-mt-1 group-hover:!tw-bg-primary-700">
                                            <i class="fa-regular fa-plus fa-lg"></i>
                                        </span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right animated fadeIn tw-text-base">
                                        <li class="dropdown-header tw-mb-1">
                                            <?php echo _l('quick_create'); ?>
                                        </li>
                                        <?php foreach ($quickActions as $key => $item) {
                                            $url = '';
                                            if (isset($item['permission'])) {
                                                if (staff_cant('create', $item['permission'])) {
                                                    continue;
                                                }
                                            }
                                            if (isset($item['custom_url'])) {
                                                $url = $item['url'];
                                            } else {
                                                $url = admin_url('' . $item['url']);
                                            }
                                            $href_attributes = '';
                                            if (isset($item['href_attributes'])) {
                                                foreach ($item['href_attributes'] as $key => $val) {
                                                    $href_attributes .= $key . '=' . '"' . $val . '"';
                                                }
                                            } ?>
                                            <li>
                                                <a href="<?php echo e($url); ?>" <?php echo $href_attributes; ?>
                                                    class="tw-group tw-inline-flex tw-space-x-0.5 tw-text-neutral-700">
                                                    <?php if (isset($item['icon'])) { ?>
                                                        <i
                                                            class="<?php echo e($item['icon']); ?> tw-text-neutral-400 group-hover:tw-text-neutral-600 tw-h-5 tw-w-5"></i>
                                                    <?php } ?>
                                                    <span>
                                                        <?php echo e($item['name']); ?>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php
                                        } ?>
                                    </ul>
                                </li>
                            <?php } ?>
                        </ul>
                </div>

                <div class="mobile-menu tw-shrink-0 ltr:tw-ml-4 rtl:tw-mr-4">
                    <button type="button"
                        class="navbar-toggle visible-md visible-sm visible-xs mobile-menu-toggle collapsed tw-ml-1.5"
                        data-toggle="collapse" data-target="#mobile-collapse" aria-expanded="false">
                        <i class="fa fa-chevron-down fa-lg"></i>
                    </button>
                    <ul class="mobile-icon-menu tw-inline-flex tw-mt-5">

                    </ul>
                    <div class="mobile-navbar collapse" id="mobile-collapse" aria-expanded="false" style="height: 0px;"
                        role="navigation">
                        <ul class="nav navbar-nav">
                            <li class="header-my-profile"><a href="<?php echo admin_url('profile'); ?>">
                                    <?php echo _l('nav_my_profile'); ?>
                                </a>
                            </li>
                            <li class="header-my-timesheets"><a href="<?php echo admin_url('staff/timesheets'); ?>">
                                    <?php echo _l('my_timesheets'); ?>
                                </a>
                            </li>
                            <li class="header-edit-profile"><a href="<?php echo admin_url('staff/edit_profile'); ?>">
                                    <?php echo _l('nav_edit_profile'); ?>
                                </a>
                            </li>
                            <li class="header-logout">
                                <a href="#" onclick="logout(); return false;">
                                    <?php echo _l('nav_logout'); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <ul class="nav navbar-nav navbar-right">
                    <?php hooks()->do_action('client_navbar_start'); ?>

                    <!-- cart -->
                    <?php
                    if (is_client_logged_in()) {
                        $cart_count = count($cart_prospects);
                    ?>
                        <?php if (is_client_logged_in()) { ?>
                            
                            <ul class="nav navbar-nav navbar-right">
                           
                                <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-cart-shopping"></i>
                                        <?php if ($cart_count > 0) { ?>
                                            <span class="badge"><?php echo $cart_count; ?></span>
                                        <?php } ?>
                                    </a>
                                    <ul class="dropdown-menu animated fadeIn">
                                    <h2 class="cart">Cart</h2>
                                        <?php if ($cart_count > 0) { ?>
                                            <li>
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Desired amount</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($cart_prospects as $prospect): ?>
                                                            
                                                            <tr>
                                                                <td>
                                                                        <?php echo htmlspecialchars($prospect['first_name']); ?>
                                                                   </td>
                                                                <td><?php echo htmlspecialchars($prospect['email']); ?></td>
                                                                <td><?php  echo htmlspecialchars($prospect['desired_amount']); ?></td>
                                                                <td>
                                                                    <a href="javascript:void(0);" class="text-danger" onclick="deleteItem(<?php echo $prospect['prospect_id']; ?>)">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>

                                                                </td>
                                                            </tr>
                                                           
                                                        <?php endforeach; ?>
                                                        <tr>
                                                        <tr>
    <td class="see_all" colspan="4"> 
        
            <a href="<?php echo site_url('marketplace/cart_view/'); ?>" class="text-primary primary" >See all</a>
        
    </td>
</tr>


                                                            </tr>
                                                    </tbody>
                                                </table>
                                            </li>
                                        <?php } else { ?>
                                            <li class="text-center">
                                                <p>Cart is empty</p>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            </ul>
                        <?php } ?>

                        <?php if (is_client_logged_in()) { ?>
                            <li class="dropdown customers-nav-item-profile">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                    aria-expanded="false">
                                    <img src="<?php echo e(contact_profile_image_url($contact->id, 'thumb')); ?>"
                                        data-toggle="tooltip"
                                        data-title="<?php echo e($contact->firstname . ' ' . $contact->lastname); ?>"
                                        data-placement="bottom" class="client-profile-image-small">
                                </a>
                                <ul class="dropdown-menu animated fadeIn">
                                    <li class="customers-nav-item-edit-profile">
                                        <a href="<?php echo site_url('clients/profile'); ?>">
                                            <?php echo _l('clients_nav_profile'); ?>
                                        </a>
                                    </li>
                                    <?php if ($contact->is_primary == 1) { ?>
                                        <?php if (can_loggged_in_user_manage_contacts()) { ?>
                                            <li class="customers-nav-item-edit-profile">
                                                <a href="<?php echo site_url('contacts'); ?>">
                                                    <?php echo _l('clients_nav_contacts'); ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <li class="customers-nav-item-company-info">
                                            <a href="<?php echo site_url('clients/company'); ?>">
                                                <?php echo _l('client_company_info'); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (can_logged_in_contact_update_credit_card()) { ?>
                                        <li class="customers-nav-item-stripe-card">
                                            <a href="<?php echo site_url('clients/credit_card'); ?>">
                                                <?php echo _l('credit_card'); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (is_gdpr() && get_option('show_gdpr_in_customers_menu') == '1') { ?>
                                        <li class="customers-nav-item-announcements">
                                            <a href="<?php echo site_url('clients/gdpr'); ?>">
                                                <?php echo _l('gdpr_short'); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li class="customers-nav-item-announcements">
                                        <a href="<?php echo site_url('clients/announcements'); ?>">
                                            <?php echo _l('announcements'); ?>
                                            <?php if ($total_undismissed_announcements != 0) { ?>
                                                <span class="badge"><?php echo e($total_undismissed_announcements); ?></span>
                                            <?php } ?>
                                        </a>
                                    </li>
                                    <?php if (!is_language_disabled()) {
                                    ?>
                                        <li class="dropdown-submenu pull-left customers-nav-item-languages">
                                            <a href="#" tabindex="-1">
                                                <?php echo _l('language'); ?>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-left">
                                                <li class="<?php if (get_contact_language() == '') {
                                                                echo 'active';
                                                            } ?>">
                                                    <a href="<?php echo site_url('clients/change_language'); ?>">
                                                        <?php echo _l('system_default_string'); ?>
                                                    </a>
                                                </li>
                                                <?php foreach ($this->app->get_available_languages() as $user_lang) { ?>
                                                    <li <?php if (get_contact_language() == $user_lang) {
                                                            echo 'class="active"';
                                                        } ?>>
                                                        <a href="<?php echo site_url('clients/change_language/' . $user_lang); ?>">
                                                            <?php echo e(ucfirst($user_lang)); ?>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php
                                    } ?>
                                    <li class="customers-nav-item-logout">c
                                        <a href="<?php echo site_url('authentication/logout'); ?>">
                                            <?php echo _l('clients_nav_logout'); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                </ul>
                            </li>

                            <?php hooks()->do_action('client_navbar_end'); ?>
                </ul>
            </div>
        </nav>
    </div>
<?php }
                } ?>


<script>
    function deleteItem(prospectId) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                url: '<?php echo site_url('marketplace/delete_from_cart/'); ?>' + prospectId,
                type: 'POST',

                data: {
                    'prospect_id': prospectId,
                    'csrf_token_name': '<?php echo $this->security->get_csrf_hash(); ?>'
                },

                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status === 'success') {
                        // Remove the item from the UI
                        $('a[onclick="deleteItem(' + prospectId + ')"]').closest('tr').remove();

                        // Update the cart count badge
                        var currentCount = parseInt($('.badge').text(), 10);
                        if (currentCount > 1) {
                            $('.badge').text(currentCount - 1);
                        } else {
                            $('.badge').text('');
                            $('.dropdown-menu .table').html('<tbody><tr><td colspan="5" class="text-center">Cart is empty</td></tr></tbody>');
                        }

                        // Re-enable the "Add to Cart" button
                        $('#add-to-cart-btn-' + prospectId).prop('disabled', false).val('Add to cart');
                    } else {
                        alert('Failed to delete the item. Please try again.');
                    }
                },
                error: function() {
                    alert('Error occurred while deleting the item.');
                }
            });
        }
    }
</script>