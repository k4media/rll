<?php

/**
 * Cache results
 */
$key = "dfdl-front-page-map";
$K4 = new K4;
$K4->fragment_cache( $key, function() { 

     $title     = "";
     $subtitle  = "";
     $offices   = "";
     $countries = array();
     $popups    = array();
     $output    = array();

     // get fields
     if ( function_exists('get_fields') ) {
          
          $title = get_field('title');
          $subtitle = get_field('subtitle');
          $offices = get_field('offices');

          /**
           * Make html for office popups
           */
          foreach ( $offices as $key => $o ) {
               if ("singapore" === $key) {
                    $key = "singapore_city";
               }
               $popups[$key] = "";
               if ( isset($o['title']) ) {
                    $popups[$key] .= '<h4>' . $o['title'] . '</h4>';
                    //$popups[$key] .= '<p>' . nl2br($o['address']) . '</p>';
                    if ( array_key_exists( 'solutions', $o ) && is_array($o['solutions']) ) {
                         $popups[$key] .= '<ul>';
                         foreach( $o['solutions'] as $s ) {
                              if ( isset($s['solution']->post_title) && "" !== $s['solution']->post_title ) {
                                   // $popups[$key] .= '<li><a href="'. get_permalink($s['solution']->ID) . '">' . esc_attr($s['solution']->post_title) . '</a></li>';
                                   $popups[$key] .= '<li>' . esc_attr($s['solution']->post_title) . '</li>';
                              }
                         }
                         $popups[$key] .= '</ul>';
                    }
               }
               
          }
     }

     // get countires
     $countries = dfdl_get_countries();
     foreach( $countries as $c ) {
          $post_title = get_the_title($c);
          $country = str_replace(" ", "_", strtolower($post_title));
          $output[] = '<li id="link-' . $country . '">';
          $output[] = '<a data-country="' . $country . '" href="' . get_permalink($c) . '">'  ;
          $output[] = '<span>&#x2022;</span>' . ' ' . $post_title;
          $output[] = '</a>';
          $output[] = '</li>';
     }
?>
<div class="dfdl-countries-stage callout">
     <div class="dfdl-countries silo">
          <h2><?php echo $title ?></h2>
          <h3><?php echo $subtitle ?></h3>
          <div class="stage">
               <div class="map">
                    <div id="popup">
                         <h4>City name</h4>
                         <ul>
                              <li>Solution 1</li>
                              <li>Solution 2</li>
                              <li>Solution 3</li>
                         </ul>
                    </div>
                    <object id="dfdl-map" type="image/svg+xml" data="<?php echo get_stylesheet_directory_uri() ?>/assets/media/dfdl-map-2023.svg"></object>
               </div>
               <div id="country-list" class="countries">
                    <ul><?php echo implode($output) ?></ul>
               </div>
          </div>
     </div>
</div>
<script>

window.addEventListener("load", function() {

     var popups = <?php echo json_encode($popups) ?>
     
     var dfdl_offices = {
          'bangladesh': ['dhaka'],
          'cambodia': ['phnom_penh'],
          'indonesia': ['jakarta'],
          'lao_pdr': ['vientiane'],
          'myanmar': [ 'yangoon', 'naypyidaw' ],
          'philippines': ['manilla'],
          'singapore' : ['singapore_city'],
          'thailand' : [ 'bangkok' ],
          'vietnam' : [ 'hanoi', 'ho_chi_minh' ],
     }
     var svgObject = document.getElementById('dfdl-map').contentDocument;
     var svg = svgObject.getElementById('mapsvg');

     document.querySelectorAll('#country-list ul a').forEach(function(el) {
          el && el.addEventListener("mouseover", function() {
               var country = this.getAttribute('data-country');
               var country_pin = svg.getElementById( dfdl_offices[country][0] );
               // do_map( country_pin );
               hilite(country);
          });
     });
     document.querySelectorAll('#country-list ul a').forEach(function(el) {
          el && el.addEventListener("mouseleave", function() {
               var country = this.getAttribute('data-country');
               var country_pin = svg.getElementById( dfdl_offices[country][0] );
               reset_map(country_pin);
          });
     });  
     svg.querySelectorAll('.map-pin').forEach(function(el) {
          el && el.addEventListener("mouseover", function() {
               do_map(this);
          });
     });
     svg.querySelectorAll('.map-pin').forEach(function(el) {
          el && el.addEventListener("mouseleave", function() {
               reset_map(this);
          });
     });
     function hilite(country) {
          svg.querySelectorAll('.country').forEach(function(el) {
               el.classList.add("disabled");
          });
          svg.querySelectorAll('.other').forEach(function(el) {
               el.classList.add("disabled");
          });
          svg.querySelectorAll('.other1').forEach(function(el) {
               el.classList.add("disabled");
          });
          svg.querySelectorAll('.map-pin').forEach(function(el) {
               el.classList.add("disabled");
          });
          svg.getElementById(country).classList.remove("disabled");
          svg.querySelectorAll( "#" + country + " .map-pin ").forEach(function(el) {
               el.classList.remove("disabled");
          });
     };


     function do_map(el) {
          svg.querySelectorAll('.country').forEach(function(el) {
               el.classList.add("disabled");
          });
          svg.querySelectorAll('.other').forEach(function(el) {
               el.classList.add("disabled");
          });
          svg.querySelectorAll('.other1').forEach(function(el) {
               el.classList.add("disabled");
          });
          svg.querySelectorAll('.map-pin').forEach(function(el) {
               el.classList.add("disabled");
          });
          svg.getElementById(el.id).classList.remove("disabled");
          svg.getElementById(el.getAttribute('data-country')).classList.remove("disabled");
          document.getElementById("link-" + el.getAttribute('data-country')).classList.add("hilite");
          show_popup(el);
     }
     function reset_map(el) {
          svg.querySelectorAll('.country').forEach(function(el) {
               el.classList.remove("disabled");
          });
          svg.querySelectorAll('.other').forEach(function(el) {
               el.classList.remove("disabled");
          });
          svg.querySelectorAll('.map-pin').forEach(function(el) {
               el.classList.remove("disabled");
          });
          document.getElementById("link-" + el.getAttribute('data-country') ).classList.remove("hilite");
          document.getElementById("popup").classList.remove("show");
     }
     function show_popup(el) {
          var pin   = el.getBoundingClientRect();
          var popup = document.getElementById("popup");
          var popup_coords = popup.getBoundingClientRect();
          var pin_half  = pin.width/2;
          var pin_center = pin.x + pin_half;
          var box_half  = popup_coords.width/2;
          if ( el.getAttribute('data-country') == "bangladesh" ) {
               popup.style.left = pin_center - 20 + "px";
          } else if ( el.getAttribute('data-country') == "myanmar") {
               popup.style.left = pin_center - 80 + "px";
          } else {
               popup.style.left = pin_center - box_half + "px";
          }
          popup.style.top =  pin.top + pin.height + 4 + "px";
          popup.innerHTML = popups[el.id];
          popup.classList.add("show"); 
     }
});

</script>
<?php }); // close K4 fragment ?>