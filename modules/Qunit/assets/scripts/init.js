/**
 * @tableofcontents
 *
 * 1. qunit
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. qunit */

rs.modules.qunit =
{
	init: rs.registry.firstParameter === 'qunit',
	options:
	{
		element:
		{
			qunit: '#qunit',
			qunitHeader: '#qunit-header',
			qunitBanner: '#qunit-banner',
			qunitToolbar: '#qunit-testrunner-toolbar',
			qunitUserAgent: '#qunit-userAgent',
			qunitResult: '#qunit-testresult',
			qunitTest: '#qunit-tests',
			qunitAssert: 'ol.rs-qunit-assert-list',
			qunitFixture: '#qunit-fixture'
		},
		className:
		{
			qunitHeader: 'rs-title-qunit',
			qunitBanner: 'rs-box-note rs-banner-qunit',
			qunitToolbar: 'rs-box-qunit rs-toolbar-qunit',
			qunitUserAgent: 'rs-box-qunit rs-user-agent-qunit',
			qunitResult: 'rs-title-qunit',
			qunitTest: 'rs-list-qunit',
			qunitAssert: 'rs-list-qunit-assert',
			qunitFixture: 'rs-box-fixture'
		},
		duration: 500
	}
};
