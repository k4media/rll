document.addEventListener('DOMContentLoaded', function () {
    var body      = document.getElementById("hamburger");
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

}, false);
var forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(var c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(var e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};
function isScrolledIntoView(el) {
    const { top, bottom } = el.getBoundingClientRect()
    return top >= 0 && bottom <= window.innerHeight
}