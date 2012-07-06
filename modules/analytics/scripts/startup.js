/* analytics */

if (typeof _gat === 'object')
{
	var tracker = _gat._createTracker('UA-00000000-0');

	tracker._setDomainName('domain.com');
	tracker._initData();
	tracker._trackPageview();
}