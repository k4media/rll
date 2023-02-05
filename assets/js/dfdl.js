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

jQuery("#award_years, #award_solutions, #award_bodies").on("change", debounce(function() {
    updateAwards()
}, 700));

function updateAwards() {
    console.log("loading results");
    jQuery("#results_stage > div ").replaceWith( "<div class='loading'>loading</div>" );
    postAjax(
        ajax_object.ajaxurl, {
            action: "filter_awards",
            nonce: ajax_object.awards_nonce,
            fTypes:jQuery('#award_bodies').select2("val"),
            fSolutions:jQuery('#award_solutions').select2("val"),
            fYears: jQuery('#award_years').select2("val"),
            fCountry: jQuery('#dfdl_award_country').val()
        }, function(data){
            data = JSON.parse(data);
            if ( data.code === 200 ) {
                jQuery("#results_stage > div ").replaceWith( "<div>" + data.html + "</div>" );
            } else {
                jQuery("#results_stage > div ").replaceWith( "<div class='no-awards not-found'><p>No awards just yet</p></div>" );
                console.log(data);
            }
            console.log("results loaded");
        }
    )
}
function postAjax(url, data, success) {
    var params = typeof data == 'string' ? data : Object.keys(data).map(
            function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }
        ).join('&');
    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    xhr.open('POST', url);
    xhr.onreadystatechange = function() {
        if (xhr.readyState>3 && xhr.status==200) { success(xhr.responseText); }
    };
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(params);
    return xhr;
}
function debounce(cb, interval, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
        timeout = null;
        if (!immediate) cb.apply(context, args);
    };          
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, interval);
    if (callNow) cb.apply(context, args); };
};