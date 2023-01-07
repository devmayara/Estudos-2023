<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1,user=scalable=no"/>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header>
        <div class="top_header">
            <nav class="navbar navbar-expand-sm">

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

                <div class="container">

                    <!-- <div class="navbar-header">
                        <div class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </div>
                    </div> -->

                    <div class="collapse navbar-collapse" id="navbar">
                        <?php
                            if(has_nav_menu('top')) {
                                wp_nav_menu(array(
                                    'theme_location' => 'top',
                                    'container' => false,
                                    'fallback_cb' => false,
                                    'menu_class' => 'navbar-nav mr-auto'
                                ));
                            }
                        ?>
                    </div>

                </div>
            </nav>
        </div>
        <div class="main_header">
            <div class="container">
                <div class="logo">
                    <?php
                        if(has_custom_logo()) {
                            the_custom_logo();
                        }
                    ?>
                </div>
                <div class="main_nav_border">
                    <div class="main_nav">
                        <?php 
                            if(has_nav_menu('primary')) {
                                wp_nav_menu(array(
                                    'theme_location' => 'primary',
                                    'container' => false,
                                    'fallback_cb' => false,
                                    'menu_class' => 'navbar-nav bd-navbar-nav flex-row'
                                ));
                            }
                        ?>
                        <div class="search_area">
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                    <div class="main_info">
                        <div class="row">
                            <div class="col-sm-8 random_post">
                                <strong>Você já viu?</strong>
                                <?php
                                $array = array();
                                $bm_query = new WP_Query(array(
                                    'posts_per_page' => 1,
                                    'post_type' => 'post',
                                    'orderby' => 'rend'
                                ));
                                if($bm_query->have_posts()) {
                                    while($bm_query->have_posts()) {
                                        $bm_query->the_post();
                                        ?>
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        <?php
                                    }
                                    wp_reset_postdata();
                                }
                                ?>
                            </div>
                            <div class="col-sm-4 social_area">
                                <div class="social_txt">
                                    SIGA:
                                </div>
                                <div class="social_icons">
                                    <?php if(get_theme_mod('bm_facebook')): ?>
                                        <a href="<?php echo get_theme_mod('bm_facebook'); ?>" target="_blank">
                                            <img src="<?php echo get_template_directory_uri().'/assets/images/facebook.png'; ?>">
                                        </a>
                                    <?php endif; ?>
                                    <a href="https://google.com" target="_blank">
                                        <img src="<?php echo get_template_directory_uri().'/assets/images/gplus.png'; ?>">
                                    </a>
                                    <a href="https://instagram.com" target="_blank">
                                        <img src="<?php echo get_template_directory_uri().'/assets/images/instagram.png'; ?>">
                                    </a>
                                    <a href="https://twitter.com" target="_blank">
                                        <img src="<?php echo get_template_directory_uri().'/assets/images/twitter.png'; ?>">
                                    </a>
                                    <a href="https://youtube.com" target="_blank">
                                        <img src="<?php echo get_template_directory_uri().'/assets/images/youtube.png'; ?>">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>