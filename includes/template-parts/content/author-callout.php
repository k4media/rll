<div class="dfdl-single-member author member">
    <img src="<?php echo esc_url($author['avatar']) ?>">
    <div class="details-stage">
        <div class="details">
            <div class="name"><?php echo esc_attr($author['name']) ?></div>
            <?php if( isset($author['position']) && "" !== $author['position']) : ?>
                <div class="position"><?php echo esc_attr($author['position']) ?></div> 
            <?php endif; ?>
            <?php if( isset($author['location']) && "" !== $author['location']) : ?>
                <div class="location"><?php echo esc_attr($author['location']) ?></div>
            <?php endif; ?>
            <?php if( isset($author['bio']) && "" !== $author['bio']) : ?>
                <div class="bio"><?php echo esc_attr($author['bio']) ?></div>
            <?php endif; ?>
            <?php if( isset($author['link']) && "" !== $author['link'] ) : ?>
                <div class="link"><a href="<?php echo esc_url($author['link']) ?>">View Profile</a></div>
            <?php endif; ?>
        </div>
    </div>
</div>