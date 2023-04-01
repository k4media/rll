<div id="filters-stage" class="news-filters-stage filters-stage silo">
    <div class="stage">
        <div class="news-filters filters">
            <div class="col col1 news-solutions">
                <h4>Solutions</h4>
                <?php do_action("dfdl_filter", "insights_solutions") ?>
            </div>
            <div class="col col2 news-types">
                <h4>News Types</h4>
                <?php do_action("dfdl_filter", "insights_categories") ?>
            </div>
            <div class="col col3 news-years">
                <h4>Years</h4>
                <?php do_action("dfdl_filter", "insights_years") ?>
            </div>
        </div>
    </div>
</div>
<script>
img = new Image();
img.src = '<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-filter-active.svg';
jQuery( document ).ready(function() {
    jQuery("#insights_solutions, #insights_categories, #insights_years").select2('data', null);
    jQuery("#insights_solutions, #insights_categories, #insights_years").select2({
        closeOnSelect : false,
        allowHtml: true,
        allowClear: false,
        placeholder: "All",
        tags: true
    });
});
</script>