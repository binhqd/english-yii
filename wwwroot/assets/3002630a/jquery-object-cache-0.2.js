/*
 * jQuery Object Cache 0.2
 * By Luciano Germán Panaro (http://www.decodeuri.com)
 * Project site: http://www.decodeuri.com/2008/11/20/new-jquery-plugin-object-cache
 *
 * Copyright (c) 2008, 2009 Luciano Germán Panaro
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.gnu.org/licenses/gpl-2.0.txt) licenses.
*/

; if(window.jQuery) {

  _$$ = window.$$;
  
  window.jQueryCache = window.$$ = function($) {

      $.extend({
          object_cache: {}    
      });      

      $.fn.extend({
                  
          cache: function (key) {
              if (typeof key == "string") {
                  return $.object_cache[key] = this;
              }
          }
          
      });
      
      var jQueryCache = function( key, reload ) {
          if (!key || typeof key != "string" ) {
              return false;             
          }

          if (reload || !$.object_cache[key]) {
              $(key).cache(key);
          }
                
          return $.object_cache[key];
      };
      
      jQueryCache.remove = function( key ) {          
          $.object_cache[key] = null;      
      };
      
      jQueryCache.clear = function() {
          $.object_cache = [];
      };      
      
      jQueryCache.noConflict = function() {
          window.$$ = _$$;
          return jQueryCache;
      };      

      return jQueryCache;
  
  }(jQuery);
  
}
