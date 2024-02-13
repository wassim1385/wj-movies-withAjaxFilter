<?php

/**
*Plugin Name: Wassim Films
*Plugin URI: https://wordpress.org/wj-movies
*Description: My plugin's description
*Version: 1.0
*Requires at least: 5.6
*Author: Wassim Jelleli
*Author URI: https://www.linkedin.com/in/wassim-jelleli/
*Text Domain: wj-movies
*Domain Path: /languages
*/

if ( ! defined ( 'ABSPATH' ) ) {
    exit;
}

if( ! class_exists( 'WJ_Movies' ) ) {

    class WJ_Movies {

        public function __construct() {

            $this->define_constants();
            add_filter ('page_template', array($this, 'movies_page_template'));
            require_once(WJ_MOVIES_PATH . 'cpt/wj-movies-cpt.php');
            $wj_movies_cpt = new WJ_Movies_CPT();
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_action( 'wp_ajax_filter', array( $this, 'filter_ajax' ) );
            add_action( 'wp_ajax_nopriv_filter', array( $this, 'filter_ajax' ) );
        }

        public function define_constants() {

            define( 'WJ_MOVIES_PATH', plugin_dir_path( __FILE__ ) );
            define( 'WJ_MOVIES_URL', plugin_dir_url( __FILE__ ) );
            define( 'WJ_MOVIES_VERSION', '1.0.0' );
        }

        public function enqueue_scripts() {
            wp_enqueue_style('movies-style', WJ_MOVIES_URL . 'assets/style.css');
            if(is_page('our-movies')) {
                wp_enqueue_script('movies-js', WJ_MOVIES_URL . 'assets/movies.js', array('jquery'), WJ_MOVIES_VERSION, true);
                wp_localize_script( 'movies-js', 'MOVIES', array(
                    'movies_url' => admin_url( 'admin-ajax.php' )
                ) );
            }

        }

        public function movies_page_template() {
			global $post;
            if (is_page('our-movies')) {
                $template = WJ_MOVIES_PATH . 'templates/our-movies.php';
            } 
            return $template;
        }

        public function filter_ajax() {

            $args = array(
                'post_type' => 'wj-movies',
                'posts_per_page' => -1,
                'status' => 'publish'
            );

            $title = $_POST['movie-title'];
            $type = $_POST['genre'];
            $tags = $_POST['films-keywords'];
            //var_dump($title, $type, $tags);

            if( ! empty( $title ) ) {
                $args['s'] = $title;
            }

            if( ! empty( $type ) ) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'film_genre',
                    'field' => 'slug',
                    'terms' => $type
                );
            }
            if( ! empty( $tags ) ) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'film_keywords',
                    'field' => 'slug',
                    'terms' => $tags
                );
            }
            $films = new WP_Query( $args );
            if( $films->have_posts() ) : ?>
                    <?php while( $films->have_posts() ) : $films->the_post(); ?>
                    <article class="film">
                        <?php if( has_post_thumbnail() ) { ?>
                            <picture><a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url('movie-poster'); ?>" alt="<?php the_title(); ?>" class="img-fluid"></a></picture>
                        <?php } ?>
                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <?php $cats = get_the_terms( get_the_ID(), 'film_genre' ); 
                            if( ! empty( $cats ) ) {
                                foreach( $cats as $cat ) { ?>
                                    <span><b>Category:</b> <a href="<?php echo get_term_link( $cat, 'film_genre' ); ?>"><?php echo $cat->name; ?></a></span>
                                <?php }
                            }
                        ?>
                    </article>
                    <?php endwhile; wp_reset_postdata(); ?>

            <?php endif; wp_die();
                
        }

        public static function activate() {

            update_option( 'rewrite_rules', '' );
			global $post;
            if( $post->post_name !== 'our-movies' ) {

                $current_user = wp_get_current_user();
                $page = array(
                    'post_title' => esc_html__( 'Our Movies', 'wj-movies' ),
                    'post_name' => 'our-movies',
                    'post_status' => 'publish',
                    'post_author' => $current_user->ID,
                    'post_type' => 'page'
                );
                wp_insert_post( $page );
            }
        }

        public static function deactivate() {

            flush_rewrite_rules();
            unregister_post_type( 'wj-movies' );
        }

        public static function uninstall() {
            
        }

    }
}

if( class_exists( 'WJ_Movies' ) ) {

    register_activation_hook( __FILE__, array( 'WJ_Movies', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'WJ_Movies', 'deactivate' ) );
    register_uninstall_hook( __FILE__, array( 'WJ_Movies', 'uninstall' ) );

    $wj_movies = new WJ_Movies();
}

?>