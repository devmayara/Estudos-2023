<?php
if (post_password_required()) {
    return;
}

if(have_comments()) {
    foreach($comments as $comment) {
        ?>
        <div class="comentario">
            <div class="comentario_foto">
                <?php echo get_avatar($comment, 60); ?>
            </div>
            <div class="comentario_content">
                <strong><?php comment_author(); ?></strong> - 
                <?php comment_date(); ?> <br>
                <?php comment_text(); ?>
            </div>
        </div>
        
        <?php
    }

    the_comments_pagination();
}

comment_form(array(
    'comment_field' => 'Comentário:<br><textarea name"comment"></textarea>',
    'fields' => array(
        'author' => 'Comentário:<br><input type="text" name="author"/>',
        'email' => 'E-mail:<br><input type="email" name="email"/>',
        'url' => 'Url:<br><input type="text" name="url"/>'
    )
));

?>