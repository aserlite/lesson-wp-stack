<?php

namespace Portfolio;

class Taxonomies
{
    public function register()
    {
        add_action('init', [$this, 'categories']);
    }

    public function categories()
    {
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
}