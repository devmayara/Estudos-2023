<?php get_header(); ?>
    <section>
        <div class="container">
            <?php if(have_posts()): ?>
                <?php while(have_posts()): ?>
                    <?php the_post(); ?>
                    
                    <article id="art_post">

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
                        
                        <p>
                            <?php the_content(); ?>
                        </p>

                        <p>
                            <?php comments_number('0 comentários', '1 comentário', '% comentários');?> |
                            <a href="<?php the_permalink(); ?>">LEIA MAIS</a>
                        </p>

                    </article>

                <?php endwhile; ?>
            <?php endif; ?>

            <div class="paginacao">
                <div class="pagina_anterior">
                    <?php previous_posts_link('Página Anterior'); ?>                            
                </div>
                <div class="pagina_proxima">
                    <?php next_posts_link('Próxima Página'); ?>
                </div>
            </div>
        </div>
        
        <?php get_sidebar(); ?>

        <div style="clear: both;"></div>
    </section>
<?php get_footer(); ?>