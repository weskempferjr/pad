<?php

/**
 * 
 */
class PAD_Theme_Settings
{
    private $options_name = PAD_THEME_OPTIONS_NAME;

    private $version = PAD_THEME_VERSION ;

    private $default_display_site_title = true;
    private $default_logo_height = PAD_LOGO_DEFAULT_HEIGHT;
    private $default_logo_width = PAD_LOGO_DEFAULT_WIDTH;

    /**
     * Constructor
     *
     * @since 0.0.1
     */
    public function __construct() {

    }

    /*
	 * Get the theme options name.
	 *
	 * @return string theme options name.
	 */
    public function get_options_name() {
        return $this->options_name;
    }

    /*
	 * This function was intended to be called to delete the
	 * options from the database.
	 *
	 * @since 0.0.1
	 */

    public function delete_options() {
        if ( current_user_can('delete_plugins') ) {
            delete_option($this->options_name );
        }
    }

    /*
	 * This method defines the theme settings page.
	 *
	 * @since 1.0.0
	 *
	 * @param none
	 * @return void
	 */
    public function settings_init(  ) {

        register_setting( 'pad-theme-settings-group', $this->options_name, array( $this, 'sanitize') );

        add_settings_section(
            'pad-theme-settings-general-section',
            __( 'PAD Theme General Settings', PAD_THEME_TEXTDOMAIN ),
            array($this, 'section_general_render'),
            'pad-theme-settings-page'
        );


        add_settings_field(
            'display_site_title',
            __( 'Display site title', PAD_THEME_TEXTDOMAIN ),
            array($this, 'display_site_title_render'),
            'pad-theme-settings-page',
            'pad-theme-settings-general-section'
        );

        add_settings_field(
            'logo_width',
            __( 'Logo Width', PAD_THEME_TEXTDOMAIN ),
            array($this, 'logo_width_render'),
            'pad-theme-settings-page',
            'pad-theme-settings-general-section'
        );

        add_settings_field(
            'logo_height',
            __( 'Logo Height', PAD_THEME_TEXTDOMAIN ),
            array($this, 'logo_height_render'),
            'pad-theme-settings-page',
            'pad-theme-settings-general-section'
        );


    }


    /*
     * Render general settings section info.
     * @since 0.0.1
     */
    public function section_general_render () {
        echo '<p>' . __("General settings for PAD Theme", PAD_THEME_TEXTDOMAIN) . '</p>';
    }

    /*
     * Render display site title checkbox field.
     * @since 1.0.0
     */
    public function display_site_title_render(  ) {

        $options = get_option( $this->options_name );
        if ( isset($options['display_site_title'] )) {
            $display_site_title = $options['display_site_title'] ;
        }
        else {
            $display_site_title = $this->default_display_site_title;
        }
        ?>
        <input id="pad_theme_display_site_title_input" type="checkbox" name="pad_theme_settings[display_site_title]" <?php checked( $display_site_title, 1 ); ?> value='1'>
        <br><label for="pad_theme_display_site_title_input"><?php _e('Display site title in header', PAD_THEME_TEXTDOMAIN) ?></label>
        <?php

    }

    /*
    * Render logo height, width fields.
    * @since 1.0.0
    */
    public function logo_width_render(  ) {

        $options = get_option( $this->options_name );
        if ( isset( $options['logo_width'])) {
            $logo_width = $options['logo_width'] ;
        }
        else {
            $logo_width = $this->default_logo_width ;
        }
        ?>
        <input id="pad_theme_logo_width_input" type="number" name="pad_theme_settings[logo_width]" value='<?php echo $logo_width ; ?>'>
        <br><label for="pad_theme_logo_width_input"><?php _e('Set suggested width for site logo', PAD_THEME_TEXTDOMAIN) ?></label>
        <?php

    }

    public function logo_height_render(  ) {

        $options = get_option( $this->options_name );
        if ( isset( $options['logo_height'])) {
            $logo_height = $options['logo_height'] ;
        }
        else {
            $logo_height = $this->default_logo_height ;
        }
        ?>
        <input id="pad_theme_logo_height_input" type="number" name="pad_theme_settings[logo_height]" value='<?php echo $logo_height; ?>'>
        <br><label for="pad_theme_logo_height_input"><?php _e('Set suggested height for site logo', PAD_THEME_TEXTDOMAIN) ?></label>
        <?php

    }


    /*
	 * Calls add_options_page to register the page and menu item.
	 * 
	 * @since 0.0.1
	 * 
	 * @param none
	 */
    public function add_pad_theme_options_page( ) {

        // Add the top-level admin menu
        $page_title = 'PAD Theme Settings';
        $menu_title = 'PAD Theme';
        $capability = 'manage_options';
        $menu_slug = 'pad-theme-settings';
        $function = 'settings_page';
        add_options_page($page_title, $menu_title, $capability, $menu_slug, array($this, $function)) ;


    }

    /*
	 * Defines and displays the plugin settings page.
	 * @since 0.0.1
	 * 
	 * @param none
	 * @return none
	 */
    public function settings_page(  ) {

        $this->add_option_defaults();

        ?>
        <div class="wrap">
            <form action='options.php' method='post'>

                <h2>PAD Theme Settings</h2>
                <div id="pad-theme-settings-container">
                    <?php

                    settings_fields( 'pad-theme-settings-group' );
                    do_settings_sections( 'pad-theme-settings-page' );
                    submit_button();
                    ?>
                </div>
                <div id="pad-theme-settings-info-container">
                    <h3>PAD Theme</h3>
                    <p><em> Version: <?php echo $this->version ?></em></p>
                </div>


            </form>
        </div>
        <?php

    }

    /*
         * Sanitize user input before passing values on to update options.
         * @since 1.0.0
         */
    public function sanitize( $input ) {

        $new_input = array();

        if( isset( $input['display_site_title'] ) ) {
            $new_input['display_site_title'] = sanitize_text_field( $input['display_site_title'] );
        }
        else {
            // set to default 
            $new_input['display_site_title'] = false ;
        }


        if( isset( $input['logo_width'] ) ) {
            $new_input['logo_width'] = intval( $input['logo_width'] );
        }
        else {
            // set to default
            $new_input['logo_width'] = PAD_LOGO_DEFAULT_WIDTH ;
        }

        if( isset( $input['logo_height'] ) ) {
            $new_input['logo_height'] = intval( $input['logo_height'] );
        }
        else {
            // set to default
            $new_input['logo_height'] = PAD_LOGO_DEFAULT_HEIGHT ;
        }
        
        return $new_input ;
    }

    public function add_option_defaults() {

        if ( current_user_can('install_themes') ) {

            if ( get_option( PAD_THEME_OPTIONS_NAME ) === false ) {
                $options = array();

                $options['display_site_title'] = $this->default_display_site_title;

                add_option( $this->options_name, $options );
            }

        }

    }

}