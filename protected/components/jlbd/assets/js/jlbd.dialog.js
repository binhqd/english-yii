;(function($, scope){
	scope['dialog']	= {
		Libs : {
			confirm		: false, 		// Ok and Cancel buttons
			verify		: false, 	// Yes and No buttons
			input		: false, 		// Text input (can be true or string for default text)
			animate		: false, 	// Groovy animation (can true or number, default is 400)
			textOk		: 'OK', 	// Ok button default text
			textCancel	: 'Cancel', // Cancel button default text
			textYes		: 'Yes', 	// Yes button default text
			textNo		: 'No', 	// No button default text
			dialogClass	: null,
			top			: 92,
			
			// Class JLDialogButton
			JLDialogButton : function(dialog, label, options) {
				this.dialog = dialog;
				/*
				 * options.OK = callback
				 * options.Cancel = callback
				 * options.isDefault = true | false
				 */
				this.instance = $('<input type="button" value="' + label + '"/>');
				
				this.instance.focus(function() {
					$(this).addClass('focus');
				});
				
				this.instance.blur(function() {
					$(this).removeClass('focus');
				});
				
				if (typeof options == 'undefined') {
					options = {
						OK : function() {},
						Cancel : function() {},
						isDefault : false
					};
				}
				
				if (options.isDefault) {
					this.instance.addClass('defaultButton');
					this.defaults = true;
				} else {
					this.defaults = false;
				}
				
				if (typeof options.OK == "function") {
					var _dialog = this.dialog;
					this.instance.click(function(){
						_dialog.remove();
						return options.OK(true);
					});
					
					/*this.instance.keypress( function(e) {
						if(e.keyCode == 13) this.instance.trigger('click');
					});*/
				}
				
				if (typeof options.Cancel == "function") {
					var _dialog = this.dialog;
					this.instance.click(function(){
						_dialog.remove();
						return options.Cancel(false);
					});
					
					/*this.instance.keypress( function(e) {
						if(e.keyCode == 27) this.instance.trigger('click');
					});*/
				}
			},
			JLDialog : function(options) {
				this.container = $('<div class="appriseOuter"></div>');
				//this.titleHolder = $('<h4 class=wd-title></h4>');
				this.buttonPanel = $('<div class="wd-container-bt-popup"><div class="wd-bt-big-2"></div></div>');
				this.messageContainer = $('<div class="appriseInner"></div>');
				this.iconHolder = $('<div class="wd-popup-icon"></div>');
				this.messageHolder = $('<div class="message"></div>');
				
				this.defaultButton = null;
							
				var aHeight = $(document).height();
				var aWidth = $(document).width();
				
				$('body').append(this.container);
				
				//var pos = ($.browser.msie && parseInt($.browser.version) <= 6 ) ? 'absolute' : 'fixed';
				
				this.container.css({
					//position: pos,
					zIndex: 100,
					margin: 0
				});
					
				//this.container.append(this.titleHolder);
				this.container.append(this.buttonPanel).append(this.messageContainer);
				//this.messageContainer.append('<div class="wd-popup-icon-pub"></div>');
				//this.messageContainer.find('.wd-popup-icon-pub').append(this.iconHolder).append(this.messageHolder);
				//this.container.append(this.buttonPanel);
				//this.messageContainer.find('.wd-popup-icon-pub').append(this.messageHolder);
				this.messageContainer.append(this.messageHolder);

				var _self = this;
				this.remove = function() {
					if ($.browser.msie && parseInt($.browser.version) <= 6) {
						this.container.remove();
					} else {
						this.container.stop(true,true).animate({opacity:0 }, 350, function(){
							_self.container.remove();
						});
					}				
				}
				
				this.addButton = function(btn) {
					this.buttonPanel.find('.wd-bt-big-2').append(btn.instance);
					//this.buttonPanel.find('.wd-bt-big-2').addClass('update-wd-bt-big-2-center');
					if (btn.defaults) {
						this.defaultButton = btn;
					}
				}
				
				if(typeof options == "undefined" ||options == '' || options == null) {
					options = {};
				}
				if(typeof options.textOk == "undefined" ||options.textOk == '' || options.textOk == null) {
					options.textOk = jlbd.dialog.Libs.textOk;
				}
				if(typeof options.textCancel == "undefined" ||options.textCancel == '' || options.textCancel == null) {
					options.textCancel = jlbd.dialog.Libs.textCancel;
				}
				
				this.renderAlert = function(title, message, callback) {
					var _dialog = this;
					this.iconHolder.addClass('wd-alert');
						
					if (typeof callback != "function") {callback = function() {}}
					var btnOK = new jlbd.dialog.Libs.JLDialogButton(this, options.textOk, {
						isDefault : true,
						OK : callback
					});
					btnOK.instance.attr('id', 'popup_ok');
					this.addButton(btnOK);
					
					//this.titleHolder.html(title);
					this.messageHolder.html(message);
					
				}

				this.renderConfirm = function(title, message, callback) {
					var _dialog = this;
					this.iconHolder.addClass('wd-confirm');
					
					//this.titleHolder.html(title);
					this.messageHolder.html(message);
					
					if (typeof callback != "function") {
						if (typeof callback == "string" && callback.indexOf('function') >= 0) {
							eval('callback = ' + callback);
						} else {
							callback = function() {}
						}
					}
					var btnOK = new jlbd.dialog.Libs.JLDialogButton(this, options.textOk, {
						isDefault : true,
						OK : callback
					});
					btnOK.instance.attr('id', 'popup_ok');
					this.addButton(btnOK);
					
					var btnCancel = new jlbd.dialog.Libs.JLDialogButton(this, options.textCancel, {
						Cancel : callback
					});
					btnCancel.instance.attr('id', 'popup_cancel');
					this.addButton(btnCancel);
					
				}
				
				this.renderPrompt = function(title, message, defaultVal, callback) {
					var _dialog = this;
					this.iconHolder.addClass('wd-prompt');
					
					//this.titleHolder.html(title);
					this.messageHolder.html(message);
					
					var input = $('<div class="wd-popup-prompt"><input type="text" id="aTextBox" class="aTextbox txtInput" value="'+defaultVal+'"/></div>');
					this.messageHolder.after(input);
					
					if (typeof callback != "function") {callback = function() {}}
					var btnOK = new jlbd.dialog.Libs.JLDialogButton(this, options.textOk, {
						isDefault : true
					});
					btnOK.instance.attr('id', 'popup_ok');
					this.addButton(btnOK);
					
					this.messageContainer.find('.txtInput')
						.keydown(function(){
							return this; //doThis(this,event);
						})
						.focus(function(){
							if($(this).val()==defaultVal)
								$(this).attr('value','');
						})
						.blur(function(){
							if($(this).val()=='') 
								$(this).attr('value',defaultVal);
						})
						.keypress( function(e) {
							if( e.keyCode == 13 || e.keyCode == 27 ) $("#popup_ok").trigger('click');
					});
					
					btnOK.instance.click(function(){
						var	_callData	= _dialog.container.find("input.txtInput").val();
						_dialog.remove();
						if(callback) return callback(true, _callData);
					});
					
					var btnCancel = new jlbd.dialog.Libs.JLDialogButton(this, options.textCancel, {
						Cancel : callback
					});
					btnCancel.instance.attr('id', 'popup_cancel');
					this.addButton(btnCancel);
				}
				this._top = jlbd.dialog.Libs.top;
				
				this.show = function() {
					//var top = ($.browser.msie && parseInt($.browser.version) <= 6 ) ? this._top + $(window).scrollTop() : this._top;
					//var pos = ($.browser.msie && parseInt($.browser.version) <= 6 ) ? 'absolute' : 'fixed';
					
					/*_self.container.offset({
						top : $(window).height()/2 - _self.container.innerHeight()/2 + $(window).scrollTop() - 100,
						left : ($(window).width()/2	- _self.container.innerWidth()/2) + $(window).scrollLeft()
					});*/
					var _dialog = this;
					_self.container.fadeIn(200, function() {
						if (typeof _dialog.defaultButton != "undefined") {
							_dialog.defaultButton.instance[0].focus();
						}
					});
				}
			}
		},
		alert:	function(title, message, callback, options)	{
			if(title=='' || title==null)	title='Alert!';
			var dialog = new jlbd.dialog.Libs.JLDialog(options);
			dialog.renderAlert(title, message, callback);
			dialog.show();
		},
		confirm:	function(title, message, callback, options){
			if(title=='' || title==null)	title='Confirm!';
			var dialog = new jlbd.dialog.Libs.JLDialog(options);
			dialog.renderConfirm(title, message, callback);
			dialog.show();
		},
		prompt : function(title, message, defaultVal, callback, options, loadFunction){
			if(title=='' || title==null)	title='Prompt!';
			var dialog = new jlbd.dialog.Libs.JLDialog(options);
			dialog.renderPrompt(title, message, defaultVal, callback);
			dialog.show();
			if(typeof loadFunction != "undefined" && loadFunction != '' && loadFunction != null) {
				window.onload = loadFunction;
			}
		},
		
		message : function(options, isModal) {
			var box = new jlbd.messagebox.Libs.JLMessageBox(options);
			
			if (typeof isModal != "undefined" && isModal) {
				box.show(true);
			} else {
				box.show();
			}
		},
		modalMessage : function(options) {
			var box = new jlbd.messagebox.Libs.JLMessageBox(options);
			box.show(true);
		},
		notify : function(option) {
			var notify = new jlbd.notifyCation.Libs.JLNotifyCation(option);
			this.close = function() {
				notify.close();
			}
			// thinhpq comment code hack for myzone (16/08/2013)
			// notify.show();
		},
		deNotify : function() {
			var notify = new jlbd.notifyCation.Libs.Destroy();
			notify.close();
		},
		showTrayNotify : function(options, callback) {
			options.sticky = false;
			options.after_open = function(obj) {
				callback(this, obj);
			}
			$.extend($.gritter.options, {
				position: 'bottom-right', // possibilities: bottom-left, bottom-right, top-left, top-right
				time: 4000 // hang on the screen for...
			});
			$.gritter.add(options);
			
			
		}
	}
})(jQuery, jlbd);