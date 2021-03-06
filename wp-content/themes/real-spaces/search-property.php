<?php
$property_images = get_post_meta(get_the_ID(),'imic_property_sights',false);
$property_id = get_post_meta(get_the_ID(),'imic_property_site_id',true);
								$total_images = count($property_images);
								$property_term_type = '';
								$property_area = get_post_meta(get_the_ID(),'imic_property_area',true); 
								  $property_baths = get_post_meta(get_the_ID(),'imic_property_baths',true);
								  $property_beds = get_post_meta(get_the_ID(),'imic_property_beds',true);
								  $property_parking = get_post_meta(get_the_ID(),'imic_property_parking',true); 
								  $property_address = get_post_meta(get_the_ID(),'imic_property_site_address',true);
								  $property_city = get_post_meta(get_the_ID(),'imic_property_site_city',true);
								  $property_price = get_post_meta(get_the_ID(),'imic_property_price',true); 
								  $contract = wp_get_object_terms( get_the_ID(), 'property-contract-type', array('fields'=>'ids')); 
								  $property_type = wp_get_object_terms( get_the_ID(), 'property-type', array('fields'=>'ids')); 
                                                                  global $imic_options;
                                                                  $currency_symbol = imic_get_currency_symbol($imic_options['currency-select']);
                                                                  $src = wp_get_attachment_image_src(get_post_thumbnail_id(),'150-100-size');
                                                                  if(!empty($src)):
                                                                    $image_container= '<span class ="property_image_map">'.$src[0].'</span>';
                                                                    else:
                                                                    $image_container='';
                                                                    endif;  
                                                                    if(!empty($contract)) {
								  $term = get_term( $contract[0], 'property-contract-type'); $property_term_type = $term->name; } 
                                                                  if(!empty($contract)) {
								  $property_type_term = get_term( $property_type[0], 'property-type'); $property_type_name= $property_type_term->name; } 
								  ?>
                                                                 <li class="property_element grid-item type-rent col-md-12 <?php echo $property_term_type.' '.$property_type_name; ?>">
                                                                     <div class="col-md-4"> <a href="<?php the_permalink(); ?>" class="property-featured-image"><?php the_post_thumbnail('600-400-size'); ?><span class="images-count"><i class="fa fa-picture-o"></i> <?php echo $total_images; ?></span> <span class="badges sortby_rent"><?php echo $property_term_type; ?></span> </a> </div>
                                                               <div class="col-md-8">
                                                               <div class="property-info">
                                                               <div class="price"><strong><?php echo $currency_symbol; ?></strong><span class ="sort_by_price" ><?php echo $property_price; ?></span></div>
                                                               <h3><a href="<?php the_permalink();?>"><?php echo get_the_title(); ?></a><span class="pid"> (<?php echo $property_id; ?>)</span></h3>
                                                               <span class="location sort_by_location"><?php echo $property_city; ?></span>
                                                               <?php echo imic_excerpt(10); ?>
                                                               
                                                               </div>
                                                             <?php
                                                             if(!empty($property_area)||!empty($property_baths)||!empty($property_beds)||!empty($property_parking)){
                                                             echo '<div class="property-amenities clearfix">';
                                                            if(!empty($property_area)){
                                                                echo '<span class="area"><strong>'.$property_area.'</strong>'.__('Area','framework').'</span>';
                                                            }
                                                            if(!empty($property_baths)){
                                                              echo '<span class="baths"><strong>'.$property_baths.'</strong>'.__('Baths','framework').'</span>';  
                                                            }
                                                            if(!empty($property_beds)):
                                                              echo '<span class="beds"><strong>'.$property_beds.'</strong>'.__('Beds','framework').'</span>';  
                                                            endif;
                                                            if(!empty($property_parking)):
                                                            echo '<span class="parking"><strong>'.$property_parking.'</strong>'.__('Parking','framework').'</span>';
                                                            endif;
                                                            echo '</div>';
                                                             }?>
                                                        </div></li>