<?php
/*
Template Name: Submit Property 
*/
get_header(); 
global $current_user; // Use global
get_currentuserinfo(); // Make sure global is set, if not set it.
if((user_can( $current_user, "agent" ) )||(user_can( $current_user, "administrator" ) )):
    global $imic_options;
    $currency_symbol = imic_get_currency_symbol($imic_options['currency-select']);
    $msg = '';
    $flag=0;
    $property_address_value=$property_title=$property_pin=$property_amenities_value=$property_area_value=$property_baths_value=$property_beds_value=$property_city_value=$property_parking_value=$property_price_value=$Property_Id=$property_contract_type_value=$property_type_value=$property_content=$property_sights_value=$othertextonomies='';
    if(get_query_var('site')){
	$Property_Id = get_query_var('site');
        $property_title =  get_the_title($Property_Id);
	$property_address_value = get_post_meta($Property_Id,'imic_property_site_address',true);
        $property_content=get_post_field('post_content', $Property_Id);
	$property_city_value = get_post_meta($Property_Id,'imic_property_site_city',true);
	$property_pin = get_post_meta($Property_Id,'imic_property_pincode',true);
	$property_price_value = get_post_meta($Property_Id,'imic_property_price',true);
	$property_area_value = get_post_meta($Property_Id,'imic_property_area',true);
	$property_baths_value = get_post_meta($Property_Id,'imic_property_baths',true);
	$property_beds_value = get_post_meta($Property_Id,'imic_property_beds',true);
	$property_parking_value = get_post_meta($Property_Id,'imic_property_parking',true);
	$property_amenities_value = get_post_meta($Property_Id,'imic_property_amenities',true);
	$property_sights_value = get_post_meta($Property_Id,'imic_property_sights',false);
	$property_amenities_per = $property_amenities_value;
	$type = wp_get_object_terms( $Property_Id, 'property-type', array('fields'=>'ids')); 
	if(!empty($type)) {
	$term = get_term( $type[0], 'property-type' );
         $property_type_value = $term->name;
       }
       $city_type = wp_get_object_terms( $Property_Id, 'city-type', array('fields'=>'ids')); 
	if(!empty($city_type)) {
	$city_term = get_term( $city_type[0], 'city-type');
        $city_type_value = $city_term->slug;
       }
       $contract = wp_get_object_terms( $Property_Id, 'property-contract-type', array('fields'=>'ids')); 
	if(!empty($contract)) {
	$terms = get_term( $contract[0], 'property-contract-type' );
        $property_contract_type_value = $terms->name;}
        $othertextonomies = get_post_meta($Property_Id,'imic_property_custom_city',true);
        
}
$Property_Status = get_post_meta(get_the_ID(),'imic_property_status',true);
// Check if the form was submitted
if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] )) {
   
$property_title = $_POST['title'];
$property_pin = $_POST['pin'];
$property_content = $_POST['description'];
$property_address_value = $_POST['address'];
$property_custom_city = $_POST['othertextonomies'];
$property_city_value = $_POST['city'];
$property_price_value = $_POST['price'];
$property_beds_value = $_POST['beds'];
$property_baths_value = $_POST['baths'];
$property_parking_value = $_POST['parking'];
$property_area_value = $_POST['area'];
$property_contract_type_value=$_POST['contract'];
$property_type_value=$_POST['type'];
if(isset($_POST['textonomies_city'])){
    $city_type_value=$_POST['textonomies_city'];
}
$ac=$heating=$balcony=$dish=$pool=$net=$terrace=$microwave=$fridge=$cable=$camera=$toaster=$grill=$oven=$fans=$servants=$furnished =$city_type_value= '';
if(isset($imic_options['properties_amenities'])&&count($imic_options['properties_amenities'])>1){
            $amenity_array=array();
    foreach($imic_options['properties_amenities'] as $properties_amenities){
               $am_name= strtolower(str_replace(' ','',$properties_amenities));
            if(isset($_POST[$am_name])){
             $am_name=$properties_amenities;  
            }else{
              $am_name = __('Not Selected','framework');  
            }
            array_push($amenity_array,$am_name);
}}
if(($city_type_value=='other')||($city_type_value=='city')){
	$city_type_value = '';
}
else{
    if(isset($_POST['textonomies_city'])){
       
   $property_custom_city = ''; 
}}
if(empty($property_title)) {
	$msg = __("Please enter property name.","framework")."<br/>";
}
if(empty($property_content)){
	$msg .= __("Please enter property description.","framework")."<br/>";
}
if(empty($property_address_value)){
	$msg .= __("Please enter property address.","framework")."<br/>";	
}
if($property_city_value==__('Province','framework')){
	$msg .= __("Please select property province.","framework")."<br/>";	
}
if(empty($property_price_value)){
	$msg .= __("Please enter property value.","framework")."<br/>";	
}
if($property_beds_value==__('Beds','framework')){
	$msg .= __("Please select property bedrooms.","framework")."<br/>";	
}
if($property_baths_value==__('Baths','framework')){
	$msg .= __("Please enter property baths.","framework")."<br/>";	
}
if($property_parking_value==__('Parking','framework')){
	$msg .= __("Please select property parking.","framework")."<br/>";	
}
if($property_type_value==__('Property Type','framework')){
	$msg .= __("Please select property type.","framework")."<br/>";	
}
if($property_contract_type_value==__('Contract Type','framework')){
	$msg .= __("Please select property contract type.","framework")."<br/>";	
}
if(empty($property_area_value)){
	$msg .= __("Please enter property area.","framework")."<br/>";	
}
if(empty($property_sights_value)){
if(!file_exists($_FILES['sightMulti']['tmp_name'][0])&&empty($_FILES['sightMulti']['tmp_name'][0])){
	$msg .= __("Please upload file.","framework");
}}
if($msg=='') {
if(get_query_var('site')) {
	$post = array(
	'ID'			=> get_query_var('site'),
    'post_title'    => $property_title,
    'post_content'  => $property_content,
    'post_status'   => 'publish',         // Choose: publish, preview, future, etc.
    'post_type'     => 'property'  // Use a custom post type if you want to
);
$pid = wp_update_post($post);
// Pass  the value of $post to WordPress the insert function
$flag=1;
}
else {
$post = array(
'post_title'    => $property_title,
'post_content'  => $property_content,
'post_status'   => 'draft',
'post_type'     => 'property'  // Use a custom post type if you want to
);
$pid = wp_insert_post($post);
$flag=1;
}
wp_set_object_terms($pid,$property_contract_type_value,'property-contract-type');
wp_set_object_terms($pid,$property_type_value,'property-type');
if( 'POST' == $_SERVER['REQUEST_METHOD']  ) {
update_post_meta($pid,'imic_property_site_address',$property_address_value);
update_post_meta($pid,'imic_property_pincode',$property_pin);
update_post_meta($pid,'imic_property_site_city',$property_city_value);
update_post_meta($pid,'imic_property_price',$property_price_value);
update_post_meta($pid,'imic_property_area',$property_area_value);
update_post_meta($pid,'imic_property_baths',$property_baths_value);
update_post_meta($pid,'imic_property_beds',$property_beds_value);
update_post_meta($pid,'imic_property_amenities',$amenity_array);
update_post_meta($pid,'imic_property_parking',$property_parking_value);
wp_set_object_terms($pid,$property_contract_type_value,'property-contract-type');
wp_set_object_terms($pid,$property_type_value,'property-type');
if(!empty($property_custom_city)){
  $city_type_value_with_filtered=$property_custom_city;  
  }else if(!empty($city_type_value)){
    $city_type_value_with_filtered=$city_type_value; 
}else{
   $city_type_value_with_filtered='';
}
if(get_query_var('site')) {
wp_set_object_terms($pid,$city_type_value_with_filtered,'city-type'); 
}
else if(!empty($city_type_value)){
wp_set_object_terms($pid,$city_type_value,'city-type');
}
else{
update_post_meta($pid,'imic_property_custom_city',$property_custom_city);
}
if(!empty($_FILES['sightMulti']['tmp_name'][0])) {
		$i = 1;
		$files = $_FILES['sightMulti'];
		foreach ($files['name'] as $key => $value) { 			
			if ($files['name'][$key]) { 
				$file = array( 
					'name' => $files['name'][$key],
					'type' => $files['type'][$key], 
					'tmp_name' => $files['tmp_name'][$key], 
					'error' => $files['error'][$key],
					'size' => $files['size'][$key]
				); 
				$_FILES = array ("sight".$i => $file); 
				$newuploadMulti = sight("sight".$i,$pid);
                               if($i==1){
                                  update_post_meta($pid,'_thumbnail_id',$newuploadMulti);
                                }
				add_post_meta($pid,'imic_property_sights',$newuploadMulti,false);						
}
			$i++;
		}
 }
}
if(get_query_var('site')){
$Property_Id = get_query_var('site');
$property_title =  get_the_title($Property_Id);
$property_address_value = get_post_meta($Property_Id,'imic_property_site_address',true);
$property_pin = get_post_meta($Property_Id,'imic_property_pincode',true);
$property_city_value = get_post_meta($Property_Id,'imic_property_site_city',true);
$property_price_value = get_post_meta($Property_Id,'imic_property_price',true);
$property_area_value = get_post_meta($Property_Id,'imic_property_area',true);
$property_baths_value = get_post_meta($Property_Id,'imic_property_baths',true);
$property_beds_value = get_post_meta($Property_Id,'imic_property_beds',true);
$property_parking_value = get_post_meta($Property_Id,'imic_property_parking',true);
$property_amenities_value = get_post_meta($Property_Id,'imic_property_amenities',true);
$property_sights_value = get_post_meta($Property_Id,'imic_property_sights',false);
$property_amenities_per = $property_amenities_value;
$type = wp_get_object_terms( $Property_Id, 'property-type', array('fields'=>'ids')); 
if(!empty($type)) {
$term = get_term( $type[0], 'property-type' );
 $property_type_value = $term->name;
}
$contract = wp_get_object_terms( $Property_Id, 'property-contract-type', array('fields'=>'ids')); 
if(!empty($contract)) {
$terms = get_term( $contract[0], 'property-contract-type' );
$property_contract_type_value = $terms->name;}
}
}
}
if(get_query_var('site')){
$current_Id = get_query_var('site');
} else{
$current_Id=  get_the_ID();
}
if(($flag==1)&&(!get_query_var('site'))){
wp_reset_query();
$id ='submit_success';
$text=__('Thank you for submitting this property. Our team will analyze it and if everything is  ok we will approve it soon.','framework');
$url1="location.href='". get_permalink(get_the_ID())."'";
$url2="location.href='".site_url()."'";
echo $modalBox = '<button class="btn btn-primary btn-lg property_prompt" data-toggle="modal" data-target="#'.$id.'"></button>
<div class="modal fade" id="'.$id.'" tabindex="-1" role="dialog" aria-labelledby="'.$id.'Label" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
</div>
<div class="modal-body"> '. $text .' </div>
<div class="modal-footer">
<button type="button" onclick="'.$url1.'" class="btn btn-default inverted" data-dismiss="modal">Submit another property</button></a>
<button type="button" onclick="'.$url2.'" class="btn btn-default inverted" data-dismiss="modal">Back to Home</button>
</div>
  </div>
</div>
</div>';
?>
<script>
jQuery(document).ready(function () {
jQuery('.property_prompt').hide();
jQuery('.property_prompt').click(function () { 
jQuery('.modal').addClass('in').show();
});
jQuery('.property_prompt').trigger('click');
});
</script>
<?php
}
if(($flag==1)&&(get_query_var('site'))){
wp_reset_query();
$id ='submit_success';
$text=__('Your property has been successfully updated.','framework');
$url1="location.href='". get_permalink(get_the_ID())."'";
$url2="location.href='".site_url()."'";
echo $modalBox = '<button class="btn btn-primary btn-lg property_prompt" data-toggle="modal" data-target="#'.$id.'"></button>
<div class="modal fade" id="'.$id.'" tabindex="-1" role="dialog" aria-labelledby="'.$id.'Label" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
</div>
<div class="modal-body"> '. $text .' </div>
<div class="modal-footer">
<button type="button" onclick="'.$url1.'" class="btn btn-default inverted" data-dismiss="modal">Add property</button></a>
<button type="button" onclick="'.$url2.'" class="btn btn-default inverted" data-dismiss="modal">Back to Home</button>
</div>
  </div>
</div>
</div>';
?>
<script>
jQuery(document).ready(function () {
jQuery('.property_prompt').hide();
jQuery('.property_prompt').click(function () { 
jQuery('.modal').addClass('in').show();
});
jQuery('.property_prompt').trigger('click');
});
</script>
<?php
}
?>
  <!-- Start Content -->
<div class="main" role="main"><div id="content" class="content full"><div class="container">
<div class="page"><div class="row"><div class="col-md-12">
<form id="add-property" action="#submit-property" method="post" enctype="multipart/form-data">
<div class="block-heading" id="details">
<a href="#additionalinfo" class="btn btn-sm btn-default pull-right"><?php _e('Additional Info ','framework'); ?><i class="fa fa-chevron-down"></i></a>
<h4><span class="heading-icon"><i class="fa fa-home"></i></span><?php _e('Property Details','framework'); ?></h4>
</div>
<div class="padding-as25 margin-30 lgray-bg">
<div class="row">
<div class="col-md-4 col-sm-4">
<input name="title" value="<?php echo $property_title; ?>" type="text" class="form-control" placeholder="<?php _e('Property name','framework'); ?>">
</div>
<div class="col-md-4 col-sm-4">
<input name="address" value="<?php echo $property_address_value; ?>" type="text" class="form-control" placeholder="<?php _e('Address','framework'); ?>">
</div>
<div class="col-md-4 col-sm-4">
<input name="area" value="<?php echo $property_area_value; ?>" type="text" class="form-control" placeholder="<?php _e('Area','framework'); ?>">
</div>
 </div>
 <div class="row">
<?php $imic_country_wise_city = imic_get_multiple_city();
if (!empty($imic_country_wise_city)) {
echo '<div class="col-md-4 col-sm-4">';
echo '<select name="city" class="form-control margin-0 selectpicker">';
echo'<option>' . __('Province', 'framework') . '</option>';
foreach ($imic_country_wise_city as $key => $value) {
echo "<option value='" . $key . "' ".selected($property_city_value,$key).">" . $value . "</option>";
}
echo'</select>';
echo '</div>';
}
?>
<div class="col-md-4 col-sm-4">
<input name="pin" value="<?php echo $property_pin; ?>" type="text" class="form-control" placeholder="<?php _e('Property Pin Code','framework'); ?>">
</div>
<div class="col-md-4 col-sm-4 submit-description">
<textarea name="description" style="resize:vertical" class="form-control margin-0" rows="1" cols="10" placeholder="<?php _e('Propery Description','framework'); ?>"><?php echo $property_content; ?></textarea>
</div>
 </div>
<?php 
echo '<div class="row">';
$args = array('orderby' => 'count', 'hide_empty' => false);
$terms=get_terms(array('city-type'), $args);
if (!empty($terms)) { 
echo '<div class="col-md-4 col-sm-4">';
echo '<select name="textonomies_city" class="textonomies_city form-control margin-0 selectpicker">';
echo'<option value="city">' . __('City', 'framework') . '</option>';
foreach ($terms as $term_data) {
echo "<option value='" .$term_data->slug. "' ".selected($city_type_value,$term_data->slug).">" . $term_data->name . "</option>";
}
echo'<option value ="other">' . __('Other', 'framework') . '</option>';
echo'</select>';
echo '</div>';
}
echo '<div class="col-md-4 col-sm-4">';
echo '<input type="text" name="othertextonomies" value ="'.$othertextonomies.'" class ="form-control othertextonomies margin-0" placeholder="'.__('Enter city name','framework').'">';
echo'</div>';
echo '</div>';?>
</div>
<div class="block-heading" id="additionalinfo">
<a href="#amenities" class="btn btn-sm btn-default pull-right"><?php _e('Enter Amenities ','framework'); ?><i class="fa fa-chevron-down"></i></a>
<h4><span class="heading-icon"><i class="fa fa-plus"></i></span><?php _e('Additional Info','framework'); ?></h4>
</div>
<div class="padding-as25 margin-30 lgray-bg">
<div class="row">
<div class="col-md-4 col-sm-4">
<input name="price" value="<?php echo $property_price_value; ?>" type="text" class="form-control" placeholder="<?php echo __('Price','framework').'('.$currency_symbol.')'; ?>">
</div>
<?php
echo '<div class="col-md-4 col-sm-4 submit-property-type">';
echo '<select name="type" class="form-control margin-0 selectpicker">';
echo '<option>' . __('Property Type', 'framework') . '</option>';
$property_type = get_terms( 'property-type', array(
'hide_empty' => 0
 ) ); 
if ( !empty( $property_type ) && !is_wp_error( $property_type ) ){
foreach ( $property_type as $term ) {
$selected = ($property_type_value==$term->name)?"selected":"";
echo '<option '.$selected.'>'.$term->name.'</option>';
}
}
echo "</select>";
echo '</div>';
echo '<div class="col-md-4 col-sm-4 submit-contract-type">';
echo '<select name="contract" class="form-control margin-0 selectpicker">';
echo '<option>' . __('Contract Type', 'framework') . '</option>'; $property_contract_type = get_terms( 'property-contract-type', array(
'hide_empty' => 0
) ); 
if ( !empty( $property_contract_type ) && !is_wp_error( $property_contract_type ) ){
foreach ( $property_contract_type as $term ) {
$selected = ($property_contract_type_value==$term->name)?"selected":"";
echo '<option '.$selected.'>'.$term->name.'</option>';
}
} echo "</select>";
echo '</div>';
?>
</div>
<div class="row">
<div class="col-md-4 col-sm-4">
<select name="beds" class="form-control selectpicker">
<?php
echo'<option>' .__('Beds','framework') .'</option>';
for ($g = 1; $g <= 10; $g++) {
echo "<option value='" . $g . "' ".selected($property_beds_value,$g).">" . $g . "</option>";
}
?>
</select>
</div>
<div class="col-md-4 col-sm-4">
<select name="baths" class="form-control selectpicker">
<?php
echo'<option>' . __('Baths', 'framework') . '</option>';
for ($i = 1; $i <= 10; $i++) {
echo "<option value='" . $i . "' ".selected($property_baths_value,$i).">" . $i . "</option>";
}
?>
</select>
</div>
<div class="col-md-4 col-sm-4">
<select name="parking" class="form-control selectpicker">
<?php
echo'<option>' . __('Parking', 'framework') . '</option>';
for ($j = 1; $j <= 5; $j++) {
echo "<option value='" . $j . "' ".selected($property_parking_value,$j).">" . $j . "</option>";
}
?>
</select>
</div>
</div>
<div class="row">
<div class="col-md-12 col-sm-12">
<label><?php _e('Upload Images','framework'); ?></label>
<p><?php _e('Upload images that are best clicked for better appearance of your property','framework'); ?></p> 
</div>
</div>
    <div class="row" id="multiplePhotos" style="margin-top:15px;">
	<div class="col-md-12 col-sm-12">
    <?php 
        echo'<div class="image-placeholder" id="photoList" style="background-color:#EEE;">';
       if(!empty($property_sights_value)){
        foreach($property_sights_value as $property_sights){
             echo '<div class="col-md-2 col-sm-2">'; 
          echo '<img src="'.wp_get_attachment_thumb_url($property_sights).'" class="image-placeholder" id="filePhoto2" alt=""/>';  
        echo '</div>';
        }}
        echo '</div>';?>
<input id="filePhotoMulti" type="file" name="sightMulti[]" multiple onChange="previewMultiPhotos();">
</div>
</div>
</div>
<div class="block-heading" id="amenities">
<a href="#submit-property" class="btn btn-sm btn-default pull-right"><?php _e('Submit Property ','framework'); ?><i class="fa fa-chevron-down"></i></a>
<h4><span class="heading-icon"><i class="fa fa-star"></i></span><?php _e('Amenities','framework'); ?></h4>
</div>
<div class="padding-as25 margin-30 lgray-bg">
<div class="row">
<?php $amenity_array=array();
$id=get_query_var('site');
if(!empty($id)){
$property_amenities = get_post_meta($id,'imic_property_amenities',true);
global $imic_options;		
foreach($property_amenities as $properties_amenities_temp){
if($properties_amenities_temp!='Not Selected'){
array_push($amenity_array,$properties_amenities_temp);
}}}
global $imic_options;
if(isset($imic_options['properties_amenities'])&&count($imic_options['properties_amenities'])>1){
foreach($imic_options['properties_amenities'] as $properties_amenities){
$am_name= strtolower(str_replace(' ','',$properties_amenities));
$check='';
if(in_array($properties_amenities, $amenity_array)){
$check='checked="checked"';
}
echo '<div class="col-md-2 col-sm-2 col-xs-6">';
echo '<label class="checkbox">';
echo '<input type ="checkbox" name ="'.$am_name.'" '.$check.' value ="'.$properties_amenities.'">'.$properties_amenities;
echo '</label>';
echo '</div>';
}
}else{
_e('There is no Properties Amenities','framework');
}
?>
</div>
</div>
<div class="text-align-center" id="submit-property">
<input type="submit" value="<?php _e('Submit Now','framework'); ?>" name="submit" class="btn btn-primary btn-lg"/>
<input type="hidden" name="post_type" id="post_type" value="domande" />
<input type="hidden" name="action" value="post" />
<?php wp_nonce_field( 'new-post' ); ?>
</div>
</form>	
<?php if($msg!='') {
echo '<div id="message"><div class="alert alert-error">'.$msg.'</div></div>'; 
} 
if(isset($pid)) {
//echo "<div id=\"message\"><div class=\"alert alert-success\">".__('Successfully Added Property','framework')."</div></div>"; 
} ?>
</div></div></div></div></div></div>
<?php else:  echo imic_unidentified_agent(); endif;  get_footer(); ?>