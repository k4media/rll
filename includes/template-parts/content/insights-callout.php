<section id="<?php echo esc_attr($term->slug)  ?>" class="<?php echo esc_attr($term->slug) ?> callout silo">
    <header>
        <h2 class="title"><?php echo esc_attr($term->name) ?></h2>
        <a href="<?php echo get_term_link($term) ?>">View All</a>
    </header>
    <div class="posts">{posts}</div>
</section>