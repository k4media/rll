<div class="author">
    <img src="<?php echo get_avatar_url($user->data->ID, array('size' => 320)) ?>">
    <div class="details-stage">
        <div class="details">
            <div class="name"><?php echo $user->data->display_name ?></div>
            <?php if( isset($position) ) : ?>
                <div class="position"><?php echo $position ?></div> 
            <?php endif; ?>
            <?php if( is_array($locations) && count($locations) > 0 ) : ?>
                <div class="location"><?php echo implode(", ", $locations) ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

