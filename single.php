<?php get_header();?>
<div class="content pages">
    <div class="wrapper pb50">
        <h1 class="title fcb"><?php the_title();?></h1>
         <div class="single-navigation clearfix">

                    <?php next_post_link('<strong>%link</strong>', 'Previous'); ?>
                    <?php previous_post_link('<strong>%link</strong>', 'Next'); ?>

            </div>
        <?php if (have_posts()): while (have_posts()): the_post();?>
                <?php if (has_post_thumbnail()):?>
                    
                    <?php if(get_post_meta ($post->ID, 'image_align_select', true)){
                            if(get_post_meta ($post->ID, 'image_align_select', true) == 'fn'){$tclass = '';}
                            if(get_post_meta ($post->ID, 'image_align_select', true) == 'flr'){$tclass = 'flr';}
                            if(get_post_meta ($post->ID, 'image_align_select', true) == 'fll'){$tclass = 'fll';}
                        } else {
                            $tclass = 'fll';
                        }?>
                    <div class="tac <?php echo $tclass; ?> thumbnails">
                        <?php the_post_thumbnail('blogThumb');?>
                    </div> 
                <?php endif;?>
                <?php the_content();?>
                <?php
            endwhile;
        endif;
        ?>
    </div>
</div>
<?php get_footer();?>