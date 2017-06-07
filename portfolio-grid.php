<?php
// Template Name: Portfolio
get_header();?>
<div class="portfolio portfolio-three portfolio-masonry" style="width:100%">
<div class="wrapper">
  <h1 class="title fcb">Portfolio: </h1>
 <?php
                /*  if (is_front_page()) {
                  $paged = (get_query_var('page')) ? get_query_var('page') : 1;
                  } else {
                  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                  } */
                $args = array(
                    'post_type' => 'rwt-portfolio',
                    //'paged' => $paged,
                    'posts_per_page' => -1,
                );
                $pcats = get_post_meta(get_the_ID(), 'pyre_portfolio_category', true);
                if ($pcats && $pcats[0] == 0) {
                    unset($pcats[0]);
                }
                if ($pcats) {
                    $args = array(
                        'post_type' => 'rwt-portfolio',
                        'paged' => $paged,
                        'posts_per_page' => $data['portfolio_items'],
                    );
                }

                $gallery = new WP_Query($args);
                if (is_array($gallery->posts) && !empty($gallery->posts)) {
                    foreach ($gallery->posts as $gallery_post) {
                        $post_taxs = wp_get_post_terms($gallery_post->ID, 'portfolio-category', array("fields" => "all"));
                        if (is_array($post_taxs) && !empty($post_taxs)) {
                            foreach ($post_taxs as $post_tax) {
                                $portfolio_taxs[$post_tax->slug] = $post_tax->name;
                            }
                        }
                    }
                }
                $portfolio_category = get_terms('portfolio-category');
                if (is_array($portfolio_taxs) && !empty($portfolio_taxs) && get_post_meta($post->ID, 'pyre_portfolio_filters', true) != 'no'):
                    ?>
  
                  
                  <?php endif; ?>
                <div class="portfolio-wrapper">
                    <?php
                    while ($gallery->have_posts()): $gallery->the_post();
                        if ($pcats) {
                            $permalink = tf_addUrlParameter(get_permalink(), 'portfolioID', $current_page_id);
                        } else {
                            $permalink = get_permalink();
                        }
                        if (has_post_thumbnail() || get_post_meta($post->ID, 'pyre_video', true)):
                            ?>
                            <?php
                            $item_classes = '';
                            $item_cats = get_the_terms($post->ID, 'portfolio-category');
                            if ($item_cats):
                                foreach ($item_cats as $item_cat) {
                                    $item_classes .= $item_cat->slug . ' ';
                                }
                            endif;
                            ?>
                            <div class="portfolio-item <?php echo $item_classes; ?>">
                                    <?php if (has_post_thumbnail()): ?>
                                    <div class="image">
                                        <?php if ($data['image_rollover']): ?>
                                            <?php the_post_thumbnail('portfolio-three'); ?>
                                        <?php else: ?>
                                            <a href="<?php echo $permalink; ?>"><?php the_post_thumbnail('portfolio-three'); ?></a>
                                        <?php endif; ?>
                                        <?php
                                        if (get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'link') {
                                            $link_icon_css = 'display:inline-block;';
                                            $zoom_icon_css = 'display:none;';
                                        } elseif (get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'zoom') {
                                            $link_icon_css = 'display:none;';
                                            $zoom_icon_css = 'display:inline-block;';
                                        } elseif (get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'no') {
                                            $link_icon_css = 'display:none;';
                                            $zoom_icon_css = 'display:none;';
                                        } else {
                                            $link_icon_css = 'display:inline-block;';
                                            $zoom_icon_css = 'display:inline-block;';
                                        }

                                        $icon_url_check = get_post_meta(get_the_ID(), 'pyre_link_icon_url', true);
                                        if (!empty($icon_url_check)) {
                                            $icon_permalink = get_post_meta($post->ID, 'pyre_link_icon_url', true);
                                        } else {
                                            $icon_permalink = $permalink;
                                        }
                                        ?>
                                        <div class="image-extras">
                                            <div class="image-extras-content">
                                                <?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
                                                <a style="<?php echo $link_icon_css; ?>" class="icon link-icon" href="<?php echo $icon_permalink; ?>">Permalink</a>
                                                <?php
                                                if (get_post_meta($post->ID, 'pyre_video_url', true)) {
                                                    $full_image[0] = get_post_meta($post->ID, 'pyre_video_url', true);
                                                }
                                                ?>
                                                <a style="<?php echo $zoom_icon_css; ?>" class="icon gallery-icon" href="<?php echo $full_image[0]; ?>" rel="prettyPhoto" title="<?php echo get_post_field('post_content', get_post_thumbnail_id($post->ID)); ?>">Gallery</a>
                                                <h3><?php the_title(); ?></h3>
                                                <h4><?php echo catLinks(get_the_term_list($post->ID, 'portfolio-category', '', ', ', ''))?></h4>
                                            </div>
                                        </div>
                                    </div>
                            <?php endif; ?>
                            </div>
                            <?php
                        endif;
                    endwhile;
                    ?>
                </div>
    <? ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <script type="text/javascript" charset="utf-8">
 jQuery(document).ready(function(){
  
   jQuery("a[rel^='prettyPhoto']").prettyPhoto();
	setTimeout(function() {
		  jQuery(".portfolio-tabs > li.active > a").addClass('trued').click();
	}, 200);
  
	

  });
</script>
<?php get_footer(); ?>