document.addEventListener('DOMContentLoaded', function () {
    var hamburger = document.getElementById("hamburger");
    var side_menu = document.getElementById("menu-side");
    var close     = document.getElementById("menu-side-close");
    hamburger.addEventListener("click", function() {
        side_menu.classList.toggle("is-active");
        document.body.classList.toggle("noscroll");
    });
    close.addEventListener("click", function() {
        side_menu.classList.toggle("is-active");
        document.body.classList.toggle("noscroll");
    });
    var award_toggle = document.getElementById("awards-filters-toggle");
    var awards_filters = document.getElementById("awards-filters-stage");
    award_toggle && award_toggle.addEventListener("click", function() {
        award_toggle.classList.toggle("is-active");
        awards_filters.classList.toggle("is-active");
    });
}, false);
var forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(var c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(var e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};
function isScrolledIntoView(el) {
    const { top, bottom } = el.getBoundingClientRect()
    return top >= 0 && bottom <= window.innerHeight
}



jQuery("#award_years, #award_solutions, #award_bodies").on("change", function () {

    var awardsFiltersType = jQuery("#award_bodies");
    var awardsFiltersSolutions = jQuery("#award_solutions");
    var awardsFiltersYears = jQuery("#award_years");

    console.log(awardsFiltersType.select2('data'));
    console.log(awardsFiltersSolutions.select2('data'));
    console.log(awardsFiltersYears.select2('data'));

 });