<?php get_header();?>
<div class="content pages">
    <div class="wrapper">
        <h1 class="title fcb"><?php the_title();?></h1>
        <?php if (have_posts()): while (have_posts()): the_post();?>
                <?php the_content();?>
        <div class="clear"> </div>
            <?php endwhile;
        endif;
        ?>
<?php if (is_page(array('terms-conditions', 'our-founder', 'privacy-policy'))):?>
            <div class="pb50 blog_links">
                <a class="fll w17 mt15 fwb fs18 lh42 bloglink toran" href="/products/websites/standard-recruitment-website/">
                    <span class="stand_sova"></span>
                    Standard site
                </a>
				 <a class="fll w17 ml10 mt15 fwb fs18 lh42 bloglink tblue " href="/products/website-template/">
                    <span class="temp_sova"></span>
                    Templates
                </a>
                <a class="fll w17 ml10 mt15 fwb fs18 lh42 bloglink tgreen" href="/products/websites/advanced-recruitment-website/">
                    <span class="sova adv_sova"></span>
                    Advanced site
                </a>
                <a class="fll w17 ml10 mt15 fwb fs18 lh42 bloglink toran  " href="/products/career-sites/">
                    <span class="sova career_sova"></span>
                    Career site
                </a>
                <a class="fll w17 ml10 mt15 fwb fs18 lh42 bloglink tblue" href="/products/mobile/">
                    <span class="sova mob_sova"></span>
                    Mobile site
                </a>
               
                <a class="fll w17 ml10 mt15 fwb fs18 lh42 bloglink tgreen" href="/support-services/">
                    <span class="fee_sova"></span>
                    Fee Generation
                </a>
                <div class="clear"></div>
            </div>
<?php endif;?> 
    </div>
</div>
<?php get_footer();?>