<?php
/* Template Name:  Recruitment website template */
get_header();
$dir_slug = the_slug();
if (preg_match("/web-design/", $dir_slug) != 0) {
    $web_design = true;
} elseif (preg_match("/website-template/", $dir_slug) != 0) {
    $website_template = true;
} else {
    $service = true;
}
?>
<div class="content pages tac">
    <div class="wrapper pb50 tal dib">
        <img class="dib ml85 mr30 mt30" src="/wp-content/themes/recruit_wise/img/prices_orange_owl.png">
        <h1 class="title fcb lowl dib"><?php the_title(); ?></h1>
        <?php if (is_page(471)): ?>
<a class="submit flr btn btn-defaults mt60 obckg fs25" href="/price-list/">Prices</a>
        <?php endif; ?>

        <?php if (have_posts()): while (have_posts()): the_post(); ?>
                <?php
                $pdf_file_links = $pamd->get_downloads($post->ID, "return", "array", "_blank");
                $pdf_file_link_content = '<ul class="menu fll">';
                foreach ($pdf_file_links as $key => $pdf_link) {
                    $pdf_file_link_content.='<li><a target="_blank" href="' . $pdf_link["url"] . '"><img src="' . get_template_directory_uri() . '/img/product_pdf.jpg"><span>' . $pdf_link["label"] . '</span></a></li>' . "\n";
                }
                $pdf_file_link_content.=' </ul>';
                ?>
                <div class="<?php $service || $website_template ? print'w88'  : print'w100'  ?> tal fll descr eqlh">
                    <div class="m20 fll">
                        <?php the_content(); ?>
                    </div>
                </div>

                <?php if ($service || $website_template): ?>
                    <div class="w10 flr righ_col eqlh">
                        <?php echo $pdf_file_link_content ?>
                    </div>
                <?php endif; ?>
                <?php if ($web_design): ?>
                    <div class="bottom_pdf">
                        <h2 style="overflow:visible;margin-left: 20px"class="title w100 fll fcb dib ml20">Case Studies</h2>
                        <?php echo $pdf_file_link_content ?>
                        <div class="clearfix"></div>
                    </div>
                    <?php
                endif;
            endwhile;
        endif;
        ?>
        <?php if ($website_template) : ?>
            <div class="bottom_templates">
                <?php
					query_posts('post_type=websitethemplates&order=ASC&orderby=title&posts_per_page=-1');
						if (have_posts()): ?>
							<h2 style="overflow:visible;margin-left: 20px"class="title fcb dib ml20">Template examples:</h2>
							<div class="portfolio-wrapper">
								<?php while (have_posts()): the_post(); ?>
								
									<?php
									$links = get_post_meta($post->ID, "url", true);
									if($links && has_post_thumbnail()){	 ?>
										<div class="portfolio-item">
											<div class="image"><a target="_blank" href="<?php echo $links; ?>"><?php the_post_thumbnail('portfolio-three'); ?></a></div>
											
										</div>
									<?php } 
								endwhile; ?>
							</div>
						<?php endif;
					wp_reset_query();
				?>
                <div class="clearfix"></div>
            </div>
        <?php endif; ?>
    </div>
    <div class="clearfix"></div>
</div>
<script>
    jQuery(window).load(function() {
        jQuery('.content').each(function() {
            jQuery(this).find('.eqlh').equalHeights();
        });
    });
</script>
<?php get_footer(); ?>