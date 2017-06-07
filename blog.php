<?php
/* Template Name:  Blog */
get_header();
?>
<div class="content">
    <div class="wrapper">
        <h1 class="title"><?php the_title();?></h1>
        <div class="w75 fll">
			<div class="posts-wrapper">
            <?php
			$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
            query_posts('type_post=post&posts_per_page=12&paged='.$page);
            while (have_posts()): the_post();
                ?>
                <!-- BEGIN .post -->
                <article class="w33 fll post-851 post type-post status-publish format-standard has-post-thumbnail vc_col-sm-4 hentry category-design category-graphic" id="post-851">

                    <div class="post-inner clearfix p10">
                        <header class="post-header">
                            <h2 class="blog_title"><a href="<?php the_permalink();?>"> <?php the_title();?></a></h2>
                        </header>
                        <div class="post-item clearfix">
                            <div class="image-overlay post-overlay <?php if (!has_post_thumbnail()): ?> without_thumb<?php endif; ?>">
							<a class="" href="<?php the_permalink();?>">
                                <?php if (has_post_thumbnail()): ?>
									<?php the_post_thumbnail('blogThumb');?>
                                    <?php else: ?>
                                <img src="<?php echo get_template_directory_uri();?>/img/grey_owl.png" class=""/>

                                    <?php endif; ?>
							</a>
                            </div>
                        </div><!-- / .post-item -->

                        <div class="clearfix"></div>

                        <div class="post-content clearfix">

                            <div class="excerpt"> <?php the_excerpt();?></div>

                            <p> <a class="more-link greybckg btn btn-defaults" href="<?php the_permalink();?>">Continue reading →</a></p>
                            <div class="clear"></div>
                        </div><!-- / .post-content -->
                        <div class="meta-grid clearfix">
                            <div class="meta-inner-grid">
                                <span class="meta-item meta-grid-date"><i class="fa fa-clock-o"></i> <?php if (get_the_time('M'))
        the_time('F');
    else
        the_ID();
    ?> <?php if (get_the_time('d'))
        the_time('d');
    else
        the_ID();
    ?>, <?php if (get_the_time('Y'))
        the_time('Y');
    else
        the_ID();
    ?></span>
                                <!--<span class="meta-item meta-grid-comments"><i class="fa fa-comment-o"></i> <a title="Comment on Practical designer workspace" class="comments-link" href="<?php the_permalink()?>#comments"><?php comments_number('No comments', '1 comment', '% comments');?></a></span>-->
                            </div>
                        </div><!-- / .meta -->
                    </div><!-- / .post-inner -->
                </article><!-- / .post -->			<!-- END .post -->
<?php endwhile;?>
</div>
            <!-- END blog -->
			<?php // Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( '←'),
				'next_text'          => __( '→' ),
				'mid_size'			 => 3,
				
			) );?>
        </div><!-- / .sidebar-inner-content -->
         <script>
                    jQuery(window).load(function() {
                        jQuery('.content').each(function() {
                            jQuery(this).find('.excerpt').equalHeights();
                            jQuery(this).find('.image-overlay').equalHeights();
                            jQuery(this).find('.blog_title').equalHeights();
                            jQuery(this).find('.meta-top').equalHeights();
                        });
                    });
                </script>
        <div class="w20 flr">
<?php get_sidebar('right_column'); //подключаю файл  right_column.php?>
        </div>
    </div><!-- / .wrap -->
    <div class="clear"></div>
</div>

<?php get_footer();?>