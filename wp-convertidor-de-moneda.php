<?php
/*******************************************************************************
** Plugin Name: WP Convertidor de moneda
**
** Description: Un widget de conversión de moneda ajax para que sus visitantes puedan convertir las cantidades de moneda sobre la marcha (con tecnología de Google Finance).
**
** Author: Felix Barros
** Author URI: https://felixbarros.blog
** Plugin URI: https://github.com/soyFelixBarros/WP-Convertidor-de-moneda
** Version: 1.0.0
** Text Domain: wpcdm
*******************************************************************************/

require_once('wpcdmWidget.php'); // incluir el widget
require_once('wpcdmShortcode.php'); // incluir el código corto

/*******************************************************************************
** wpcdmMenu()
**
** Configurar el menú de opciones de plugin.
**
** @since 1.0.0
*******************************************************************************/
function wpcdmMenu() {
	if (is_admin()) {
		register_setting('wp-convertidor-de-moneda', 'wpcdmOptions');
		add_options_page('WP Convertidor de moneda - Ajustes', 'WP Convertidor de moneda', 'administrator', __FILE__, 'wpcdmOptions', '');
	}
}

/*******************************************************************************
** wpcdmOptions()
**
** Página de opciones de plugin
**
** @since 1.0.0
*******************************************************************************/
function wpcdmOptions() {
	require_once('wpcdmSymbols.php');
	
	if (!current_user_can('manage_options'))  {
		wp_die( __('No tiene permisos suficientes para acceder a esta página.') );
	}
	
	echo '<div class="wrap">' . screen_icon() . '<h2>WP Convertidor de moneda</h2>';
	
	$wpcdmOptions = get_option('wpcdmOptions');
	
	if (!isset($wpcdmOptions['from_currencies']) || empty($wpcdmOptions['from_currencies'])) {
		$wpcdmOptions['from_currencies'] = implode("\n", array_keys($currency));
	}
	
	if (!isset($wpcdmOptions['to_currencies']) || empty($wpcdmOptions['to_currencies'])) {
		$wpcdmOptions['to_currencies'] = implode("\n", array_keys($currency));
	}
	
	echo '<div id="wpcdm_default_currencies" style="display: none;">' . implode("\n", array_keys($currency)) . '</div>';
	
	echo '<form method="post" action="options.php">';
	
	wp_nonce_field('update-options');
	settings_fields( 'wp-currency-converter' );
	
	echo '<table class="form-table" style="width: 430px">';
	
	echo '<tr valign="top">
	<th scope="col" style="white-space: nowrap;">De la lista de divisas:</th>
	<th scope="col" style="white-space: nowrap;">A la lista de divisas:</th>
	</tr>
	
	<tr valign="top">
	
	<td>
		<textarea id="wpcdm_from_currencies" name="wpcdmOptions[from_currencies]" rows="20" cols="10">' . $wpcdmOptions['from_currencies'] . '</textarea>
		<p><span class="description">' . __('Un código de moneda por línea', 'wpcdm') . '<br /><a href="#" id="wpcdm_restore_default_from_currencies">' . __('Haga clic para restaurar los valores predeterminados', 'wpcdm') . '</a><br /><a href="#" id="wpcdm_copy_to_currencies">' . __('Copiar "a" monedas', 'wpcdm') . '</a></span></p>
	</td>
	
	<td>
		<textarea id="wpcdm_to_currencies" name="wpcdmOptions[to_currencies]" rows="20" cols="10">' . $wpcdmOptions['to_currencies'] . '</textarea>
		<p><span class="description">' . __('Un código de moneda por línea', 'wpcdm') . '<br /><a href="#" id="wpcdm_restore_default_to_currencies">' . __('Haga clic para restaurar los valores predeterminados', 'wpcdm') . '</a><br /><a href="#" id="wpcdm_copy_from_currencies">' . __('Copiar "De" monedas', 'wpcdm') . '</a></span></p>
	</td>
	
	</tr>
	
	<tr valign="top">
	<td colspan="2">
		<input type="submit" class="button-primary" value="Guardar ajustes" />
	</td>
	</tr>';
	
	echo '</form></div>';
}

/*******************************************************************************
** wpcdmAjaxConvert()
**
** Convertir las cantidades dadas
**
** @since 1.0.0
*******************************************************************************/
function wpcdmAjaxConvert() {
	require_once('wpcdmSymbols.php'); // simbolos de moneda para conversiones
	
	$amount = $_POST['wpcdm_currency_amount'];
	$currency_from = $_POST['wpcdm_currency_from'];
	$currency_to = $_POST['wpcdm_currency_to'];
	
	if(!strstr($amount, '.')){
		$amount = $amount . '.00';
	}
	
	// URL de la API
	$url = 'https://free.currencyconverterapi.com/api/v6/convert?q=' . urlencode($currency_from) . '_' . urlencode($currency_to) .'&compact=ultra&apiKey=39ddcdeb35dd71a6843e&_=' . urlencode($amount);
	
	if (function_exists('curl_init')) { // cURL está instalado en el servidor, así que use esto preferiblemente
		$ch = curl_init();
		
		$ua = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
		
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/html"));
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	
		$responseString = curl_exec($ch);
		
		curl_close($ch);
	} else {
		// intente usar file_get_contents, aunque esto causa algunos problemas en algunos servidores
		$responseString = file_get_contents($url, true);
	}

	// Parseamos el valor obtenido de la API
	$value = explode(":", $responseString);
	$result = (int) $amount * rtrim($value[1], '}');

	if (isset($result)) {
		$output = sprintf("%.2f", $result);
		echo '<p>' . __('Cantidad', 'wpcdm') . ' (' . $currency_to . '): ' . $currency[$currency_to] . $output . '</p>';
	} else {
		echo '<p class="wpcdm_error">' . __('Error: La conversión de moneda no está disponible temporalmente. Inténtalo de nuevo.', 'wpcdm') . '</p>';
	}
	
	exit();
}

/*******************************************************************************
** wpcdmOnActivation()
**
** Activar el plugin y cargar las opciones por defecto.
**
** @since 1.0.0
*******************************************************************************/
function wpcdmOnActivation() {
	require_once('wpcdmSymbols.php'); // simbolos de moneda para conversiones
	$wpcdmOptions = get_option('wpcdmOptions');
	
	if (!isset($wpcdmOptions['from_currencies']) || empty($wpcdmOptions['from_currencies']))
		$wpcdmOptions['from_currencies'] = implode("\n", array_keys($currency));
	
	if (!isset($wpcdmOptions['to_currencies']) || empty($wpcdmOptions['to_currencies']))
		$wpcdmOptions['to_currencies'] = implode("\n", array_keys($currency));
		
	update_option('wpcdmOptions', $wpcdmOptions);
		
}

/*******************************************************************************
** wpcdmInit()
**
** Inicializar plugin
**
** @since 1.0.0
*******************************************************************************/
function wpcdmInit() {
	//add_action('admin_menu', 'wpcdmMenu');
	
	if (!is_admin()) {
		wp_enqueue_script('wp-convertidor-de-moneda', plugins_url('wp-convertidor-de-moneda/js/wp-convertidor-de-moneda.js'), array('jquery'));
		wp_enqueue_style('wp-convertidor-de-moneda', plugins_url('wp-convertidor-de-moneda/css/wp-convertidor-de-moneda.css'));
	}
}

/*******************************************************************************
** wpcdmAdminInit()
**
** Inicializar el lado del administrador
**
** @since 1.0.0
*******************************************************************************/
function wpcdmAdminInit() {
	wp_enqueue_script('wp-convertidor-de-moneda-admin', plugins_url('wp-convertidor-de-moneda/js/wp-convertidor-de-moneda-admin.js'), array('jquery'));
}

/*******************************************************************************
** wpcdmHead()
**
** Inicializar encabezado
**
** @since 1.0.0
*******************************************************************************/
function wpcdmHead() {
	if (!is_admin()) {
		echo '<script type="text/javascript">var wpcdmAjaxLink="' . admin_url('admin-ajax.php') . '";</script>';
	}
}

/*******************************************************************************
** wpcdmPluginLoaded()
**
** Plugin cargado, hacer cosas como cargar el dominio de texto.
**
** @since 1.0.0
*******************************************************************************/
function wpcdmPluginLoaded() {
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain('wpcdm', false, $plugin_dir);
}

add_action('plugins_loaded', 'wpcdmPluginLoaded');
register_activation_hook(__FILE__, 'wpcdmOnActivation');
add_action('init', 'wpcdmInit');
add_action('admin_init', 'wpcdmAdminInit');
add_action('wp_head', 'wpcdmHead');
add_action('wp_ajax_wpcdmAjaxConvert', 'wpcdmAjaxConvert');
add_action('wp_ajax_nopriv_wpcdmAjaxConvert', 'wpcdmAjaxConvert');
