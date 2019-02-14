<?php
/*******************************************************************************
** Clase de widgets - WP Convertidor de moneda
** Description: Un widget inteligente para mostrar una herramienta de conversión de moneda.
** @since 1.0.0
*******************************************************************************/
class Widget_WPCDM extends WP_Widget {

	function Widget_WPCDM() {

		$widget_ops = array( 
			'description' => __('Herramienta de conversión de moneda proporcionada por WP Convertidor de moneda', 'wpcdm')
		);
		
		$this->WP_Widget( 
			'wp-convertidor-de-moneda', 
			'Widget WP Convertidor de moneda', 
			$widget_ops
		);
		
	}

	function widget($args, $instance) {
		extract( $args, EXTR_SKIP );
		
		$title = $instance['titulo'];
		$parrafo_anterior = $instance['parrafo_anterior'];
		$convertir_desde = $instance['convertir_desde'];
		$a = $instance['a'];
		
		$from_currency_options = $this->retrieveCurrencyOptions('from_currencies', $convertir_desde);
		$to_currency_options = $this->retrieveCurrencyOptions('to_currencies', $a);
		
		echo '<div id="' . $args['widget_id'] . '" class="widget wp-convertidor-de-moneda-widget">';
		
		echo '<h3>' . $title . '</h3>';
		
		// iniciar el contenido del widget
		echo '<div>' . (!empty($parrafo_anterior) ? '<p>' . $parrafo_anterior . '</p>' : '');
		
		echo '<div class="wpcdm_tool">
		<p><label for="wpcdm_currency_from">' . __('De', 'wpcdm') . ':</label><br />
		<select name="wpcdm_currency_from" id="wpcdm_currency_from">
		' . $from_currency_options . '
		</select></p>
		
		<p><label for="wpcdm_currency_to">' . __('A', 'wpcdm') . ':</label><br />
		<select name="wpcdm_currency_to" id="wpcdm_currency_to">
		' . $to_currency_options . '
		</select></p>
		
		<p><label for="wpcdm_currency_amount">' . __('Cantidad', 'wpcdm') . ':</label><br />
		<input type="text" size="4" name="wpcdm_currency_amount" id="wpcdm_currency_amount" /></p>
		
		<p><input type="button" value="' . __('Convertir', 'wpcdm') . '" name="wpcdm_convert" id="wpcdm_convert" />&nbsp;&nbsp;<img src="' . plugins_url('wp-convertidor-de-moneda/images/converting.gif') . '" alt="" id="wpcdm_converting" /></p>
		<p id="wpcdm_results"></p>';
		
		echo '</div></div>'; // contenido del widget final
		echo '</div>'; // cerrar widget
	}
	
	function retrieveCurrencyOptions($from_or_to = 'from_currencies', $default = '') {
		$wpcdmOptions = get_option('wpcdmOptions');
		echo $wpcdmOptions;
		$currencies = explode("\n", $wpcdmOptions[$from_or_to]);
		
		$optionsArray = array();
		foreach ($currencies as $currency) {
			$optionsArray[] = '<option value="' . trim($currency) . '"' . 
			(strcasecmp(trim($currency), trim($default)) == 0 ? ' selected="selected"' : '') . '>' . 
			trim($currency) . '</option>';
		}
		
		return implode("\n", $optionsArray);
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$new_instance = (array) $new_instance;

		$instance['titulo'] = (!empty($new_instance['titulo']) ? strip_tags($new_instance['titulo']) : '');
		$instance['parrafo_anterior'] = $new_instance['parrafo_anterior'];
		$instance['convertir_desde'] = trim(!empty($new_instance['convertir_desde']) ? strip_tags($new_instance['convertir_desde']) : '');
		$instance['a'] = trim(!empty($new_instance['a']) ? strip_tags($new_instance['a']) : '');
		
		return $instance;
	}

	function form($instance) {

		// Valores por defecto
		$defaults = array(
			'titulo' => __('Convertidor de moneda', 'wpcdm'),
			'parrafo_anterior' => __('Prueba nuestro conversor de divisas', 'wpcdm') . ':',
			'convertir_desde' => '',
			'a' => ''
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		echo '<p>
		<label for="' . 
		$this->get_field_id('titulo') . 
		'">' . __('Título', 'wpcdm') . ':</label>
		<input type="text" id="' . 
		$this->get_field_id('titulo') . 
		'" name="' . 
		$this->get_field_name('titulo') . 
		'" value="' . 
		$instance['titulo'] . '" />
		</p>';
	
		echo '<p>
		<label for="' . 
		$this->get_field_id('parrafo_anterior') . 
		'">' . __('Párrafo_anterior', 'wpcdm') . ':</label>
		<textarea id="' . 
		$this->get_field_id('parrafo_anterior') . 
		'" name="' . 
		$this->get_field_name('parrafo_anterior') . 
		'">' . $instance['parrafo_anterior'] . '</textarea>
		</p>';
		
		echo '<p>
		<label for="' . 
		$this->get_field_id('convertir_desde') . 
		'">' . __('Desde', 'wpcdm') . ':</label>
		<input type="text" id="' . 
		$this->get_field_id('convertir_desde') . 
		'" name="' . 
		$this->get_field_name('convertir_desde') . 
		'" value="' . 
		$instance['convertir_desde'] . '" placeholder="Ej: USD, ARS, etc" />
		</p>';
		
		echo '<p>
		<label for="' . 
		$this->get_field_id('a') . 
		'">' . __('A', 'wpcdm') . ':</label>
		<input type="text" id="' . 
		$this->get_field_id('a') . 
		'" name="' . 
		$this->get_field_name('a') . 
		'" value="' . 
		$instance['a'] . '" placeholder="Ej: USD, ARS, etc" />
		</p>';
			
	}
}

add_action('widgets_init', 'wpcdmRegisterWidget');
function wpcdmRegisterWidget() {
    register_widget('Widget_wpcdm');
}
?>
