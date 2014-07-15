/**
 * @tableofcontents
 *
 * 1. clean alias
 * 2. generate alias
 * 3. startup
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. clean alias */

	$.fn.cleanAlias = function (input)
	{
		var output = input.toLowerCase();

		/* replace special characters */

		output = output.replace(/[\u00c0-\u00c3\u00e0-\u00e3\u0100-\u0105\u0386\u0391\u03ac\u03b1\u0410\u0430\u05d0\u10d0\u1ea0-\u1eb7\u3041\u3042\u30a1\u30a2\xc0-\xc5\xe0-\xe5]/g, 'a');
		output = output.replace(/[\u05e2]/g, 'aa');
		output = output.replace(/[\xc6\xe6]/g, 'ae');
		output = output.replace(/[\u0392\u03b2\u05d1\u10d1]/g, 'b');
		output = output.replace(/[\u3070\u30d0]/g, 'ba');
		output = output.replace(/[\u0411\u0431\u3079\u30d9]/g, 'be');
		output = output.replace(/[\u3073\u30d3]/g, 'bi');
		output = output.replace(/[\u307c\u30dc]/g, 'bo');
		output = output.replace(/[\u3076\u30d6]/g, 'bu');
		output = output.replace(/[\u0106-\u010d\u0147\u0148\u05da\u05db\xc7\xe7]/g, 'c');
		output = output.replace(/[\u03a7\u03c7\u10e9\u10ed]/g, 'ch');
		output = output.replace(/[\u0427\u0447]/g, 'che');
		output = output.replace(/[\u010e-\u0111\u0394\u03b4\u05d3\u10d3\xd0\xf0]/g, 'd');
		output = output.replace(/[\u3060\u30c0]/g, 'da');
		output = output.replace(/[\u0414\u0434\u3067\u30c7]/g, 'de');
		output = output.replace(/[\u3062\u30c2]/g, 'di');
		output = output.replace(/[\u0402\u0452]/g, 'dje');
		output = output.replace(/[\u3069\u30c9]/g, 'do');
		output = output.replace(/[\u3065\u30c5]/g, 'du');
		output = output.replace(/[\u10eb]/g, 'dz');
		output = output.replace(/[\u0405\u0455]/g, 'dze');
		output = output.replace(/[\u040f\u045d]/g, 'dzhe');
		output = output.replace(/[\u00c8-\u00ca\u00e8-\u00ea\u0116-\u011b\u0112\u0113\u0388\u0395\u03ad\u03b5\u042d\u044d\u10d4\u1eb8-\u1ec7\u3047\u3048\u30a7\u30a8\xc8-\xcb\xe8-\xeb]/g, 'e');
		output = output.replace(/[\u0424\u0444]/g, 'ef');
		output = output.replace(/[\u041b\u043b]/g, 'el');
		output = output.replace(/[\u041c\u043c]/g, 'em');
		output = output.replace(/[\u041d\u043d]/g, 'en');
		output = output.replace(/[\u0420\u0440]/g, 'er');
		output = output.replace(/[\u0421\u0441]/g, 'es');
		output = output.replace(/[\u03a6\u03c6]/g, 'f');
		output = output.replace(/[\u011c-\u0123\u0393\u03b3\u05d2\u10d2]/g, 'g');
		output = output.replace(/[\u304c\u30ac]/g, 'ga');
		output = output.replace(/[\u3052\u30b2]/g, 'ge');
		output = output.replace(/[\u304e\u30ae]/g, 'gi');
		output = output.replace(/[\u10e6]/g, 'gh');
		output = output.replace(/[\u0413\u0433]/g, 'ghe');
		output = output.replace(/[\u0403\u0453]/g, 'gje');
		output = output.replace(/[\u3054\u30b4]/g, 'go');
		output = output.replace(/[\u3050\u30b0]/g, 'gu');
		output = output.replace(/[\u0124-\u0127\u05d4\u10f0]/g, 'h');
		output = output.replace(/[\u0425\u0445\u306f\u30cf]/g, 'ha');
		output = output.replace(/[\u3078\u30d8]/g, 'he');
		output = output.replace(/[\u3072\u30d2]/g, 'hi');
		output = output.replace(/[\u307b\u30db]/g, 'ho');
		output = output.replace(/[\u3075\u30d5]/g, 'hu');
		output = output.replace(/[\u00cc\u00cd\u00ec\u00ed\u0128-\u012b\u012e-\u0132\u013a\u0389\u038a\u0390\u0397\u0399\u03aa\u03ae\u03af\u03b7\u03b9\u03ca\u0406\u0418\u0419\u0438\u0439\u0456\u10d8\u1ec8-\u1ecb\u3043\u3044\u30a3\u30a4\xa1\xcc-\xcf\xec-\xef]/g, 'i');
		output = output.replace(/[\u0404\u0415\u0435\u0454]/g, 'ie');
		output = output.replace(/[\u0401\u0451]/g, 'io');
		output = output.replace(/[\u0134\u0135\u10ef]/g, 'j');
		output = output.replace(/[\u0408\u0458]/g, 'je');
		output = output.replace(/[\u0136\u0137\u039a\u03ba\u05d7\u05e7\u10d9]/g, 'k');
		output = output.replace(/[\u041a\u043a\u304b\u3095\u30ab\u30f5]/g, 'ka');
		output = output.replace(/[\u3051\u3096\u30b1\u30f6]/g, 'ke');
		output = output.replace(/[\u304d\u30ad]/g, 'ki');
		output = output.replace(/[\u10ee]/g, 'kh');
		output = output.replace(/[\u040c\u045c]/g, 'kje');
		output = output.replace(/[\u3053\u30b3]/g, 'ko');
		output = output.replace(/[\u304f\u30af]/g, 'ku');
		output = output.replace(/[\u0139-\u013e\u0141\u0142\u039b\u03bb\u05dc\u10da]/g, 'l');
		output = output.replace(/[\u0409\u0459]/g, 'lje');
		output = output.replace(/[\u039c\u03bc\u05dd\u05de\u10db]/g, 'm');
		output = output.replace(/[\u307e\u30de]/g, 'ma');
		output = output.replace(/[\u3081\u30e1]/g, 'me');
		output = output.replace(/[\u307f\u30df]/g, 'mi');
		output = output.replace(/[\u3082\u30e2]/g, 'mo');
		output = output.replace(/[\u3080\u30e0]/g, 'mu');
		output = output.replace(/[\u0143-\u0148\u039d\u03bd\u05df\u05e0\u10dc\u3093\u30f3\xd1\xf1]/g, 'n');
		output = output.replace(/[\u306a\u30ca]/g, 'na');
		output = output.replace(/[\u306d\u30cd]/g, 'ne');
		output = output.replace(/[\u306b\u30cb]/g, 'ni');
		output = output.replace(/[\u306e\u30ce]/g, 'no');
		output = output.replace(/[\u306c\u30cc]/g, 'nu');
		output = output.replace(/[\u040a\u045a]/g, 'nje');
		output = output.replace(/[\u00d2-\u00d5\u00f2-\u00f5\u014d\u014c\u0150\u0151\u01a0\u01a1\u038c\u038f\u039f\u03a9\u03bf\u03c9\u03cc\u03ce\u041e\u043e\u10dd\u1ecc-\u1ee3\u3049\u304a\u30a9\u30aa\xd2-\xd6\xd8\xf2-\xf6\xf8]/g, 'o');
		output = output.replace(/[\u039e\u03be\u0152\u0153]/g, 'oe');
		output = output.replace(/[\u03a0\u03c0\u05e3\u05e4\u10de]/g, 'p');
		output = output.replace(/[\u3071\u30d1]/g, 'pa');
		output = output.replace(/[\u041f\u043f\u307a\u30da]/g, 'pe');
		output = output.replace(/[\u10e4]/g, 'ph');
		output = output.replace(/[\u3074\u30d4]/g, 'pi');
		output = output.replace(/[\u03c8\u03a8\u0398\u03b8]/g, 'ps');
		output = output.replace(/[\u307d\u30dd]/g, 'po');
		output = output.replace(/[\u3077\u30d7]/g, 'pu');
		output = output.replace(/[\u10e5]/g, 'q');
		output = output.replace(/[\u0154-\u0159\u03a1\u03c1\u05e8\u10e0]/g, 'r');
		output = output.replace(/[\u3089\u30e9]/g, 'ra');
		output = output.replace(/[\u308c\u30ec]/g, 're');
		output = output.replace(/[\u308a\u30ea]/g, 'ri');
		output = output.replace(/[\u308d\u30ed]/g, 'ro');
		output = output.replace(/[\u308b\u30eb]/g, 'ru');
		output = output.replace(/[\u015a-\u0161\u03a3\u03c2\u03c3\u05e1\u10e1\x8a\x9a\xdf]/g, 's');
		output = output.replace(/[\u3055\u30b5]/g, 'sa');
		output = output.replace(/[\u05e9\u10e8]/g, 'sh');
		output = output.replace(/[\u0428\u0448]/g, 'sha');
		output = output.replace(/[\u0429\u042a\u0449]/g, 'shcha');
		output = output.replace(/[\u305b\u30bb]/g, 'se');
		output = output.replace(/[\u3057\u30b7]/g, 'si');
		output = output.replace(/[\u305d\u30bd]/g, 'so');
		output = output.replace(/[\u3059\u30b9]/g, 'su');
		output = output.replace(/[\xdf]/g, 'sz');
		output = output.replace(/[\u0162-\u0167\u021a\u021b\u03a4\u03c4\u05d8\u05ea\u10e2\u10d7]/g, 't');
		output = output.replace(/[\u305f\u30bf]/g, 'ta');
		output = output.replace(/[\u0422\u0442\u3066\u30c6]/g, 'te');
		output = output.replace(/[\u0398\u03b8\xde\xfe]/g, 'th');
		output = output.replace(/[\u3061\u30c1]/g, 'ti');
		output = output.replace(/[\u3068\u30c8]/g, 'to');
		output = output.replace(/[\u05e5\u05e6\u10ea\u10ec]/g, 'ts');
		output = output.replace(/[\u0426\u0446]/g, 'tse');
		output = output.replace(/[\u040b\u045b]/g, 'tshe');
		output = output.replace(/[\u3063\u3064\u30c3\u30c4]/g, 'tu');
		output = output.replace(/[\u00d9\u00da\u00f9\u00fa\u0171\u0173\u0168-\u0170\u01af\u01b0\u0423\u0443\u040e\u045d\u10e3\u1ee4-\u1ef1\u3045\u3046\u30a5\u30a6\xb5\xd9-\xdc\xf9-\xfc]/g, 'u');
		output = output.replace(/[\u05d5\u10d5]/g, 'v');
		output = output.replace(/[\u30f7]/g, 'va');
		output = output.replace(/[\u0412\u0432\u30f9]/g, 've');
		output = output.replace(/[\u30f8]/g, 'vi');
		output = output.replace(/[\u30fa]/g, 'vo');
		output = output.replace(/[\u3094\u30f4]/g, 'vu');
		output = output.replace(/[\u308e\u308f\u30ee\u30ef]/g, 'wa');
		output = output.replace(/[\u3091\u30f1]/g, 'we');
		output = output.replace(/[\u3090\u30f0]/g, 'wi');
		output = output.replace(/[\u3092\u30f2]/g, 'wo');
		output = output.replace(/[\u03be\u039e]/g, 'x');
		output = output.replace(/[\u00da\u00fd\u038e\u03a5\u03ab\u03b0\u03c5\u03cb\u03cd\u05d9\u10e7\u1ef2-\u1ef9\x9f\xdd\xfd\xff]/g, 'y');
		output = output.replace(/[\u042f\u044f\u3083\u3084\u30e3\u30e4]/g, 'ya');
		output = output.replace(/[\u0407\u0457]/g, 'yi');
		output = output.replace(/[\u3087\u3088\u30e7\u30e8]/g, 'yo');
		output = output.replace(/[\u042e\u044e\u3085\u3086\u30e5\u30e6]/g, 'yu');
		output = output.replace(/[\u0179-\u017e\u0396\u03b6\u05d6\u10d6\x9e]/g, 'z');
		output = output.replace(/[\u3056\u30b6]/g, 'za');
		output = output.replace(/[\u10df]/g, 'zh');
		output = output.replace(/[\u3058\u30b8]/g, 'zi');
		output = output.replace(/[\u0416\u0436]/g, 'zhe');
		output = output.replace(/[\u0417\u0437\u305c\u30bc]/g, 'ze');
		output = output.replace(/[\u305e\u30be]/g, 'zo');
		output = output.replace(/[\u305a\u30ba]/g, 'zu');
		output = output.replace(/\W+/g, ' ');
		output = output.trim();
		output = output.replace(/\s+/g, '-');
		return output;
	};

	/* @section 2. generate alias */

	$.fn.generateAlias = function (options)
	{
		/* extend options */

		if (r.plugins.generateAlias.options !== options)
		{
			options = $.extend({}, r.plugins.generateAlias.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for change and input */

			$(this).on('change input', function ()
			{
				var field = $(this),
					form = field.closest('form'),
					fiedValue = $.trim(field.val()),
					related = form.find(options.element.field),
					aliasValue = '';

				/* clean alias if value */

				if (fiedValue)
				{
					aliasValue = $.fn.cleanAlias(fiedValue);
					if (aliasValue)
					{
						related.val(aliasValue).add(field).attr('data-related', 'alias').trigger('related');
					}
				}

				/* else clear related value */

				else
				{
					related.val('');
				}
			});
		});
	};

	/* @section 3. startup */

	$(function ()
	{
		if (r.plugins.generateAlias.startup)
		{
			$(r.plugins.generateAlias.selector).generateAlias(r.plugins.generateAlias.options);
		}
	});
})(window.jQuery || window.Zepto);