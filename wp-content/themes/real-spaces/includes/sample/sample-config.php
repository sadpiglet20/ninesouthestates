<?php
/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */
if (!class_exists('Redux_Framework_sample_config')) {
    class Redux_Framework_sample_config {
        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;
        public function __construct() {
            if (!class_exists('ReduxFramework')) {
                return;
            }
            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }
        }
        public function initSettings() {
            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();
            // Set the default arguments
            $this->setArguments();
            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();
            // Create the sections and fields
            $this->setSections();
            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }
            // If Redux is running as a plugin, this will remove the demo notice and links
            //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }
        /**
          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.
         * */
        function compiler_action($options, $css) {
            //echo '<h1>The compiler hook has run!</h1>';
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }
              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }
        /**
          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.
          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons
         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'redux-framework-demo'),
                //'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );
            return $sections;
        }
        /**
          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;
            return $args;
        }
        /**
          Filter hook for filtering the default value of any given field. Very useful in development mode.
         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';
            return $defaults;
        }
        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {
            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);
                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }
        public function setSections() {
            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();
            if (is_dir($sample_patterns_path)) :
                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();
                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {
                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;
            ob_start();
            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';
            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'redux-framework-demo'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                <?php endif; ?>
                <h4><?php echo $this->theme->display('Name'); ?></h4>
                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'redux-framework-demo'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'redux-framework-demo'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'redux-framework-demo') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'redux-framework-demo'), $this->theme->parent()->display('Name'));
            }
            ?>
                </div>
            </div>
            <?php
            $item_info = ob_get_contents();
            ob_end_clean();
            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }
			
			$defaultLogo = get_template_directory_uri().'/images/logo.png';
			$defaultAdminLogo = get_template_directory_uri().'/images/logo@2x.png';
			$defaultBannerImages = get_template_directory_uri().'/images/page-header1.jpg';
			$defaultFavicon = get_template_directory_uri().'/images/favicon.ico';
                    $this->sections[] = array(
                'type' => 'divide',
            );
            $this->sections[] = array(
                'icon'      => 'el-icon-cogs',
                'title'     => __('General Settings', 'redux-framework-demo'),
                'fields'    => array(
						array(
						'id' => 'logo_upload',
						'type' => 'media',
						'url' => true,
						'title' => __('Upload Logo', 'framework'),
						'subtitle' => __('Upload site logo to display in header.', 'framework'),
						'default' => array('url' => $defaultLogo),
					),
					array(
						'id' => 'banner_image',
						'type' => 'media',
						'url' => true,
						'title' => __('Header Image', 'framework'),
						'desc' => __('Default header image for post types.', 'framework'),
						'subtitle' => __('Set this image as default header image for all Page/Post/Property/Agents/Gallery.', 'framework'),
						'default' => array('url' => $defaultBannerImages),
					),        
                    array(
						'id' => 'enable_maintenance',
						'type' => 'switch',
						'title' => __('Enable Maintenance', 'framework'),
						'subtitle' => __('Enable the themes in maintenance mode.', 'framework'),
						'default' => 0,
						'on' => __('Enabled', 'framework'),
						'off' => __('Disabled', 'framework'),
					),
					array(
						'id' => 'switch-responsive',
						'type' => 'switch',
						'title' => __('Enable Responsive', 'framework'),
						'subtitle' => __('Enable/Disable the responsive behaviour of the theme', 'framework'),
						'default' => 1,
					),
					array(
						'id' => 'enable_backtotop',
						'type' => 'switch',
						'title' => __('Enable Back To Top', 'framework'),
						'subtitle' => __('Enable the back to top button that appears in the bottom right corner of the screen.', 'framework'),
						'default' => 0,
					),
                                          array(
                                          'id' => 'custom_favicon',
                                          'type' => 'media',
                                          'compiler' => 'true',
                                          'title' => __('Custom favicon', 'imic-framework-admin'),
                                          'desc' => __('Upload a image that will represent your website favicon', 'imic-framework-admin'),
                                          'default' => array('url' => $defaultFavicon),
                                          ),
				   array(
						'id' => 'tracking-code',
						'type' => 'textarea',
						'title' => __('Tracking Code', 'framework'),
						'subtitle' => __('Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', 'framework'),
						'default'   => ''
					),
					array(
						'id' => 'custom_admin_login_logo',
						'type' => 'media',
						'url' => true,
						'title' => __('Custom admin login logo', 'framework'),
						'compiler' => 'true',
						//'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
						'desc' => __('Upload a 254 x 95px image here to replace the admin login logo.', 'framework'),
						'subtitle' => __('', 'framework'),
						'default' => array('url' => $defaultAdminLogo),
					),
					$fields = array(
    'id'       => 'country-select',
    'multi'    => true,                                       
    'type'     => 'select',
    'title'    => __('Select Country', 'framework'),
    'subtitle' => __('Select country for this site.', 'framework'),
    'desc'     => __('State list will generate through selected country.', 'framework'),
    // Must provide key => value pairs for select options
    'options'  => array(
        'Algeria' => 'Algeria',
        'Angola' => 'Angola',
        'Benin' => 'Benin',
		'Botswana' => 'Botswana',
		'Burkina-Faso' => 'Burkina Faso',
		'Burundi' => 'Burundi',
		'Cameroon' => 'Cameroon',
		'Cape-Verde' => 'Cape Verde',
		'Central-African-Republic' => 'Central African Republic',
		'Chad' => 'Chad',
		'Comoros' => 'Comoros',
		'Congo' => 'Congo',
		'democratic-republic-of-the-congo' => 'Democratic Republic of the Congo',
		'Djibouti' => 'Djibouti',
		'Egypt' => 'Egypt',
		'Equatorial-Guinea' => 'Equatorial Guinea',
		'Eritrea' => 'Eritrea',
		'Ethiopia' => 'Ethiopia',
		'Gabon' => 'Gabon',
		'Gambia' => 'Gambia',
		'Ghana' => 'Ghana',
		'Guinea' => 'Guinea',
		'Guinea-Bissau' => 'Guinea-Bissau',
		'ivory-coast' => 'Ivory Coast',
		'Kenya' => 'Kenya',
		'Lesotho' => 'Lesotho',
		'Liberia' => 'Liberia',
		'Libya' => 'Libya',
		'Madagascar' => 'Madagascar',
		'Malawi' => 'Malawi',
		'Mali' => 'Mali',
		'Mauritania' => 'Mauritania',
		'Mauritius' => 'Mauritius',
		'Morocco' => 'Morocco',
		'Mozambique' => 'Mozambique',
		'Namibia' => 'Namibia',
		'Niger' => 'Niger',
		'Nigeria' => 'Nigeria',
		'Rwanda' => 'Rwanda',
		'saint-helena' => 'Saint Helena',
		'Sao-Tome-Principe' => 'Sao Tome/Principe',
		'Senegal' => 'Senegal',
		'Seychelles' => 'Seychelles',
		'Sierra-Leone' => 'Sierra Leone',
		'Somalia' => 'Somalia',
		'South-Africa' => 'South Africa',
		'Sudan' => 'Sudan',
		'Swaziland' => 'Swaziland',
		'Tanzania' => 'Tanzania',
		'Togo' => 'Togo',
		'Tunisia' => 'Tunisia',
		'Uganda' => 'Uganda',
		'Zambia' => 'Zambia',
		'Zimbabwe' => 'Zimbabwe',
		'Bangladesh' => 'Bangladesh',
		'Bhutan' => 'Bhutan',
		'Brunei' => 'Brunei',
		'Myanmar' => 'Myanmar',
		'Cambodia' => 'Cambodia',
		'China' => 'China',
		'East-Timor' => 'East Timor',
		'India' => 'India',
		'Indonesia' => 'Indonesia',
		'Japan' => 'Japan',
		'Kazakhstan' => 'Kazakhstan',
		'Korea-north' => 'Korea (north)',
		'Korea-south' => 'Korea (south)',
		'Laos' => 'Laos',
		'Malaysia' => 'Malaysia',
		'Maldives' => 'Maldives',
		'Mongolia' => 'Mongolia',
		'Nepal' => 'Nepal',
		'Philippines' => 'Philippines',
		'russia' => 'Russia',
		'Singapore' => 'Singapore',
		'Sri-Lanka' => 'Sri Lanka',
		'Taiwan' => 'Taiwan',
		'Thailand' => 'Thailand',
		'Vietnam' => 'Vietnam',
		'Australia' => 'Australia',
		'Fiji' => 'Fiji',
		'Kiribati' => 'Kiribati',
		'Micronesia' => 'Micronesia',
		'Nauru' => 'Nauru',
		'New-Zealand' => 'New Zealand',
		'Palau' => 'Palau',
		'Papua-New-Guinea' => 'Papua New Guinea',
		'Samoa' => 'Samoa',
		'solomon-islands' => 'Solomon Islands',
		'Tonga' => 'Tonga',
		'Tuvalu' => 'Tuvalu',
		'Vanuatu' => 'Vanuatu',
		'Anguilla' => 'Anguilla',
		'Antigua-Barbuda' => 'Antigua/Barbuda',
		'Aruba' => 'Aruba',
		'Bahamas' => 'Bahamas',
		'Barbados' => 'Barbados',
		'Cuba' => 'Cuba',
		'Dominica' => 'Dominica',
		'Dominican-Republic' => 'Dominican Republic',
		'Grenada' => 'Grenada',
		'Guadeloupe'=>'Guadeloupe','Haiti'=>'Haiti','Jamaica'=>'Jamaica','Martinique'=>'Martinique','Montserrat'=>'Montserrat','Netherlands -Antilles'=>'Netherlands Antilles','Puerto-Rico'=>'Puerto Rico','St-Kitts-Nevis'=>'St. Kitts/Nevis','St-Lucia'=>'St. Lucia','St Vincent/Grenadines'=>'St-Vincent-Grenadines','Trinidad-Tobago'=>'Trinidad/Tobago','Turks-Caicos'=>'Turks/Caicos','Belize'=>'Belize','Costa-Rica'=>'Costa Rica','El-Salvador'=>'El Salvador','Guatemala'=>'Guatemala','Honduras'=>'Honduras','Nicaragua'=>'Nicaragua','Panama'=>'Panama','Albania'=>'Albania','Andorra'=>'Andorra','Austria'=>'Austria','Belarus'=>'Belarus','Belgium'=>'Belgium','Bosnia-Herzegovina'=>'Bosnia/Herzegovina','Bulgaria'=>'Bulgaria','Croatia'=>'Croatia','Czech-Republic'=>'Czech Republic','Denmark'=>'Denmark','Estonia'=>'Estonia','Finland'=>'Finland','France'=>'France','Georgia'=>'Georgia','Germany'=>'Germany','Greece'=>'Greece','Hungary'=>'Hungary','Iceland'=>'Iceland','Ireland'=>'Ireland','Italy'=>'Italy','Latvia'=>'Latvia','Liechtenstein'=>'Liechtenstein','Lithuania'=>'Lithuania','Luxembourg'=>'Luxembourg','Macedonia'=>'Macedonia','Malta'=>'Malta','Moldova'=>'Moldova','Monaco'=>'Monaco','Netherlands'=>'Netherlands','northern-ireland' => 'Northern Ireland in United Kingdom','Norway'=>'Norway','Poland'=>'Poland','Portugal'=>'Portugal','Romania'=>'Romania','San-Marino'=>'San Marino','scotland'=>'Scotland in United Kingdom','Serbia'=>'Serbia','Slovakia'=>'Slovakia','Slovenia'=>'Slovenia','Spain'=>'Spain','Sweden'=>'Sweden','Switzerland'=>'Switzerland','Ukraine'=>'Ukraine','wales'=>'Wales','United-Kingdom'=>'United Kingdom','Arctic-Ocean'=>'Arctic Ocean','Atlantic-Ocean-North'=>'Atlantic Ocean (North)','Atlantic-Ocean-South'=>'Atlantic Ocean (South)','Assorted'=>'Assorted','Caribbean-Sea'=>'Caribbean Sea','Greek-Isles'=>'Greek Isles','Indian-Ocean'=>'Indian Ocean','Mediterranean-Sea'=>'Mediterranean Sea','Oceania'=>'Oceania','Pacific-Ocean-North'=>'Pacific Ocean (North)','Afghanistan'=>'Afghanistan','Armenia'=>'Armenia','Azerbaijan'=>'Azerbaijan','Bahrain'=>'Bahrain','Cyprus'=>'Cyprus','Iran'=>'Iran','Iraq'=>'Iraq','Israel'=>'Israel','Jordan'=>'Jordan','Kuwait'=>'Kuwait','Kyrgyzstan'=>'Kyrgyzstan','Lebanon'=>'Lebanon','macao'=>'Macao','Oman'=>'Oman','Pakistan'=>'Pakistan','Qatar'=>'Qatar','Saudi-Arabia'=>'Saudi Arabia','Syria'=>'Syria','Tajikistan'=>'Tajikistan','Turkey'=>'Turkey','Turkmenistan'=>'Turkmenistan','United-Arab-Emirates'=>'United Arab Emirates','Uzbekistan'=>'Uzbekistan','Yemen'=>'Yemen','Bermuda'=>'Bermuda','Canada'=>'Canada','cayman-islands'=>'Cayman Islands','Caribbean'=>'Caribbean','Greenland'=>'Greenland','Mexico'=>'Mexico','Brazil' => 'Brazil','United-States'=>'United States','us-virgin-islands'=>'U.S. Virgin Islands','Argentina'=>'Argentina','Bolivia'=>'Bolivia','Chile'=>'Chile','Colombia'=>'Colombia','Ecuador'=>'Ecuador','Guyana'=>'Guyana','Paraguay'=>'Paraguay','Peru'=>'Peru','Suriname'=>'Suriname','Uruguay'=>'Uruguay','Venezuela'=>'Venezuela'
    ),
    'default'  => array('United-States')
),
$fields = array(
    'id'       => 'currency-select',
    'type'     => 'select',
    'title'    => __('Select Currency', 'framework'),
    'subtitle' => __('Select Currency for this site.', 'framework'),
    //'desc'     => __('State list will generate through selected country.', 'framework'),
    // Must provide key => value pairs for select options
    'options'  => array(
        'AED' => __( 'United Arab Emirates Dirham', 'framework' ),
				'AUD' => __( 'Australian Dollars', 'framework' ),
				'BDT' => __( 'Bangladeshi Taka', 'framework' ),
				'BRL' => __( 'Brazilian Real', 'framework' ),
				'BGN' => __( 'Bulgarian Lev', 'framework' ),
				'CAD' => __( 'Canadian Dollars', 'framework' ),
				'CLP' => __( 'Chilean Peso', 'framework' ),
				'CNY' => __( 'Chinese Yuan', 'framework' ),
				'COP' => __( 'Colombian Peso', 'framework' ),
				'CZK' => __( 'Czech Koruna', 'framework' ),
				'DKK' => __( 'Danish Krone', 'framework' ),
				'EUR' => __( 'Euros', 'framework' ),
				'HKD' => __( 'Hong Kong Dollar', 'framework' ),
				'HRK' => __( 'Croatia kuna', 'framework' ),
				'HUF' => __( 'Hungarian Forint', 'framework' ),
				'ISK' => __( 'Icelandic krona', 'framework' ),
				'IDR' => __( 'Indonesia Rupiah', 'framework' ),
				'INR' => __( 'Indian Rupee', 'framework' ),
				'ILS' => __( 'Israeli Shekel', 'framework' ),
				'JPY' => __( 'Japanese Yen', 'framework' ),
				'KRW' => __( 'South Korean Won', 'framework' ),
				'MYR' => __( 'Malaysian Ringgits', 'framework' ),
				'MXN' => __( 'Mexican Peso', 'framework' ),
				'NGN' => __( 'Nigerian Naira', 'framework' ),
				'NOK' => __( 'Norwegian Krone', 'framework' ),
				'NZD' => __( 'New Zealand Dollar', 'framework' ),
				'PHP' => __( 'Philippine Pesos', 'framework' ),
				'PLN' => __( 'Polish Zloty', 'framework' ),
				'GBP' => __( 'Pounds Sterling', 'framework' ),
				'RON' => __( 'Romanian Leu', 'framework' ),
				'RUB' => __( 'Russian Ruble', 'framework' ),
				'SGD' => __( 'Singapore Dollar', 'framework' ),
				'ZAR' => __( 'South African rand', 'framework' ),
				'SEK' => __( 'Swedish Krona', 'framework' ),
				'CHF' => __( 'Swiss Franc', 'framework' ),
				'TWD' => __( 'Taiwan New Dollars', 'framework' ),
				'THB' => __( 'Thai Baht', 'framework' ),
				'TRY' => __( 'Turkish Lira', 'framework' ),
				'USD' => __( 'US Dollars', 'framework' ),
				'VND' => __( 'Vietnamese Dong', 'framework' ),
    ),
    'default'  => 'USD',
),
                )
            );
			$this->sections[] = array(
				'icon' => 'el-icon-chevron-up',
				'title' => __('Featured Block', 'framework'),
				'desc' => __('<p class="description">Add Slides for featured block area on homepage.</p>', 'framework'),
				'fields' => array(
				$fields = array(
    'id'          => 'opt-slides',
    'type'        => 'slides',
    'title'       => __('Featured Block Options', 'framework'),
    'subtitle'    => __('Add Slides for featured block on homepage.', 'framework'),
    'desc'        => __('', 'redux-framework-demo'),
    'placeholder' => array(
        'title'           => __('Insert title for featured block', 'framework'),
        'description'     => __('Insert description for featured block', 'framework'),
        'url'             => __('Insert URL for featured block!', 'framework'),
    ),
),
	),
			);
			$this->sections[] = array(
				'icon' => 'el-icon-chevron-up',
				'title' => __('Header Options', 'framework'),
				'desc' => __('<p class="description">These are the options for the header.</p>', 'framework'),
				'fields' => array(
                                    array(
						'id' => 'enable-top-header-login-dropdown',
						'type' => 'switch',
						'title' => __('Enable Top header login dropdown', 'framework'),
						'subtitle' => __('Enable/Disable Top header login dropdown behaviour of the theme', 'framework'),
						'default' => 1,
					),
					array(
						'id' => 'top_social_links',
						'type' => 'sortable',
						'label' => true,
						'compiler'=>true,
						'title' => __('Social Links', 'framework'),
						'desc' => __('Enter the social links and sort to active and display according to sequence.', 'framework'),
						'options' => array(
										'fa-dribbble' => 'dribbble',
										'fa-dropbox' => 'dropbox',
										'fa-facebook' => 'facebook',
										'fa-flickr' => 'flickr',
										'fa-foursquare' => 'foursquare',
										'fa-github' => 'github',
										'fa-google-plus' => 'plus.google',
										'fa-instagram' => 'instagram',
										'fa-linkedin' => 'linkedin',
										'fa-pinterest' => 'pinterest',
										'fa-skype' => 'skype',
										'fa-stack-exchange' => 'stackexchange',
										'fa-stack-overflow' => 'stackoverflow',
										'fa-trello' => 'trello',
										'fa-tumblr' => 'tumblr',
										'fa-twitter' => 'twitter',
										'fa-vimeo-square' => 'vimeo',
										'fa-xing' => 'xing',
										'fa-youtube' => 'youtube',
										'fa-rss' => 'rss'
							),
					),
					array(
						'id' => 'enable-header-stick',
						'type' => 'switch',
						'title' => __('Enable Header Stick', 'framework'),
						'subtitle' => __('Enable/Disable Header Stick behaviour of the theme', 'framework'),
						'default' => 1,
					),
					array(
						'id' => 'header_free_line',
						'type' => 'text',
						'title' => __('Free line for you', 'framework'),
						'subtitle' => __('Enter free line number', 'framework'),
						'validate'  => 'numeric',
						'default' => '08037867890',
					),
					array(
                        'id'        => 'header_email_us',
                        'type'      => 'text',
                        'title'     => __('Email Us', 'redux-framework-demo'),
                        'subtitle'  => __('Enter email id.', 'redux-framework-demo'),
                        'validate'  => 'email',
                        'msg'       => 'custom error message',
                        'default'   => 'sales@realspaces.com',
                    ),
					array(
						'id' => 'header_working_hours',
						'type' => 'text',
						'title' => __('Working Hours', 'framework'),
						'subtitle' => __('Enter working hours.', 'framework'),
						'default' => '09:00 to 17:00',
					),
                                    array(
						'id' => 'header_background_color',
						'type' => 'color',
						'title' => __('Header Background Color', 'framework'),
						'subtitle' => __('Pick a color for Header Background.', 'imic-framework-admin'),
			                        'validate' => 'color',
					),
					
				),
			);
			$this->sections[] = array(
    'icon' => 'el-icon-check-empty',
    'title' => __('Layout Options', 'imic-framework-admin'),
    'fields' => array(
        array(
			'id'=>'site_layout',
			'type' => 'image_select',
			'compiler'=>true,
			'title' => __('Page Layout', 'imic-framework-admin'), 
			'subtitle' => __('Select the page layout type', 'imic-framework-admin'),
			'options' => array(
					'wide' => array('alt' => 'Wide', 'img' => ReduxFramework::$_url.'assets/img/wide.png'),
					'boxed' => array('alt' => 'Boxed', 'img' => ReduxFramework::$_url.'assets/img/boxed.png')
				),
			'default' => 'wide',
			),
		array(
			'id'=>'repeatable-bg-image',
			'type' => 'image_select',
			'required' => array('site_layout','equals','boxed'),
			'title' => __('Repeatable Background Images', 'imic-framework-admin'), 
			'subtitle' => __('Select image to set in background.', 'imic-framework-admin'),
			'options' => array(
				'pt1.png' => array('alt' => 'pt1', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt1.png'),
				'pt2.png' => array('alt' => 'pt2', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt2.png'),
				'pt3.png' => array('alt' => 'pt3', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt3.png'),
				'pt4.png' => array('alt' => 'pt4', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt4.png'),
				'pt5.png' => array('alt' => 'pt5', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt5.png'),
				'pt6.png' => array('alt' => 'pt6', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt6.png'),
				'pt7.png' => array('alt' => 'pt7', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt7.png'),
				'pt8.png' => array('alt' => 'pt8', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt8.png'),
				'pt9.png' => array('alt' => 'pt9', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt9.png'),
				'pt10.png' => array('alt' => 'pt10', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt10.png'),
				'pt11.jpg' => array('alt' => 'pt11', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt11.jpg'),
				'pt12.jpg' => array('alt' => 'pt12', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt12.jpg'),
				'pt13.jpg' => array('alt' => 'pt13', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt13.jpg'),
				'pt14.jpg' => array('alt' => 'pt14', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt14.jpg'),
				'pt15.jpg' => array('alt' => 'pt15', 'img' => ReduxFramework::$_url.'assets/img/patterns/pt15.jpg')
				)
			),	
		array(
			'id'=>'upload-repeatable-bg-image',
			'compiler'=>true,
			'required' => array('site_layout','equals','boxed'),
			'type' => 'media', 
			'url'=> true,
			'title' => __('Upload Repeatable Background Image', 'imic-framework-admin')
			),
		array(
			'id'=>'full-screen-bg-image',
			'compiler'=>true,
			'required' => array('site_layout','equals','boxed'),
			'type' => 'media', 
			'url'=> true,
			'title' => __('Upload Full Screen Background Image', 'imic-framework-admin')
			),	
		
    ),
);
			$this->sections[] = array(
				'icon' => 'el-icon-chevron-down',
				'title' => __('Footer Options', 'framework'),
				'desc' => __('<p class="description">These are the options for the footer.</p>', 'framework'),
				'fields' => array(
					array(
                        'id'        => 'footer_column',
                        'type'      => 'image_select',
                        'title'     => __('Footer Columns', 'framework'),
                        'desc'      => __('Select type of footer column to display in footer.', 'framework'),
                        'options'   => array(
                            '12' => array('alt' => 'One Column', 'img' => ReduxFramework::$_url . 'assets/img/footerColumns/one-column.png'),
                            '6' => array('alt' => 'One Half Column', 'img' => ReduxFramework::$_url . 'assets/img/footerColumns/one-half-column.png'),
                            '4' => array('alt' => 'One Third Column', 'img' => ReduxFramework::$_url . 'assets/img/footerColumns/one-third-column.png'),
                            '3' => array('alt' => 'One Fourth Column', 'img' => ReduxFramework::$_url . 'assets/img/footerColumns/one-fourth-column.png'),
                        ), 
                        'default' => '3'
                    ),array(
                            'id' => 'footer_sidebar_background_color',
                            'type' => 'color',
                            'title' => __('Footer Sidebar Background Color', 'framework'),
                            'subtitle' => __('Pick a color for Footer Sidebar Background.', 'imic-framework-admin'),
                            'validate' => 'color',
                            ),array(
                                    'id' => 'footer_background_color',
                                    'type' => 'color',
                                    'title' => __('Footer Background Color', 'framework'),
                                    'subtitle' => __('Pick a color for Footer Background.', 'imic-framework-admin'),
                                    'validate' => 'color',
                            ),
					array(
						'id' => 'footer_copyright_text',
						'type' => 'text',
						'title' => __('Footer Copyright Text', 'framework'),
						'subtitle' => __(' Enter Copyright Text', 'framework'),
						'default' => __('All Rights Reserved', 'framework')
					),
					array(
						'id' => 'footer_social_links',
						'type' => 'sortable',
						'label' => true,
						'compiler'=>true,
						'title' => __('Social Links', 'framework'),
						'desc' => __('Enter the social links and sort to active and display according to sequence in footer.', 'framework'),
						'options' => array(
										'fa-dribbble' => 'dribbble',
										'fa-dropbox' => 'dropbox',
										'fa-facebook' => 'facebook',
										'fa-flickr' => 'flickr',
										'fa-foursquare' => 'foursquare',
										'fa-github' => 'github',
										'fa-google-plus' => 'plus.google',
										'fa-instagram' => 'instagram',
										'fa-linkedin' => 'linkedin',
										'fa-pinterest' => 'pinterest',
										'fa-skype' => 'skype',
										'fa-stack-exchange' => 'stackexchange',
										'fa-stack-overflow' => 'stackoverflow',
										'fa-trello' => 'trello',
										'fa-tumblr' => 'tumblr',
										'fa-twitter' => 'twitter',
										'fa-vimeo-square' => 'vimeo',
										'fa-xing' => 'xing',
										'fa-youtube' => 'youtube',
										'fa-rss' => 'rss'
							),
					),
				),
			);
                        $this->sections[] = array(
				'icon' => 'el-icon-search',
				'title' => __('Home Search Options', 'framework'),
				'desc' => __('<p class="description">These are the options for the Search.</p>', 'framework'),
				'fields' => array(
					   array(
						'id' => 'enable_type_in_search',
						'type' => 'switch',
						'title' => __('Enable Property Type', 'framework'),
						'subtitle' => __('Enable/Disable Type in Search Options.', 'framework'),
						'default' => 1,
						'on' => __('Enabled', 'framework'),
						'off' => __('Disabled', 'framework'),
					),
                                    array(
						'id' => 'enable_contract_in_search',
						'type' => 'switch',
						'title' => __('Enable Contract', 'framework'),
						'subtitle' => __('Enable/Disable Contract in Search Options.', 'framework'),
						'default' => 1,
						'on' => __('Enabled', 'framework'),
						'off' => __('Disabled', 'framework'),
					),array(
						'id' => 'enable_location_in_search',
						'type' => 'switch',
						'title' => __('Enable Location', 'framework'),
						'subtitle' => __('Enable/Disable Location in Search Options.', 'framework'),
						'default' => 1,
						'on' => __('Enabled', 'framework'),
						'off' => __('Disabled', 'framework'),
					),array(
						'id' => 'enable_city_in_search',
						'type' => 'switch',
						'title' => __('Enable City', 'framework'),
						'subtitle' => __('Enable/Disable City in Search Options.', 'framework'),
						'default' => 1,
						'on' => __('Enabled', 'framework'),
						'off' => __('Disabled', 'framework'),
					),array(
						'id' => 'enable_beds_in_search',
						'type' => 'switch',
						'title' => __('Enable Beds', 'framework'),
						'subtitle' => __('Enable/Disable Beds in Search Options.', 'framework'),
						'default' => 1,
						'on' => __('Enabled', 'framework'),
						'off' => __('Disabled', 'framework'),
					),
					array(
						'id' => 'enable_bath_in_search',
						'type' => 'switch',
						'title' => __('Enable Baths', 'framework'),
						'subtitle' => __('Enable/Disable Baths in Search Options.', 'framework'),
						'default' => 1,
						'on' => __('Enabled', 'framework'),
						'off' => __('Disabled', 'framework'),
					),
                                    array(
						'id' => 'enable_price_in_search',
						'type' => 'switch',
						'title' => __('Enable  Price', 'framework'),
						'subtitle' => __('Enable/Disable  Price in Search Options.', 'framework'),
						'default' => 1,
						'on' => __('Enabled', 'framework'),
						'off' => __('Disabled', 'framework'),
					),
                                    array(
						'id' => 'enable_area_in_search',
						'type' => 'switch',
						'title' => __('Enable  Area', 'framework'),
						'subtitle' => __('Enable/Disable  Area in Search Options.', 'framework'),
						'default' => 1,
						'on' => __('Enabled', 'framework'),
						'off' => __('Disabled', 'framework'),
					),
                                    array(
						'id' => 'enable_search_by_in_search',
						'type' => 'switch',
						'title' => __('Enable  Search By', 'framework'),
						'subtitle' => __('Enable/Disable  Search By in Search Options.', 'framework'),
						'default' => 1,
						'on' => __('Enabled', 'framework'),
						'off' => __('Disabled', 'framework'),
					)
                                    ));
                             $this->sections[] = array(
				'icon' => 'el-icon-search',
				'title' => __('Search Widget Options', 'framework'),
				'desc' => __('<p class="description">These are the options for Search Widget.</p>', 'framework'),
                            'fields' => array(array(
                                'id'      => 'search-widget-blocks',
                                'type'    => 'sorter',
                                'title'   => 'Search Widget Layout Manager',
                                'desc'    => 'Organize how you want the layout to appear on the Search Widget',
                                'options' => array(
                                    'Enabled'  => array(
                                        'property_type' => 'Property Type',
                                        'contract'     => 'Contract',
                                        'location' => 'Location',
                                        'city'   => 'City',
                                        'beds'   => 'Beds',
                                        'baths'   => 'Baths',
                                        'price'   => 'Price',
                                         'area'   => 'Area',
                                        'search_by'   => 'Search By'
                                       ),
                                    'Disabled' => array(
                                      )
                                ),
                                )));
                         $this->sections[] = array(
				'icon' => 'el-icon-home',
				'title' => __('Single Property Options', 'framework'),
				'desc' => __('<p class="description">These are the options for Agent details.</p>', 'framework'),
				'fields' => array(
					   array(
						'id' => 'enable_agent_details',
						'type' => 'switch',
						'title' => __('Enable Agent details', 'framework'),
						'subtitle' => __('Enable/Disable Agent details on single property page.', 'framework'),
						'default' => 1,
						'on' => __('Enabled', 'framework'),
						'off' => __('Disabled', 'framework'),
					),
                                   
                                    ));
                             $this->sections[] = array(
				'icon' => 'el-icon-home',
				'title' => __('Property Amenities Options', 'framework'),
				'desc' => __('<p class="description">These are the options for Property Amenities.</p>', 'framework'),
				'fields' => array(
					   array(
						'id' => 'properties_amenities',
						'type' => 'multi_text',
						'title' => __("Enter Property's amenity", "framework"),
						'subtitle' => __("Enter Property's amenity","framework"),
					'default' => array(__('Air Conditioning','framework'),__('Heating','framework'),__('Balcony','framework'),__('Dishwasher','framework'),__('Pool','framework'),__('Internet','framework'),__('Terrace','framework'),__('Microwave','framework'),__('Fridge','framework'),__('Cable TV','framework'),__('Security Camera','framework'),__('Toaster','framework'),__('Grill','framework'),__('Oven','framework'),__('Fans','framework'),__('Servants','framework'),__('Furnished','framework'),__('Cupboards','framework')),
                                               ),
                                   
                                    ));
			$this->sections[] = array(
    'icon' => 'el-icon-edit',
    'title' => __('Custom CSS/JS', 'imic-framework-admin'),
    'fields' => array(
        array(
            'id' => 'custom_css',
            'type' => 'ace_editor',
            //'required' => array('layout','equals','1'),	
            'title' => __('CSS Code', 'imic-framework-admin'),
            'subtitle' => __('Paste your CSS code here.', 'imic-framework-admin'),
            'mode' => 'css',
            'theme' => 'monokai',
            'desc' => '',
            'default' => "#header{\nmargin: 0 auto;\n}"
        ),
        array(
            'id' => 'custom_js',
            'type' => 'ace_editor',
            //'required' => array('layout','equals','1'),	
            'title' => __('JS Code', 'imic-framework-admin'),
            'subtitle' => __('Paste your JS code here.', 'imic-framework-admin'),
            'mode' => 'javascript',
            'theme' => 'chrome',
            'desc' => '',
            'default' => "jQuery(document).ready(function(){\n\n});"
        )
    ),
);
            $this->sections[] = array(
                'icon'      => 'el-icon-website',
                'title'     => __('Styling Options', 'framework'),
                'fields'    => array(
                    array(
						'id'=>'theme_color_type',
						'type' => 'button_set',
						'compiler'=>true,
						'title' => __('Page Layout', 'framework'), 
						'subtitle' => __('Select the color type', 'framework'),
						'options' => array(
								'0' => __('Pre-Defined Color Schemes','framework'),
								'1' => __('Custom Color','framework')
							),
						'default' => '0',
						),
					array(
						'id' => 'theme_color_scheme',
						'type' => 'select',
						'required' => array('theme_color_type','equals','0'),
						'title' => __('Theme Color Scheme', 'framework'),
						'subtitle' => __('Select your themes alternative color scheme.', 'redux-framework-demo'),
						'options' => array('color1.css' => 'color1.css', 'color2.css' => 'color2.css', 'color3.css' => 'color3.css', 'color4.css' => 'color4.css', 'color5.css' => 'color5.css', 'color6.css' => 'color6.css', 'color7.css' => 'color7.css', 'color8.css' => 'color8.css', 'color9.css' => 'color9.css', 'color10.css' => 'color10.css'),
						'default' => 'color1.css',
					),	
					array(
						'id'=>'custom_theme_color',
						'type' => 'color',
						'required' => array('theme_color_type','equals','1'),
						'title' => __('Custom Theme Color', 'framework'), 
						'subtitle' => __('Pick a color for the template.', 'framework'),
						'validate' => 'color',
						),
                )
            );
            $theme_info  = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', 'framework') . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', 'framework') . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', 'framework') . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', 'framework') . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';
            
            $this->sections[] = array(
                'title'     => __('Import / Export', 'framework'),
                'desc'      => __('Import and Export your Theme Framework settings from file, text or URL.', 'framework'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => __('Import Export','framework'),
                        'subtitle'      => __('Save and restore your Theme options','framework'),
                        'full_width'    => false,
                    ),
                ),
            );                     
                    
            $this->sections[] = array(
                'type' => 'divide',
            );
            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => __('Theme Information', 'framework'),
                'desc'      => __('<p class="description">Real Spaces</p>', 'framework'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-raw-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );
            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', 'framework'),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }
        public function setHelpTabs() {
            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'redux-framework-demo'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'redux-framework-demo'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );
            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo');
        }
        /**
          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
         * */
        public function setArguments() {
            $theme = wp_get_theme(); // For use with some settings. Not necessary.
            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'imic_options',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => $theme->get('Name'),     // Name that appears at the top of your panel
                'display_version'   => $theme->get('Version'),  // Version that appears at the top of your panel
                'menu_type'         => 'menu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title'        => __('Theme Options', 'framework'),
                'page_title'        => __('IMIC Options', 'framework'),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '', // Must be defined to add google fonts to the typography module
                
                'async_typography'  => false,                    // Use a asynchronous font on the front end or font string
                'admin_bar'         => true,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => true,                    // Enable basic customizer support
                
                // OPTIONAL -> Give you extra features
                'page_priority'     => '58',                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'themes.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                      // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => '_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,                   // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE
                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );
            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/imithemes',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://twitter.com/imithemes',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );
            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace('-', '_', $this->args['opt_name']);
                }
                $this->args['intro_text'] = sprintf(__('<p>Did you know that Real Spaces sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'framework'), $v);
            } else {
                //$this->args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo');
            }
            // Add content after the form.
            //$this->args['footer_text'] = __('<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'redux-framework-demo');
        }
    }
    
    global $reduxConfig;
    $reduxConfig = new Redux_Framework_sample_config();
}
/**
  Custom function for the callback referenced above
 */
if (!function_exists('redux_my_custom_field')):
    function redux_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;
/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';
        /*
          do your validation
          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */
        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;