<?php
wp_footer();
?>

<?php
$dir = explode("/", get_current_URL());
if (preg_match("/portfolio/", $dir[3]) == 0)
    {
    ?>
    <div class="work fcb pt40 pb34 hidden-sm hidden-xs">
        <div class="wrapper">
            <h2 class="title fll">Our work<span></span></h2>
            <!--<div class="fs14 toran fll w25 toran pt15 pl15">Wide range of successful digital and print projects</div>-->
            <div class="clear"></div>
            <div id="content" class="portfolio portfolio-three portfolio-masonry<?php echo $content_class;?>" style="width:100%; overflow: hidden;
                 /*height: 550px;*/
                 ">

                <?php
                $args = array(
                    'post_type' => 'rwt-portfolio',
                    'posts_per_page' => 7,
                   /*  "tax_query" => array(array(
                            'taxonomy' => 'portfolio_category',
                            'field' => 'slug',
                            'terms' => "all"
                        )
                    ) */
                );
                $pcats = get_post_meta(get_the_ID(), 'pyre_portfolio_category', true);
                if ($pcats && $pcats[0] == 0)
                    {
                    unset($pcats[0]);
                    }
                if ($pcats)
                    {
                    $args = array(
                        'post_type' => 'rwt-portfolio',
                        'paged' => $paged,
                        'posts_per_page' => $data['portfolio_items'],
                    );
                    }

                $gallery = new WP_Query($args);
                if (is_array($gallery->posts) && !empty($gallery->posts))
                    {
                    foreach ($gallery->posts as $gallery_post) {
                        $post_taxs = wp_get_post_terms($gallery_post->ID, 'portfolio-category', array("fields" => "all"));
                        if (is_array($post_taxs) && !empty($post_taxs))
                            {
                            foreach ($post_taxs as $post_tax) {
                                $portfolio_taxs[$post_tax->slug] = $post_tax->name;
                            }
                            }
                    }
                    }
                $portfolio_category = get_terms('portfolio-category');
                if (is_array($portfolio_taxs) && !empty($portfolio_taxs) && get_post_meta($post->ID, 'pyre_portfolio_filters', true) != 'no'):
                    ?>
      
                <?php endif;?>
				<div style="display:none;"> <?php // print_r($gallery);  ?></div>
                <div class="portfolio-wrapper">
                    <?php
                    while ($gallery->have_posts()): $gallery->the_post();
                        if ($pcats)
                            {
                            $permalink = tf_addUrlParameter(get_permalink(), 'portfolioID', $current_page_id);
                            }
                        else
                            {
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
                            <div class="portfolio-item <?php echo $item_classes;?>">
                                <?php if (has_post_thumbnail()):?>
                                    <div class="image">
                                        <?php if ($data['image_rollover']):?>
                                            <?php the_post_thumbnail('portfolio-three');?>
                                        <?php else:?>
                                            <a href="<?php echo $permalink;?>"><?php the_post_thumbnail('portfolio-three');?></a>
                                        <?php endif;?>
                                        <?php
                                        if (get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'link')
                                            {
                                            $link_icon_css = 'display:inline-block;';
                                            $zoom_icon_css = 'display:none;';
                                            }
                                        elseif (get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'zoom')
                                            {
                                            $link_icon_css = 'display:none;';
                                            $zoom_icon_css = 'display:inline-block;';
                                            }
                                        elseif (get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'no')
                                            {
                                            $link_icon_css = 'display:none;';
                                            $zoom_icon_css = 'display:none;';
                                            }
                                        else
                                            {
                                            $link_icon_css = 'display:inline-block;';
                                            $zoom_icon_css = 'display:inline-block;';
                                            }

                                        $icon_url_check = get_post_meta(get_the_ID(), 'pyre_link_icon_url', true);
                                        if (!empty($icon_url_check))
                                            {
                                            $icon_permalink = get_post_meta($post->ID, 'pyre_link_icon_url', true);
                                            }
                                        else
                                            {
                                            $icon_permalink = $permalink;
                                            }
                                        ?>
                                        <div class="image-extras">
                                            <div class="image-extras-content">
                                                <?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');?>
                                                <a style="<?php echo $link_icon_css;?>" class="icon link-icon" href="<?php echo $icon_permalink;?>">Permalink</a>
                                                <?php
                                                if (get_post_meta($post->ID, 'pyre_video_url', true))
                                                    {
                                                    $full_image[0] = get_post_meta($post->ID, 'pyre_video_url', true);
                                                    }
                                                ?>
                                                <a style="<?php echo $zoom_icon_css;?>" class="icon gallery-icon" href="<?php echo $full_image[0];?>" rel="prettyPhoto[gallery]" title="<?php echo get_post_field('post_content', get_post_thumbnail_id($post->ID));?>">Gallery</a>
                                                <h3><?php the_title();?></h3>
                                                <h4><?php echo catLinks(get_the_term_list($post->ID, 'portfolio-category', '', ', ', ''))?></h4>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif;?>
                            </div>
                            <?php
                        endif;
                    endwhile;
                    ?>
                </div>
               
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function () {
            jQuery(".portfolio-tabs li a").on("click", function () {
                var filter = jQuery(this).data('filter-aj');
                jQuery.post(
                        "/wp-content/themes/recruit_wise/ajax/portfolio_filter.php",
                        {
                            filter: filter,
                        },
                        onAjaxSuccess
                        );

                function onAjaxSuccess(data)
                {
                    jQuery("#content .portfolio-wrapper").html("");
                  //  var container = jQuery("#content .portfolio-wrapper");
                    jQuery("#content .portfolio-wrapper").prepend(data).isotope().isotope('reloadItems').isotope({sortBy: 'original-order'});
                    jQuery("a[rel^='prettyPhoto']").prettyPhoto();
                }
            })
        })
    </script>
    <?php
    }
else
    {
    echo $facts;
    }
?>
 <?php wp_reset_query(); wp_reset_postdata(); ?>

<div id="skils" class="skils w100 hidden-sm hidden-xs xxx">
    <div class="wrapper">
        <div class="descrip mt75">
            <h2 class="title" style="margin-bottom:14px"><span class="db lh42 mb5">Our skills</span><i class="mb20 db">Recruitment know-how:</i></h2>
            <div class="clear"></div>
            <?php
            if (is_active_sidebar('text_for_skills'))
                {
                dynamic_sidebar('text_for_skills');
                }
            ?>
            <a href="/testimonials/" class="byb">Read our testimonials</a>
        </div>
        <div class="lines mt75">
            25 years recruitment sector experience
            <div class="line-wrap">
                <div class="oranges" style="width: 100%;" data-length="100"><span class="pl10">100%</span></div>
            </div>
            20 years online recruitment knowhow
            <div class="line-wrap">
                <div class="blue" style="width: 100%;" data-length="100"><span class="pl10">100%</span></div>
            </div>
            10 years recruitment website experience
            <div class="line-wrap">
                <div class="green" style="width: 80%;" data-length="100"><span class="pl10">100%</span></div>
            </div>
            7 years mobile recruitment experience
            <div class="line-wrap" id="visibleMarker">
                <div class="gray" style="width: 70%;" data-length="100"><span class="pl10">100%</span></div>
            </div>
        </div>
    </div>
</div>

<?php
$facts = '
<div class="facts w100 hidden-sm hidden-xs">
    <div class="wrapper">
        <h2 class="title fcb">
            RecruiterWEB facts:
        </h2>
        <ul>
            <li><span class="orange">14,000,000+</span><p class="revi">Pages reviewed by visitors<br /> of our clients sites per month</p></li>
            <li><span class="orange">10,000+</span><p class="surv">Recruiters<br />Surveyed</p></li>
            <li><span class="orange">3,000+</span><p class="own">Recruitment Owners<br />Surveyed</p></li>
            <li><span class="orange">150+</span><p class="sect">Recruitment<br />Sectors Covered</p></li>
            <li><span class="orange">100+</span><p class="made">Recruitment<br />Sites Made</p></li>
            <li><span class="orange">15+</span><p class="client">Countries<br />Supplied</p></li>
            <li><span class="orange">99%</span><p class="rate">Clients<br />Retained</p></li>
        </ul>
    </div>
</div>
';
$dir = explode("/", get_current_URL());
//if (preg_match("/portfolio/", $dir[3]) == 0)
  //  {
    echo $facts;
   // }
?>
<div class="socio w100">
    <div class="container">
        <div class="row">
            <div class="soc_title tac"><h2 class="title dib">Get connected <span class="db">Share us</span></h2></div>
            <div class="">
                <ul class="row">
					<?php 	
						$url   = urlencode(get_permalink( $post->ID )); 
						$title = wp_title('«', false, 'right');
						$title2 = urlencode(wp_title('«', false, 'right'));
					?>
                    <li class="google col-md-3 col-xs-6">
                        <a target="_blank" title="<?php echo $title; ?>" href="https://plus.google.com/share?url=<?php echo $url; ?>">

                            <span class="fa-stack fa-lg" style="font-size: 55px;">
                                <i class="fa fa-circle fa-stack-2x" style="color: #ff4716;"></i>
                                <i class="fa fa-google-plus fa-stack-1x fa-inverse"></i>
                            </span>

                            <span class="soc_name">Google+</span>
                           
                        </a>
                    </li>
                    <li class="linkedin col-md-3 col-xs-6">
                        <a target="_blank" title="<?php echo $title; ?>" href="http://www.linkedin.com/cws/share?token&amp;url=<?php echo $url; ?>&amp;isFramed=false">
                            <span class="fa-stack fa-lg" style="font-size: 55px;">
                                <i class="fa fa-circle fa-stack-2x" style="color: #2e9fc5;"></i>
                                <i class="fa fa-linkedin fa-stack-1x fa-inverse"></i>
                            </span>
                            <span class="soc_name">LinkedIn</span>
                           
                        </a>
                    </li>
                    
                    <li class="facebook col-md-3 col-xs-6">
                        <a target="_blank" title="<?php echo $title; ?>" href="http://www.facebook.com/sharer.php?sdk=joey&amp;display=popup&amp;u=<?php echo $url; ?>">
                            <span class="fa-stack fa-lg" style="font-size: 55px;">
                                <i class="fa fa-circle fa-stack-2x" style="color: #0079ff;"></i>
                                <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                            </span>
                            <span class="soc_name">Facebook</span>
                            
                        </a>
                    </li>
                   
                    <li class="twitter col-md-3 col-xs-6">
                        <a target="_blank" title="<?php echo $title; ?>" href="https://twitter.com/intent/tweet?text=<?php echo $title2; ?>&amp;url=<?php echo $url; ?>">
                            <span class="fa-stack fa-lg" style="font-size: 55px;">
                                <i class="fa fa-circle fa-stack-2x" style="color: #00d0fd;"></i>
                                <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                            </span>
                            <span class="soc_name">Twitter</span>
                           
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="footer w100 hidden-sm hidden-xs">
    <div class="wrapper">
        <?php wp_nav_menu(array('menu' => '3', 'menu_class' => 'footer_menu fll'));?>
		<a class="pull_left" href="<?php bloginfo('url'); ?>/home-mobile/?main=2">Go to mobile site</a>
        <div class="copyright flr">
            &copy; 2000-2016, RecruiterWEB. All rights reserved
        </div>
    </div>
    <a id="backtotop" href="javascript:void(0);" style="" title="To top"></a>
</div>

<script type="text/javascript">
	jQuery(document).ready(function () {
		
		jQuery('#menu-header_menu li.menu-item-has-children').hoverIntent({
				over : function(){
					//jQuery(this).children('.menu_cont').show();
					jQuery(this).children('ul').slideDown(150);
					jQuery(this).children('ul').css('opacity', 1);
				},
				timeout: 100,
				sensitivity: 40,
				out : function(){
					//jQuery(this).children('.menu_cont').hide();
					jQuery(this).children('ul').slideUp(150);
				}
		});
	
		jQuery('#menu-header_menu li.menu-item-has-children > ul li.menu-item-has-children, #menu-header_menu li.menu-item-has-children > ul li.menu-item-has-children > ul li.menu-item-has-children').hoverIntent({
				over : function(){
					jQuery(this).children('ul').show(150);
				},
				timeout: 100,
				sensitivity: 40,
				out: function(){
					jQuery(this).children('ul').hide(150);
				}
		});
		jQuery.noConflict();	
		jQuery("a[rel^='prettyPhoto']").prettyPhoto();
	}); // close DOM ready function
</script>
</body>
</html>

