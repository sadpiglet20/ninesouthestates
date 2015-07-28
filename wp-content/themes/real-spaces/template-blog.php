<?php
/*
Template Name: Blog
*/
get_header();
/* Site Showcase */
imic_page_banner($pageID = get_the_ID());
/* End Site Showcase */
$blog_type = get_post_meta(get_the_ID(),'imic_blog_type',true);
$sidebar = get_post_meta(get_the_ID(),'imic_select_sidebar_from_list',false); 
$class = (empty($sidebar)||!is_active_sidebar($sidebar[0]))?12:9;
$blog_classes_open = ($blog_type=='masonry')?'<div class="row"><div class="col-md-'.$class.'"><ul class="grid-holder col-3">':'<ul class="timeline">';
$blog_classes_close = ($blog_type=='masonry')?'</ul></div>':'</ul>';
?>
<!-- Start Content -->
  <div class="main" role="main">
    <div id="content" class="content full">
      <div class="container">
        <?php echo $blog_classes_open; $paged = (get_query_var('paged'))?get_query_var('paged'):1;
		query_posts(array('post_type'=>'post','paged'=>$paged));
		if(have_posts()):
		while(have_posts()):the_post();
				get_template_part( 'content', $blog_type );
				endwhile;
				else:
                                get_template_part( 'content', 'none' );
				endif;
                              echo $blog_classes_close;
                                if($blog_type=='masonry'):
								echo '<div class="text-align-center">';
								pagination(); echo '</div>'; 
                             echo '</div>';
                   endif; wp_reset_query(); ?>
     </div>
  </div>
<?php get_footer(); ?>