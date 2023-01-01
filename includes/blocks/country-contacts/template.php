<?php

     $address = "";
     $contact = "";
     $hours   = "";

     // get fields
     if ( function_exists('get_fields') ) {
          $address = get_field('address');
          $contact = get_field('contact');
          $hours   = get_field('hours');
     }

?>
<div class="country-contacts-stage callout">
     <div class="country-contacts narrow">
          <div class="address">
               <h3>Address</h3>
               <?php echo $address ?>
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
