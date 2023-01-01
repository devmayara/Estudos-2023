<?php get_header(); ?>
    <section>
        <div class="container">
            <?php if(have_posts()): ?>
                <?php while(have_posts()): ?>
                    <?php the_post(); ?>
                    
                    <article id="art_post">

                        <h2 class="title">
                            <?php the_title(); ?>
                        </h2>

                        <?php if(has_post_thumbnail()): ?>
                            <?php the_post_thumbnail('full'); ?>
                        <?php endif; ?>

                        <p>
                            <?php the_content(); ?>
                        </p>

                        <p>
                            <?php comments_number('0 comentários', '1 comentário', '% comentários');?> |
                        </p>

                        <hr>

                        <?php 
                            if (comments_open()) {
                                comments_template();
                            }
                        ?>

                    </article>

                <?php endwhile; ?>
            <?php endif; ?>

            <div class="paginacao">
                <div class="pagina_anterior">
                    <?php previous_post_link(); ?>                            
                </div>
                <div class="pagina_proxima">
                    <?php next_post_link(); ?>
                </div>
            </div>
        </div>
        
        <?php get_sidebar(); ?>

        <div style="clear: both;"></div>
    </section>
<?php get_footer(); ?>