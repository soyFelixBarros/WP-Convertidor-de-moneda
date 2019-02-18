<?php
/*******************************************************************************
** Código corto - WP Convertidor de moneda 
** Description: Un widget inteligente para mostrar una herramienta de conversión de moneda.
** @since 1.0.0
*******************************************************************************/
function wpcdmByShortcode($atts) {
    global $wp_widget_factory;
    
    extract(
    	shortcode_atts(
    		array(
    			'titulo' => '',
    			'parrafo_anterior' => __('Herramienta de conversión de moneda proporcionada por WP Convertidor de moneda', 'wpcdm'),
    			'convertir_desde' => '',
    			'a' => ''
    		), 
    		$atts
    	)
    );
    
    $widget_name = 'Widget_WPCDM';
    
	$instance = "titulo=${titulo}";
    
    if ( ! empty($parrafo_anterior) ) $instance .= "&parrafo_anterior=${parrafo_anterior}";
    if ( ! empty($convertir_desde) ) $instance .= "&convertir_desde=${convertir_desde}";
	if ( ! empty($a) ) $instance .= "&a=${a}";
        
    if ( ! is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget') ) {
        $wp_class = 'WP_Widget_' . ucwords(strtolower($class));
        if ( ! is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget' ) ) {
            return '<p>' . __('ERROR: El widget WP Convertidor de moneda no se ha inicializado correctamente.', 'wpcdm') . '</p>';
    	} else {
            $class = $wp_class;
    	}
	}
    
    ob_start();
    
    the_widget(
    	$widget_name, 
    	$instance, 
    	array(
			'widget_id' => 'shortcode-wpcdm-widget-' . $id,
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
			'inline' => true // le dice al widget que no se envuelva en etiquetas de lista
		)
	);
	
    $output = ob_get_contents();
    
    ob_end_clean();
    
    return $output;
    
}

add_shortcode('wpcdm','wpcdmByShortcode',1); 

?>
