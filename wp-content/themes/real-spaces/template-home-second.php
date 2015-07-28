<?php
/*
  Template Name: Home Second
 */
get_header(); 
/* Hero Slider Options
===========================*/
global $imic_options;
$currency_symbol = imic_get_currency_symbol($imic_options['currency-select']);
$homeID = get_the_ID();
$autoSlide = get_post_meta($homeID,'imic_slider_auto_slide',true);
$sliderArrows = get_post_meta($homeID,'imic_slider_direction_arrows',true);
$sliderEffect = get_post_meta($homeID,'imic_slider_effects',true);
?>
<!-- Site Showcase -->
<div class="site-showcase">
    <?php
         $imic_slider_with_property= get_post_meta($homeID,'imic_slider_with_property',true);
         if($imic_slider_with_property==1){
          $imic_slider_image=get_post_meta($homeID,'imic_slider_image',false);
          if(count($imic_slider_image)>0){
               echo '<div class="slider-mask overlay-transparent"></div>
                <!-- Start Hero Slider -->
    <div class="hero-slider flexslider clearfix" data-autoplay='.$autoSlide.' data-pagination="no" data-arrows='.$sliderArrows.' data-style='. $sliderEffect.' data-pause="yes">';
              echo '<ul class="slides">';
                foreach ($imic_slider_image as $custom_home_image) {
                   $image = wp_get_attachment_image_src($custom_home_image, '1200-500-size' ); 
                 echo '<li class=" parallax" style="background-image:url('.$image[0].')">';
                 echo '</li>';  
                }
                echo '</ul></div>';
          }
       }else{
        query_posts(array('post_type'=>'property','post_status'=>'publish','posts_per_page'=>-1,'meta_query' => array(array('key' => 'imic_slide_property','value' => 1,'compare' => '=='),),)); 
	  if(have_posts()):
	  echo '<div class="slider-mask overlay-transparent"></div>
    <!-- Start Hero Slider -->
    <div class="hero-slider flexslider clearfix" data-autoplay='.$autoSlide.' data-pagination="no" data-arrows='.$sliderArrows.' data-style='. $sliderEffect.' data-pause="yes">';
              echo '<ul class="slides">';
              while(have_posts()):the_post(); 
          $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), '1200-500-size' );
          echo '<li class=" parallax" style="background-image:url('.$image[0].')">';
                echo '<div class="flex-caption">';
                $imic_property_site_address= get_post_meta(get_the_ID(),'imic_property_site_address',true);
                if(!empty($imic_property_site_address)):
                    echo '<strong class="title">'.$imic_property_site_address;
                 $imic_property_site_city=get_post_meta(get_the_ID(),'imic_property_site_city',true); if(!empty($imic_property_site_city)){ echo ', <em>'.$imic_property_site_city.'</em>'; }
                echo '</strong>';
                 endif;
                $imic_property_price = get_post_meta(get_the_ID(),'imic_property_price',true);
             if(!empty($imic_property_price)):
                 echo '<div class="price"><strong>'.$currency_symbol.'</strong><span>'.$imic_property_price.'</span></div>';
             endif;
             echo '<a href="'.get_permalink(get_the_ID()).'" class="btn btn-primary btn-block">'.__('Details','framework').'</a>';
             echo '<div class="hero-agent">';
             $post_author_id = get_post_field( 'post_author', get_the_ID() );
             $userImg = esc_attr(get_the_author_meta('agent-image', $post_author_id)); $userLoadedImgSrc = wp_get_attachment_image_src($userImg, '365-365-size');
             if(!empty($userLoadedImgSrc[0])):
             echo '<img src="'.$userLoadedImgSrc[0].'" alt="" class="hero-agent-pic"/>';
             endif;
             echo '<a href="'.get_author_posts_url($post_author_id).'" class="hero-agent-contact" data-placement="left"  data-toggle="tooltip" title="" data-original-title="'.__('Contact Agent','framework').'"><i class="fa fa-envelope"></i></a>';
             echo '</div></div>';
             echo '</li>';
       endwhile; echo '</ul></div>'; endif; wp_reset_query();} ?>
      <!-- Site Search Module -->
    <?php  if(($imic_options['enable_type_in_search']==1)||($imic_options['enable_contract_in_search']==1)||$imic_options['enable_location_in_search']||($imic_options['enable_beds_in_search']==1)||($imic_options['enable_bath_in_search']==1)||($imic_options['enable_price_in_search']==1)||($imic_options['enable_area_in_search']==1)){
        if(($imic_options['enable_beds_in_search']==1)||($imic_options['enable_bath_in_search']==1)||($imic_options['enable_price_in_search']==1)||($imic_options['enable_area_in_search']==1)||($imic_options['enable_search_by_in_search']==1)){
            $flag_advance=1;
        }else{
            $flag_advance=0;
        }
?>
    <div class="site-search-module">
        <div class="container">
            <div class="site-search-module-inside">
                <form method="get" action="<?php echo home_url(); ?>/">
                    <input type="hidden" class="form-control" name="s" id="s" value="<?php _e('Search1', 'framework'); ?>" />
                    <div class="form-group">
                        <div class="row sfrow">
                            <?php
                           if($imic_options['enable_type_in_search']==1){
                            $args = array('orderby' => 'count', 'hide_empty' => true);
                            $propertyterms = get_terms('property-type', $args);
                            if (!empty($propertyterms)) {
                                echo '<div class="col-md-2">';
                                $output = '<select name="propery_type" class="form-control input-lg selectpicker">';
                                $output .='<option selected>' . __('Type', 'framework') . '</option>';
                                foreach ($propertyterms as $term) {
                                    $term_name = $term->name;
                                    $term_slug = $term->slug;
                                    $output .="<option value='" . $term_slug . "'>" . $term_name . "</option>";
                                }
                                $output .="</select>";
                                echo $output;
                                echo '</div>';
                           }}
                           if($imic_options['enable_contract_in_search']==1){
                            $args = array('orderby' => 'count', 'hide_empty' => true);
                            $property_contract_type_terms = get_terms('property-contract-type', $args);
                            if (!empty($property_contract_type_terms)) {
                                echo '<div class="col-md-2">';
                                $output = '<select name="propery_contract_type" class="form-control input-lg selectpicker">';
                                $output .='<option selected>' . __('Contract', 'framework') . '</option>';
                                foreach ($property_contract_type_terms as $term) {
                                    $term_name = $term->name;
                                    $term_slug = $term->slug;
                                   $output .="<option value='".$term_slug."'>".$term_name."</option>";
                                }
                                $output.="</select>";
                                echo $output;
                                echo '</div>';
                           }}
                           if($imic_options['enable_location_in_search']==1){
                            $imic_country_wise_city = imic_get_multiple_city();
                            if (!empty($imic_country_wise_city)) {
                                echo '<div class="col-md-2">
                  <select name="propery_location" class="form-control input-lg selectpicker">
                    <option selected>' . __('Location', 'framework') . '</option>';
                                foreach ($imic_country_wise_city as $key => $value) {
                                    echo "<option value='" . $key . "'>" . $value . "</option>";
                                }
                                echo'</select></div>';
                           }}
						   if(isset($imic_options['enable_city_in_search'])&&($imic_options['enable_city_in_search']==1)){
                               $args = array('orderby' => 'count', 'hide_empty' =>true);
                               $terms=get_terms(array('city-type'), $args);
                              if (!empty($terms)) {
                                echo '<div class="col-md-2">
                  <select name="property_city" class="form-control input-lg selectpicker">
                    <option selected>' . __('City', 'framework') . '</option>';
                               foreach ($terms as $term_data) {
                                    echo "<option value='" .$term_data->slug. "'>" . $term_data->name . "</option>";
                                }
                                echo'</select></div>';
                           }}
                            ?>
                            <div class="col-md-2"> <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-search"></i> <?php _e('Search','framework'); ?> </button> </div>
                            <?php if($flag_advance==1){
                                echo '<div class="col-md-2"> <a href="#" id="ads-trigger" class="btn btn-default btn-block"><i class="fa fa-plus"></i> <span>'.__('Advanced','framework').'</span></a> </div>';
                            }  ?>
                        </div>
                         <?php if($flag_advance==1){  ?>
                        <div class="row sfrow hidden-xs hidden-sm">
                            <?php  if($imic_options['enable_beds_in_search']==1){ ?>
                            <div class="col-md-2">
                                <label><?php _e('Min Beds', 'framework'); ?></label>
                                <select name="beds" class="form-control input-lg selectpicker">
                                    <?php
                                    echo'<option selected>' .__('Any','framework') .'</option>';
                                    for ($i = 1; $i <= 10; $i++) {
                                        echo "<option value='" . $i . "'>" . $i . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php } if($imic_options['enable_bath_in_search']==1){ ?>
                            <div class="col-md-2">
                                <label><?php _e('Min Baths','framework'); ?></label>
                                <select name="baths" class="form-control input-lg selectpicker">
                                    <?php
                                    echo'<option selected>' . __('Any', 'framework') . '</option>';
                                    for ($i = 1; $i <= 10; $i++) {
                                        echo "<option value='" . $i . "'>".$i."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php } if($imic_options['enable_price_in_search']==1){  ?>
                            <div class="col-md-2">
                                <label><?php _e('Min Price','framework'); ?></label>
                                <select name="min_price" class="form-control input-lg selectpicker">
                                    <?php
                                    echo'<option selected>' . __('Any', 'framework') .'</option>';
                                    $m_price_value = array(1000, 5000, 10000, 50000, 100000, 3000000, 5000000, 10000000);
                                    foreach($m_price_value as $price_value) {
                                    echo "<option value='" . $price_value . "'>".$currency_symbol." ".$price_value."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                           <div class="col-md-2">
                            <label><?php _e('Max Price', 'framework'); ?></label>
                            <select name="max_price" class="form-control input-lg selectpicker">
                            <?php
                            echo'<option selected>' . __('Any', 'framework') . '</option>';
                            $max_price_value = array(1000, 5000, 10000, 50000, 100000, 3000000, 5000000, 10000000);
                            foreach ($max_price_value as $price_value) {
                            echo "<option value='" . $price_value . "'>".$currency_symbol." ".$price_value."</option>";
                            }
                            ?>
                            </select>
                            </div>
                            <?php } if($imic_options['enable_area_in_search']==1){  ?>
                            <div class="col-md-2">
                                <label><?php _e('Min Area (Sq Ft)', 'framework'); ?></label>
                                <input type="text" name="min_area" class="form-control input-lg" placeholder="<?php _e('Any', 'framework'); ?>">
                            </div>
                            <div class="col-md-2">
                                <label><?php _e('Max Area (Sq Ft)', 'framework'); ?></label>
                                <input type="text" name="max_area" class="form-control input-lg" placeholder="<?php _e('Any', 'framework'); ?>">
                            </div>
                           <?php } ?>
                          </div>
                        
                            <?php  if($imic_options['enable_search_by_in_search']==1){ ?>
                            <div class="row sfrow hidden-xs hidden-sm">
                            <div class="search_by">
                            <div class="col-md-2">
                                <label><?php _e('Search By', 'framework'); ?></label>
                                <select name="search_by" class="form-control input-lg selectpicker">
                                    <?php
                                    echo'<option selected>' .__('Search By','framework') .'</option>';
                                   echo "<option value='Id'>" . __('Id','framework') . "</option>";
                                    echo "<option value='Address'>" . __('Address','framework') . "</option>";
                                    echo "<option value='Pincode'>" . __('Pincode','framework') . "</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label><?php _e('Keyword','framework'); ?></label>
                             	<input type="text" name="search_by_keyword" class="form-control input-lg search_by_keyword" placeholder="<?php _e('Please Select', 'framework'); ?>">
                            </div>
                                
                             </div></div>
                            <?php } ?>
                        </div>
                         <?php } ?>
                    
                </form>
            </div>
        </div>
    </div>
    <?php } ?>
       </div>
    <!-- End Showcase --> 
<!-- Start Content -->
          <?php
          echo '<div class="main" role="main">';
          echo '<div id="content" class="content full">';
          global $imic_options; 
          if(isset($imic_options['opt-slides'])){
            $fblocks= $imic_options['opt-slides']; 
          }else{
              $fblocks='';
          }
          if(!empty($fblocks[0]['title'])){  echo '<div class="featured-blocks"><div class="container"><div class="row">';
          foreach($fblocks as $fblock) { $link=$link_close=''; if($fblock['url']!='') { $link = '<a href="'.$fblock['url'].'">'; $link_close = '</a>'; }  echo $link;
            echo '<div class="col-md-4 col-sm-4 featured-block">';
          if(!empty($fblock['image'])){
                echo '<img alt="featured block" src="'.$fblock['image'].'" class="img-thumbnail">';
            }
            if(!empty($fblock['title'])){
            echo '<h3>'.$fblock['title'].'</h3>';
            }
            if(!empty($fblock['description'])){
                echo $fblock['description'];
            }
            echo '</div>';
            echo $link_close; } echo '</div></div></div><div class="spacer-40"></div>'; } ?>
      <?php $Featured_List = get_post_meta(get_the_ID(),'imic_home_featured_section',true); 
	  if($Featured_List==1) { 
       echo '<div id="featured-properties">
        <div class="container">';
            echo '<div class="row">
            <div class="col-md-12">
              <div class="block-heading">';
            $imic_home_featured_heading=get_post_meta(get_the_ID(),'imic_home_featured_heading',true);
            $imic_home_featured_heading=!empty($imic_home_featured_heading)?$imic_home_featured_heading:__('Featured Section Heading','framework');
           echo '<h4><span class="heading-icon"><i class="fa fa-star"></i></span>'.$imic_home_featured_heading.'</h4>';
           echo ' </div></div></div>'; 
           query_posts(array('post_type'=>'property','post_status'=>'publish','posts_per_page'=>-1,'meta_query' => array(array('key' => 'imic_featured_property','value' => 1,'compare' => '=='),),)); 
			  if(have_posts()):
                              echo '<div class="row"><ul class="owl-carousel owl-alt-controls" data-columns="4" data-autoplay="no" data-pagination="no" data-arrows="yes" data-single-item="no">';
                              while(have_posts()):the_post();
							  $property_images = get_post_meta(get_the_ID(),'imic_property_sights',false);
								$total_images = count($property_images);
                           echo '<li class="item property-block">';
                     if(has_post_thumbnail()):
                          echo'<a href="'.get_permalink().'" class="property-featured-image">';
                              the_post_thumbnail('600-400-size');
                              echo'<span class="images-count"><i class="fa fa-picture-o"></i> '.$total_images.'</span>';
                              echo'<span class="badges">'; $contract = wp_get_object_terms( get_the_ID(), 'property-contract-type', array('fields'=>'ids')); $term = get_term( $contract[0], 'property-contract-type' ); echo $term->name;
                              echo'</span></a>';
                              endif; 
							  		$property_id = get_post_meta(get_the_ID(),'imic_property_site_id',true);
                               $imic_property_site_address= get_post_meta(get_the_ID(),'imic_property_site_address',true);
                               $imic_property_site_city=get_post_meta(get_the_ID(),'imic_property_site_city',true);
                               $imic_property_price= get_post_meta(get_the_ID(),'imic_property_price',true);
                          if(!empty($imic_property_site_address)||!empty($imic_property_site_city)||!empty($imic_property_price)){
                          echo '<div class="property-info">';
                            echo '<h4><a href="'.get_permalink().'">'.get_the_title().'</a><span class="pid"> ('.$property_id.')</span></h4>'; 
                          if(!empty($imic_property_site_city)){
                            echo '<span class="location">'.$imic_property_site_city.'</span>';  
                          }
                          if(!empty($imic_property_price)){
                              echo '<div class="price"><strong>'.$currency_symbol.'</strong><span>'.$imic_property_price.'</span></div>';
                          }
                          echo '</div>';
                          }echo '</li>';  endwhile; 
                          echo '</ul></div>';
                          endif; wp_reset_query();
                          echo '</div>
                          </div><div class="spacer-40"></div>';
                          } echo '<div class="container">'; 
                        $Recent_List = get_post_meta(get_the_ID(),'imic_home_recent_section',true); 
                        $sidebar = get_post_meta(get_the_ID(),'imic_select_sidebar_from_list',false); 
                         $class = (empty($sidebar)||!is_active_sidebar($sidebar[0]))?12:9;
                        if($Recent_List==1) {
                        echo'<div class="row"><div class="col-md-'.$class.'"><div class="block-heading">';
                    $imic_home_recent_heading=get_post_meta(get_the_ID(),'imic_home_recent_heading',true);
                    $imic_home_recent_heading=!empty($imic_home_recent_heading)?$imic_home_recent_heading:__('Recent Listed','framework');
                    echo '<h4><span class="heading-icon"><i class="fa fa-leaf"></i></span>'.$imic_home_recent_heading.'</h4>';
                     $imic_home_recent_more=get_post_meta(get_the_ID(),'imic_home_recent_more',true);
                     if(!empty($imic_home_recent_more)){
                   echo '<a href="'.$imic_home_recent_more.'" class="btn btn-primary btn-sm pull-right">'.__('View more properties ','framework').'<i class="fa fa-long-arrow-right"></i></a>';   
                    } 
                  echo ' </div><div class="property-listing">';
                  $Recent_Page = get_post_meta(get_the_ID(),'imic_home_recent_property_no',true);
			  query_posts(array('post_type'=>'property','post_status'=>'publish','posts_per_page'=>$Recent_Page)); 
			  if(have_posts()):
                              echo '<ul>';
                          while(have_posts()):the_post(); 
				$property_images = get_post_meta(get_the_ID(),'imic_property_sights',false);
								$total_images = count($property_images);
								$property_id = get_post_meta(get_the_ID(),'imic_property_site_id',true);
			  $property_area = get_post_meta(get_the_ID(),'imic_property_area',true); 
			  $property_baths = get_post_meta(get_the_ID(),'imic_property_baths',true);
			  $property_beds = get_post_meta(get_the_ID(),'imic_property_beds',true);
			  $property_parking = get_post_meta(get_the_ID(),'imic_property_parking',true); 
			  $property_address = get_post_meta(get_the_ID(),'imic_property_site_address',true);
			  $property_city = get_post_meta(get_the_ID(),'imic_property_site_city',true);
			  $property_price = get_post_meta(get_the_ID(),'imic_property_price',true); 
			  $contract = wp_get_object_terms( get_the_ID(), 'property-contract-type', array('fields'=>'ids')); 
                         if(!empty($contract)) {
			  $term = get_term( $contract[0], 'property-contract-type' ); }
                          echo '<li class="type-rent col-md-12">';
                          if(has_post_thumbnail()):
                          echo '<div class="col-md-4">
                    		<a href="'.get_permalink().'" class="property-featured-image">';
                             the_post_thumbnail('600-400-size');
                            	echo'<span class="images-count"><i class="fa fa-picture-o"></i> '.$total_images.'</span>';
                            	if(!empty($term)) { echo'<span class="badges">'.$term->name.'</span>'; }
                       	echo'</a>
                   	</div>';
                          endif;
                           $imic_property_site_address= get_post_meta(get_the_ID(),'imic_property_site_address',true);
                               $imic_property_site_city=get_post_meta(get_the_ID(),'imic_property_site_city',true);
                               $imic_property_price= get_post_meta(get_the_ID(),'imic_property_price',true);
                               echo '<div class="col-md-8">';
                               if(!empty($imic_property_site_address)||!empty($imic_property_site_city)||!empty($imic_property_price)){
                                echo '<div class="property-info">';
                                 if(!empty($imic_property_price)){
                              echo '<div class="price"><strong>'.$currency_symbol.'</strong><span>'.$imic_property_price.'</span></div>';
                          }
                            echo '<h3><a href="'.get_permalink().'">'.get_the_title().'</a><span class="pid"> ('.$property_id.')</span></h3>'; 
                          if(!empty($imic_property_site_city)){
                            echo '<span class="location">'.$imic_property_site_city.'</span>';  
                          }
                          echo imic_excerpt(10);
                          echo '</div>';
  }
                          $imic_property_area= get_post_meta(get_the_ID(),'imic_property_area',true);
                          $imic_property_baths= get_post_meta(get_the_ID(),'imic_property_baths',true);
                          $imic_property_beds= get_post_meta(get_the_ID(),'imic_property_beds',true);
                          $imic_property_parking= get_post_meta(get_the_ID(),'imic_property_parking',true);
                           if(!empty($imic_property_area)||!empty($imic_property_baths)||!empty($imic_property_beds)||!empty($imic_property_parking)):
                          echo '<div class="property-amenities clearfix">';
                          if(!empty($imic_property_area)):
                             echo '<span class="area">'.$imic_property_area.'<strong></strong>'.__('Area','framework').'</span>'; 
                          endif;
                          if(!empty($imic_property_baths)):
                            echo '<span class="baths"><strong>'.$imic_property_baths.'</strong>'.__('Baths','framework').'</span>';  
                          endif;
                          if(!empty($imic_property_beds)):
                            echo '<span class="beds"><strong>'.$imic_property_beds.'</strong>'.__('Beds','framework').'</span>';  
                          endif;
                          if(!empty($imic_property_parking)):
                              echo '<span class="parking"><strong>'.$imic_property_parking.'</strong>'.__('Parking','framework').'</span>';
                          endif;
                          echo '</div>';
                          endif;
                         echo '</div></li>';
                       endwhile;
                       echo '</ul>';
                      endif;
                      echo '</div></div>';
             } 
//            -- Start Sidebar --
             if(!empty($sidebar)&&is_active_sidebar($sidebar[0])) { 
                      echo '<div class="sidebar right-sidebar col-md-3">';
                      dynamic_sidebar($sidebar[0]);
                      echo '</div>';
                      }
            echo '</div></div>';
                 $Partner_Heading = get_post_meta($homeID,'imic_home_partner_heading',true); 
                        $Partner_Heading_Url = get_post_meta($homeID,'imic_home_partner_url',true);
                        $Partner_Section = get_post_meta($homeID,'imic_home_partners_section',true);
                        $Partner_Heading=!empty($Partner_Heading)?$Partner_Heading:__('Our Partners','framework');
                        if($Partner_Section!=0) { echo '<div class="container"><div class="block-heading">'; 
                        echo '<h4><span class="heading-icon"><i class="fa fa-users"></i></span>'.$Partner_Heading.'</h4>';
                        if($Partner_Heading_Url!='') {
                        echo '<a href="'.$Partner_Heading_Url.'" class="btn btn-primary btn-sm pull-right">'.__('All partners ','framework').'<i class="fa fa-long-arrow-right"></i></a>';
                        } 
                        echo '</div>';
                        query_posts(array('post_type'=>'partner','posts_per_page'=>-1));
			if(have_posts()):
                            echo '<div class="row">
                            <ul class="owl-carousel" id="clients-slider" data-columns="6" data-autoplay="yes" data-pagination="no" data-arrows="no" data-single-item="no" data-items-desktop="6" data-items-desktop-small="4" data-items-mobile="2" data-items-tablet="4">';
                            while(have_posts()):the_post(); 
                            $partner_logo = get_post_meta(get_the_ID(),'imic_partner_logo',true);
                            $partner_url = get_post_meta(get_the_ID(),'imic_partner_url',true);
                            if(!empty($partner_logo)) {
                            $userLoadedImgSrc = wp_get_attachment_image_src($partner_logo, '140-47-size');
                            $userImgSrc = $userLoadedImgSrc[0];
                            }
                            $target = get_post_meta(get_the_ID(),'imic_partner_target',true); 
                            if($target==1) { $target = "self"; } else{ $target = "blank"; }
                            if($partner_url!='') {
                                echo '<li class="item"> <a href="'.$partner_url.'" target="_'.$target.'"><img src="'.$userImgSrc.'" alt=""></a> </li>'; } else { 
                                echo '<li class="item"> <img src="'.$userImgSrc.'" alt=""></li>';  
                                    } endwhile; echo '</ul></div>';
                            endif; wp_reset_query();
                            echo '</div>'; } echo '</div>'; 
                            get_footer(); ?>