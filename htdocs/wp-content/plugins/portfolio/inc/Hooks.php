<?php

namespace Portfolio;

class Hooks
{
    public function register()
    {
        add_filter('post_type_link', array($this, 'portfolio_permalink'), 10, 3);
        add_filter( 'comments_open', '__return_false', 10 , 2 );
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

}