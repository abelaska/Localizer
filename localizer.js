( function() {
	// default english localization
	var localization = {}, nplurals_def = nplurals = 2, nplural_def = nplural = function(n) {
		return n < 2 ? 0 : 1;
	}
	String.locale = function(nplurals_def_val, nplural_def_fn, nplurals_val, nplural_fn, data) {
		localization = data;
		nplurals_def = nplurals_def_val;
		nplural_def = nplural_def_fn;
		nplurals = nplurals_val;
		nplural = nplural_fn;
		return String.prototype.locale.apply(arguments);
	};
	String.prototype.locale = function() {
		var this_value = this.valueOf();
		var localized = localization[this_value];
		if( typeof localized === 'undefined') {
			return this_value;
		} else {
			return localized;
		}
	};
	Array.prototype.locale = function(number) {
		var this_value = this.valueOf();
		if(this_value.length == 0) {
			return "";
		}
		var localized = this_value[0].locale();
		if(isArray(localized)) {
			return localized[nplural(number)];
		} else {
			return this_value[nplural_def(number)];
		}
	};
	__n = function() {
		var i, a = arguments, len = a.length, number = a[a.length - 1];
		var reqLen = nplurals_def + 1;
		if(len == reqLen) {
			var args = [];
			for( i = 0; i < a.length - 1; i++) {
				args[i] = a[i];
			}
			return sprintf(args.locale(number), number);
		} else if(len < reqLen) {
			var err = "__n(";
			while(len++ < reqLen) {
				err += "!!!missing plural argument!!!";
				if(len > 0) {
					err += ",";
				}
			}
			for( i = 0; i < a.length; i++) {
				if(i > 0) {
					err += ",";
				}
				err += a[i];
			}
			err += ')';
			return err;
		} else {
			var err = "__n(!!!too many plurals!!!,";
			for( i = 0; i < a.length; i++) {
				if(i > 0) {
					err += ",";
				}
				err += a[i];
			}
			err += ')';
			return err;
		}
	};
	__ = function() {
		var a = arguments, string = a[0].locale();
		if(!isString(string)) {
			if(isArray(string)) {
				string = string[0];
			} else {
				string = "__(!!!required string value!!!" + string + ")";
			}
		}
		if(a.length > 1) {
			var args = [];
			for(var i = 1; i < a.length; i++) {
				args[i - 1] = a[i];
			}
			string = sprintf(string, args);
		}
		return string;
	};
	function isArray(variable) {
		return typeof variable === 'undefined' ? false : (variable.constructor == Array);
	};

	function isString(variable) {
		return typeof variable === 'undefined' ? false : (variable.constructor == String);
	};

	function sprintf() {
		// http://kevin.vanzonneveld.net
		// +   original by: Ash Searle (http://hexmen.com/blog/)
		// + namespaced by: Michael White (http://getsprink.com)
		// +    tweaked by: Jack
		// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +      input by: Paulo Freitas
		// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +      input by: Brett Zamir (http://brett-zamir.me)
		// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// *     example 1: sprintf("%01.2f", 123.1);
		// *     returns 1: 123.10
		// *     example 2: sprintf("[%10s]", 'monkey');
		// *     returns 2: '[    monkey]'
		// *     example 3: sprintf("[%'#10s]", 'monkey');
		// *     returns 3: '[####monkey]'
		var regex = /%%|%(\d+\$)?([-+\'#0 ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([scboxXuidfegEG])/g;
		var a = arguments, i = 0, format = a[i++];

		// pad()
		var pad = function(str, len, chr, leftJustify) {
			if(!chr) {
				chr = ' ';
			}
			var padding = (str.length >= len) ? '' : Array(1 + len - str.length >>> 0).join(chr);
			return leftJustify ? str + padding : padding + str;
		};
		// justify()
		var justify = function(value, prefix, leftJustify, minWidth, zeroPad, customPadChar) {
			var diff = minWidth - value.length;
			if(diff > 0) {
				if(leftJustify || !zeroPad) {
					value = pad(value, minWidth, customPadChar, leftJustify);
				} else {
					value = value.slice(0, prefix.length) + pad('', diff, '0', true) + value.slice(prefix.length);
				}
			}
			return value;
		};
		// formatBaseX()
		var formatBaseX = function(value, base, prefix, leftJustify, minWidth, precision, zeroPad) {
			// Note: casts negative numbers to positive ones
			var number = value >>> 0;
			prefix = prefix && number && {
			'2': '0b',
			'8': '0',
			'16': '0x'
			}[base] || '';
			value = prefix + pad(number.toString(base), precision || 0, '0', false);
			return justify(value, prefix, leftJustify, minWidth, zeroPad);
		};
		// formatString()
		var formatString = function(value, leftJustify, minWidth, precision, zeroPad, customPadChar) {
			if(precision != null) {
				value = value.slice(0, precision);
			}
			return justify(value, '', leftJustify, minWidth, zeroPad, customPadChar);
		};
		// doFormat()
		var doFormat = function(substring, valueIndex, flags, minWidth, _, precision, type) {
			var number;
			var prefix;
			var method;
			var textTransform;
			var value;

			if(substring == '%%') {
				return '%';
			}

			// parse flags
			var leftJustify = false, positivePrefix = '', zeroPad = false, prefixBaseX = false, customPadChar = ' ';
			var flagsl = flags.length;
			for(var j = 0; flags && j < flagsl; j++) {
				switch (flags.charAt(j)) {
					case ' ':
						positivePrefix = ' ';
						break;
					case '+':
						positivePrefix = '+';
						break;
					case '-':
						leftJustify = true;
						break;
					case "'":
						customPadChar = flags.charAt(j + 1);
						break;
					case '0':
						zeroPad = true;
						break;
					case '#':
						prefixBaseX = true;
						break;
				}
			}

			// parameters may be null, undefined, empty-string or real valued
			// we want to ignore null, undefined and empty-string values
			if(!minWidth) {
				minWidth = 0;
			} else if(minWidth == '*') {
				minWidth = +a[i++];
			} else if(minWidth.charAt(0) == '*') {
				minWidth = +a[minWidth.slice(1, -1)];
			} else {
				minWidth = +minWidth;
			}

			// Note: undocumented perl feature:
			if(minWidth < 0) {
				minWidth = -minWidth;
				leftJustify = true;
			}

			if(!isFinite(minWidth)) {
				throw new Error('sprintf: (minimum-)width must be finite');
			}

			if(!precision) {
				precision = 'fFeE'.indexOf(type) > -1 ? 6 : (type == 'd') ? 0 : undefined;
			} else if(precision == '*') {
				precision = +a[i++];
			} else if(precision.charAt(0) == '*') {
				precision = +a[precision.slice(1, -1)];
			} else {
				precision = +precision;
			}

			// grab value using valueIndex if required?
			value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++];

			switch (type) {
				case 's':
					return formatString(String(value), leftJustify, minWidth, precision, zeroPad, customPadChar);
				case 'c':
					return formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad);
				case 'b':
					return formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
				case 'o':
					return formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
				case 'x':
					return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
				case 'X':
					return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad).toUpperCase();
				case 'u':
					return formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
				case 'i':
				case 'd':
					number = (+value) | 0;
					prefix = number < 0 ? '-' : positivePrefix;
					value = prefix + pad(String(Math.abs(number)), precision, '0', false);
					return justify(value, prefix, leftJustify, minWidth, zeroPad);
				case 'e':
				case 'E':
				case 'f':
				case 'F':
				case 'g':
				case 'G':
					number = +value;
					prefix = number < 0 ? '-' : positivePrefix;
					method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())];
					textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2];
					value = prefix + Math.abs(number)[method](precision);
					return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]();
				default:
					return substring;
			}
		};
		return format.replace(regex, doFormat);
	}

}());
