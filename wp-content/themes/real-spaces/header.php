<!DOCTYPE HTML>
<html <?php language_attributes(); ?> class="no-js">
<head>
<?php global $imic_options; 
$bodyClass = ($imic_options['site_layout'] == 'boxed') ? ' boxed' : '';
        $style='';
       if($imic_options['site_layout'] == 'boxed'){
            if (!empty($imic_options['upload-repeatable-bg-image']['id'])) {
            $style = ' style="background-image:url(' . $imic_options['upload-repeatable-bg-image']['url'] . '); background-repeat:repeat; background-size:auto;"';
        } else if (!empty($imic_options['full-screen-bg-image']['id'])) {
            $style = ' style="background-image:url(' . $imic_options['full-screen-bg-image']['url'] . '); background-repeat: no-repeat; background-size:cover;"';
        }
           else if(!empty($imic_options['repeatable-bg-image'])) {
            $style = ' style="background-image:url(' . get_template_directory_uri() . '/includes/ReduxCore/assets/img/patterns/' . $imic_options['repeatable-bg-image'] . '); background-repeat:repeat; background-size:auto;"';
        }
        } ?>
<!-- Basic Page Needs
  ================================================== -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
<meta charset="<?php bloginfo('charset'); ?>" />
<?php $responsive = $imic_options['switch-responsive']; if($responsive==1) { ?>
<!-- Mobile Specific Metas
  ================================================== -->
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="format-detection" content="telephone=no">
<?php } 
if (isset($imic_options['custom_favicon']) && $imic_options['custom_favicon'] != "") { ?><link rel="shortcut icon" href="<?php echo $imic_options['custom_favicon']['url']; ?>" />
<?php } ?>
<!-- CSS
  ================================================== -->
<!--[if lte IE 8]><link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie8.css" media="screen" /><![endif]-->
<!-- Color Style -->
<!-- SCRIPTS
  ================================================== -->
<?php wp_head(); //WORDPRESS HEAD HOOK ?>
</head>
<?php 
global $imic_options;
$header_style='';
if(isset($imic_options['header_background_color'])&&!empty($imic_options['header_background_color'])){
$header_style='style="background-color: '.$imic_options['header_background_color'].'"';
}if(is_page_template('template-home.php')||is_page_template('template-home-second.php')){
  $home_class = 'home';
}else{
    $home_class='';
} ?>
<body <?php body_class($home_class.' '.$bodyClass); echo $style; ?>>
<!--[if lt IE 7]>
	<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
<![endif]-->
<div class="body">
  <!-- Start Site Header -->
  <header class="site-header" <?php echo $header_style; ?>>
      <?php 
      $menu_locations =get_nav_menu_locations();
      if($imic_options['enable-top-header-login-dropdown']==1){ ?>
    <div class="top-header hidden-xs">
      <div class="container">
        <div class="row">
          <div class="col-md-4 col-sm-6">
          	
            <ul class="horiz-nav pull-left">
              <li class="dropdown">
                    <?php 
					if (is_user_logged_in()) { 
						global $current_user;
      					get_currentuserinfo();
						
						/* Display Current Agent Options
						=========================================*/
						echo '<a data-toggle="dropdown">
			                	<i class="fa fa-user"></i> ';
									if(!empty($current_user->user_firstname)) {
										echo $current_user->user_firstname;   
									} else {
										echo $current_user->user_login;
									}
						echo '	<b class="caret"></b>
                			  </a>';			
					} else {
						//Agent login page link
						$agent_register_url = imic_get_template_url('template-register.php');
						
						/* Display Agent Login Options
						=========================================*/
						echo '<a href="'. $agent_register_url .'">
			                	<i class="fa fa-user"></i>'
									. __(' Login ','framework') .
							 '</a>';	
					}
					
				/* Current Agent Options
				=========================================*/
				global $current_user; // Use global
				get_currentuserinfo(); // Make sure global is set, if not set it.
				if((user_can( $current_user, "agent" ) )||(user_can( $current_user, "administrator" ) )) { ?>
                <ul class="dropdown-menu">
                  <?php
				  //Agent Profile page link
				  $agent_properties = imic_get_template_url('template-agent-properties.php');
				  $add_property = imic_get_template_url('template-submit-property.php');
                  	echo '<li><a href="'. $agent_properties.'">'. __('My properties','framework') .'</a></li>';
				  ?>
                  <li><a href="<?php echo $add_property; ?>"><?php _e('Add a property','framework'); ?></a></li>
                  <?php
				  $agent_favourite_properties = imic_get_template_url('template-favorite-properties.php');
				  echo '<li><a href="'. $agent_favourite_properties .'" title="'. __('Favorite Properties','framework') .'">'. __('Favorite Properties','framework') .'</a></li>';
				  $agent_favourite_searches = imic_get_template_url('template-favorite-search.php');
				  echo '<li><a href="'. $agent_favourite_searches .'" title="'. __('Saved Searches','framework') .'">'. __('Saved Searches','framework') .'</a></li>';
				  //Agent Profile page link
				  $agent_profile = imic_get_template_url('template-agent-profile.php');
                  	echo '<li class="register"><a href="'. $agent_profile .'" title="'. __('My Profile','framework') .'">'. __('My Profile','framework') .'</a></li>';
                  ?>
                  <li class="login"><a href="<?php echo wp_logout_url(home_url()); ?>" title="<?php _e('Logout','framework'); ?>"><?php _e('Logout','framework'); ?></a></li>
                </ul>
                <?php } 
				else { ?>
					<ul class="dropdown-menu">
                  <?php
				  $agent_favourite_properties = imic_get_template_url('template-favorite-properties.php');
				  echo '<li><a href="'. $agent_favourite_properties .'" title="'. __('Favorite Properties','framework') .'">'. __('Favorite Properties','framework') .'</a></li>';
				  $agent_favourite_searches = imic_get_template_url('template-favorite-search.php');
				  echo '<li><a href="'. $agent_favourite_searches .'" title="'. __('Saved Searches','framework') .'">'. __('Saved Searches','framework') .'</a></li>';
				  //Agent Profile page link
				  $agent_profile = imic_get_template_url('template-agent-profile.php');
                  	echo '<li class="register"><a href="'. $agent_profile .'" title="'. __('My Profile','framework') .'">'. __('My Profile','framework') .'</a></li>';
                  ?>
                  <li class="login"><a href="<?php echo wp_logout_url(home_url()); ?>" title="<?php _e('Logout','framework'); ?>"><?php _e('Logout','framework'); ?></a></li>
                </ul>
					<?php } ?>
              </li>
              </ul>
          </div>
          <div class="col-md-8 col-sm-6">
            <ul class="horiz-nav pull-right">
            	<?php
				/* Display Top Bar Social Links
				=======================================*/
				$socialSites = $imic_options['top_social_links'];
				if(!empty($socialSites)) {
				foreach($socialSites as $key => $value) {
					if(filter_var($value, FILTER_VALIDATE_URL)){ 
						echo '<li><a href="'. $value .'" target="_blank"><i class="fa '. $key .'"></i></a></li>';
					}
				} }
				?>
            </ul>
          </div>
        </div>
      </div>
    </div>
      <?php } ?>
    <div class="middle-header">
      <div class="container">
        <div class="row">
          <div class="col-md-4 col-sm-8 col-xs-8">
            <h1 class="logo"> 
            	<?php
				/* Display Site Logo
				==========================*/
				echo '<a href="' . site_url() . '" title="' . site_url() . '">';
				echo (!empty($imic_options['logo_upload']['url'])) ? '<img src="' . $imic_options['logo_upload']['url'] . '" alt="Logo">' : '<img src="' . get_template_directory_uri() . '/images/logo.png" alt="Logo">';
				echo '</a>';
				?>
            </h1>
          </div>
          <div class="col-md-8 col-sm-4 col-xs-4">
              <div class="contact-info-blocks hidden-sm hidden-xs">
                <?php
				/* Display Header Content Info Block
				===========================================*/ 
				if ($imic_options['header_free_line'] != '') {
					echo '<div>
							<i class="fa fa-phone"></i> '.__('Free Line For You','framework').'
							<span>'.$imic_options['header_free_line'].'</span>
						  </div>';
				}
				if ($imic_options['header_email_us'] != '') {
					echo '<div>
							<i class="fa fa-envelope"></i> '.__('Email Us','framework').'
							<span>'.$imic_options['header_email_us'].'</span>
						  </div>';
				}
				if ($imic_options['header_working_hours'] != '') {
					echo '<div>
							<i class="fa fa-clock-o"></i> '.__('Working Hours','framework').'
							<span>'.$imic_options['header_working_hours'].'</span>
						  </div>';	
				}
				?>
             </div>
              <a href="#" class="visible-sm visible-xs menu-toggle"><i class="fa fa-bars"></i></a>
          </div>
        </div>
      </div>
    </div>
    <?php  ?>
    <div class="main-menu-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <nav class="navigation">
              <?php if(!empty($menu_locations['primary-menu'])){
			  /* Display Header Primary Menu
			  ===========================================*/
			  wp_nav_menu(array('theme_location' => 'primary-menu', 'menu_class' => 'sf-menu', 'container' => '')); ?>	<?php } else { echo '<ul class="sf-menu sf-js-enabled">'; wp_list_pages('number=8&title_li='); echo '</ul>'; } ?>
            </nav>
          </div>
        </div>
      </div>
    </div>
    </header>
  <!-- End Site Header -->