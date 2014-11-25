(function($) {


	$('.form-usuario').on('submit',function() {

		if ($(this).find('.form-usuario-email') == "") {
			alert('E-mail não informado!');
			return false;
		}
		if ($(this).find('.form-usuario-nome') == "") {
			alert('Nome não informado!');
			return false;
		}
		if ($(this).find('.form-usuario-senha').val() != $(this).find('.form-usuario-nova-senha').val()) {
			alert('Senhas não coincidem!');
			return false;
		}

	})

})(jQuery)