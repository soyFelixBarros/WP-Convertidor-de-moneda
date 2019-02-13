/*******************************************************************************
** File: wp-convertidor-de-moneda.js
** Description: Funciones de ayuda para el plugin WP Convertidor de moneda.
** @since 1.0.0
*******************************************************************************/

jQuery(document).ready(function() {
	jQuery('#wpcdm_converting').hide();
	
	jQuery('#wpcdm_convert').click(function() {
		var wpcdm_currency_amount = jQuery('#wpcdm_currency_amount').val();
		var wpcdm_currency_from = jQuery('#wpcdm_currency_from').val();
		var wpcdm_currency_to = jQuery('#wpcdm_currency_to').val();
		
		jQuery('#wpcdm_converting').show();
		
		jQuery.post(
			wpcdmAjaxLink,
			{
				action: 'wpcdmAjaxConvert',
				wpcdm_currency_amount: wpcdm_currency_amount,
				wpcdm_currency_from: wpcdm_currency_from,
				wpcdm_currency_to: wpcdm_currency_to
			},
			function(results) {
				jQuery('#wpcdm_results').html(results);
				jQuery('#wpcdm_results').slideDown(400);
				jQuery('#wpcdm_converting').delay(400).hide();
			}
		);
	});
	
});
