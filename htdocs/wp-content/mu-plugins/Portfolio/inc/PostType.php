<?php

namespace Portfolio;

class PostType
{
    public const SLUG = 'portfolio';

    public function register(): void
    {
        add_action('init', function () {
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
                'supports'     => array(
                    'title',
                    'editor',
                    'excerpt',
                    'author',
                    'thumbnail',
                    'comments',
                    'revisions',
                    'custom-fields',
                ),
                'show_in_rest' => true,
                'hierarchical' => false,
                'public'       => true,
                'has_archive'  => true,
                'rewrite'      => array('slug' => self::SLUG),
                'show_in_nav_menus' => true,
                'menu_position' => 5,
                'menu_icon' => 'dashicons-portfolio',
            );

            register_post_type( self::SLUG, $args );
        });
    }
}