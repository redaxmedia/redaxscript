/* call home */

if (typeof _gat === 'object' && r.constant.FIRST_PARAMETER === 'admin' && r.constant.ADMIN_PARAMETER === '')
{
	var tracker = _gat._createTracker('UA-16122280-10');

	tracker._setDomainName('none');
	tracker._initData();
	tracker._trackPageview();

	/* call home */

	tracker._trackEvent('call-home', r.version, r.baseURL);
}