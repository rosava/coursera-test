<?php get_header();?>
<?php
global $data;
$portfolio_width = 'full';
?>
<?php
$sidebar_check = get_post_meta($post->ID, 'pyre_sidebar', true);

    $content_css = 'width:100%';
    $sidebar_css = 'display:none';

?>
<div class="portfolio-<?php echo $portfolio_width;?> " style="<?php echo $content_css;?>">
    <div class="wrapper">
        <?php wp_reset_query();?>
        <?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;?>
        <?php query_posts($query_string . '&paged=' . $paged);?>
        <?php
        $nav_categories = '';
        if (isset($_GET['portfolioID']))
            {
            $portfolioID = array($_GET['portfolioID']);
            }
        else
            {
            $portfolioID = '';
            }
        if (isset($_GET['categoryID']))
            {
            $categoryID = $_GET['categoryID'];
            }
        else
            {
            $categoryID = '';
            }
        $page_categories = get_post_meta($portfolioID, 'pyre_portfolio-category', true);
        if ($page_categories && is_array($page_categories) && $page_categories[0] !== '0')
            {
            $nav_categories = implode(',', $page_categories);
            }
        if ($categoryID)
            {
            $nav_categories = $categoryID;
            }
            ?><h1 class="title"><?php the_title();?></h1>
            <?php if (!$data['portfolio_pn_nav']):?>
            <div class="single-navigation clearfix">

                    <?php next_post_link('<strong>%link</strong>', 'Previous',true,"","portfolio-category"); ?>
                    <?php previous_post_link('<strong>%link</strong>', 'Next',true,"","portfolio-category"); ?>

            </div>
<?php endif;?>
                    <?php if (have_posts()): the_post();?>
            <div id="post-<?php the_ID();?>" <?php post_class();?>>
                            <?php
                            if (!$data['portfolio_featured_images']):
                                if ($data['legacy_posts_slideshow']):
                                    $args = array(
                                        'post_type' => 'attachment',
                                        'numberposts' => $data['posts_slideshow_number'] - 1,
                                        'post_status' => null,
                                        'post_parent' => $post->ID,
                                        'orderby' => 'menu_order',
                                        'order' => 'ASC',
                                        'post_mime_type' => 'image',
                                        'exclude' => get_post_thumbnail_id()
                                    );
                                    $attachments = get_posts($args);
                                    if ((has_post_thumbnail() || get_post_meta($post->ID, 'pyre_video', true))):
                                        ?>
                            <div class="flexslider post-slideshow">
                                <ul class="slides">
                <?php if (get_post_meta($post->ID, 'pyre_video', true)):?>
                                        <li class="full-video">
                                        <?php echo get_post_meta($post->ID, 'pyre_video', true);?>
                                        </li>
                <?php endif;?>
                            <?php if (has_post_thumbnail() && !get_post_meta($post->ID, 'pyre_video', true)):?>
                                <?php $attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');?>
                                <?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');?>
                                <?php $attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id());?>
                                        <li>
                                            <a href="<?php echo $full_image[0];?>" rel="prettyPhoto[gallery<?php the_ID();?>]" title="<?php echo get_post_field('post_content', get_post_thumbnail_id());?>"><img src="<?php echo $attachment_image[0];?>" alt="<?php echo get_post_field('post_excerpt', get_post_thumbnail_id());?>" /></a>
                                        </li>
                                    <?php endif;?>
                                    <?php if ($data['posts_slideshow']):?>
                                            <?php foreach ($attachments as $attachment):?>
                                                <?php $attachment_image = wp_get_attachment_image_src($attachment->ID, 'full');?>
                                            <?php $full_image = wp_get_attachment_image_src($attachment->ID, 'full');?>
                                            <?php $attachment_data = wp_get_attachment_metadata($attachment->ID);?>
                                            <li>
                                                <a href="<?php echo $full_image[0];?>" rel="prettyPhoto[gallery<?php the_ID();?>]" title="<?php echo get_post_field('post_content', $attachment->ID);?>"><img src="<?php echo $attachment_image[0];?>" alt="<?php echo get_post_field('post_excerpt', $attachment->ID);?>" /></a>
                                            </li>
                                        <?php endforeach;?>
                <?php endif;?>
                                </ul>
                            </div>
                                <?php endif;?>
                            <?php else:?>
                                <?php
                                if ((1==0)&&((has_post_thumbnail() || get_post_meta($post->ID, 'pyre_video', true)))):
								// 1==0 remove image but leave functionality for future.
                                    ?>
                            <div class="flexslider1 post-slideshow">
                                <ul class="slides">
                                    <?php if (get_post_meta($post->ID, 'pyre_video', true)):?>
                                        <li class="full-video">
                                        <?php echo get_post_meta($post->ID, 'pyre_video', true);?>
                                        </li>
                <?php endif;?>
                <?php if (has_post_thumbnail() && !get_post_meta($post->ID, 'pyre_video', true)):?>
                                        <?php $attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');?>
                                        <?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');?>
                                        <?php $attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id());?>
                                        <li>
                                            <a href="<?php echo $full_image[0];?>" rel="prettyPhoto[gallery<?php the_ID();?>]" title="<?php echo get_post_field('post_content', get_post_thumbnail_id());?>"><img src="<?php echo $attachment_image[0];?>" alt="<?php echo get_post_field('post_excerpt', get_post_thumbnail_id());?>" /></a>
                                        </li>
                            <?php endif;?>
                            <?php if ($data['posts_slideshow']):?>
                                <?php
                                $i = 2;
                                while ($i <= $data['posts_slideshow_number']):
                                    $new_attachment_ID = kd_mfi_get_featured_image_id('featured-image-' . $i, 'avada_portfolio');
                                    if ($new_attachment_ID):
                                        ?>
                                        <?php $attachment_image = wp_get_attachment_image_src($new_attachment_ID, 'full');?>
                                        <?php $full_image = wp_get_attachment_image_src($new_attachment_ID, 'full');?>
                                        <?php $attachment_data = wp_get_attachment_metadata($new_attachment_ID);?>
                                                <li>
                                                    <a href="<?php echo $full_image[0];?>" rel="prettyPhoto[gallery<?php the_ID();?>]" title="<?php echo get_post_field('post_content', $new_attachment_ID);?>"><img src="<?php echo $attachment_image[0];?>" alt="<?php echo get_post_field('post_excerpt', $new_attachment_ID);?>" /></a>
                                                </li>
                                    <?php endif;
                                    $i++;
                                endwhile;?>
                <?php endif;?>
                                </ul>
                            </div>
            <?php endif;?>
                            <?php endif;?>
                        <?php endif; // portfolio single image theme option check ?>
    <?php
    $project_info_style = '';
    $project_desc_style = '';
    $project_desc_title_style = '';
    if (get_post_meta($post->ID, 'pyre_project_details', true) == 'no')
        {
        $project_info_style = 'display:none;';
        }
    if ($portfolio_width == 'full' && get_post_meta($post->ID, 'pyre_project_details', true) == 'no')
        {
        $project_desc_style = 'width:100%;';
        }
    if (get_post_meta($post->ID, 'pyre_project_desc_title', true) == 'no')
        {
        $project_desc_title_style = 'display:none;';
        }
    ?>
                <div class="project-content">
                    <div class="project-description post-content" style="<?php echo $project_desc_style;?>">
                        <h2 style="<?php echo $project_desc_title_style;?>" class="fs22 pt20 fwb heading3">
                            <?php 
                                if('' == get_post_meta($post->ID, 'destitle', 1)){
                                    echo __('Project Description', 'Avada');
                                } else {
                                    echo get_post_meta($post->ID, 'destitle', 1);
                                }
                            ?>
                        </h2>
                        <?php the_content();?>
                        <?php
                        $demoUrl = get_post_meta($post->ID, 'url', false);
                        $demoName = get_post_meta($post->ID, 'name', false);
                        if(!empty($demoUrl[0]) && !empty($demoName[0])):?>
                        <h3 style="margin-bottom:20px!important" class="fs22 tblack pt20 fwb">Project Demo</h3>
                        <a target="_blank" rel="nofollow" class="fs16" title="<?php echo $demoName[0]?>" href="<?php echo $demoUrl[0]?>"><?php echo $demoName[0]?></a>
                        <?php endif;?>
                    </div>
                    <div class="project-info" style="<?php echo $project_info_style;?>">
                         <h3 class="fs22 tblack pt20 fwb"><?php echo __('Project Details', 'Avada');?></h3>
    <?php if (get_the_term_list($post->ID, 'portfolio_skills', '', '<br />', '')):?>
                            <div class="project-info-box">
                                <h4 class="fs13 tblack fwb"><?php echo __('Skills Needed', 'Avada')?>:</h4>
                                <div class="clear"></div>
                                <div class="project-terms">
        <?php //echo get_the_term_list($post->ID, 'portfolio_skills', '', '<br />', '');?>
                                </div>
                            </div>
    <?php endif;?>
            <?php if (get_the_term_list($post->ID, 'portfolio-category', '', '<br />', '')):?>
                            <div class="project-info-box">
                                <h4 class="fs13 tblack fwb"><?php echo __('Categories', 'Avada')?>:</h4>
                                                                <div class="clear"></div>

                                <div class="project-terms">
        <?php  echo catLinks(get_the_term_list($post->ID, 'portfolio-category', '', ', ', ''))?>
                                </div>
                            </div>
    <?php endif;?>
    <?php if (get_post_meta($post->ID, 'pyre_project_url', true) && get_post_meta($post->ID, 'pyre_project_url_text', true)):?>
                            <div class="project-info-box">
                                <h4 class="fs13 tblack fwb"><?php echo __('Project URL', 'Avada')?>:</h4>
                                                                <div class="clear"></div>

                                <span><a href="<?php echo get_post_meta($post->ID, 'pyre_project_url', true);?>"><?php echo get_post_meta($post->ID, 'pyre_project_url_text', true);?></a></span>
                            </div>
    <?php endif;?>
    <?php if (get_post_meta($post->ID, 'pyre_copy_url', true) && get_post_meta($post->ID, 'pyre_copy_url_text', true)):?>
                            <div class="project-info-box">
                                <h4 class="fs13 tblack fwb"><?php echo __('Copyright', 'Avada');?>:</h4>
                                                                <div class="clear"></div>

                                <span><a href="<?php echo get_post_meta($post->ID, 'pyre_copy_url', true);?>"><?php echo get_post_meta($post->ID, 'pyre_copy_url_text', true);?></a></span>
                            </div>
    <?php endif;?>
                    </div>
                </div>
                <div style="clear:both;"></div>

            </div>
<?php endif;?>
        <div style="clear:both;"></div>
    </div>
</div>

<?php get_footer();?>