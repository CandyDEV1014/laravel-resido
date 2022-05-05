class SimpleSliderManagement {
    static setAnimation(_elem, _InOut) {
        var animationEndEvent =
            'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        _elem.each(function () {
            var $elem = $(this);
            var $animationType = 'animated ' + $elem.data('animation-' + _InOut);

            $elem.addClass($animationType).one(animationEndEvent, function () {
                $elem.removeClass($animationType);
            });
        });
    }

    init() {
        let target = $(document).find('.owl-slider');
        if (target.length > 0) {
            target.each(function () {
                let el = $(this),
                    dataAuto = el.data('owl-auto'),
                    dataLoop = el.data('owl-loop'),
                    dataSpeed = el.data('owl-speed'),
                    dataGap = el.data('owl-gap'),
                    dataNav = el.data('owl-nav'),
                    dataDots = el.data('owl-dots'),
                    dataAnimateIn = el.data('owl-animate-in')
                        ? el.data('owl-animate-in')
                        : '',
                    dataAnimateOut = el.data('owl-animate-out')
                        ? el.data('owl-animate-out')
                        : '',
                    dataDefaultItem = el.data('owl-item'),
                    dataItemXS = el.data('owl-item-xs'),
                    dataItemSM = el.data('owl-item-sm'),
                    dataItemMD = el.data('owl-item-md'),
                    dataItemLG = el.data('owl-item-lg'),
                    dataItemXL = el.data('owl-item-xl'),
                    dataNavLeft = el.data('owl-nav-left')
                        ? el.data('owl-nav-left')
                        : '<i class="fa fa-angle-left"></i>',
                    dataNavRight = el.data('owl-nav-right')
                        ? el.data('owl-nav-right')
                        : '<i class="fa fa-angle-right"></i>',
                    duration = el.data('owl-duration'),
                    dataMouseDrag = el.data('owl-mousedrag') === 'on',
                    center = el.data('owl-center');
                if (
                    target.children('div, span, a, img, h1, h2, h3, h4, h5, h5').length >=
                    2
                ) {
                    el.owlCarousel({
                        rtl: $('body').prop('dir') === 'rtl',
                        animateIn: dataAnimateIn,
                        animateOut: dataAnimateOut,
                        margin: dataGap,
                        autoplay: dataAuto,
                        autoplayTimeout: dataSpeed,
                        autoplayHoverPause: true,
                        loop: dataLoop,
                        nav: dataNav,
                        mouseDrag: dataMouseDrag,
                        touchDrag: true,
                        autoplaySpeed: duration,
                        navSpeed: duration,
                        dotsSpeed: duration,
                        dragEndSpeed: duration,
                        navText: [dataNavLeft, dataNavRight],
                        dots: dataDots,
                        items: dataDefaultItem,
                        center: Boolean(center),
                        responsive: {
                            0: {
                                items: dataItemXS
                            },
                            480: {
                                items: dataItemSM
                            },
                            768: {
                                items: dataItemMD
                            },
                            992: {
                                items: dataItemLG
                            },
                            1200: {
                                items: dataItemXL
                            },
                            1680: {
                                items: dataDefaultItem
                            }
                        }
                    });

                    el.on('change.owl.carousel', function (event) {
                        var $currentItem = $('.owl-item', el).eq(event.item.index);
                        var $elementsToAnimation = $currentItem.find('[data-animation-out]');
                        SimpleSliderManagement.setAnimation($elementsToAnimation, 'out');
                    });

                    el.on('changed.owl.carousel', function (event) {
                        var $currentItem = $('.owl-item', el).eq(event.item.index);
                        var $elementsToAnimation = $currentItem.find('[data-animation-in]');
                        SimpleSliderManagement.setAnimation($elementsToAnimation, 'in');
                    });
                }
            });
        }
    }
}

$(document).ready(() => {
    new SimpleSliderManagement().init();
});
