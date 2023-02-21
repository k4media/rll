<section id="<?php echo esc_attr($term->slug)  ?>" class="<?php echo esc_attr($term->slug) ?> callout silo">
    <header>
        <h2 class="title"><?php echo esc_attr($term->name) ?></h2>
        <a href="{archive_link}">View All</a>
    </header>
    <div class="posts">{posts}</div>
</section>