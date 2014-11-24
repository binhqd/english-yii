/**
*	jQuery.jNotify
*	jQuery Notification Engine
*		
*   Copyright (c) 2010 Fabio Franzini
*
*	Permission is hereby granted, free of charge, to any person obtaining a copy
*	of this software and associated documentation files (the "Software"), to deal
*	in the Software without restriction, including without limitation the rights
*	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
*	copies of the Software, and to permit persons to whom the Software is
*	furnished to do so, subject to the following conditions:
*
*	The above copyright notify and this permission notify shall be included in
*	all copies or substantial portions of the Software.
*
*	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
*	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
*	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
*	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
*	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
*	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
*	THE SOFTWARE.
*	
*	@author 	Fabio Franzini
* 	@copyright  2010 www.fabiofranzini.com
*	@version    1
**/

(function(jQuery) {
	jQuery.fn.jnotifyInizialize = function(options) {
		var element = this;

		var defaults = {
			oneAtTime: false,
			appendType: 'append'
		};

		var options = jQuery.extend({}, defaults, options);

		this.addClass('notify-wrapper');

		if (options.oneAtTime)
			this.addClass('notify-wrapper-oneattime');

		if (options.appendType == 'prepend' && options.oneAtTime == false)
			this.addClass('notify-wrapper-prepend');

		return this;
	};
	jQuery.fn.jnotifyAddMessage = function(options) {

		var notifyWrapper = this;

		if (notifyWrapper.hasClass('notify-wrapper')) {

			var defaults = {
				text: '',
				type: 'message',
				showIcon: false,
				permanent: false,
				disappearTime: 3000,
				callback: false,
				showClose: false
			};

			var options = jQuery.extend({}, defaults, options);
			var styleClass;
			var iconClass;

			switch (options.type) {
				case 'message':
					{
						styleClass = 'jnotify-state-highlight';
						iconClass = 'ui-icon-info';
					}
					break;
				case 'error':
					{
						styleClass = 'jnotify-state-error';
						iconClass = 'ui-icon-alert';
					}
					break;
				default:
					{
						styleClass = 'jnotify-state-highlight';
						iconClass = 'ui-icon-info';
					}
					break;
			}

			if (notifyWrapper.hasClass('notify-wrapper-oneattime')) {
				this.children().remove();
			}

			var notifyItemWrapper = jQuery('<div class="jnotify-item-wrapper"></div>');
			var notifyItem = jQuery('<div class="jnotify-corner-all jnotify-item"></div>')
									.addClass(styleClass);

			if (notifyWrapper.hasClass('notify-wrapper-prepend'))
				notifyItem.prependTo(notifyWrapper);
			else
				notifyItem.appendTo(notifyWrapper);

			notifyItem.wrap(notifyItemWrapper);

			if (options.showIcon)
				jQuery('<span class="ui-icon" style="float:left; margin-right: .3em;" />')
									.addClass(iconClass)
									.appendTo(notifyItem);

			jQuery('<div class="jnotify-item-content"></div>').html(options.text).appendTo(notifyItem);
			
			if (options.showClose)
				jQuery('<div class="jnotify-item-close"><span class="ui-icon ui-icon-circle-close"/></div>')
									.prependTo(notifyItem)
									.click(function() { notifyItem.removeItem(); });

			// IEsucks
			if (navigator.userAgent.match(/MSIE (\d+\.\d+);/)) {
				//notifyWrapper.css({ top: document.documentElement.scrollTop });
				//http://groups.google.com/group/jquery-dev/browse_thread/thread/ba38e6474e3e9a41
				notifyWrapper.removeClass('IEsucks');
			}
			// ------

			if (!options.permanent) {
				notifyItem.timeout = setTimeout(function() { notifyItem.removeItem(); }, options.disappearTime);
				/**
				 * HuyTBT fix bug: khi hover mouse vao notification thi notification se ko bi mat
				 */
				notifyItem.hover(function(){
					clearTimeout(notifyItem.timeout);
				},function(){
					notifyItem.timeout = setTimeout(function() { notifyItem.removeItem(); }, options.disappearTime);
				});
			}
			
			// HuyTBT them chuc nang callback
			if (options.callback !== false) options.callback();

			notifyItem.removeItem = function() {
				var obj = this;
				var parent = obj.parent();
				setTimeout(function(){if (obj) obj.remove();}, 600);
				setTimeout(function(){if (parent) parent.remove();}, 900);
				obj.animate({ opacity: '0' }, 600, function() {
					parent.animate({ height: '0px' }, 300, function() {
						  parent.remove();
						  // IEsucks
						  if (navigator.userAgent.match(/MSIE (\d+\.\d+);/)) {
							  //http://groups.google.com/group/jquery-dev/browse_thread/thread/ba38e6474e3e9a41
							  parent.parent().removeClass('IEsucks');
						  }
						  // -------
					});
					obj.remove();
				});
			}
			
			var clone = notifyItem.clone();
			clone.css({
				'position': 'fixed',
				'top': '-999px',
				'left': '-999px'
			});
			clone.find('.jnotify-item-content').css('float', 'right');
			jQuery('body').append(clone);
			//var width = clone.outerWidth() + 10;
			var width = clone.find('.jnotify-item-content').width() + clone.find('.jnotify-item-close').width() + (clone.find('.jnotify-item-close').width() ? 50 : 25); // Fix IE 7
			clone.remove();
			notifyItem.parent().css('width', width + "px");
			notifyItem.parent().css('position', 'relative');
			notifyItem.parent().css('left', (($(window).width() - width) / 2) + $(window).scrollLeft() + "px");
		}
		
		return notifyItem;
	};
	jQuery.fn.jnotifyCenter = function () {
		/*this.parent().css('position', 'relative');
		this.parent().css("left", (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft() + "px");*/
		return this;
	};
})(jQuery);