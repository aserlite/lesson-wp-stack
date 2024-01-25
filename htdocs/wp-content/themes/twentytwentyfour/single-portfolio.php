<?php
/*
Template Name: Single Portfolio
*/
?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php while (have_posts()) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title rotate">', '</h1>'); ?>
                </header>

                <?php
                $terms = get_the_terms(get_the_ID(), 'portfolio_category');
                if ($terms && !is_wp_error($terms)) {
                    echo '<ul class="portfolio-taxonomy-list">';
                    foreach ($terms as $term) {
                        echo '<li><a href="' . get_term_link($term) . '">' . esc_html($term->name) . '</a></li>';
                    }
                    echo '</ul>';
                }
                ?>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>

        <?php endwhile; ?>

    </main>
</div>

<?php get_footer(); ?>
