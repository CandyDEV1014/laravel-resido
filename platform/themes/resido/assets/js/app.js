const { isBuffer } = require("lodash");

$(function () {
    "use strict";
    const lazyLoad = new LazyLoad();
    let isRTL = $('body').prop('dir') === 'rtl';

    var showError = message => {
        window.showAlert('alert-danger', message);
    }

    var showSuccess = message => {
        window.showAlert('alert-success', message);
    }

    var handleError = data => {
        if (typeof (data.errors) !== 'undefined' && data.errors.length) {
            handleValidationError(data.errors);
        } else if (typeof (data.responseJSON) !== 'undefined') {
            if (typeof (data.responseJSON.errors) !== 'undefined') {
                if (data.status === 422) {
                    handleValidationError(data.responseJSON.errors);
                }
            } else if (typeof (data.responseJSON.message) !== 'undefined') {
                showError(data.responseJSON.message);
            } else {
                $.each(data.responseJSON, (index, el) => {
                    $.each(el, (key, item) => {
                        showError(item);
                    });
                });
            }
        } else {
            showError(data.statusText);
        }
    }

    var handleValidationError = errors => {
        let message = '';
        $.each(errors, (index, item) => {
            if (message !== '') {
                message += '<br />';
            }
            message += item;
        });
        showError(message);
    }

    window.showAlert = (messageType, message) => {
        if (messageType && message !== '') {
            let alertId = Math.floor(Math.random() * 1000);

            let html = `<div class="alert ${messageType} alert-dismissible" id="${alertId}">
                                <span class="close elegant-icon icon_close" data-dismiss="alert" aria-label="close"></span>
                                <i class="fas fa-` + (messageType === 'alert-success' ? 'check' : 'times') + ` message-icon"></i>
                                ${message}
                            </div>`;
            $('#alert-container').append(html).ready(() => {
                window.setTimeout(() => {
                    $(`#alert-container #${alertId}`).remove();
                }, 6000);
            });
        }
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });

    $(window).on('load', function () {
        $('#preloader').delay(350).fadeOut('slow');
        $('body').delay(350).css({'overflow': 'visible'});
        lazyLoad.update();
    })

    /* --- Popup youtube --- */
    if ($.fn.magnificPopup) {
        $('#popup-youtube').magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            hiddenClass: 'zxcv',
            overflowY: 'hidden',
            iframe: {
                patterns: {
                    youtube: {
                        index: 'youtube.com',
                        id: function (url) {
                            var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
                            var match = url.match(regExp);
                            return (match && match[7].length == 11) ? match[7] : url;
                        },
                        src: '//www.youtube.com/embed/%id%?autoplay=1'
                    }
                }
            }
        });
    }

    /*---- Map ----*/
    function initMaps() {
        let $map = $('#map');
        var totalPage = 0;
        var currentPage = 1;
        var params = {type: $map.data('type'), page: currentPage};
        var center = $('#map').data('center');
        const centerFirst = $('#properties-list .property-item[data-lat][data-long]').filter(function () {
            return $(this).data('lat') && $(this).data('long')
        });
        if (centerFirst && centerFirst.length) {
            center = [centerFirst.data('lat'), centerFirst.data('long')]
        }
        if (window.activeMap) {
            window.activeMap.off();
            window.activeMap.remove();
        }

        let map = L.map('map', {
            zoomControl: true,
            scrollWheelZoom: true,
            dragging: true,
            maxZoom: 22
        }).setView(center, 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        var markers = new L.MarkerClusterGroup();
        var markersList = [];
        let $templatePopup = $('#traffic-popup-map-template').html();
        function populate() {
            if ((totalPage == 0) || currentPage <= totalPage) {
                params.page = currentPage;
                $.ajax({
                    url: $map.data('url'),
                    type: 'POST',
                    data: params,
                    success: function (res) {
                        if (res.data.length > 0) {
                            res.data.forEach(house => {
                                if (house.latitude && house.longitude) {
                                    var myIcon = L.divIcon({
                                        className: 'boxmarker',
                                        iconSize: L.point(50, 20),
                                        html: house.map_icon
                                    });
                                    let popup = templateReplace(house, $templatePopup);
                                    var m = new L.Marker(new L.LatLng(house.latitude, house.longitude), {icon: myIcon})
                                        .bindPopup(popup)
                                        .addTo(map);
                                    markersList.push(m);
                                    markers.addLayer(m);

                                    map.flyToBounds(L.latLngBounds(markersList.map(marker => marker.getLatLng())));
                                }
                            });
                            if (totalPage == 0) {
                                totalPage = res.meta.last_page
                            }
                            currentPage++;
                            populate();
                        }
                    }
                });
            }

            return false;
        }

        populate();
        map.addLayer(markers);

        window.activeMap = map;
    }

    if ($('#map').length) {
        initMaps();
    }

    //Property detail
    let trafficMap;

    function setTrafficMap($related) {
        if (trafficMap) {
            trafficMap.off();
            trafficMap.remove();
        }
        trafficMap = L.map($related.data('map-id'), {
            zoomControl: false,
            scrollWheelZoom: true,
            dragging: true,
            maxZoom: 18
        }).setView($related.data('center'), 14);
        var myIcon = L.divIcon({
            className: 'boxmarker',
            iconSize: L.point(50, 20),
            html: $related.data('map-icon')
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(trafficMap);
        L.marker($related.data('center'), {icon: myIcon}).addTo(trafficMap)
            .bindPopup($($related.data('popup-id')).html())
            .openPopup();
    }

    if ($('[data-popup-id="#traffic-popup-map-template"]').length) {
        setTrafficMap($('[data-popup-id="#traffic-popup-map-template"]'));
    }

    function templateReplace(data, template) {
        const keys = Object.keys(data);
        for (const i in keys) {
            if (keys.hasOwnProperty(i)) {
                const key = keys[i]
                template = template.replace(new RegExp('__' + key + '__', 'gi'), data[key] || '')
            }
        }
        return template;
    }

    $(document).on('submit', '#ajax-filters-form', function (event) {
        event.preventDefault();
        const $form = $(event.currentTarget);
        const formData = $form.serializeArray();
        let data = [];
        let uriData = [];
        formData.forEach(function (obj) {
            if (obj.value) {
                data.push(obj);
                uriData.push(obj.name + '=' + obj.value);
            }
        });
        const nextHref = $form.attr('action') + (uriData && uriData.length ? ('?' + uriData.join('&')) : '');
        // Show selects to dropdown
        $form.find('.select-dropdown').map(function () {
            showTextForDropdownSelect($(this))
        })
        // add to params get to popstate not show json
        data.push({name: 'is_searching', value: 1});

        $.ajax({
            url: $form.attr('action'),
            type: 'GET',
            data: data,
            beforeSend: function () {
                $('#loading').show();
                $('html, body').animate({
                    scrollTop: $('#ajax-filters-form').offset().top - ($('.main-header').height() + 50)
                }, 500);
                // Close filter on mobile
                $form.find('.search-box').removeClass('active');
            },
            success: function (res) {
                if (res.error == false) {
                    console.log( $form.find('.data-listing'))
                    $form.find('.data-listing').html(res.data);
                    window.wishlishInElement($form.find('.data-listing'))
                    if (window.activeMap) {
                        const theFirst = $('#properties-list .property-item[data-lat][data-long]').filter(function () {
                            return $(this).data('lat') && $(this).data('long')
                        })
                        if (theFirst.length) {
                            window.activeMap.setView([theFirst.data('lat'), theFirst.data('long')], 8)
                        }
                    }
                    if (nextHref != window.location.href) {
                        window.history.pushState(data, res.message, nextHref);
                    }
                } else {
                    window.showAlert('alert-error', res.message || 'Opp!');
                }
            },
            complete: function () {
                $('#loading').hide();
            }
        });
    });

    $(document).on('click', '#ajax-filters-form .pagination a', function (e) {
        e.preventDefault();
        var url = new URL($(e.currentTarget).attr('href'));
        var page = url.searchParams.get("page");
        $('#ajax-filters-form input[name=page]').val(page)
        $('#ajax-filters-form').trigger('submit');
    });

    function searchToObject() {
        var pairs = window.location.search.substring(1).split('&'),
            obj = {},
            pair,
            i;

        for (i in pairs) {
            if (pairs[i] === '') continue;

            pair = pairs[i].split('=');
            obj[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
        }

        return obj;
    }
    $(document).on('change', '#ajax-filters-form select, #ajax-filters-form .input-filter', function (event) {
        $('#ajax-filters-form').trigger('submit');
    })
    window.addEventListener('popstate', function () {
        const $formSearch = $('#ajax-filters-form');
        var url = window.location.origin + window.location.pathname;
        if ($formSearch.attr('action') == url) {
            const pairs = searchToObject();
            $formSearch.find('input, select, textarea').each(function (e, i) {
                const $el = $(i);
                let value = (pairs[$el.attr('name')] || '');
                if ($el.val() != value) {
                    $el.val(value).trigger('change');
                }
            });
            $formSearch.trigger('submit');
        } else {
            history.back()
        }
        ;
    }, false);

    /*---- Rating ---*/
    function rating() {
        $(document).find('select.rating').each(function () {
            let readOnly;
            readOnly = $(this).attr('data-read-only') === 'true';
            $(this).barrating({
                theme: 'fontawesome-stars',
                readonly: readOnly,
                initialRating: 5,
                onSelect: function (value, text) {
                    calculateRating()
                }
            });
        });
    }
    function calculateRating() {
        let sum = 0;
        let avg_rate = 5;
        $(document).find('select.rating').each(function () {
            sum += parseFloat($(this).val());
        });
        avg_rate = sum/($(document).find('select.rating').length);
        $('input[name="star"]').val(avg_rate);
        $('.user_commnet_avg_rate').html(avg_rate);
    }
    if ($('select.rating').length) {
        rating();
    }
    /*---- Bottom To Top Scroll Script ---*/
    $(window).on('scroll', function () {
        var height = $(window).scrollTop();
        if (height > 100) {
            $('#back2Top').fadeIn();
        } else {
            $('#back2Top').fadeOut();
        }
    });

    $("#back2Top").on('click', function (event) {
        event.preventDefault();
        $("html, body").animate({scrollTop: 0}, "slow");
        return false;
    });

    // Navigation
    !function (n, e, i, a) {
        n.navigation = function (t, s) {
            var o = {
                    responsive: !0,
                    mobileBreakpoint: 992,
                    showDuration: 300,
                    hideDuration: 300,
                    showDelayDuration: 0,
                    hideDelayDuration: 0,
                    submenuTrigger: "hover",
                    effect: "fade",
                    submenuIndicator: !0,
                    hideSubWhenGoOut: !0,
                    visibleSubmenusOnMobile: !1,
                    fixed: !1,
                    overlay: !0,
                    overlayColor: "rgba(0, 0, 0, 0.5)",
                    hidden: !1,
                    offCanvasSide: "left",
                    onInit: function () {
                    },
                    onShowOffCanvas: function () {
                    },
                    onHideOffCanvas: function () {
                    }
                },
                u = this,
                r = Number.MAX_VALUE,
                d = 1,
                f = "click.nav touchstart.nav",
                l = "mouseenter.nav",
                c = "mouseleave.nav";
            u.settings = {};
            var t = (n(t), t);
            n(t).find(".nav-menus-wrapper").prepend("<span class='nav-menus-wrapper-close-button'>✕</span>"), n(t).find(".nav-search").length > 0 && n(t).find(".nav-search").find("form").prepend("<span class='nav-search-close-button'>✕</span>"), u.init = function () {
                u.settings = n.extend({}, o, s), "right" == u.settings.offCanvasSide && n(t).find(".nav-menus-wrapper").addClass("nav-menus-wrapper-right"), u.settings.hidden && (n(t).addClass("navigation-hidden"), u.settings.mobileBreakpoint = 99999), v(), u.settings.fixed && n(t).addClass("navigation-fixed"), n(t).find(".nav-toggle").on("click touchstart", function (n) {
                    n.stopPropagation(), n.preventDefault(), u.showOffcanvas(), s !== a && u.callback("onShowOffCanvas")
                }), n(t).find(".nav-menus-wrapper-close-button").on("click touchstart", function () {
                    u.hideOffcanvas(), s !== a && u.callback("onHideOffCanvas")
                }), n(t).find(".nav-search-button").on("click touchstart", function (n) {
                    n.stopPropagation(), n.preventDefault(), u.toggleSearch()
                }), n(t).find(".nav-search-close-button").on("click touchstart", function () {
                    u.toggleSearch()
                }), n(t).find(".megamenu-tabs").length > 0 && y(), n(e).resize(function () {
                    m(), C()
                }), m(), s !== a && u.callback("onInit")
            };
            var v = function () {
                n(t).find("li").each(function () {
                    n(this).children(".nav-dropdown,.megamenu-panel").length > 0 && (n(this).children(".nav-dropdown,.megamenu-panel").addClass("nav-submenu"), u.settings.submenuIndicator && n(this).children("a").append("<span class='submenu-indicator'><span class='submenu-indicator-chevron'></span></span>"))
                })
            };
            u.showSubmenu = function (e, i) {
                g() > u.settings.mobileBreakpoint && n(t).find(".nav-search").find("form").slideUp(), "fade" == i ? n(e).children(".nav-submenu").stop(!0, !0).delay(u.settings.showDelayDuration).fadeIn(u.settings.showDuration) : n(e).children(".nav-submenu").stop(!0, !0).delay(u.settings.showDelayDuration).slideDown(u.settings.showDuration), n(e).addClass("nav-submenu-open")
            }, u.hideSubmenu = function (e, i) {
                "fade" == i ? n(e).find(".nav-submenu").stop(!0, !0).delay(u.settings.hideDelayDuration).fadeOut(u.settings.hideDuration) : n(e).find(".nav-submenu").stop(!0, !0).delay(u.settings.hideDelayDuration).slideUp(u.settings.hideDuration), n(e).removeClass("nav-submenu-open").find(".nav-submenu-open").removeClass("nav-submenu-open")
            };
            var h = function () {
                    n("body").addClass("no-scroll"), u.settings.overlay && (n(t).append("<div class='nav-overlay-panel'></div>"), n(t).find(".nav-overlay-panel").css("background-color", u.settings.overlayColor).fadeIn(300).on("click touchstart", function (n) {
                        u.hideOffcanvas()
                    }))
                },
                p = function () {
                    n("body").removeClass("no-scroll"), u.settings.overlay && n(t).find(".nav-overlay-panel").fadeOut(400, function () {
                        n(this).remove()
                    })
                };
            u.showOffcanvas = function () {
                h(), "left" == u.settings.offCanvasSide ? n(t).find(".nav-menus-wrapper").css("transition-property", "left").addClass("nav-menus-wrapper-open") : n(t).find(".nav-menus-wrapper").css("transition-property", "right").addClass("nav-menus-wrapper-open")
            }, u.hideOffcanvas = function () {
                n(t).find(".nav-menus-wrapper").removeClass("nav-menus-wrapper-open").on("webkitTransitionEnd moztransitionend transitionend oTransitionEnd", function () {
                    n(t).find(".nav-menus-wrapper").css("transition-property", "none").off()
                }), p()
            }, u.toggleOffcanvas = function () {
                g() <= u.settings.mobileBreakpoint && (n(t).find(".nav-menus-wrapper").hasClass("nav-menus-wrapper-open") ? (u.hideOffcanvas(), s !== a && u.callback("onHideOffCanvas")) : (u.showOffcanvas(), s !== a && u.callback("onShowOffCanvas")))
            }, u.toggleSearch = function () {
                "none" == n(t).find(".nav-search").find("form").css("display") ? (n(t).find(".nav-search").find("form").slideDown(), n(t).find(".nav-submenu").fadeOut(200)) : n(t).find(".nav-search").find("form").slideUp()
            };
            var m = function () {
                    u.settings.responsive ? (g() <= u.settings.mobileBreakpoint && r > u.settings.mobileBreakpoint && (n(t).addClass("navigation-portrait").removeClass("navigation-landscape"), D()), g() > u.settings.mobileBreakpoint && d <= u.settings.mobileBreakpoint && (n(t).addClass("navigation-landscape").removeClass("navigation-portrait"), k(), p(), u.hideOffcanvas()), r = g(), d = g()) : k()
                },
                b = function () {
                    n("body").on("click.body touchstart.body", function (e) {
                        0 === n(e.target).closest(".navigation").length && (n(t).find(".nav-submenu").fadeOut(), n(t).find(".nav-submenu-open").removeClass("nav-submenu-open"), n(t).find(".nav-search").find("form").slideUp())
                    })
                },
                g = function () {
                    return e.innerWidth || i.documentElement.clientWidth || i.body.clientWidth
                },
                w = function () {
                    n(t).find(".nav-menu").find("li, a").off(f).off(l).off(c)
                },
                C = function () {
                    if (g() > u.settings.mobileBreakpoint) {
                        var e = n(t).outerWidth(!0);
                        n(t).find(".nav-menu").children("li").children(".nav-submenu").each(function () {
                            n(this).parent().position().left + n(this).outerWidth() > e ? n(this).css("right", 0) : n(this).css("right", "auto")
                        })
                    }
                },
                y = function () {
                    function e(e) {
                        var i = n(e).children(".megamenu-tabs-nav").children("li"),
                            a = n(e).children(".megamenu-tabs-pane");
                        n(i).on("click.tabs touchstart.tabs", function (e) {
                            e.stopPropagation(), e.preventDefault(), n(i).removeClass("active"), n(this).addClass("active"), n(a).hide(0).removeClass("active"), n(a[n(this).index()]).show(0).addClass("active")
                        })
                    }

                    if (n(t).find(".megamenu-tabs").length > 0)
                        for (var i = n(t).find(".megamenu-tabs"), a = 0; a < i.length; a++) e(i[a])
                },
                k = function () {
                    w(), n(t).find(".nav-submenu").hide(0), navigator.userAgent.match(/Mobi/i) || navigator.maxTouchPoints > 0 || "click" == u.settings.submenuTrigger ? n(t).find(".nav-menu, .nav-dropdown").children("li").children("a").on(f, function (i) {
                        if (u.hideSubmenu(n(this).parent("li").siblings("li"), u.settings.effect), n(this).closest(".nav-menu").siblings(".nav-menu").find(".nav-submenu").fadeOut(u.settings.hideDuration), n(this).siblings(".nav-submenu").length > 0) {
                            if (i.stopPropagation(), i.preventDefault(), "none" == n(this).siblings(".nav-submenu").css("display")) return u.showSubmenu(n(this).parent("li"), u.settings.effect), C(), !1;
                            if (u.hideSubmenu(n(this).parent("li"), u.settings.effect), "_blank" == n(this).attr("target") || "blank" == n(this).attr("target")) e.open(n(this).attr("href"));
                            else {
                                if ("#" == n(this).attr("href") || "" == n(this).attr("href")) return !1;
                                e.location.href = n(this).attr("href")
                            }
                        }
                    }) : n(t).find(".nav-menu").find("li").on(l, function () {
                        u.showSubmenu(this, u.settings.effect), C()
                    }).on(c, function () {
                        u.hideSubmenu(this, u.settings.effect)
                    }), u.settings.hideSubWhenGoOut && b()
                },
                D = function () {
                    w(), n(t).find(".nav-submenu").hide(0), u.settings.visibleSubmenusOnMobile ? n(t).find(".nav-submenu").show(0) : (n(t).find(".nav-submenu").hide(0), n(t).find(".submenu-indicator").removeClass("submenu-indicator-up"), u.settings.submenuIndicator ? n(t).find(".submenu-indicator").on(f, function (e) {
                        return e.stopPropagation(), e.preventDefault(), u.hideSubmenu(n(this).parent("a").parent("li").siblings("li"), "slide"), u.hideSubmenu(n(this).closest(".nav-menu").siblings(".nav-menu").children("li"), "slide"), "none" == n(this).parent("a").siblings(".nav-submenu").css("display") ? (n(this).addClass("submenu-indicator-up"), n(this).parent("a").parent("li").siblings("li").find(".submenu-indicator").removeClass("submenu-indicator-up"), n(this).closest(".nav-menu").siblings(".nav-menu").find(".submenu-indicator").removeClass("submenu-indicator-up"), u.showSubmenu(n(this).parent("a").parent("li"), "slide"), !1) : (n(this).parent("a").parent("li").find(".submenu-indicator").removeClass("submenu-indicator-up"), void u.hideSubmenu(n(this).parent("a").parent("li"), "slide"))
                    }) : k())
                };
            u.callback = function (n) {
                s[n] !== a && s[n].call(t)
            }, u.init()
        }, n.fn.navigation = function (e) {
            return this.each(function () {
                if (a === n(this).data("navigation")) {
                    var i = new n.navigation(this, e);
                    n(this).data("navigation", i)
                }
            })
        }
    }
    (jQuery, window, document), $(document).ready(function () {
        $("#navigation").navigation()
    });

    $(window).scroll(function () {
        var scroll = $(window).scrollTop();

        if (scroll >= 50) {
            $(".header").addClass("header-fixed");
        } else {
            $(".header").removeClass("header-fixed");
        }
    });


    // Compare Slide
    $('.csm-trigger').on('click', function () {
        $('.compare-slide-menu').toggleClass('active');
    });
    $('.compare-button').on('click', function () {
        $('.compare-slide-menu').addClass('active');
    });

    // Property Slide
    if($('.property-slide').length) {
        $('.property-slide').slick({
            slidesToShow: 3,
            arrows: false,
            rtl: isRTL,
            dots: true,
            autoplay: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        arrows: false,
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        arrows: false,
                        slidesToShow: 1
                    }
                }
            ]
        });
    }
    

    // location Slide
    if($('.location-slide').length) {
        $('.location-slide').slick({
            slidesToShow: 4,
            dots: true,
            rtl: isRTL,
            arrows: false,
            autoplay: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        arrows: false,
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        arrows: false,
                        slidesToShow: 1
                    }
                }
            ]
        });
    }
    

    // Property Slide
    if($('.team-slide').length) {
        $('.team-slide').slick({
            slidesToShow: 4,
            arrows: false,
            rtl: isRTL,
            autoplay: true,
            dots: true,
            responsive: [
                {
                    breakpoint: 1023,
                    settings: {
                        arrows: false,
                        dots: true,
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                        slidesToShow: 1
                    }
                }
            ]
        });
    }
    



    // Range Slider Script
    if ($(".js-range-slider").length) {
        $(".js-range-slider").ionRangeSlider({
            type: "double",
            min: 0,
            max: 1000,
            from: 200,
            to: 500,
            grid: true
        });
    }

    if ($("#select-bedroom").length) {
        // Select Bedrooms
        $('#select-bedroom').select2({
            allowClear: true
        });
    }

    if ($("#select-bathroom").length) {
        // Select Bathrooms
        $('#select-bathroom').select2({
            allowClear: true
        });
    }

    if ($("#ptypes").length) {
        // Select Property Types
        $('#ptypes').select2({
            allowClear: true
        });
    }

    if ($("#select-type").length) {
        // Select Property Types
        $('#select-type').select2({
            allowClear: true
        });
    }

    if ($("#sort_by").length) {
        // specialisms
        $('#sort_by').select2({
            allowClear: true
        });

        $('body').on('change', '#sort_by', function() {

            if($('form#filters-form').length) {
                $('#filter_sort_by').val($(this).val());
                $('form#filters-form').submit();
            } else if ('URLSearchParams' in window) {
                var searchParams = new URLSearchParams(window.location.search);
                searchParams.set("sort_by", $(this).val());
                window.location.search = searchParams.toString();
            }

        })
    }

    if ($("#minprice").length) {
        // Select Min price
        $('#minprice').select2({
            allowClear: true
        });
    }

    if ($("#maxprice").length) {
    // Select Max Price
        $('#maxprice').select2({
            allowClear: true
        });
    }

    if ($("#agent").length) {
    // Select Max Price
        $('#agent').select2({
            allowClear: true
        });
    }
    
    if ($("#city_id").length) {
        // Select Town

        $('#city_id').select2({
            allowClear: true,
            ajax: {
                url: $('#city_id').data('url'),
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: data.data.map(i => {
                            return {
                                "id": i.id,
                                "text": i.name + (i.state_name != null && i.state_name != '' ? (', ' + i.state_name) : '')
                            }
                        })
                    };
                }
            },

        });
    }

    // Select Rooms
    if ($("#rooms").length) {
        $('#rooms').select2({
            placeholder: "Choose Rooms",
            allowClear: true
        });
    }

    // Select Garage
    if ($("#garage").length) {
        $('#garage').select2({
            placeholder: "Choose Rooms",
            allowClear: true
        });
    }

    // Select Rooms
    if ($("#bage").length) {
        $('#bage').select2({
            placeholder: "Select An Option",
            allowClear: true
        });
    }

    // Home Slider
    if ($(".home-slider").length) {
        $('.home-slider').slick({
            centerMode: false,
            slidesToShow: 1,
            rtl: isRTL,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: true,
                        slidesToShow: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                        slidesToShow: 1
                    }
                }
            ]
        });
    }

    if ($(".click").length && !$(".click").hasClass('not-slider')) {
        $('.click').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            rtl: isRTL,
            autoplay: false,
            autoplaySpeed: 2000,
        });
    };

    // Featured Slick Slider
    if($('.featured_slick_gallery-slide').length) {
        $('.featured_slick_gallery-slide').slick({
            centerMode: false,
            infinite: true,
            rtl: isRTL,
            centerPadding: '80px',
            slidesToShow: 1,
            // responsive: [
            //     {
            //         breakpoint: 768,
            //         settings: {
            //             arrows: true,
            //             centerMode: true,
            //             centerPadding: '60px',
            //             slidesToShow: 3
            //         }
            //     },
            //     {
            //         breakpoint: 480,
            //         settings: {
            //             arrows: false,
            //             centerMode: true,
            //             centerPadding: '20px',
            //             slidesToShow: 1
            //         }
            //     }
            // ]
        }).magnificPopup({
            type: 'image',
            delegate: 'a.mfp-gallery',
            fixedContentPos: true,
            fixedBgPos: true,
            overflowY: 'auto',
            closeBtnInside: false,
            preloader: true,
            removalDelay: 0,
            mainClass: 'mfp-fade',
            gallery: {
                enabled: true
            }
        });
    }
    
    // MagnificPopup
    if($('.list-gallery-inline').length) {
        $('.list-gallery-inline').magnificPopup({
            type: 'image',
            delegate: 'a.mfp-gallery',
            fixedContentPos: true,
            fixedBgPos: true,
            overflowY: 'auto',
            closeBtnInside: false,
            preloader: true,
            removalDelay: 0,
            mainClass: 'mfp-fade',
            gallery: {
                enabled: true
            }
        });
    }

    // fullwidth home slider
    function inlineCSS() {
        $(".home-slider .item").each(function () {
            var attrImageBG = $(this).attr('data-background-image');
            var attrColorBG = $(this).attr('data-background-color');
            if (attrImageBG !== undefined) {
                $(this).css('background-image', 'url(' + attrImageBG + ')');
            }
            if (attrColorBG !== undefined) {
                $(this).css('background', '' + attrColorBG + '');
            }
        });
    }

    inlineCSS();

    // Search Radio
    function searchTypeButtons() {
        $('.property-search-type label.active input[type="radio"]').prop('checked', true);
        var buttonWidth = $('.property-search-type label.active').width();
        var arrowDist = $('.property-search-type label.active').position();
        $('.property-search-type-arrow').css('left', arrowDist + (buttonWidth / 2));
        $('.property-search-type label').on('change', function () {
            $('.property-search-type input[type="radio"]').parent('label').removeClass('active');
            $('.property-search-type input[type="radio"]:checked').parent('label').addClass('active');
            var buttonWidth = $('.property-search-type label.active').width();
            var arrowDist = $('.property-search-type label.active').position().left;
            $('.property-search-type-arrow').css({
                'left': arrowDist + (buttonWidth / 1.7),
                'transition': 'left 0.4s cubic-bezier(.95,-.41,.19,1.44)'
            });
        });
    }

    if ($(".hero-banner").length) {
        searchTypeButtons();
        $(window).on('load resize', function () {
            searchTypeButtons();
        });
    }
    $(document).on('click', '.contact-form button[type=submit]', function (event) {
        event.preventDefault();
        event.stopPropagation();

        let _self = $(this);

        _self.addClass('button-loading');

        $.ajax({
            type: 'POST',
            cache: false,
            url: _self.closest('form').prop('action'),
            data: new FormData(_self.closest('form')[0]),
            contentType: false,
            processData: false,
            success: res => {
                _self.removeClass('button-loading');

                if (typeof refreshRecaptcha !== 'undefined') {
                    refreshRecaptcha();
                }
                if (res.error) {
                    showError(res.message);
                    return false;
                }

                _self.closest('form').find('input[type=email]').val('');
                showSuccess(res.message);
            },
            error: res => {
                if (typeof refreshRecaptcha !== 'undefined') {
                    refreshRecaptcha();
                }
                _self.removeClass('button-loading');
                handleError(res);
            }
        });
    });

    $(document).on('change', '.js_payment_method', function (e) {
        $('.payment_collapse_wrap').removeClass('collapse').removeClass('show').removeClass('active');
        $(this).closest('.list-group-item').find('.payment_collapse_wrap').addClass('collapse show');
    });


    $(document).on('click', '.filter_search_opt', function (e) {
        if($('#filter_search').hasClass('filter_search_open')) {
            $("#filter_search").removeClass('filter_search_open').animate({
                left: -310
            });
        } else {
            $("#filter_search").addClass('filter_search_open').animate({
                left: -0
            });
        }

    });
    $(document).on('click',function (e) {
        if ($(e.target).closest(".filter_search_opt").length == 0 && $(e.target).closest("#filter_search").length == 0) {
            $("#filter_search").removeClass('filter_search_open').animate({
                left: -310
            });
        }
        if($(e.target).closest(".close_search_menu").length) {
            $("#filter_search").removeClass('filter_search_open').animate({
                left: -310
            });
        }
    });

    $(document).on('click', '.newsletter-form button[type=submit]', function (event) {
        event.preventDefault();
        event.stopPropagation();

        let _self = $(this);

        _self.addClass('button-loading');

        $.ajax({
            type: 'POST',
            cache: false,
            url: _self.closest('form').prop('action'),
            data: new FormData(_self.closest('form')[0]),
            contentType: false,
            processData: false,
            success: res => {
                _self.removeClass('button-loading');

                if (typeof refreshRecaptcha !== 'undefined') {
                    refreshRecaptcha();
                }

                if (res.error) {
                    showError(res.message);
                    return false;
                }

                _self.closest('form').find('input[type=email]').val('');
                showSuccess(res.message);
            },
            error: res => {
                if (typeof refreshRecaptcha !== 'undefined') {
                    refreshRecaptcha();
                }
                _self.removeClass('button-loading');
                handleError(res);
            }
        });
    });

    $('body')
        .on('change', 'select[name=category_id].has-sub-category', function () {
            let _this = $(this);
            if ($('#sub_category').length < 1) {
                return;
            }

            $.ajax({
                url: _this.data('url'),
                data: {
                    id: _this.val()
                },
                beforeSend: () => {
                    $('#sub_category').html('<option value="">' + ($('#sub_category').data('placeholder')) + '</option>');
                },
                success: data => {
                    let option = '<option value="">' + ($('#sub_category').data('placeholder')) + '</option>';
                    $.each(data.data, (index, item) => {
                        option += '<option value="' + item.id + '">' + item.name + '</option>';
                    });

                    $('#sub_category').html(option).select2();
                }
            });
        })
        .on('change', 'select#filter_country_id', function () {
            let _this = $(this);

            $.ajax({
                url: $('#filter_state_id').data('url'),
                data: {
                    id: _this.val()
                },
                beforeSend: () => {
                    $('#filter_state_id').html('<option value="">' + ($('#filter_state_id').data('placeholder')) + '</option>');
                    $('#filter_city_id').html('<option value="">' + ($('#filter_city_id').data('placeholder')) + '</option>');
                },
                success: data => {
                    let option = '<option value="">' + ($('#filter_state_id').data('placeholder')) + '</option>';
                    $.each(data.data, (index, item) => {
                        option += '<option value="' + item.id + '">' + item.name + '</option>';
                    });

                    $('#filter_state_id').html(option).select2();
                }
            });
        })
        .on('change', 'select#filter_state_id', function () {
            let _this = $(this);

            $.ajax({
                url: $('#filter_city_id').data('url'),
                data: {
                    id: _this.val()
                },
                beforeSend: () => {
                    $('#filter_city_id').html('<option value="">' + ($('#filter_city_id').data('placeholder')) + '</option>');
                },
                success: data => {
                    let option = '<option value="">' + ($('#filter_city_id').data('placeholder')) + '</option>';
                    $.each(data.data, (index, item) => {
                        option += '<option value="' + item.id + '">' + item.name + '</option>';
                    });

                    $('#filter_city_id').html(option).select2();
                }
            });
        });

    if ($('#filter_country_id').length > 0) {
        $('#filter_country_id').select2({
            allowClear: true
        });
    }

    if ($('#filter_state_id').length > 0) {
        $('#filter_state_id').select2({
            allowClear: true
        });
    }

    if ($('#filter_city_id').length > 0) {
        $('#filter_city_id').select2({
            allowClear: true
        });
    }

    if ($('#sub_category').length > 0) {
        $('#sub_category').select2({
            allowClear: true
        });
    }
});
