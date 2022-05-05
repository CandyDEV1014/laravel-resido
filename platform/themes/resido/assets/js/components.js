/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other plugins. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

"use strict";

import PropertyComponent from './components/PropertyComponent';
import NewsComponent from './components/NewsComponent';
import sanitizeHTML from 'sanitize-html';
import Vue from 'vue';
import FeaturedAgentsComponent from './components/FeaturedAgentsComponent';
import TestimonialsComponent from './components/TestimonialsComponent';
import RealEstateReviewsComponent from './components/RealEstateReviewsComponent';
import PostReviewsComponent from './components/PostReviewsComponent';
import CountdownComponent from './components/CountdownComponent';

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('property-component', PropertyComponent);
Vue.component('news-component', NewsComponent);
Vue.component('featured-agents-component', FeaturedAgentsComponent);
Vue.component('testimonials-component', TestimonialsComponent);
Vue.component('real-estate-reviews-component', RealEstateReviewsComponent);
Vue.component('post-reviews-component', PostReviewsComponent);
Vue.component('countdown-component', CountdownComponent);

/**
 * This let us access the `__` method for localization in VueJS templates
 * ({{ __('key') }})
 */
Vue.prototype.__ = key => {
    return window.trans[key] !== 'undefined' ? window.trans[key] : key;
};

Vue.prototype.themeUrl = url => {
    return window.themeUrl + '/' + url;
}

Vue.prototype.$sanitize = sanitizeHTML;

const app = new Vue({
    el: '#app'
});

Vue.directive('slick', {
    inserted: function (element) {
        $(element).slick({
            slidesToShow: 3,
            infinite: true,
            rtl: $('body').prop('dir') === 'rtl',
            arrows: false,
            autoplay: true,
            responsive: [
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
    },
});
