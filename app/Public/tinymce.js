(function () {
	/* Register the buttons */
	tinymce.create('tinymce.plugins.arvcbmce', {

		/**
		 * 
		 */
		onclick: function(ev){
			ArvCB.$refs['arv-modal'].show();
			// selection = tinyMCE.activeEditor.selection.getContent();
			// tinyMCE.activeEditor.selection.setContent('[pay productId="ID"]' + selection + '[/pay]');
		},

		/**
		 * 
		 */
		init: function (ed, url) {

			ed.addButton('arvcbPayment', {
				title: 'Insert shortcode',
				onclick: this.onclick.bind(this),
				"text": "CB"
			});

		},

		createControl: function (n, cm) {
			return null;
		},

	});
	/* Start the buttons */
	tinymce.PluginManager.add('my_button_script', tinymce.plugins.arvcbmce);
})();
