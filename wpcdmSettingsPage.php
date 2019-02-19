<?php

/**
 * Clase para la configruación del plugin.
 * 
 * @since 1.0.0
 */
class wpcdmSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Añadir página de submenú al menú de configuración.
     */
    public function add_plugin_page()
    {
        $titleMenu = 'WP Convertidor de moneda';
        add_options_page(
            $titleMenu, // $page_api_key
            $titleMenu, // $menu_api_key
            'manage_options', 
            'wp-convertidor-de-moneda', // $menu_slug
            array( $this, 'create_admin_page' ) // function
        );
    }

    /**
     * Página de opciones callback
     */
    public function create_admin_page()
    {
        // Establecer propiedad de clase
        $this->options = get_option( 'wpcdmApi' );
        ?>
        <div class="wrap">
            <h1>WP Convertidor de moneda</h1>
            <form method="post" action="options.php">
            <?php
                // Esto imprime todos los campos de configuración ocultos
                settings_fields( 'my_option_group' );
                do_settings_sections( 'my-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Registrar y añadir ajustes
     */
    public function page_init()
    {        
        register_setting(
            'my_option_group', // Option group
            'wpcdmApi', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Datos de la API', // api_key
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );

        add_settings_field(
            'key', 
            'API Key', 
            array( $this, 'api_key_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['key'] ) )
            $new_input['key'] = sanitize_text_field( $input['key'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Pasos para activar el servicio de divisas:<br>
        <strong>1.</strong> Ingresa tu correo en <a href="https://free.currencyconverterapi.com/free-api-key" target="_blank">currencyconverterapi.com</a>.<br>
        <strong>2.</strong> Te llegara un correo con la API Key, copiala y pégala aquí abajo:';
    }

    /** 
     * Obtén la matriz de opciones de configuración e imprime uno de sus valores.
     */
    public function api_key_callback()
    {
        printf(
            '<input type="text" id="key" name="wpcdmApi[key]" value="%s" />',
            isset( $this->options['key'] ) ? esc_attr( $this->options['key']) : ''
        );
    }
}

if( is_admin() )
    $wpcdm_settings_page = new wpcdmSettingsPage();