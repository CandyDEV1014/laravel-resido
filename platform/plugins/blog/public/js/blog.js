/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************************************!*\
  !*** ./platform/plugins/blog/resources/assets/js/blog.js ***!
  \***********************************************************/
$(document).ready(function () {
  BDashboard.loadWidget($('#widget_posts_recent').find('.widget-content'), route('posts.widget.recent-posts'));
});
/******/ })()
;