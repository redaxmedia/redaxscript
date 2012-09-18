/* call home */

if (r.module.callHome.startup && (r.constant.LOGGED_IN === r.constant.TOKEN) && (r.constant.FIRST_PARAMETER === 'admin' && r.constant.ADMIN_PARAMETER === '') && typeof _gat === 'object')
{
	r.module.callHome.tracker = _gat._createTracker(r.module.callHome.id);
	r.module.callHome.tracker._setDomainName(r.module.callHome.url);
	r.module.callHome.tracker._initData();
	r.module.callHome.tracker._trackPageview();

	/* track event */

	r.module.callHome.tracker._trackEvent('call-home', r.version, r.baseURL);
}