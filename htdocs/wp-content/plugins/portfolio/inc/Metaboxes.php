<?php

namespace Portfolio;

class Metaboxes
{
    public function register()
    {
        add_action('save_post_portfolio', array($this, 'save_project_completion_date'));
        add_action('add_meta_boxes', array($this, 'add_project_completion_date_metabox'));
        add_action('save_post_portfolio', array($this, 'save_project_completion_date'));
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
}