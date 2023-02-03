<?php

     $address = "";
     $contact = "";
     $hours   = "";

     // get fields
     if ( function_exists('get_fields') ) {
          //$address = get_field('address');
          //$contact = get_field('contact');
          //$hours   = get_field('hours');
     }

?>
<div class="awards-callout-stage callout">
     <div class="awards-callout narrow">
          <h2>Awards Placeholder</h2>
          <a href="<?php echo get_home_url(null, '/awards/') ?>">Awards Page</a>
     </div>
</div>
