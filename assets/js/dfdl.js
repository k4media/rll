document.addEventListener('DOMContentLoaded', function () {
    var hamburger = document.getElementById("hamburger");
    var side_menu = document.getElementById("menu-side");
    hamburger && hamburger.addEventListener("click", function() {
        hamburger.classList.toggle("open");
        side_menu.classList.toggle("is-active");
    });
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
    var header = document.getElementById("header"); 
    var beacon = document.getElementById("beacon");
    var subnav = document.getElementById("subnav-stage");
    if (beacon && subnav) {
        window.addEventListener("scroll", function(){
            rect = beacon.getBoundingClientRect();
            if ( rect.top <= 80 ) {
                subnav.classList.add("fixed");
                beacon.classList.add("stage");
                header.classList.add("solid");
                header.classList.add("solid");
            } 
            if ( rect.top > 80 ) {
            if ( rect.top > 80 ) {
                subnav.classList.remove("fixed");
                beacon.classList.remove("stage");
                header.classList.remove("solid");
                header.classList.remove("solid");
            } 
        });
    }

    try {
        var swiper = document.getElementsByClassName("swiper");
        if (swiper) {
            Array.prototype.forEach.call(swiper, function(element) {
                element.classList.remove("loading");
            });
        }
    } catch(e) {

    };

    try {
        var subnavSwiper = new Swiper(".subnav-swiper", {
            // slidesPerView: 10,
            // spaceBetween: 16,
            // freeMode: true,
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
    } catch(e) {
        
    };
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
if (document.getElementById("ajax_count")) {
    var counter = document.getElementById("ajax_count").value;
    console.log("ajax_count = " + counter );
}

function insightsSeeMore() {
    var wrap = document.getElementById("insights-posts");
    var insights_more = document.getElementById("insights-all-see-more");
    var search_count = document.getElementById("search-count");
    insights_more.classList.add("disabled", "loading");
    postAjax(
        ajax_object.ajaxurl, {
            action: "insights_more",
            nonce: ajax_object.insights_see_more,
            permalink: ajax_object.permalink,
            page: document.getElementById("insights_all_page").value,
            source: document.getElementById("insights-all-see-more").dataset.source,
            iSolutions:jQuery('#insights_solutions').select2("val"),
            iCategories: jQuery('#insights_categories').select2("val") || jQuery('#insights_events').select2("val"),
            iSection: jQuery('#insights_section').val(),
            iYears: jQuery('#insights_years').val(),
            iCountry: jQuery('#insights_country').val(),
            iContentHub: jQuery('#content_hub').val(),
            iCountry: document.getElementById("insights_country").value,
            iTerm: document.getElementById("insights_term").value
        }, function(data){
            data = JSON.parse(data);
            console.log("results loaded");
            console.log(data);
            if ( data.code === 200 ) {
                data.html.forEach((el) => {
                    counter++;
                    var new_span = document.createElement("div");
                    new_span.innerHTML = el;
                    wrap.appendChild(new_span);
                })
                if (search_count) {
                    search_count.innerHTML = counter;
                }
                console.log( "Showing " + counter + " of " + data.found + " posts");
                if ( parseInt(counter) < parseInt(data.found) ) {
                    insights_more.classList.remove("disabled");
                }
            } else {
                insights_more.classList.add("disabled");
            }
            insights_more.classList.remove("loading");
        }
    )
}
function filterInsights() {
    console.log("loading results");
    document.getElementById("insights_all_page").value = 1;
    jQuery("#results_stage").addClass("no-results");
    jQuery("#results_stage > div ").replaceWith( "<div class='loading'>loading ...</div>" );
    postAjax(
        ajax_object.ajaxurl, {
            action: "filter_insights",
            nonce: ajax_object.insights_nonce,
            permalink: ajax_object.permalink,
            page: document.getElementById("insights_all_page").value,
            iSolutions:jQuery('#insights_solutions').select2("val"),
            iCategories: jQuery('#insights_categories').select2("val") || jQuery('#insights_events').select2("val"),
            iSection: jQuery('#insights_section').val(),
            iYears: jQuery('#insights_years').val(),
            iCountry: jQuery('#insights_country').val(),
            iContentHub: jQuery('#content_hub').val(),
            iTerm: document.getElementById("insights_term").value
        }, function(data){
            data = JSON.parse(data);
            counter = data.count;
            if ( data.code === 200 ) {
                jQuery("#results_stage").removeClass("no-results");
                jQuery("#results_stage > div ").replaceWith( "<div>" + data.html + "</div>" );
            } else {
                jQuery("#results_stage > div ").replaceWith( '<div><p class="no-insights not-found">Nothing found. Please refine your search.</p></div>' );
            }
            console.log("results loaded");
            console.log(data);
        }
    )
}

function teamsSeeMore() {
    var wrap = document.getElementById("swiper-wrapper");
    var teams_more = document.getElementById("teams-all-see-more");
    teams_more.classList.add("disabled", "loading");
    postAjax(
        ajax_object.ajaxurl, {
            action: "teams_more",
            nonce: ajax_object.teams_see_more,
            permalink: ajax_object.permalink,
            solutions:jQuery('#teams_solutions').select2("val"),
            sort: jQuery('#teams_sort').select2("val"),
            country: document.getElementById("dfdl_teams_country").value,
            page: document.getElementById("teams_all_page").value
        }, function(data){
            data = JSON.parse(data);
            console.log("results loaded");
            console.log(data);
            if ( data.code === 200 ) {
                data.html.forEach((el) => {
                    counter++;
                    var new_span = document.createElement("span");
                    var spanclass = "member" ;
				    new_span.classList.add(spanclass);
                    new_span.innerHTML = el;
                    wrap.appendChild(new_span);
                })
                if ( parseInt(counter) < parseInt(data.found) ) {
                    teams_more.classList.remove("disabled");
                    console.log("showing " + counter + " of " + data.found + " team members")
                }
            } else {
                teams_more.classList.remove("disabled");
            }
            teams_more.classList.remove("loading");
        }
    )
}
function filterTeams() {
    console.log("loading results");
    jQuery("#results_stage").addClass("no-results");
    jQuery("#results_stage > #team-grid-swiper").replaceWith( "<div class='loading'>loading ...</div>" );
    document.getElementById("teams_all_page").value = 1;
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
                if ( data.count.length > 0 ) {
                    jQuery("#results_stage").removeClass("no-results");
                    jQuery("#results_stage > div ").replaceWith( data.html );
                    swiperInit = false;
                    window.dispatchEvent(new Event('resize'));
                } else {
                    jQuery("#results_stage > div ").replaceWith( '<div><p class="no-team-members not-found">No Team Members found</p></div>' );
                }
            } else {
                jQuery("#results_stage > div ").replaceWith( '<div><p class="no-team-members not-found">No Team Members found</p></div>' );
            }
            console.log("results loaded");
            console.log(data);
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