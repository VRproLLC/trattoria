$(document).ready(function () {
    //Маска
    $('input[type="tel"]').mask("+38 (999) 999 99 99");
    //Оправка форм
    //    $('form').submit(function (e) {
    //        e.preventDefault();
    //        var data = $(this).serialize(),
    //            btn = $(this).find('input[type="submit"]');
    //        btn.val('Загрузка...');
    //        btn.prop('disabled', true);
    //        $.ajax({
    //            type: 'POST',
    //            url: 'send.php',
    //            data: data,
    //            success: function (data) {
    //                swal({
    //                    type: 'success',
    //                    title: 'Заявка отправлена.',
    //                    text: 'Спасибо за обращение!'
    //                });
    //                btn.val('Отправлено');
    //                btn.prop('disabled', true);
    //                $('.close_btn').click();
    //            },
    //            error: function (data) {
    //                swal({
    //                    type: 'error',
    //                    title: 'Оправка не удалась!',
    //                    text: 'Что-то пошло не так :-('
    //                });
    //                btn.val('Повторить');
    //                btn.prop('disabled', false);
    //            }
    //        });
    //    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if ($('.time_step').attr("checked") == 'checked') {
        $('.show_time').slideDown(100);
    }

    //    Смена языка
    $('.current_lang').click(function () {
        $('.toggle_lang').slideToggle(100);
    })

    //Переключение табов
    $('.one_click').click(function () {
        if (!$(this).hasClass('active')) {
            if (!$(this).hasClass('maps_point')) {
                var numberScrolls = 280;
                if ($(this).parents('.other_page').hasClass('page_favorite')) {
                    numberScrolls = 80;
                }
                var thisIndex = $(this).index();
                $('.one_click').removeClass('active')
                $(this).addClass('active');
                $('.other_page').animate({
                    scrollTop: $('.one_tab_show').eq(thisIndex).position().top + numberScrolls
                }, 500);
            } else {
                var thisIndex = $(this).index();
                $('.one_click').removeClass('active')
                $(this).addClass('active');
                $('.one_tab_show.this_not_border').hide().eq(thisIndex).show();
            }
        }
    });

    $('body').on('click', '.prop_order_section .one_click', function () {
        var thisIndex = $(this).index();
        $('.one_click').removeClass('active')
        $(this).addClass('active');
        $('.left_order_props').animate({
            scrollTop: $('.one_tab_show').eq(thisIndex).position().top - 10
        }, 500);
    });

    //    Плюс и минус
    $('body').on('click', '.minus, .plus', function () {
        var thisButtons = $(this);
        var thisInput = thisButtons.siblings('input');
        var siblingsInput = +thisButtons.siblings('input').val();
        if (thisButtons.parents('.name_product_text_cart').length > 0 && !thisButtons.hasClass('devices_plus_minus')) {
            var link = thisButtons.parent().attr('data-link');
            var idProduct = thisButtons.parent().attr('data-id-product');
            if (thisButtons.hasClass('minus')) {
                var amount = siblingsInput - 1;
            } else {
                var amount = siblingsInput + 1;
            }

            $.ajax({
                type: 'POST',
                url: link,
                data: {
                    product_id: idProduct,
                    amount: amount
                },
                success: function (data) {
                    if (thisButtons.hasClass('minus')) {
                        amount = siblingsInput - 1;
                    } else {
                        amount = siblingsInput + 1;
                    }
                    if (amount == 0) {
                        thisButtons.parents('.one_product_block_cart').remove();
                    }
                    $('.red_count_product').text(data.total_amount);
                    $('.append_total_price').text(data.full_price + ' ₴');
                    thisInput.val(amount);

                    if ($('.one_product_block_cart').length == 1) {
                        document.location.reload();
                    }
                },
                error: function (data) {

                }
            });
        } else if (thisButtons.parents('form.serilize_first_form').length > 0) {
            var newPrice = 0;
            if ($(this).hasClass('minus')) {
                if (siblingsInput == 0) {
                    return;
                }
                siblingsInput--;
            } else {
                siblingsInput++;
            }
            $(this).siblings('input').val(siblingsInput);
            $(this).parents('.serilize_first_form').children('.line_props_product_info ').each(function () {
                var thisCount = +$(this).find('.wrap_plus_minus').children('input').val();
                var thisPrice = +$(this).find('.price_to_prop').text().split(' ')[0];
                console.log(thisCount);
                console.log(thisPrice);
                newPrice = newPrice + (thisCount * thisPrice);
            });
            $('.bottom_prop_order .price_to_prop').text(newPrice + ' ₴');

        } else {
            if ($(this).hasClass('minus')) {
                if (siblingsInput == 1) {
                    return;
                }
                siblingsInput--;
            } else {
                siblingsInput++;
            }
            $(this).siblings('input').val(siblingsInput);
        }
    });


    //Добавление в избранное
    $('body').on('click', '.link_like ', function (e) {
        e.preventDefault();
        var thisLink = $(this);
        var url = $(this).attr('href');
        var id = $(this).attr('data-id-product');
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                product_id: id,
            },
            success: function (data) {
                thisLink.toggleClass('active');
                if (!thisLink.hasClass('active') && thisLink.parents('.show_tab_click').hasClass('favorite_class')) {
                    thisLink.parents('.one_product_block').fadeOut(300, function () {
                        thisLink.parents('.one_product_block').remove();
                    });
                }
            },
            error: function (data) {
                if (data.status == 401) {
                    window.location.href = window.location.origin + "/login";
                }
                //                $('body').removeClass('onloadStyle');
            }
        })
    });
    var initialPoint;
    var finalPoint;
    //Выбор товара для добавления в корзину
    $('.click_product_image_subtrue').click(function () {

        if ($(this).parents('.one_product_block').hasClass('in_stop_list')) {

            return false;
        }
        window.location.hash = 'show_product';
        var checkHash = setInterval(function () {
            if (window.location.hash != '#show_product') {
                clearTimeout(checkHash);
                $('.append_product_section').removeClass('active');
                setTimeout(function () {
                    $('.append_product_section').children().remove();
                }, 300);
            }
        }, 500);
        $('body').addClass('onloadStyle');
        var idProduct = $(this).attr('data-product-id');
        $.ajax({
            type: 'GET',
            url: idProduct,
            data: {},
            success: function (data) {
                $('.append_product_section').html(data);
                document.addEventListener("backbutton", function (e) {
                    return false
                }, false);
                setTimeout(function () {
                    $('body').removeClass('onloadStyle');
                    $('.append_product_section').addClass('active');
                    $('.append_product_section.active')[0].addEventListener('touchstart', function (event) {
                        initialPoint = event.changedTouches[0];
                    }, false);
                    $('.append_product_section.active')[0].addEventListener('touchend', function (event) {
                        finalPoint = event.changedTouches[0];
                        var xAbs = Math.abs(initialPoint.pageX - finalPoint.pageX);
                        var yAbs = Math.abs(initialPoint.pageY - finalPoint.pageY);
                        if (xAbs > 20 || yAbs > 20) {
                            if (xAbs > yAbs) {
                                if (finalPoint.pageX < initialPoint.pageX) {
                                    //                                    console.log('СВАЙП ВЛЕВО')
                                } else {
                                    //                                    console.log('СВАЙП ВПРАВО')
                                }
                            } else {
                                if (finalPoint.pageY < initialPoint.pageY) {
                                    //                                    console.log('СВАЙП ВВЕРХ')
                                } else {
                                    window.history.back();
                                    $('.append_product_section').removeClass('active');
                                    setTimeout(function () {
                                        $('.append_product_section').children().remove();
                                    }, 300);
                                }
                            }
                        }
                    }, false);
                }, 300);
            },
            error: function (data) {
                $('body').removeClass('onloadStyle');
            }
        });
    });


    //    Добавление в корзину 1
    $('body').on('click', '.add_to_cart_button', function (e) {
        e.preventDefault();
        var pageX = e.pageX;
        var pageY = e.pageY;
        $('body').addClass('onloadStyle');
        var idProduct = $(this).attr('data-id-product');
        var count = $(this).siblings('.wrap_plus_minus').find('input').val();
        var comments = $(this).parents('.text_about_products').find('textarea').val();
        var link = $(this).attr('data-link');
        $.ajax({
            type: 'POST',
            url: link,
            data: {
                product_id: idProduct,
                amount: count,
                comment: comments
            },
            success: function (data) {
                var butWrap = $(this).parents('.add_to_cart_button');

                $('.red_count_product').text(data.total_amount);
                $('body').removeClass('onloadStyle');
                $('.append_product_section').removeClass('active');
                window.history.back();
                setTimeout(function () {
                    $('body').append('<div class="animtocart"></div>');
                    $('.animtocart').css({
                        'left': pageX - 50,
                        'top': pageY - 50,
                    });
                    var cart = $('.red_count_product').offset();
                    $('.animtocart').animate({
                        top: cart.top + 'px',
                        left: cart.left + 'px',
                        width: 0,
                        height: 0
                    }, 800, function () {
                        $(this).remove();
                    });
                    $('.append_product_section').children().remove();
                }, 300);
            },
            error: function (data) {
                if (data.status == 401) {
                    window.location.href = window.location.origin + "/login";
                }
                $('body').removeClass('onloadStyle');
            }
        });
    });


    //    Добавление в корзину 2
    $('body').on('click', '.new_add_buttons_to_cart', function (e) {
        if ($(this).parents('.one_product_block').hasClass('in_stop_list')) {
            return false;
        }
        e.preventDefault();
        var pageX = e.pageX;
        var pageY = e.pageY;
        $('body').addClass('onloadStyle');
        var idProduct = $(this).attr('data-product-id');
        var count = 1;
        var comments = 'Комментарий';
        var link = $(this).attr('data-link');
        $.ajax({
            type: 'POST',
            url: link,
            data: {
                product_id: idProduct,
                amount: count,
                //comment: comments
            },
            success: function (data) {
                var butWrap = $(this).parents('.add_to_cart_button');

                $('.red_count_product').text(data.total_amount);
                $('body').removeClass('onloadStyle');
                $('.append_product_section').removeClass('active');
                setTimeout(function () {
                    $('body').append('<div class="animtocart"></div>');
                    $('.animtocart').css({
                        'left': pageX - 25,
                        'top': pageY - 25,
                    });
                    var cart = $('.red_count_product').offset();
                    $('.animtocart').animate({
                        top: cart.top + 'px',
                        left: cart.left + 'px',
                        width: 0,
                        height: 0
                    }, 800, function () {
                        $(this).remove();
                    });
                    $('.append_product_section').children().remove();
                }, 300);
            },
            error: function (data) {
                if (data.status == 401) {
                    window.location.href = window.location.origin + "/login";
                }
                $('body').removeClass('onloadStyle');
            }
        });
    });


    $('body').on('click', '.gray_section_overlay', function (e) {
        var thisTarget = e.target.className;
        if (thisTarget == 'gray_section_overlay' || thisTarget == 'close_section_products') {
            $('.append_product_section').removeClass('active');
            window.history.back();
            setTimeout(function () {
                $('.append_product_section').children().remove();
            }, 400);
        }
    });
    //Показываем или скрываем блок с временем самовывоза
    $('.time_check input').change(function () {
        if ($(this).hasClass('time_step')) {
            $('.show_time').show(100);
            $('.show_time input').click();
        } else {
            $('.show_time').hide(100);
            $('.show_time').children('input').val('');
        }
    });

    $('.close_modal').click(function () {
        $('.modal_block').fadeOut(100, function () {
            $('.overlay').fadeOut(100);
        });
    });

    $('.append_addres_block a').click(function (e) {
        //        e.preventDefault();
        var thisDataCountProduct = +$('.red_count_product').text();
        var thisCurrentOrganiszation = $(this).attr('data-current_organization');
        var thisOrganization = $(this).attr('data-organization');
        //        console.log(thisDataCountProduct);
        if (thisCurrentOrganiszation.length > 0) {
            if (thisCurrentOrganiszation != thisOrganization) {
                if (thisDataCountProduct != 0) {
                    var thisHref = $(this).attr('href');
                    e.preventDefault();
                    $('.overlay').fadeIn(100, function () {
                        $('.modal_sure').fadeIn(100);
                    });
                    $('.button_ok_replace').click(function () {
                        window.location.href = thisHref;
                    });
                }
            }
        }
    });


    $('.search_form.form_dish input[type="text"]').on('input', function () {
        var text = $(this).val().toLowerCase();
        $(".name_product").each(function () {
            var $this = $(this);
            if ($this.text().toLowerCase().indexOf(text) === -1) {
                $this.parents('.one_product_block').hide();
                if (!$this.parents('.one_product_block').siblings('.one_tab_show ').is(":hidden")) {
                    $this.parents('.one_tab_show').hide();
                }
            } else {
                $this.parents('.one_product_block').show();
                if ($this.parents('.one_product_block').siblings().is(":hidden")) {
                    $this.parents('.one_tab_show').show();
                }
            }
        });
    });

    $('.search_form.form_search_addres input[type="text"]').on('input', function () {
        var text = $(this).val().toLowerCase();
        $(".name_address").each(function () {
            var $this = $(this);
            if ($this.text().toLowerCase().indexOf(text) === -1) {
                $this.parents('.search_parent_block').hide();
            } else {
                $this.parents('.search_parent_block').show();
            }
        });
    });
    var sections = $('.one_tab_show'),
        nav = $('.block_line_dish'),
        nav_height = nav.outerHeight();

    $('.other_page').scroll(function () {
        var cur_pos = $(this).scrollTop();
        var countScrollPx = 275;
        if ($(this).hasClass('page_favorite')) {
            countScrollPx = 75
        }
        if (cur_pos > 50) {
            $('.line_header').css({
                'paddingTop': '5px',
                'paddingBottom': '5px',
            });
            $('.content').css({
                'paddingTop': '46px',
            });
            if (cur_pos > countScrollPx) {
                $('.block_line_dish .tab_click').addClass('absolute');
                if ($('.block_line_dish .pseudo_dish').length == 0) {
                    $('.block_line_dish').prepend('<div class="pseudo_dish"></div>');
                }
            } else if ($(this).scrollTop() < countScrollPx) {
                $('.block_line_dish .tab_click').removeClass('absolute');
                $('.pseudo_dish').remove();
            }

        } else {
            $('.line_header').css({
                'paddingTop': '20px',
                'paddingBottom': '20px',
            });
            $('.content').css({
                'paddingTop': '75px',
            });
        }
        sections.each(function () {
            var top = $(this).position().top - ($(window).height() / 2),
                bottom = $(this).position().top + ($(window).height() / 2);

            if (cur_pos >= top && cur_pos <= bottom) {
                nav.find('.one_click').removeClass('active');
                nav.find('.one_click').eq($(this).index()).addClass('active');
            }
        });
        var col = $('.block_line_dish .one_click.active');
        var div = $('.block_line_dish .tab_click');
        if (col.length > 0) {
            div.scrollLeft(col.position().left + div.scrollLeft() - 15);
        }
    });


    $('.return a').click(function (e) {
        e.preventDefault();
        window.history.back();
    })

    //    Админка администратора буфета-----------------------------------------------------------------------------------------------------------------------=======================================================================-----------------------------


    // Enable pusher logging - don't include this in production
    if ($('.one_admin_block').length > 0) {
        Pusher.logToConsole = false;

        var pusher = new Pusher('4d9a7612d8126fd0ed61', {
            cluster: 'eu'
        });

        var channel = pusher.subscribe('my-channel');
        var datamessages = '';
        channel.bind('my-event', function (data) {

            // alert(JSON.stringify(data));
            if (data.message.action == 'update_wrapper') {
                datamessages = data.message;
                var thisAdminId = $('.text_admin_red').attr('data-action');
                $.ajax({
                    type: 'POST',
                    url: './content',
                    data: {
                        id: thisAdminId
                    },
                    success: function (data) {
                        $('.wrap_block_bufet').html(data);
                        if (datamessages.is_need_sound == true) {
//                            var audio = new Audio('../../or.mp3');
//                            audio.play();
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            }
        });
    }


    $('body').on('click', '.cancel_prop_order', function (e) {
        $('.modal_order_admin').fadeOut(100, function () {
            $('.overlay_modal').fadeOut(100);
        });
    });
    $('body').on('click', '.border_order_wrap', function (e) {
        e.preventDefault();
        var thisHref = $(this).attr('href');
        var thisId = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: thisHref,
            data: {
                id: thisId
            },
            success: function (data) {
                $('.append_about_order_block').html(data);
                $('.overlay_modal').fadeIn(100, function () {
                    $('.append_about_order_block').fadeIn(100);
                })
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    $('body').on('click', '.button_edit_order, .linkbutton_edit', function (e) {
        e.preventDefault();
        var thisHref = $(this).attr('data-action');
        var thisId = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: thisHref,
            data: {
                id: thisId
            },
            success: function (data) {
                $('.append_edit_order_block').html(data);
                $('.append_about_order_block').fadeOut(100, function () {
                    $('.append_edit_order_block').fadeIn(100);
                })
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    $('body').on('click', '.button_success_first, .success_change_status, .button_complete_order, .button_success_new', function (e) {
        e.preventDefault();
        var thisHref = $(this).attr('data-action');
        var thisId = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: thisHref,
            data: {
                id: thisId
            },
            success: function (data) {
                $('.modal_order_admin').fadeOut(100, function () {
                    $('.overlay_modal').fadeOut(100);
                });
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    $('body').on('click', '.button_success_second', function (e) {
        e.preventDefault();
        var thisHref = $(this).attr('data-action');
        var thisForm = $('.serilize_first_form').serialize();
        $.ajax({
            type: 'POST',
            url: thisHref,
            data: thisForm,
            success: function (data) {
                $('.modal_order_admin').fadeOut(100, function () {
                    $('.overlay_modal').fadeOut(100);
                });
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    $('body').on('click', '.button_add_modal_product', function (e) {
        e.preventDefault();
        var thisHref = $(this).attr('data-action');
        var thisOrganization = $('.text_admin_red').attr('data-action');
        var thisId = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: thisHref,
            data: {
                id: thisId,
                organization: thisOrganization
            },
            success: function (data) {
                $('.append_adds_order_block').html(data);
                $('.append_edit_order_block').fadeOut(100, function () {
                    $('.append_adds_order_block').fadeIn(100);
                })
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    $('body').on('click', '.button_success_third', function (e) {
        e.preventDefault();
        var thisHref = $(this).attr('data-action');
        var thisForm = $('.edit_to_form_admin').serialize();
        $.ajax({
            type: 'POST',
            url: thisHref,
            data: thisForm,
            success: function (data) {
                $.ajax({
                    type: 'POST',
                    url: data.href,
                    data: {
                        id: data.order_id
                    },
                    success: function (data) {
                        $('.append_edit_order_block').html(data);
                        $('.append_adds_order_block').fadeOut(100, function () {
                            $('.append_edit_order_block').fadeIn(100);
                        })
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    $('.read_orders_back').change(function () {
        var thisForm = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: window.location.origin + '/order/update',
            data: thisForm,
            success: function (data) {
                console.log(data);
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    var flagsend = true;
    $('.submit_checkout_button').click(function () {
        if ($(this).parents('form').hasClass('read_orders_back')) {
            //            $(this).prop('disabled', true);
            $(this).addClass('disableds');
            $('form.read_orders_back').submit();
        }
    })

    $('.delete_prod').click(function () {


        var idProduct = $(this).prev().prev().attr('data-id-product');
        var count = $(this).siblings('.wrap_plus_minus').find('input').val();
        var comments = $(this).parents('.text_about_products').find('textarea').val();
        var link = $(this).prev().prev().attr('data-link');

        $(this).prev().prev().find('input').val(0);

        $.ajax({
            type: 'POST',
            url: link,
            data: {
                product_id: idProduct,
                amount: 0
            },
            success: function (data) {


                document.location.reload();

            },
            error: function (data) {

            }
        });
    });

    $('.add_comment_field').click(function () {
        $('.comment_adds_product_to_cart').addClass('has_comment');
        $(this).parents('.comment_adds_product_to_cart').children('textarea').focus();
    });
    $('.comment_adds_product_to_cart textarea').focus(function () {
        $(this).parent('.comment_adds_product_to_cart').addClass('has_comment');
    });

    $('.comment_adds_product_to_cart textarea').focusout(function () {
        $.ajax({
            type: 'POST',
            url: window.location.origin + '/order/comment',
            data: {
                id: $(this).parents('.comment_adds_product_to_cart').children('textarea').data('id'),
                comment: $(this).parents('.comment_adds_product_to_cart').children('textarea').val()
            },
            success: function (data) {
                console.log(data);
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    $('.remove_comment_field').click(function () {
        $(this).parents('.comment_adds_product_to_cart').removeClass('has_comment');
        $(this).parents('.comment_adds_product_to_cart').children('textarea').val('')

        $.ajax({
            type: 'POST',
            url: window.location.origin + '/order/comment',
            data: {
                id: $(this).parents('.comment_adds_product_to_cart').children('textarea').data('id'),
                comment: '',
            },
            success: function (data) {
                console.log(data);
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    $('.edit_comment_field').click(function () {
        $(this).parents('.comment_adds_product_to_cart').children('textarea').focus();
    });


    //    Запись имени юзера после ухода фокуса
    $('.mini_submit_form').focusout(function () {
        let thisForm = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '/dashboard/rename',
            data: thisForm,
            success: function (data) {
                console.log(data)
            },
            error: function (data) {
                console.log(data)
            }
        });
    });

    $('body').on('click', '.close_modal_prop_orders, .close_modal_button_new', function (e) {
        $('.modal_order_admin').fadeOut(100, function () {
            $('.overlay_modal').fadeOut(100);
        });
    });

    //    Отмена заказа
    $('body').on('click', '.red_button_cancel, .remove_button', function () {
        let thisOrderId = $(this).attr('data-orderId');

        $.ajax({
            type: 'POST',
            url: '/admin/dashboard/remove_order',
            data: {
                id: thisOrderId
            },
            success: function (data) {
                let counter = parseInt($('#counter').text());

                $('#counter').html(counter - 1);

                $('.order_' + thisOrderId).remove();

                $('.modal_order_admin').fadeOut(100, function () {
                    $('.overlay_modal').fadeOut(100);
                });
            },
            error: function (data) {
                console.log(data)
            }
        });
    });


    //    Отмена нового заказа пользователем (/resources/views/pages/account/index.blade.php)
    $('body').on('click', '.red_remove_new_order', function () {
        let thisButton = $(this);
        let thisOrderId = thisButton.attr('data-orderId');

        $.ajax({
            type: 'POST',
            url: '/order/cancellation',
            data: {
                id: thisOrderId
            },
            success: function (data) {
                if(data.status === 1) {
                    thisButton.parents('.actual_data').remove();
                } else window.location.reload();
            },
            error: function (data) {
                console.log(data)
            }
        });
    });

    //   Удаление заказа из корзины (/resources/views/pages/order/index.blade.php)
    $('.red_remove_new_order_cart').click(function (e) {
        e.preventDefault();

        let thisForm = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: '/order/basket',
            data: thisForm,
            success: function (data) {
                location.reload();
            },
            error: function (data) {
            }
        });
    });

    let paginationId = 0;

    $('.button_archive').click(function () {
        let orderId = $(this).attr('data-id');
        let pageId = $(this).attr('data-page-id');

        paginationId = orderId;

        $.ajax({
            type: 'POST',
            url: '/admin/dashboard/getArchive',
            data: {
                id: orderId,
                page: pageId ?? 1
            },
            success: function (data) {
                $('.append_archive_result').html(data);
                $('.overlay_modal').fadeIn(100, function () {
                    $('.append_archive_result').fadeIn(100);
                });
            },
            error: function (data) {
                console.log(data)
            }
        });
    })

    $('body').on('submit', '.next_page_form', function (e) {
        e.preventDefault();

        let pageId = $("input[name='page']", this).val();

        if (pageId > 0) {
            $.ajax({
                type: 'POST',
                url: '/admin/dashboard/getArchive',
                data: {
                    id: paginationId,
                    page: pageId ?? 1
                },
                success: function (data) {
                    if (data.status === 0) {
                        return;
                    }
                    $('.append_archive_result').html(data);
                },
                error: function (data) {
                    console.log(data)
                }
            });
        }
    });

    $('body').on('click', '.button_archive_pagination', function (e) {
        let pageId = $(this).attr('data-id-page');

        $.ajax({
            type: 'POST',
            url: '/admin/dashboard/getArchive',
            data: {
                id: paginationId,
                page: pageId ?? 1
            },
            success: function (data) {
                $('.append_archive_result').html(data);
            },
            error: function (data) {
                console.log(data)
            }
        });
    })

    $('body').on('click', '.one_click_section_archive', function (e) {

        let orderId = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: '/admin/dashboard/archive',
            data: {
                id: orderId,
            },
            success: function (data) {
                $('.append_search_result').html(data);
                $('.overlay_modal').fadeIn(100, function () {
                    $('.append_archive_result').fadeOut(100, function(){
                        $('.append_search_result').fadeIn(100);
                    })
                })

                $('.archive_modal_close').click(function () {
                    $('.append_search_result').fadeOut(100, function(){
                        $('.append_archive_result').fadeIn(100);
                    })
                });

            },
            error: function (data) {

            }
        });
    });
    $('.remove_account').click(function (e) {
        Swal.fire({
            title: $(this).data('title'),
            text: $(this).data('text'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: $(this).data('confirm'),
            cancelButtonText: $(this).data('cancel'),
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: '/dashboard/remove',
                    data: {},
                    success: function (data) {
                        if(data.status === 1) {
                            window.location.href = '/';
                        }
                    }
                });
            }
        })
    });

    $('input[name="is_delivery"]').click(function(){
        if($(this).val() == 1){
            $('.show_hide_field_addr').slideDown(100);
        } else {
            $('.show_hide_field_addr').slideUp(100);
        }
    })

    $('input[name="payment_type"]').click(function(){
        if($(this).data('pay') === 'FONDY'){
            $('.show_hide_field_send').slideUp(100);
            $('.show_hide_field_send_pay').slideDown(100);
        } else {
            $('.show_hide_field_send').slideDown(100);
            $('.show_hide_field_send_pay').slideUp(100);
        }
    })
});
