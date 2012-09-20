/* analytics */

if (r.module.analytics.startup && (r.constant.LOGGED_IN !== r.constant.TOKEN) && typeof _gat === 'object')
{
	r.module.analytics.tracker = _gat._createTracker(r.module.analytics.options.id);
	r.module.analytics.tracker._setDomainName(r.module.analytics.options.url);
	r.module.analytics.tracker._initData();
	r.module.analytics.tracker._trackPageview();
}