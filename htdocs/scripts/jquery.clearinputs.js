/* 
 * jQuery plugin to style form inputs by overlapping them over their labels
 *  to allow a fade-out like effect
 *  
 *  Usage: 
 *  $('input').clearInputs({
 *  	css: {
 *  		backgroundColor: '#FFF'
 *  	},
 *  });
 */
(function($) {
	$.fn.clearInputs = function (options) {
		if (arguments.length >= 1) {
			options = jQuery.extend({}, options);
		} else {
			options = {};
		}
		
		var visible = 1;
		var active = 0.78;
		var inactive = 0.38;
		
		var inputCss = {
			fontWeight: 'bold',
			outline: 0,
			display: 'inline',
			backgroundColor: '#FFFFFF',
			color: '#383838',
			width: '100%',
			height: 22,
			float:'right',
			marginTop: -22,
			paddingLeft: 4,
			paddingTop: 3,
			opacity: inactive,
			border: 'none',
			position: 'relative',
			zIndex: 200
		};
		
		if (typeof (options['css']) != 'undefined') {
			for (var attrname in options['css']) { inputCss[attrname] = options['css'][attrname]; }
		}
		var labelCss = {
			display: 'block',
			clear: 'both',
			paddingLeft: 4,
			paddingTop: 6,
			backgroundColor: inputCss['backgroundColor'],
			borderColor: '#CCCCCC',
			borderWidth: 1,
			borderStyle: 'solid',
			position: 'relative',
			width: '98%',
			float:'left',
			height: inputCss['height']-2,
			marginTop:10,
			color: inputCss['color'],
			zIndex: 100
		};
		if (typeof (options['labelCss']) != 'undefined') {
			for (var attrname in options['labelCss']) { labelCss[attrname] = options['labelCss'][attrname]; }
		}
		return this.each(function() {
			var that = $(this);
			var label = that.parent();
			
			if (label.is('label')) {
				that.css (inputCss).keydown(function (e) {
					if (that.css('opacity') != visible) { 
						that.css ('opacity', visible);
					}
				}).focus(function (e) {
					if (that.val() == '') {
						that.css ('opacity', active);
					}
				}).change(function (e) {
					if (that.css('opacity') != visible) { 
						that.css ('opacity', visible);
					}
				}).focusout(function (e) {
					if (that.val() == '') {
						that.css ('opacity', inactive);
					}
				}).each (function (i, c) {
					var that = $(this);
					if (that.val() != '') {
						that.css ('opacity', visible);
					}
				});
				
				label.css (labelCss);
			}
		});
	}
})(jQuery);
