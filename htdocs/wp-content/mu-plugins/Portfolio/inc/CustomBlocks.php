<?php

namespace Portfolio;
class CustomBlocks
{
    public function register()
    {
        add_action('enqueue_block_assets', array($this, 'enqueue_portfolio_block_styles'));
        add_action('init', array($this, 'register_portfolio_block'));
        add_action('init', array($this, 'register_portfolio_specific_block'));

    }

    public function enqueue_portfolio_block_styles()
    {
        wp_enqueue_style(
            'portfolio-block-styles',
            plugin_dir_url(__FILE__) . '../css/portfolio-block-styles.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_style(
            'portfolio-styles',
            plugin_dir_url(__FILE__) . '../css/style.css',
            array(),
            '1.0.0'
        );
    }

    public
    function register_portfolio_block()
    {
        register_block_type(
            'custom/portfolio-block',
            array(
                'editor_script' => 'portfolio-block-editor',
                'render_callback' => array($this, 'render_portfolio_block'),
            )
        );

        wp_enqueue_script(
            'portfolio-block-editor',
            plugin_dir_url(__FILE__) . '../js/portfolio-block-editor.js',
            array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'),
            true
        );
    }

    public
    function render_portfolio_block($attributes)
    {
        $query = new \WP_Query(array(
            'post_type' => 'portfolio',
            'posts_per_page' => 5,
        ));

        if ($query->have_posts()) {
            $output = '<h3>Mes derniers projets</h3><ul class="portfolio-block">';
            while ($query->have_posts()) {
                $query->the_post();
                $project_completion_date = get_post_meta(get_the_ID(), '_project_completion_date', true);
                $terms = get_the_terms(get_the_ID(), 'portfolio_category');
                $output .= '<li class="portfolio-item">';
                $output .= '<a href="' . get_permalink() . '">';
                $output .= '<div class="portfolio-thumbnail">' . get_the_post_thumbnail() . '</div>';
                $output .= '<h2>' . get_the_title() . '</h2>';
                if (!empty($terms) && !is_wp_error($terms)) {
                    $output .= '<p>Catégorie : ';
                    foreach ($terms as $term) {
                        $output .= '<span class="portfolio-category">' . esc_html($term->name) . '</span>';
                    }
                    $output .= '</p>';
                }
                if ($project_completion_date) {
                    $output .= '<p>Date de réalisation : ' . esc_html($project_completion_date) . '</p>';
                }

                $output .= '</a>';
                $output .= '</li>';
            }
            $output .= '</ul>';
            wp_reset_postdata();
            return $output;
        } else {
            return '<p>Aucun projet trouvé</p>';
        }
    }

    public function register_portfolio_specific_block()
    {
        register_block_type(
            'custom/portfolio-specific',
            array(
                'render_callback' => array($this, 'render_portfolio_specific_block'),
            )
        );

        wp_enqueue_script(
            'portfolio-block',
            plugin_dir_url(__FILE__) . '../js/portfolio-block.js',
            array('wp-blocks', 'wp-components', 'wp-editor'),
            true
        );
    }

    function render_portfolio_specific_block($attributes)
    {
        $projectId = $attributes['projectId'];

        $project_post = get_post($projectId);

        if ($project_post) {
            $output = '<div class="portfolio-specific-block">';
            $output .= '<h2>' . esc_html($project_post->post_title) . '</h2>';
            $output .= apply_filters('the_content', $project_post->post_content);
            $output .= '</div>';

            return $output;
        } else {
            return '<p>Projet non trouvé</p>';
        }
    }
}