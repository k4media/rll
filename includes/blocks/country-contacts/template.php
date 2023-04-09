<?php

     
     $address   = "";
     $contact   = "";
     $hours     = "";
     $countries = "";

     // get fields
     if ( function_exists('get_fields') ) {
          $address   = get_field('address');
          $contact   = get_field('contact');
          $hours     = get_field('hours');
          $countries = get_field('countries', 'options');
     }
     
     $section = dfdl_get_section();
     if ( is_array($section) ) {
          $map = $countries[end($section)]['map'];
     }
     
?>
<div class="country-contacts-stage callout">
     <div class="country-contacts silo">
          <div class="address">
               <h3>Address</h3>
               <?php echo nl2br($address) ?>
               <?php if ( isset($map) ) : ?>
                    <br><a href='<?php echo $map ?>'>View on Google Maps</a>
               <?php endif; ?>
          </div>
          <div class="contact">
               <h3>Contact</h3>
               <?php echo $contact ?>
          </div>
          <div class="hours">
               <h3>Opening Hours</h3>
               <?php echo $hours ?>
          </div>
     </div>
</div>
