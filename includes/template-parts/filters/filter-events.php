<div id="filters-stage" class="events-filters-stage filters-stage silo">
    <div class="stage">
        <div class="events-filters filters">
            <div class="col col1 award-solutions">
                <h4>Event Type</h4>
                <?php do_action("dfdl_filter", "insights_events") ?>
            </div>
        </div>
    </div>
</div>
<script>
img = new Image();
img.src = '<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-filter-active.svg';
jQuery( document ).ready(function() {
    jQuery("#insights_events").select2({
        closeOnSelect : false,
        allowHtml: true,
        allowClear: false,
        placeholder: "All",
        tags: true
    });
});
</script>