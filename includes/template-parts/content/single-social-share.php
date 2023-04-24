<div id="social-share" class="social-share">
    <label>Share</label>
    <button id="share-linkedin" class="linked-in" onclick="window.open( 'https://www.linkedin.com/sharing/share-offsite/?url=<?php echo get_permalink() ?>', 'sharer', 'toolbar=0, status=0, width=626, height=436');return false;" title="Share on LinkedIn">
        <img id="linkedinImg" src="<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-linkedin-green.svg">
    </button>
    <button id="share-facebook"  class="facebook" onclick="window.open( 'https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink() ?>', 'sharer', 'toolbar=0, status=0, width=626, height=436');return false;" title="Share on Facebook">
        <img id="facebookImg" src="<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-facebook-green.svg">
    </button>
    <button id="share-twitter"  onclick="window.open( 'https://twitter.com/intent/tweet?text=<?php echo get_the_title() . ' ' . get_permalink() . ' via @DFDLLegalandTax'; ?>', 'sharer', 'toolbar=0, status=0, width=626, height=436');return false;" title="Share on Twitter" class="twitter">
        <img id="twitterImg" src="<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-twitter-green.svg">
    </button>
    <button id="share-print"  class="print" onclick="javascript:window.print();">
        <img id="printImg" src="<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-print-green.svg">
    </button>
    <button id="share-link"  class="link">
        <img id="linkImg" src="<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-link-green.svg">
    </button>
</div>

<?php
    global $post;
    $permalink = get_permalink($post->ID);
?>
<script>
    var shareLinkedIn = document.getElementById("linkedinImg");
    var shareFacebook = document.getElementById("facebookImg");
    var shareTwitter = document.getElementById("twitterImg");
    var sharePrint = document.getElementById("printImg");
    var shareLink = document.getElementById("linkImg");
    var permalink = '<?php echo $permalink ?>';
    shareLink && shareLink.addEventListener("click", function() {
        copyTextToClipboard(permalink);
    })
    shareLinkedIn && shareLinkedIn.addEventListener("mouseover", function() {shareLinkedIn.src = ajax_object.stylesheet_uri + "/icon-linkedin-active.svg";})
    shareLinkedIn && shareLinkedIn && shareLinkedIn.addEventListener("mouseout", function() {shareLinkedIn.src = ajax_object.stylesheet_uri + "/icon-linkedin-green.svg";})
    shareFacebook && shareFacebook.addEventListener("mouseover", function() {shareFacebook.src = ajax_object.stylesheet_uri + "/icon-facebook-active.svg";})
    shareFacebook && shareFacebook.addEventListener("mouseout", function() {shareFacebook.src = ajax_object.stylesheet_uri + "/icon-facebook-green.svg";})
    shareTwitter && shareTwitter.addEventListener("mouseover", function() {shareTwitter.src = ajax_object.stylesheet_uri + "/icon-twitter-active.svg"; })
    shareTwitter && shareTwitter.addEventListener("mouseout", function() {shareTwitter.src = ajax_object.stylesheet_uri + "/icon-twitter-green.svg";})  
    sharePrint && sharePrint.addEventListener("mouseover", function() {sharePrint.src = ajax_object.stylesheet_uri + "/icon-print-active.svg";})
    sharePrint && sharePrint.addEventListener("mouseout", function() {sharePrint.src = ajax_object.stylesheet_uri + "/icon-print-green.svg";})
    shareLink && shareLink.addEventListener("mouseover", function() {shareLink.src = ajax_object.stylesheet_uri + "/icon-link-active.svg";})
    shareLink && shareLink.addEventListener("mouseout", function() {shareLink.src = ajax_object.stylesheet_uri + "/icon-link-green.svg";})

    function copyTextToClipboard(text) {
        if (!navigator.clipboard) {
            fallbackCopyTextToClipboard(text);
            return;
        }
        navigator.clipboard.writeText(text).then(function() {
            console.log('Async: Copying to clipboard was successful!');
        }, function(err) {
            console.error('Async: Could not copy text: ', err);
        });
    }
    function fallbackCopyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Fallback: Copying text command was ' + msg);
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
        }
        document.body.removeChild(textArea);
    }
</script>