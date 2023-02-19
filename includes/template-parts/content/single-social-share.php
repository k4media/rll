<div class="social-share">
    <label>Share</label>
    <button class="linked-in" onclick="window.open( 'https://www.linkedin.com/sharing/share-offsite/?url=<?php echo get_permalink() ?>', 'sharer', 'toolbar=0, status=0, width=626, height=436');return false;" title="Share on LinkedIn">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-linkedin-green.svg">
    </button>
    <button class="facebook" onclick="window.open( 'https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink() ?>', 'sharer', 'toolbar=0, status=0, width=626, height=436');return false;" title="Share on Facebook">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-facebook-green.svg">
    </button>
    <button onclick="window.open( 'https://twitter.com/intent/tweet?text=<?php echo get_the_title() . ' ' . get_permalink() . ' via @DFDLLegalandTax'; ?>', 'sharer', 'toolbar=0, status=0, width=626, height=436');return false;" title="Share on Twitter" class="twitter">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-twitter-green.svg">
    </button>
    <button class="print" onclick="javascript:window.print();">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-print-green.svg">
    </button>
    <button class="link">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-link-green.svg">
    </button>
</div>