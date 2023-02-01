<li class="filter-button">
    <button id="awards-filters-toggle" class="button filter awards-filter">Filter</button>
</li>
<div id="awards-filters-stage" class="awards-filters-stage filters-stage silo">
    <div class="awards-filters">
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
    <button id="awards-filters-submit" class="button filter submit">Filter</button>
</div>
