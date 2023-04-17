<div id="filters-stage" class="awards-filters-stage filters-stage silo">
    <div class="stage">
        <div class="awards-filters filters">
            <div class="col col1 award-types">
                <h4>Award Types</h4>
                <?php do_action("dfdl_filter", "award_bodies") ?>
            </div>
            <div class="col col2 award-solutions">
                <h4>Years</h4>
                <?php do_action("dfdl_filter", "award_years") ?>
            </div>
        </div>
    </div>
</div>
<script>
img = new Image();
img.src = '<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-filter-active.svg';
jQuery( document ).ready(function() {
    jQuery("#award_bodies, #award_solutions, #award_years").select2({
        closeOnSelect : false,
        allowHtml: true,
        allowClear: false,
        placeholder: "All",
        tags: true
    });
});
</script>