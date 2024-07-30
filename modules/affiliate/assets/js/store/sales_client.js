(function() {
    "use strict";
    $('.add_cart, .added').click(function() {
        var qtys = $(this).closest('.add-cart').find('.qty').val(),
            ids, w_quantity;
        w_quantity = $(this).closest('.add-cart').find('.qty').data('w_quantity');
        ids = $(this).data('id');
        var cart_id_list = getCookie('cart_id_list'),
            cart_qty_list;
        if (typeof cart_id_list != "") {
            if (cart_id_list.trim()) {
                var id_list = JSON.parse('[' + cart_id_list + ']');
                cart_qty_list = getCookie('cart_qty_list');
                var qty_list = JSON.parse('[' + cart_qty_list + ']');
                var index_id = -1;
                $.each(id_list, function(key, value) {
                    if (value == ids) {
                        index_id = key;
                    }
                });
                if (index_id == -1) {
                    if (ids != '' && qtys != '') {
                        id_list.push(ids);
                        qty_list.push(qtys);
                        add_to_cart(id_list, qty_list);
                        $('#alert_add').modal('show');
                        $('.add_success').removeClass('hide');
                        $('.add_error').addClass('hide');
                        setTimeout(function() {
                            $('#alert_add').modal('hide');
                        }, 1000);
                    }
                } else {
                    var new_list_qty = [];
                    var enoungh = 1;
                    $.each(qty_list, function(key, value) {
                        if (index_id == key) {
                            var temp_qty = 0;
                            if (qtys != '') {
                                temp_qty = parseInt(value) + parseInt(qtys);
                                if (temp_qty > w_quantity) {
                                    enoungh = 0;
                                    temp_qty = w_quantity;
                                }
                                new_list_qty.push(temp_qty);
                            } else {
                                temp_qty = parseInt(value) + 1;
                                if (temp_qty > w_quantity) {
                                    enoungh = 0;
                                    temp_qty = w_quantity;
                                }
                                new_list_qty.push(temp_qty);
                            }
                        } else {
                            new_list_qty.push(value);
                        }
                    });
                    add_to_cart(id_list, new_list_qty);
                    if (enoungh == 0) {
                        $('#alert_add').modal('show');
                        $('.add_success').addClass('hide');
                        $('.add_error').removeClass('hide');
                        setTimeout(function() {
                            $('#alert_add').modal('hide');
                        }, 1000);
                    } else {
                        $('#alert_add').modal('show');
                        $('.add_success').removeClass('hide');
                        $('.add_error').addClass('hide');
                        setTimeout(function() {
                            $('#alert_add').modal('hide');
                        }, 1000);
                    }
                }
            } else {
                var id_list = [ids];
                var qtys_list = [qtys];
                add_to_cart(id_list, qtys_list);
                $('#alert_add').modal('show');
                $('.add_success').removeClass('hide');
                $('.add_error').addClass('hide');
                setTimeout(function() {
                    $('#alert_add').modal('hide');
                }, 1000);
            }
        }
        count_product_cart();
        $(this).closest('.add-cart').find('.added').removeClass('hide');
        $(this).closest('.add-cart').find('.add_cart').addClass('hide');
        var list_obj = $('.line_data'),
            count_line, sub_total = 0;
        count_line = list_obj.length;
        for (var i = 0; i < count_line; i++) {
            sub_total += list_obj.eq(i).data('price') * list_obj.eq(i).val();
        }
        $('.subtotal').text(numberWithCommas(sub_total) + '.00');
        $('.total').text(numberWithCommas(sub_total) + '.00');
    });
    $(window).on('load', function() {
        count_product_cart();
    });
    $('.btn_page').click(function() {
        $('.btn_page').removeClass('active');
        $(this).addClass('active');
        $('.product_list').html('');
        var page = $(this).data('page');
        var member_code = $(this).data('member_code');
        var group_id = $('input[name="group_id"]').val();
        var keyword = $('input[name="keyword"]').val();
        if (keyword != '') {
            keyword = '/' + keyword;
        }
        ChangeUrlWithIndex(page, group_id);
        if (page != '') {
            $.get(site_url + 'affiliate/store/get_product_by_group/' + member_code + '/' + page + '/' + group_id + '/0' + keyword).done(function(response) {
                response = JSON.parse(response);
                $('.product_list').html(response.data);
                $('.add_cart, .added').click(function() {
                    var qtys = $(this).closest('.add-cart').find('.qty').val(),
                        ids, w_quantity;
                    w_quantity = $(this).closest('.add-cart').find('.qty').data('w_quantity');
                    ids = $(this).data('id');
                    var cart_id_list = getCookie('cart_id_list'),
                        cart_qty_list;
                    if (typeof cart_id_list != "") {
                        if (cart_id_list.trim()) {
                            var id_list = JSON.parse('[' + cart_id_list + ']');
                            cart_qty_list = getCookie('cart_qty_list');
                            var qty_list = JSON.parse('[' + cart_qty_list + ']');
                            var index_id = -1;
                            $.each(id_list, function(key, value) {
                                if (value == ids) {
                                    index_id = key;
                                }
                            });
                            if (index_id == -1) {
                                if (ids != '' && qtys != '') {
                                    id_list.push(ids);
                                    qty_list.push(qtys);
                                    add_to_cart(id_list, qty_list);
                                    $('#alert_add').modal('show');
                                    $('.add_success').removeClass('hide');
                                    $('.add_error').addClass('hide');
                                    setTimeout(function() {
                                        $('#alert_add').modal('hide');
                                    }, 1000);
                                }
                            } else {
                                var new_list_qty = [];
                                var enoungh = 1;
                                $.each(qty_list, function(key, value) {
                                    if (index_id == key) {
                                        var temp_qty = 0;
                                        if (qtys != '') {
                                            temp_qty = parseInt(value) + parseInt(qtys);
                                            if (temp_qty > w_quantity) {
                                                enoungh = 0;
                                                temp_qty = w_quantity;
                                            }
                                            new_list_qty.push(temp_qty);
                                        } else {
                                            temp_qty = parseInt(value) + 1;
                                            if (temp_qty > w_quantity) {
                                                enoungh = 0;
                                                temp_qty = w_quantity;
                                            }
                                            new_list_qty.push(temp_qty);
                                        }
                                    } else {
                                        new_list_qty.push(value);
                                    }
                                });
                                add_to_cart(id_list, new_list_qty);
                                if (enoungh == 0) {
                                    $('#alert_add').modal('show');
                                    $('.add_success').addClass('hide');
                                    $('.add_error').removeClass('hide');
                                    setTimeout(function() {
                                        $('#alert_add').modal('hide');
                                    }, 1000);
                                } else {
                                    $('#alert_add').modal('show');
                                    $('.add_success').removeClass('hide');
                                    $('.add_error').addClass('hide');
                                    setTimeout(function() {
                                        $('#alert_add').modal('hide');
                                    }, 1000);
                                }
                            }
                        } else {
                            var id_list = [ids];
                            var qtys_list = [qtys];
                            add_to_cart(id_list, qtys_list);
                            $('#alert_add').modal('show');
                            $('.add_success').removeClass('hide');
                            $('.add_error').addClass('hide');
                            setTimeout(function() {
                                $('#alert_add').modal('hide');
                            }, 1000);
                        }
                    }
                    count_product_cart();
                    $(this).closest('.add-cart').find('.added').removeClass('hide');
                    $(this).closest('.add-cart').find('.add_cart').addClass('hide');
                    var list_obj = $('.line_data'),
                        count_line, sub_total = 0;
                    count_line = list_obj.length;
                    for (var i = 0; i < count_line; i++) {
                        sub_total += list_obj.eq(i).data('price') * list_obj.eq(i).val();
                    }
                    $('.subtotal').text(numberWithCommas(sub_total) + '.00');
                    $('.total').text(numberWithCommas(sub_total) + '.00');
                });
            });
        }
    });
})(jQuery);

function ChangeUrlWithIndex(page, group_id) {
    "use strict";
    var url = window.location.href,
        url = url.split("/");
    var keyword = $('input[name="keyword"]').val();
    if (keyword != '') {
        keyword = '/' + keyword;
    }
    url = url[0] + '/' + url[1] + '/' + url[2] + '/' + url[3] + '/' + url[4] + '/' + url[5] + '/' + url[6] + '/' + page + '/' + group_id + '/0' + keyword;
    window.history.pushState({}, document.title, url);
}

function add_to_cart(cart_id_list, cart_qty_list) {
    "use strict";
    add_cookie('cart_id_list', cart_id_list, 30);
    add_cookie('cart_qty_list', cart_qty_list, 30);
}

function add_cookie(cname, cvalue, exdays) {
    "use strict";
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    "use strict";
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function count_product_cart() {
    "use strict";
    var cart_qty_list = getCookie('cart_qty_list'),
        count = 0;
    if (cart_qty_list.trim()) {
        var qty_list = JSON.parse('[' + cart_qty_list + ']');
        $.each(qty_list, function(key, value) {
            count += value;
        });
    }
    if (count > 0) {
        $('.qty_total').text(count).fadeIn(500);
    } else {
        $('.qty_total').text('').fadeOut(500);
    }
}

function numberWithCommas(x) {
    "use strict";
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}