    <footer>
        <div class="footer_widgets">
            <div class="row">
                <?php
                if(is_active_sidebar('bm_footersidebar')) {
                    dynamic_sidebar('bm_footersidebar');
                }
                ?>
            </div>
        </div>
        <div class="main_footer">
            <div class="main_footer_left">
                Todos os direitos reservados.
            </div>
            <div class="main_footer_right">
                Criado por Mayara.
            </div>
            <div class="main_footer_scroll">
                <img src="<?php echo get_template_directory_uri().'/assets/images/seta-up.png'?>"/>
            </div>
        </div>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>
