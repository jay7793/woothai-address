(function( $ ) {
	'use strict';

	$(function() {

		equalHeight();

		$(document.body).on('updated_checkout', function() {
	    equalHeight();
	  })

	  function equalHeight() {
	  	$('.form-row-equal-height').matchHeight();
	  }
	});

})( jQuery );
