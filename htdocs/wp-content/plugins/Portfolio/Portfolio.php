<?php
/*
Plugin Name: Portfolio
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: Il est super cool ce plugin
Author: Arthur
Version: 1.0.0
Author URI: http://arthur.com
*/
class Portfolio {

    public function init() {
        add_action('init', array($this, 'register_portfolio'));
        add_action('enqueue_block_assets', array($this, 'enqueue_portfolio_block_styles'));
        add_action('init', array($this, 'create_portfolio_taxonomies'), 0);
        add_filter('post_type_link', array($this, 'portfolio_permalink'), 10, 3);
        add_action('init', array($this, 'register_portfolio_block'));
        add_filter('register_post_type_args', array($this, 'disable_comments_for_portfolio'), 10, 2);
        add_action('save_post_portfolio', array($this, 'save_project_completion_date'));
        add_action('add_meta_boxes', array($this, 'add_project_completion_date_metabox'));
        add_action('save_post_portfolio', array($this, 'save_project_completion_date'));
        add_action('init', array($this, 'register_portfolio_specific_block'));

    }

    public function register_portfolio() {
        $labels = array(
            'name' => 'Portfolio',
            'all_items' => 'Tous les projets',
            'singular_name' => 'Projet',
            'add_new_item' => 'Ajouter un projet',
            'edit_item' => 'Modifier le projet',
            'menu_name' => 'Portfolio'
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_in_rest' => true,
            'has_archive' => true,
            'supports' => array( 'title', 'editor','thumbnail','comments'),
            'menu_position' => 5,
            'menu_icon' => 'dashicons-portfolio',
        );

        register_post_type( 'portfolio', $args );
    }

    public function enqueue_portfolio_block_styles() {
        wp_enqueue_style(
            'portfolio-block-styles',
            plugin_dir_url( __FILE__ ) . 'css/portfolio-block-styles.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_style(
            'portfolio-styles',
            plugin_dir_url( __FILE__ ) . 'css/style.css',
            array(),
            '1.0.0'
        );
    }

    public function create_portfolio_taxonomies() {
        $labels = array(
            'name' => 'Catégories de Projet',
            'singular_name' => 'Catégorie de Projet',
            'search_items' => 'Rechercher par catégorie',
            'all_items' => 'Toutes les catégories',
            'parent_item' => 'Catégorie parente',
            'parent_item_colon' => 'Catégorie parente :',
            'edit_item' => 'Modifier la catégorie',
            'update_item' => 'Mettre à jour la catégorie',
            'add_new_item' => 'Ajouter une nouvelle catégorie',
            'new_item_name' => 'Nom de la nouvelle catégorie',
            'menu_name' => 'Catégories de Projet',
        );

        register_taxonomy(
                'portfolio_category',
            'portfolio',
            array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => array( 'slug' => 'portfolio-category' ),
                'show_in_rest' => true,
            )
        );
    }

    public function portfolio_permalink( $permalink, $post, $leavename ) {
        if ( $post->post_type == 'portfolio' && !empty( $permalink ) && strpos( $permalink, '%portfolio_category%' ) === false ) {
            $terms = get_the_terms( $post->ID, 'portfolio_category' );
            if ( !is_wp_error( $terms ) && !empty( $terms ) && is_object( $terms[0] ) )
                $portfolio_category = $terms[0]->slug;
            else
                $portfolio_category = 'uncategorized';

            $permalink = str_replace( '%portfolio_category%', $portfolio_category, $permalink );
        }
        return $permalink;
    }

    public function register_portfolio_block() {
        register_block_type(
            'custom/portfolio-block',
            array(
                'editor_script'   => 'portfolio-block-editor',
                'render_callback' => array($this, 'render_portfolio_block'),
            )
        );

        wp_enqueue_script(
            'portfolio-block-editor',
            plugin_dir_url( __FILE__ ) . 'js/portfolio-block-editor.js',
            array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
            true
        );
    }

    public function render_portfolio_block( $attributes ) {
        $query = new WP_Query( array(
            'post_type'      => 'portfolio',
            'posts_per_page' => 5,
        ) );

        if ( $query->have_posts() ) {
            $output = '<h3>Mes derniers projets</h3><ul class="portfolio-block">';
            while ( $query->have_posts() ) {
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

    public function disable_comments_for_portfolio( $args, $post_type ) {
        if ( 'portfolio' === $post_type ) {
            $args['supports'] = array_diff( $args['supports'], array( 'comments' ) );
        }

        remove_post_type_support('portfolio', 'comments');

        return $args;
    }

    public function save_project_completion_date($post_id) {
        if (isset($_POST['_project_completion_date'])) {
            update_post_meta($post_id, '_project_completion_date', sanitize_text_field($_POST['_project_completion_date']));
        }
        if (isset($_POST['portfolio_category'])) {
            $term_id = (int)$_POST['portfolio_category'];
            if (isset($_POST['portfolio_category'])) {
                update_term_meta($term_id, '_portfolio_category_color', sanitize_hex_color($_POST['_portfolio_category_color']));
            }
        }
    }

    public function add_project_completion_date_metabox() {
        add_meta_box(
            'project_completion_date_metabox',
            'Date de réalisation du projet',
            array($this, 'project_completion_date_metabox_callback'),
            'portfolio',
            'normal',
            'high'
        );
    }
    public function project_completion_date_metabox_callback($post)
    {
        $project_completion_date = get_post_meta($post->ID, '_project_completion_date', true);
        echo '<label for="project_completion_date">Date de réalisation :</label>';
        echo '<input type="date" id="project_completion_date" name="_project_completion_date" value="' . esc_attr($project_completion_date) . '">';
    }
    public function register_portfolio_specific_block() {
        register_block_type(
            'custom/portfolio-specific',
            array(
                'render_callback' => array($this, 'render_portfolio_specific_block'),
            )
        );

        wp_enqueue_script(
            'portfolio-block',
            plugin_dir_url(__FILE__) . 'js/portfolio-block.js',
            array('wp-blocks', 'wp-components', 'wp-editor'),
            true
        );
    }

    function render_portfolio_specific_block($attributes) {
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

(new Portfolio())->init();

