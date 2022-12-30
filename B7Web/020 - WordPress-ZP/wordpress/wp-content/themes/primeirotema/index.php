<?php get_header(); ?>
    <section>
        <div class="container">
            <?php if(have_posts()): ?>
                <?php while(have_posts()): ?>
                    <?php the_post(); ?>
                    
                    <article>

                        <?php if(has_post_thumbnail()): ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('full', array('class' => 'post_miniatura')); ?>
                            </a>
                        <?php endif; ?>

                        <h2 class="title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?> 
                            </a>
                        </h2>

                        <div class="date">
                            Posted on <?php the_time('F jS, Y'); ?> |
                            <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a> |
                            <?php the_category(', '); ?>
                        </div>

                    </article>

                <?php endwhile; ?>
            <?php endif; ?>
        </div>
        
        <?php get_sidebar(); ?>

        <div style="clear: both;"></div>
    </section>
<?php get_footer(); ?>