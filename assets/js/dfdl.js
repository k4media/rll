document.addEventListener('DOMContentLoaded', function () {
    var hamburger = document.getElementById("hamburger");
    var side_menu = document.getElementById("menu-side");
    var close     = document.getElementById("menu-side-close");
    hamburger && hamburger.addEventListener("click", function() {
        hamburger.classList.toggle("open");
        side_menu.classList.toggle("is-active");
        //document.body.classList.toggle("noscroll");
    });
    //close.addEventListener("click", function() {
        //side_menu.classList.toggle("is-active");
        //document.body.classList.toggle("noscroll");
    //});

    var filters_stage = document.getElementById("filters-stage");
    var filters_toggle = document.getElementById("filters-toggle");
    var mobile_filters_toggle = document.getElementById("mobile-filters-toggle");
    filters_toggle && filters_toggle.addEventListener("click", function() {
        filters_toggle.classList.toggle("is-active");
        mobile_filters_toggle.classList.toggle("is-active");
        filters_stage.classList.toggle("is-active");
    });
    mobile_filters_toggle && mobile_filters_toggle.addEventListener("click", function() {
        filters_toggle.classList.toggle("is-active");
        mobile_filters_toggle.classList.toggle("is-active");
        filters_stage.classList.toggle("is-active");
    });

    var scroll_to_top = document.getElementById("scroll-to-top");
    scroll_to_top && scroll_to_top.addEventListener("click", function() {
        jQuery("html, body").animate({ scrollTop: 0 }, "slow");
    });
    if (scroll_to_top) {
        window.addEventListener("scroll", function() {
            var y = window.scrollY;
            if (y >= 680) {
                scroll_to_top.classList.add("is_active");
            } else {
                scroll_to_top.classList.remove("is_active");
            } 
        });
    }

    var swiper = document.getElementsByClassName("swiper");
    if (swiper) {
        Array.prototype.forEach.call(swiper, function(element) {
            element.classList.remove("loading");
        });
    }

    var beacon = document.getElementById("beacon");
    var subnav = document.getElementById("subnav-stage");
    if (beacon && subnav) {
        window.addEventListener("scroll", function(){
            rect = beacon.getBoundingClientRect();
            if ( rect.top <= 0 ) {
                subnav.classList.add("fixed");
                beacon.classList.add("stage");
            } 
            if ( rect.top > 0 ) {
                subnav.classList.remove("fixed");
                beacon.classList.remove("stage");
            } 
        });
    }

    var slider = document.querySelector(".subnav-swiper");
    if (slider) {
        var subnavSwiper = new Swiper(".subnav-swiper", {
            breakpoints: {
            950: {
                slidesPerView: 11,
            },
            599: {
                slidesPerView: 7,
            },
            499: {
                slidesPerView: 6,
            },
            399: {
                slidesPerView: 4,
            },
            0: {
                slidesPerView: 3,
              },
          }
        });
    }

}, false);
var forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(var c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(var e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};
function isScrolledIntoView(el) {
    const { top, bottom } = el.getBoundingClientRect()
    return top >= 0 && bottom <= window.innerHeight
}

if (jQuery().jquery) {
    jQuery("#award_years, #award_solutions, #award_bodies").on("change", debounce(function() {
        updateAwards()
    }, 700));
    jQuery("#teams_solutions, #teams_sort").on("change", debounce(function() {
        filterTeams()
    }, 700));
    jQuery("#insights_solutions, #insights_categories, #insights_years, #insights_events").on("change", debounce(function() {
        filterInsights()
    }, 700));
}

function filterInsights() {
    console.log("loading results");
    jQuery("#results_stage").addClass("no-results");
    jQuery("#results_stage > div ").replaceWith( "<div class='loading'>loading ...</div>" );
    postAjax(
        ajax_object.ajaxurl, {
            action: "filter_insights",
            nonce: ajax_object.insights_nonce,
            permalink: ajax_object.permalink,
            iSolutions:jQuery('#insights_solutions').select2("val"),
            iCategories: jQuery('#insights_categories').select2("val") || jQuery('#insights_events').select2("val"),
            iYears: jQuery('#insights_years').val(),
            iSection: jQuery('#insights_section').val(),
            iCountry: jQuery('#insights_country').val(),
            iContentHub: jQuery('#content_hub').val()
        }, function(data){
            data = JSON.parse(data);
            if ( data.code === 200 ) {
                jQuery("#results_stage").removeClass("no-results");
                jQuery("#results_stage > div ").replaceWith( "<div>" + data.html + "</div>" );
            } else {
                jQuery("#results_stage > div ").replaceWith( '<div><p class="no-insights not-found">Nothing found. Please refine your search.</p></div>' );
                console.log(data);
            }
            console.log("results loaded");
            console.log(data);
        }
    )
}
function filterTeams() {
    console.log("loading results");
    jQuery("#results_stage").addClass("no-results");
    jQuery("#results_stage > div ").replaceWith( "<div class='loading'>loading ...</div>" );
    postAjax(
        ajax_object.ajaxurl, {
            action: "filter_teams",
            nonce: ajax_object.teams_nonce,
            tSolutions:jQuery('#teams_solutions').select2("val"),
            tSort: jQuery('#teams_sort').select2("val"),
            tCountry: jQuery('#dfdl_teams_country').val()
        }, function(data){
            data = JSON.parse(data);
            if ( data.code === 200 ) {
                jQuery("#results_stage").removeClass("no-results");
                jQuery("#results_stage > div ").replaceWith(  data.html );
                swiperInit = false;
                window.dispatchEvent(new Event('resize'));
            } else {
                jQuery("#results_stage > div ").replaceWith( '<div><p class="no-team-members not-found">No Team Members found</p></div>' );
                console.log(data);
            }
            console.log("results loaded");
        }
    )
}
function updateAwards() {
    console.log("loading results");
    jQuery("#results_stage").addClass("no-results");
    jQuery("#results_stage > div ").replaceWith( "<div class='loading'>loading ...</div>" );
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
                jQuery("#results_stage").removeClass("no-results");
                jQuery("#results_stage > div ").replaceWith( "<div>" + data.html + "</div>" );
            } else {
                jQuery("#results_stage > div ").replaceWith( '<div><p class="no-awards not-found">No awards just yet</p></div>' );
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