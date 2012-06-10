/* call home */

if (typeof _gat == 'object' && r.constant.FIRST_PARAMETER == 'admin' && r.constant.ADMIN_PARAMETER == '')
{
	var tracker = _gat._createTracker('UA-16122280-10'),
		checkBacklink = $('a.js_backlink').length;

	tracker._setDomainName('none');
	tracker._initData();
	tracker._trackPageview();

	/* call home */

	tracker._trackEvent('call-home', r.version, r.baseURL);

	/* missing backlink */

	if (checkBacklink == 0)
	{
		tracker._trackEvent('missing-backlink', r.version, r.baseURL);
	}
}