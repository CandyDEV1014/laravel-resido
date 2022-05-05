class SimpleSliderAdminManagement {
    init() {
        $.each($('#simple-slider-items-table_wrapper tbody'), (index, el) => {
            Sortable.create(el, {
                group: el + '_' + index, // or { name: "...", pull: [true, false, clone], put: [true, false, array] }
                sort: true, // sorting inside list
                delay: 0, // time in milliseconds to define when the sorting should start
                disabled: false, // Disables the sortable if set to true.
                store: null, // @see Store
                animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
                handle: 'tr',
                ghostClass: 'sortable-ghost', // Class name for the drop placeholder
                chosenClass: 'sortable-chosen', // Class name for the chosen item
                dataIdAttr: 'data-id',

                forceFallback: false, // ignore the HTML5 DnD behaviour and force the fallback to kick in
                fallbackClass: 'sortable-fallback', // Class name for the cloned DOM Element when using forceFallback
                fallbackOnBody: false,  // Appends the cloned DOM Element into the Document's Body

                scroll: true, // or HTMLElement
                scrollSensitivity: 30, // px, how near the mouse must be to an edge to start scrolling.
                scrollSpeed: 10, // px

                // dragging ended
                onEnd: () => {
                    let $box = $(el).closest('.widget-body');
                    $box.find('.btn-save-sort-order').addClass('sort-button-active').show();
                    $.each($box.find('tbody tr'), (index, sort) => {
                        $(sort).find('.order-column').text(index + 1);
                    });
                }
            });
        });

        $('.btn-save-sort-order').off('click').on('click', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            if (_self.hasClass('sort-button-active')) {
                let $box = _self.closest('.widget-body');
                $box.find('.btn-save-sort-order').addClass('button-loading');
                let items = [];
                console.log($box.find('tbody tr'));
                $.each($box.find('tbody tr'), (index, sort) => {
                    items.push(parseInt($(sort).find('td:first-child').text()));
                    $(sort).find('.order-column').text(index + 1);
                });
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: route('simple-slider.sorting'),
                    data: {
                        items: items
                    },
                    success: res => {
                        Botble.showSuccess(res.message);
                        $box.find('.btn-save-sort-order').removeClass('button-loading').hide();
                        _self.removeClass('sort-button-active');
                    },
                    error: res => {
                        Botble.showError(res.message);
                        _self.removeClass('sort-button-active');
                    }
                });
            }
        });
    }
}

$(document).ready(() => {
    new SimpleSliderAdminManagement().init();
});
