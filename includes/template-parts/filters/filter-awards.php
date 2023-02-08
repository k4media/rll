<li class="filter-button"><button id="awards-filters-toggle" class="button filter awards-filter">Filter</button></li>
<div id="awards-filters-stage" class="awards-filters-stage filters-stage silo">
    <div class="stage">
        <div class="awards-filters filters">
            <div class="col col1 award-types">
                <h4>Award Types</h4>
                <?php do_action("dfdl_filter", "award_bodies") ?>
            </div>
            <div class="col col1 award-solutions">
                <h4>Solutions</h4>
                <?php do_action("dfdl_filter", "award_solutions") ?>
            </div>
            <div class="col col1 award-years">
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