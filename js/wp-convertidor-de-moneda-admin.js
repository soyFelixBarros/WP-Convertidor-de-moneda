/*******************************************************************************
** File: wp-convertidor-de-moneda.js
** Description: Funciones de ayuda para el plugin WP Convertidor de moneda.
** @since 1.0.0
*******************************************************************************/

jQuery(document).ready(function() {
		
	jQuery('#wpcdm_restore_default_from_currencies').click(function() {
		var defaultCurrencies = jQuery('#wpcdm_default_currencies').html();
		jQuery('#wpcdm_from_currencies').val(defaultCurrencies);
	});
	
	jQuery('#wpcdm_restore_default_to_currencies').click(function() {
		var defaultCurrencies = jQuery('#wpcdm_default_currencies').html();
		jQuery('#wpcdm_to_currencies').val(defaultCurrencies);
	});
	
	jQuery('#wpcdm_copy_to_currencies').click(function() {
		var toCurrencies = jQuery('#wpcdm_to_currencies').val();
		jQuery('#wpcdm_from_currencies').val(toCurrencies);
	});
	
	jQuery('#wpcdm_copy_from_currencies').click(function() {
		var fromCurrencies = jQuery('#wpcdm_from_currencies').val();
		jQuery('#wpcdm_to_currencies').val(fromCurrencies);
	});
	
});
