<div id="filters-stage" class="teams-filters-stage filters-stage silo">
    <div class="stage">
        <div class="teams-filters filters">
            <div class="col col1 teams-solutions">
                <h4>Solutions</h4>
                <?php do_action("dfdl_filter", "teams_solutions") ?>
            </div>
            <div class="col col3 teams-sort">
                <h4>Sort By</h4>
                <?php do_action("dfdl_filter", "teams_sort") ?>
            </div>
        </div>
    </div>
</div>
<script>
img = new Image();
img.src = '<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-filter-active.svg';
img.src = '<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-search-active.svg';
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
        tags: true,
        minimumResultsForSearch: -1
    });
});
</script>