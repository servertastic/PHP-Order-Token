jQuery(document).ready(function($) {
	if ($('html').has('#same_as_admin')) {
		$(document).on('click', '#same_as_admin', function(e) {
			if ($('.tech_contact input,.tech_contact select').is('[readonly]')) {
				$('.tech_contact input,.tech_contact select').removeAttr('readonly');
				// destroy the change event handler
				$('.admin_contact input,.admin_contact select').off('change');
			} else {
				$('.tech_contact input,.tech_contact select').attr('readonly', '');
				$('.admin_contact input,.admin_contact select').each(function(index, el) {
					if ($(el).is('input')) {
						$swap_input = $(el).attr('name').replace('admin_','tech_');
						// push value to the equivilent
						$('.tech_contact input[name='+$swap_input+']').val($(el).val());
					} else {
						$swap_input = $(el).attr('name').replace('admin_','tech_');
						$('.tech_contact select[name='+$swap_input+']').val($(el).val());
					}
				});
				$('.admin_contact').find('input,select').change(function(e) {
					if ($(this).is('input')) {
						$swap_input = $(this).attr('name').replace('admin_','tech_');
						$('.tech_contact input[name='+$swap_input+']').val($(this).val());
					} else {
						$swap_input = $(this).attr('name').replace('admin_','tech_');
						$('.tech_contact select[name='+$swap_input+']').val($(this).val());
					}

				});
			}
		});
	}

	// use js to add * to all required fields as we dont know if they have until page loaded.
	$('input[data-validetta^=required],select[data-validetta^=required]').siblings('label').append(' *');

	$('input:radio[name=dv_auth_method]').change(function(e) {
		if ($(this).val() == 'EMAIL') {
			$('.panel.approver_email_address').slideDown(400);
			$('input[name=approver_email_address]').removeAttr('disabled');
		} else {
			$('.panel.approver_email_address').slideUp(400);
			$('input[name=approver_email_address]').attr('disabled','');
		}
	});

	if ($('html').has('.order_organisation')) {
		if ($('.order_organisation').has('input:text').length == 0) {
			
		}
	}

	if ($('html').has('form#validetta').length > 0) {
		$('form#validetta').validetta({
			display : 'inline',
			errorTemplateClass : 'validetta-inline',
 			errorClass : 'validetta-error',
		});
	}
});