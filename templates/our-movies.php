<?php get_header(); ?>
    <div class="container">
        <?php echo "<h2>" . get_the_title() . "</h2>"; ?>
        <?php
        $movies = new WP_Query( array(
            'post_type' => 'wj-movies',
            'posts_per_page' => -1,
            'status' => 'publish'
        ) );
        ?>
        <div class="films-template">
            <div class="wj-films-filter">
                <form class="films-filter-form">
                    <label>Search by title</label>
                    <input type="text" name="movie-title" id="movie-title" placeholder="Search by movie title">
                    <?php $terms = get_terms( array( 'taxonomy' => 'film_genre' ) );
                    if ($terms) :
                    ?>
                    <select name="genre" id="genre">
                        <option value="">Select Category</option>
                        <?php foreach( $terms as $term ) : ?>
                        <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>
                    <?php $tags = get_terms( array( 'taxonomy' => 'film_keywords' ) );
                    if ($tags) :
                        foreach( $tags as $tag ) :
                    ?>
                    <input type="checkbox" id="<?php echo $tag->slug ?>" name="films-keywords[<?php echo $tag->term_id; ?>]" value="<?php echo $tag->slug; ?>">
                    <label for="<?php echo $tag->slug ?>"><?php echo $tag->name; ?></label>
                    <?php endforeach; endif; ?>
                    <button>Filter Movies</button>
                    <input type="hidden" name="action" value="filter">
                </form>
            </div>
            <div class="wj-films">
                <?php
                while( $movies->have_posts() ) : $movies->the_post(); ?>
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
                <?php endwhile; ?>
            </div>
        </div>
    </div>
<?php get_footer(); ?>