//function prefixMe($, targetString, prefixString)
//{
//    var target = $(targetString).not('[class*="' + prefixString + '"]'),
//        targetChildren = target.find('*').not('[class*="' + prefixString + '"]');
//
//    target.add(targetChildren).each(function ()
//    {
//        var that = $(this),
//            thatPrefixed = that.data('prefixed');
//            thatClass = that.attr('class'),
//            thatNewClass = ''
//            thatTempArray = [];
//
//        if (typeof thatClass === 'string' && !thatPrefixed)
//        {
//            thatNewClass = thatClass;
//            thatNewClass = thatNewClass.replace(/_/g, '-');
//            thatNewClass = thatNewClass.replace(/-admin/g, '');
//
//
//            thatTempArray = thatNewClass.split(' ');
//            for (var i in thatTempArray)
//            {
//                thatTempArray[i] = prefixString + thatTempArray[i];
//            }
//            thatNewClass = thatTempArray.join(' ');
//            that.attr('class', thatNewClass).data('prefixed', true);
//        }
//    });
//};
//
//$(function() {
//prefixMe(window.jQuery, '#panel_admin, .js_dock_admin, .wrapper_dock_admin', 'rs-admin-');
//    prefixMe(window.jQuery, '*', 'rs-');
//});