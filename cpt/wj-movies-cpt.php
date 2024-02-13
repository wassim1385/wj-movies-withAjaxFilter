<?php

if( ! class_exists( 'WJ_Movies_CPT' ) ) {

    class WJ_Movies_CPT {

        public function __construct() {

            add_action( 'init', array( $this, 'create_post_type' ) );
            add_action( 'init', array( $this, 'register_taxonomies' ) );
            add_action( 'init', array( $this, 'register_tags' ) );
        }

        public function create_post_type() {

            register_post_type(
                'wj-movies',
                array(
                    'label' => 'Movies',
                    'description'   => 'Movies',
                    'labels' => array(
                        'name'  => 'Movies',
                        'singular_name' => 'Movie'
                    ),
                    'public'    => true,
                    'supports'  => array( 'title', 'editor', 'thumbnail' ),
                    'hierarchical'  => false,
                    'show_ui'   => true,
                    'show_in_menu'  => true,
                    'rewrite' => [ 'slug' => 'movie' ],
                    'menu_position' => 5,
                    'show_in_admin_bar' => true,
                    'show_in_nav_menus' => true,
                    'can_export'    => true,
                    'has_archive'   => true,
                    'exclude_from_search'   => false,
                    'publicly_queryable'    => true,
                    'show_in_rest'  => false, //New Gutenberg Editor
                    'menu_icon' => 'dashicons-format-video'
                )
            );
        }

        public function register_taxonomies() {

            register_taxonomy(
                'film_genre',
                'wj-movies',
                array(
                    'hierarchical' => true,
                    'labels' => array(
								'name' => 'Genres',
								'singular_name' => 'Genre',
								'menu_name' => 'Genres',
								),
					'show_ui' => true,
					'show_admin_column' => true,
					'rewrite' => array( 'slug' => 'genre' )
                )
            );
        }

        public function register_tags() {

            register_taxonomy(
                'film_keywords',
                'wj-movies',
                array(
                    'hierarchical' => false,
                    'labels' => array(
                                'name' => 'Keywords',
                                'singular_name' => 'Keyword',
                                'menu_name' => 'Keywords',
                                ),
                    'show_ui' => true,
                    'show_admin_column' => true,
                    'rewrite' => array( 'slug' => 'keyword' )
                )
            );
        }
    }
}