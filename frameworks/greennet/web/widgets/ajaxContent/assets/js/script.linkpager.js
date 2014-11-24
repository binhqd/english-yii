/**
 * This JS is used to render link pager
 *
 * @author huytbt <huytbt@gmail.com>
 * @version 1.0
 * @see HLinkPager
 */
;(function($, scope){
	scope['HLinkPager'] = {
		/**
		 * This widget is used to render link pager
		 * @param HLinkPager $hLinkPager
		 * @param array $options
		 */
		Widget : function(hLinkPager, options) {
			var $this=this;
			this.hLinkPager = null;
			this.header = '',
			this.footer = '',
			this.maxButtonCount = 10;
			this.htmlOptions = [];
			this['class'] = 'css-paging';
			this.options = null;
			this.itemTemplate = '<li class="{class}"><a href="{url}">{label}</a></li>';

			/**
			 * Initializes the pager by setting some default property values.
			 */
			$this.init = function()
			{
				$this.hLinkPager = hLinkPager;
				$this.maxButtonCount = $this.hLinkPager.options.maxButtonCount;
				$this.options = options;
				if(!$this.htmlOptions['class'])
					$this.htmlOptions['class']='yiiPager';
				
				if ($this.options) $.each($this.options, function(key, val) {
					$this[key] = val;
				});
			};

			/**
			 * This method is used to render widget to html
			 */
			$this.render = function()
			{
				return $this.run();
			}

			/**
			 * Executes the widget.
			 * This overrides the parent implementation by displaying the generated page buttons.
			 */
			$this.run = function()
			{
				$this.init();
				var $buttons=$this.createPageButtons();
				if(!$buttons.length)
					return;
				var echo = '';
				echo += '<div class="'+$this['class']+'">';
				echo += $this.header;
				echo += '<ul class="'+$this.htmlOptions['class']+'">' + $buttons.join('\n') + '</ul>';
				echo += $this.footer;
				echo += '</div>';
				return echo;
			}

			/**
			 * Creates the page buttons.
			 * @return array a list of page buttons (in HTML code).
			 */
			$this.createPageButtons = function()
			{
				var $buttons=[];

				if (typeof $this.hLinkPager.buttons !== 'undefined')
				{
					var maxButtonCount = $this.maxButtonCount;
					$.each($this.hLinkPager.buttons, function(index, button){
						if ((button['class']+' ').indexOf($this.hLinkPager.options.internalPageCssClass+' ') >= 0 && (--maxButtonCount) <= 0)
							return true;
						$buttons[index] = $this.itemTemplate
							.replace('{class}', button['class'])
							.replace('{url}', button.url)
							.replace('{label}', button.label);
					});
				}

				return $buttons;
			};

			return $this;
		}
	}
})(jQuery, jQuery);