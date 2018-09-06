/*!
*
* ColorPick jQuery plugin
* https://github.com/philzet/ColorPick.js
*
* Copyright (c) 2017 Phil Zet (a.k.a. Philipp Zakharchenko)
* Licensed under the MIT License
*
*/
(function( $ ) {

    TYPO3.jQuery.fn.colorPick = function(config) {

        return this.each(function() {
            new TYPO3.jQuery.colorPick(this, config || {});
        });

    };

    TYPO3.jQuery.colorPick = function (element, options) {
        options = options || {};
        this.options = TYPO3.jQuery.extend({}, TYPO3.jQuery.fn.colorPick.defaults, options);
        if(options.str) {
            this.options.str = TYPO3.jQuery.extend({}, TYPO3.jQuery.fn.colorpickr.defaults.str, options.str);
        }
        TYPO3.jQuery.fn.colorPick.defaults = this.options;
        this.color   = this.options.initialColor.toUpperCase();
        this.element = TYPO3.jQuery(element);

        var dataInitialColor = this.element.data('initialcolor');
        if (dataInitialColor) {
            this.color = dataInitialColor;
            this.appendToStorage(this.color);
        }

        var uniquePalette = [];
        TYPO3.jQuery.each(TYPO3.jQuery.fn.colorPick.defaults.palette.map(function(x){ return x.toUpperCase() }), function(i, el){
            if(TYPO3.jQuery.inArray(el, uniquePalette) === -1) uniquePalette.push(el);
        });

        this.palette = uniquePalette;

        return this.element.hasClass(this.options.pickrclass) ? this : this.init();
    };

    TYPO3.jQuery.fn.colorPick.defaults = {
        'initialColor': '#3498db',
        'paletteLabel': 'Default palette:',
        'allowRecent': true,
        'recentMax': 5,
        'allowCustomColor': false,
        'palette': ["#1abc9c", "#16a085", "#2ecc71", "#27ae60", "#3498db", "#2980b9", "#9b59b6", "#8e44ad", "#34495e", "#2c3e50", "#f1c40f", "#f39c12", "#e67e22", "#d35400", "#e74c3c", "#c0392b", "#ecf0f1", "#bdc3c7", "#95a5a6", "#7f8c8d"],
        'onColorSelected': function() {
            this.element.css({'backgroundColor': this.color, 'color': this.color});
        }
    };

    TYPO3.jQuery.colorPick.prototype = {

        init : function(){

            var self = this;
            var o = this.options;

            TYPO3.jQuery.proxy(TYPO3.jQuery.fn.colorPick.defaults.onColorSelected, this)();

            this.element.click(function(event) {
                event.preventDefault();
                self.show(event.pageX, event.pageY);

                TYPO3.jQuery('.customColorHash').val(self.color);

                TYPO3.jQuery('.colorPickButton').click(function(event) {
					self.color = TYPO3.jQuery(event.target).attr('hexValue');
					self.appendToStorage(TYPO3.jQuery(event.target).attr('hexValue'));
					self.hide();
					TYPO3.jQuery.proxy(self.options.onColorSelected, self)();
					return false;
            	});
                TYPO3.jQuery('.customColorHash').click(function(event) {
                    return false;
                }).keyup(function (event) {
                    var hash = TYPO3.jQuery(this).val();
                    if (hash.indexOf('#') !== 0) {
                        hash = "#"+hash;
                    }
                    if (/(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(hash)) {
                        self.color = hash;
                        self.appendToStorage(hash);
                        TYPO3.jQuery.proxy(self.options.onColorSelected, self)();
                        TYPO3.jQuery(this).removeClass('error');
                    } else {
                        TYPO3.jQuery(this).addClass('error');
                    }
                });

                return false;
            }).blur(function() {
                self.element.val(self.color);
                TYPO3.jQuery.proxy(self.options.onColorSelected, self)();
                self.hide();
                return false;
            });

            TYPO3.jQuery(document).on('click', function(event) {
                self.hide();
                return true;
            });

            return this;
        },

        appendToStorage: function(color) {
	        if (TYPO3.jQuery.fn.colorPick.defaults.allowRecent === true) {
	        	var storedColors = JSON.parse(localStorage.getItem("colorPickRecentItems"));
				if (storedColors == null) {
		     	    storedColors = [];
	        	}
				if (TYPO3.jQuery.inArray(color, storedColors) == -1) {
		    	    storedColors.unshift(color);
					storedColors = storedColors.slice(0, TYPO3.jQuery.fn.colorPick.defaults.recentMax)
					localStorage.setItem("colorPickRecentItems", JSON.stringify(storedColors));
	        	}
	        }
        },

        show: function(left, top) {

            TYPO3.jQuery("#colorPick").remove();

	        TYPO3.jQuery("body").append('<div id="colorPick" style="opacity:1;display:none;top:' + top + 'px;left:' + left + 'px"><span>'+TYPO3.jQuery.fn.colorPick.defaults.paletteLabel+'</span></div>');
	        TYPO3.jQuery.each(this.palette, function (index, item) {
				TYPO3.jQuery("#colorPick").append('<div class="colorPickButton" hexValue="' + item + '" style="background:' + item + '"></div>');
			});
            if (TYPO3.jQuery.fn.colorPick.defaults.allowCustomColor === true) {
                TYPO3.jQuery("#colorPick").append('<input type="text" style="margin-top:5px" class="customColorHash" />');
            }
			if (TYPO3.jQuery.fn.colorPick.defaults.allowRecent === true) {
				TYPO3.jQuery("#colorPick").append('<span style="margin-top:5px">Recent:</span>');
				if (JSON.parse(localStorage.getItem("colorPickRecentItems")) == null || JSON.parse(localStorage.getItem("colorPickRecentItems")) == []) {
					TYPO3.jQuery("#colorPick").append('<div class="colorPickButton colorPickDummy"></div>');
				} else {
					TYPO3.jQuery.each(JSON.parse(localStorage.getItem("colorPickRecentItems")), (index, item) => {
		        		TYPO3.jQuery("#colorPick").append('<div class="colorPickButton" hexValue="' + item + '" style="background:' + item + '"></div>');
                        if (index == TYPO3.jQuery.fn.colorPick.defaults.recentMax-1) {
                            return false;
                        }
					});
				}
			}
	        TYPO3.jQuery("#colorPick").show();
	    },

	    hide: function() {
		    TYPO3.jQuery( "#colorPick" ).fadeOut(200, function() {
			    TYPO3.jQuery("#colorPick").remove();
			    return this;
			});
        },

    };

}( jQuery ));
