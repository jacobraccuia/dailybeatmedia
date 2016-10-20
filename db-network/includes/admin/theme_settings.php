<?php

if(!class_exists('Zuko_Theme_Settings')):
class Zuko_Theme_Settings {
    private $settings_api;

    function __construct() {
        $this->settings_api = new WeDevs_Settings_API;
        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu'), 9 );
    }

    function admin_init() {
        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );
        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
		add_menu_page('Theme Settings', 'Theme Settings', 'manage_options', 'theme_settings', array($this, 'plugin_page'), 'dashicons-carrot', 99);
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'zuko_basic_settings',
                'title' => __( 'Settings', 'zuko' )
            ),
            array(
                'id' => 'zuko_advanced_settings',
                'title' => __( 'Advanced Settings', 'zuko' )
            ),
            array(
                'id' => 'zuko_logos',
                'title' => __( 'Logos', 'zuko' )
            ),
            array(
                'id' => 'zuko_field_type_examples',
                'title' => __( 'All Field Types Available to Use (they don\'t do anything though)', 'zuko' )
            ),
        );
        return $sections;
    }
    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(

            'zuko_basic_settings' => array(
                array(
                    'name' => 'theme_color_light',
                    'label' => __( 'Theme Color (Light)', 'zuko' ),
                    'desc' => __( 'This is the light color of the theme. (include #)', 'zuko' ),
                    'type' => 'text',
                    'default' => '#',
                ),
                array(
                    'name' => 'theme_color_dark',
                    'label' => __( 'Theme Color (Dark)', 'zuko' ),
                    'desc' => __( 'This is the dark color of the theme. (include #)', 'zuko' ),
                    'type' => 'text',
                    'default' => '#',
                ),
            ),

            'zuko_advanced_settings' => array(
            ),

            'zuko_logos' => array(
            ),

            'zuko_field_type_examples' => array(
                array(
                    'name'    => 'color',
                    'label'   => __( 'Color', 'zuko' ),
                    'desc'    => __( 'Color description', 'zuko' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'    => 'wysiwyg',
                    'label'   => __( 'Advanced Editor', 'zuko' ),
                    'desc'    => __( 'WP_Editor description', 'zuko' ),
                    'type'    => 'wysiwyg',
                    'default' => ''
                ),
                array(
                    'name'    => 'multicheck',
                    'label'   => __( 'Multile checkbox', 'zuko' ),
                    'desc'    => __( 'Multi checkbox description', 'zuko' ),
                    'type'    => 'multicheck',
                    'default' => array('one' => 'one', 'four' => 'four'),
                    'options' => array(
                        'one'   => 'One',
                        'two'   => 'Two',
                        'three' => 'Three',
                        'four'  => 'Four'
                    )
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'zuko' ),
                    'desc'    => __( 'Password description', 'zuko' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'file',
                    'label'   => __( 'File', 'zuko' ),
                    'desc'    => __( 'File description', 'zuko' ),
                    'type'    => 'file',
                    'default' => ''
                ),
                array(
                    'name'              => 'text_val',
                    'label'             => __( 'Text Input', 'zuko' ),
                    'desc'              => __( 'Text input description', 'zuko' ),
                    'type'              => 'text',
                    'default'           => 'Title',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name'              => 'number_input',
                    'label'             => __( 'Number Input', 'zuko' ),
                    'desc'              => __( 'Number field with validation callback `intval`', 'zuko' ),
                    'type'              => 'number',
                    'default'           => 'Title',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name'  => 'textarea',
                    'label' => __( 'Textarea Input', 'zuko' ),
                    'desc'  => __( 'Textarea description', 'zuko' ),
                    'type'  => 'textarea'
                ),
                array(
                    'name'  => 'checkbox',
                    'label' => __( 'Checkbox', 'zuko' ),
                    'desc'  => __( 'Checkbox Label', 'zuko' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'    => 'radio',
                    'label'   => __( 'Radio Button', 'zuko' ),
                    'desc'    => __( 'A radio button', 'zuko' ),
                    'type'    => 'radio',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'selectbox',
                    'label'   => __( 'Multiple Selection Dropdown', 'zuko' ),
                    'desc'    => __( 'All Pages with Mutliple Selections', 'zuko' ),
                    'type'    => 'select',
                    'multiple' => true,
                    'default' => 'no',
                    'options' => $this->get_pages(),
                ),
                array(
                    'name'    => 'selectbox_single',
                    'label'   => __( 'Single Selection Dropdown', 'zuko' ),
                    'desc'    => __( 'All Pages with Single Selection', 'zuko' ),
                    'type'    => 'select',
                    'options' => $this->get_pages(),
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'zuko' ),
                    'desc'    => __( 'Password description', 'zuko' ),
                    'type'    => 'password',
                    'default' => ''
                )
            ),    
        );
        return $settings_fields;
    }
    function plugin_page() {
        echo '<div class="wrap">';
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();
        echo '</div>';
    }
    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }
        return $pages_options;
    }

    function get_templates() {
        $stylesheet = get_stylesheet();
        $theme = wp_get_theme($stylesheet);

        $templates = $theme->get_files('php', -1);
        $pages_options = array();
        if($templates) {
            foreach($templates as $template => $template_name) {
                $pages_options[$template] = $template;
        }

    }
    return $pages_options;
}
}
endif;

?>