/**
 * This JS is used to render ajax content
 *
 * @author huytbt <huytbt@gmail.com>
 * @version 1.0
 */
;(function($, scope){
	scope['HAjaxContent'] = {
		Widget : function(divID, options, scriptFirst, scriptLoadding, scriptComplete, scriptSuccess, scriptLoadMoreSuccess) {
			var defaults = {};
			var $this = this;
			$this.divID = divID;
			$this.options = jQuery.extend({}, defaults, options);
			$this.instance = $('#' + $this.divID);
			$this.waitingPage = {};
			$this.loadMoreCurrentPage = 0; // supportLoadMore
			$this.loadMoreLoading = false; // supportLoadMore

			/**
			 * This method is uses to initial widget
			 */
			$this.init = function(){
				if (typeof scriptFirst != 'undefined' && scriptFirst !== null) scriptFirst($this);
				if ($this.options.vssDivID != '') {
					$this.initVSS($this.options.vssDivID);
				}

				scope.HAjaxContent.collection.add($this);
				if ($this.options.loadFirst === true) {
					if ($this.options.firstContent)
						$this.loadFirstContent($this.options.firstContent);
					else
						$this.loadAjax($this.options.url, $this.options.enableCache, true);
				} else {
					// $this.initAjaxPaginationLinks($('.js-h-ajax-content'));
				}

				if ($this.options.supportLoadMore != false) {
					var loadingElementClass = $this.options.supportLoadMore.loadingElementClass;
					if ($this.options.supportLoadMore.linkPager.pages.pageCount <= 1) {
						$(document).ready(function() {
							$(loadingElementClass).css('display', 'none');
						});
					} else {
						$(document).ready(function() {
							$(window).scroll(function() {
								if ($(loadingElementClass).css('display') != 'none' && $(loadingElementClass).offset().top < $(window).scrollTop() + $(window).height()) {
									$this.loadMore(function($this, response, newpage) {
										if (typeof scriptLoadMoreSuccess != 'undefined' && scriptLoadMoreSuccess !== null)
											scriptLoadMoreSuccess($this, response, newpage);
									});
								}
							});
						});
					}
				}
			};

			/**
			 * This method is uses to run widget
			 */
			$this.run = function(){
				$this.loadAjax($this.options.url, $this.options.enableCache, true);
			};

			/**
			 * This method is uses to load ajax
			 */
			$this.loadFirstContent = function(response) {
				$this.instance.find('.js-h-ajax-content-item').remove();
				var href = $this.options.url;
				$this.instance.append('<div class="js-h-ajax-content-item" ref="'+href+'"></div>');
				var newpage = $('#'+$this.divID+' .js-h-ajax-content-item[ref="'+href+'"]');
				if (typeof scriptSuccess != 'undefined' && scriptSuccess !== null) scriptSuccess($this, response, newpage);
				newpage.css('display', 'block');
				$this.initAjaxPaginationLinks(newpage);
			}

			/**
			 * This method is used to load more (supportLoadMore)
			 */
			$this.loadMore = function(callback) {
				if ($this.loadMoreLoading)
					return;
				if ($this.loadMoreCurrentPage + 2 > $this.options.supportLoadMore.linkPager.pages.pageCount) {
					var loadingElementClass = $this.options.supportLoadMore.loadingElementClass;
					$(loadingElementClass).css('display', 'none');
					return;
				}
				$this.loadMoreLoading = true;
				$this.loadMoreCurrentPage++;
				var url = $this.options.supportLoadMore.linkPager.buttons[$this.loadMoreCurrentPage+2].url;
				$this.loadAjax(url, null, null, callback);
			}

			/**
			 * This method is uses to load ajax
			 */
			$this.loadAjax = function(href, nocache, isfirst, callback) {
				if (typeof $this.waitingPage[href] != 'undefined' && $this.waitingPage[href] !== null && $this.waitingPage[href] == true) return;
				if (typeof isfirst == 'undefined' || isfirst === null) isfirst = false;
				$.ajax({
					url: href,
					type: 'POST',
					dataType: "json",
					beforeSend: function() {
						$this.waitingPage[href] = true;
						if (typeof scriptLoadding != 'undefined' && scriptLoadding !== null) scriptLoadding($this);
					},
					complete: function() {
						$this.options.url = href;
						$this.waitingPage[href] = false;
						if (typeof scriptComplete != 'undefined' && scriptComplete !== null) scriptComplete($this);
					},
					success: function(response) {
						if (($this.options.enableCache == false) || (typeof nocache != 'undefined' && nocache !== null && nocache == true) || isfirst) $this.instance.find('.js-h-ajax-content-item').remove();
						if ($this.options.supportLoadMore == false)
							if ($this.instance.find('.js-h-ajax-content-item').length >= $this.options.cachePages) $this.instance.find('.js-h-ajax-content-item').first().remove();
						$this.instance.append('<div class="js-h-ajax-content-item" ref="'+href+'"></div>');
						var newpage = $('#'+$this.divID+' .js-h-ajax-content-item[ref="'+href+'"]');
						if (typeof scriptSuccess != 'undefined' && scriptSuccess !== null) scriptSuccess($this, response, newpage);
						if ($this.options.supportLoadMore == false) {
							$this.instance.find('.js-h-ajax-content-item').css('display', 'none');
							newpage.css('display', 'block');
						} else {
							$this.loadMoreLoading = false;
							newpage.css('display', 'none').fadeIn();
							if ($this.loadMoreCurrentPage + 2 > $this.options.supportLoadMore.linkPager.pages.pageCount) {
								var loadingElementClass = $this.options.supportLoadMore.loadingElementClass;
								$(loadingElementClass).css('display', 'none');
							}
						}
						$this.initAjaxPaginationLinks(newpage);
						// Call back
						if (typeof callback != 'undefined' && callback !== null) callback($this, response, newpage);
						// Scroll to top
						if ($this.options.supportLoadMore == false)
							if (!isfirst && $this.options.syncURL == true) jQuery('html,body').scrollTop(0);
					}
				});
				if (!isfirst && $this.options.syncURL == true && typeof window.history.pushState != 'undefined' && window.history.pushState !== null) window.history.pushState('string', 'Title', href);
			};

			/**
			 * This method is uses to init ajax pagination links
			 */
			$this.initAjaxPaginationLinks = function(newpage) {
				$.each($this.options.ajaxClassLinks, function(index, ajaxClassLink) {
					newpage.find(ajaxClassLink).click(function() {
						var href = $(this).attr('href');
						if ($this.instance.find('.js-h-ajax-content-item[ref="'+href+'"]').length) {
							$this.options.url = href;
							$this.instance.find('.js-h-ajax-content-item').css('display', 'none');
							var newpage = $this.instance.find('.js-h-ajax-content-item[ref="'+href+'"]');
							newpage.css('display', 'block');
							if ($this.options.syncURL == true && typeof window.history.pushState != 'undefined' && window.history.pushState !== null) window.history.pushState('string', 'Title', href);
							if ($this.options.syncURL == true) jQuery('html,body').scrollTop(0);
						} else {
							$this.loadAjax(href);
						}

						return false;
					});
				});
			};

			/**
			 * This method is uses to initial View-Sort-Search
			 */
			$this.initVSS = function(vssDivID) {
				var vssDiv = $('#' + vssDivID);
				vssDiv.find('.js-h-vss-viewmode').change(function(event){ // View mode
					var href = scope.HLinkPaper.createURL('viewmode', $(this).val(), $this.options.url);
					$this.loadAjax(href, true);
					return false;
				});
				vssDiv.find('.js-h-vss-sortby').change(function(event){ // Sort by
					var href = scope.HLinkPaper.createURL('sortby', $(this).val(), $this.options.url);
					$this.loadAjax(href, true);
					return false;
				});
				vssDiv.find('.js-h-view-sort-search form').submit(function(event){ // Search
					var searchword = this.searchword.value;
					searchword = searchword.replace(/\\/g, '');
					searchword = searchword.replace(/\//g, '');
					var href = scope.HLinkPaper.createURL('search', searchword, $this.options.url);
					href = scope.HLinkPaper.createURL('page', '1', href);
					$this.loadAjax(href, true);
					return false;
				});
			};

			// Init Widget
			$this.init();
		},
		collection : {
			items : {},
			add : function(HAjaxContent) {
				this.items[HAjaxContent.divID] = HAjaxContent;
			},
			get : function(divID) {
				return this.items['js-h-ajax-content-' + divID] ? this.items['js-h-ajax-content-' + divID] : null; 
			}
		}
	}
})(jQuery, jQuery);