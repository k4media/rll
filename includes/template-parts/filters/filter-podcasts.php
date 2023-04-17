<div id="filters-stage" class="podcasts-filters-stage filters-stage silo">
    <div class="stage">
        <div class="podcasts-filters filters">
            <div class="col col1 podcasts-solutions">
                <h4>Solutions</h4>
                <?php do_action("dfdl_filter", "insights_solutions") ?>
            </div>
            <div class="col col3 podcasts-years">
                <h4>Years</h4>
                <?php do_action("dfdl_filter", "insights_years") ?>
            </div>
        </div>
    </div>
</div>
<script>
img = new Image();
img.src = '<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-filter-active.svg';
jQuery(document).ready(function() {
    jQuery("#insights_solutions,  #insights_years").select2({
        closeOnSelect : false,
        allowHtml: true,
        allowClear: false,
        placeholder: "All",
        tags: true
    });
});
jQuery(window).unload(function(){
    jQuery("#insights_solutions,  #insights_years").val(null).trigger('change');
});
</script>