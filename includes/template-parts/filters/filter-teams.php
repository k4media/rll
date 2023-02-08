<li class="filter-button"><button id="teams-filters-toggle" class="button filter teams-filter">Filter</button></li>
<div id="teams-filters-stage" class="teams-filters-stage filters-stage silo">
    <div class="stage">
        <div class="teams-filters filters">
            <div class="col col1 teams-solutions">
                <h4>Solutions</h4>
                <?php do_action("dfdl_filter", "teams_solutions") ?>
            </div>
            <div class="col col1 teams-sort">
                <h4>Sort By</h4>
                <?php do_action("dfdl_filter", "teams_sort") ?>
            </div>
        </div>
    </div>
</div>
<script>
img = new Image();
img.src = '<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-filter-active.svg';
jQuery( document ).ready(function() {
    jQuery("#teams_solutions").select2({
        closeOnSelect : false,
        allowHtml: true,
        allowClear: false,
        placeholder: "All",
        tags: true
    });
    jQuery("#teams_sort").select2({
        closeOnSelect : false,
        allowHtml: true,
        allowClear: false,
        placeholder: "All",
        tags: true
    });
});
</script>