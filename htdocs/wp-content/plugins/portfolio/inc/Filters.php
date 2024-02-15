<?php

namespace Portfolio;

class Filters
{
    public function register()
    {
        add_action('pre_get_posts', [$this, 'pre_get_posts']);
    }

    public function pre_get_posts($query)
    {
        if ( is_admin() || !$query->is_main_query() || empty($_POST)) {
            return;
        }

        if (is_post_type_archive(PostType::SLUG)) {
            foreach ($_POST as $name => $value) {
                if (str_contains($name, 'filter') && !empty($value)) {
                    $key = str_replace('filter-', '', $name);
                    $query->set('meta_key', $key);
                    $query->set('meta_value', $value);
                }
            }
        }
    }

    public static function display()
    {
        include __DIR__ . '/../public/templates/filters.php';
    }
}