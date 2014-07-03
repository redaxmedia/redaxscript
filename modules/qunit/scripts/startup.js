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

r.modules.qunit =
{
	startup: true,
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
			qunitAssert: 'ol.qunit-assert-list',
			qunitFixture: '#qunit-fixture'
		},
		className:
		{
			qunitHeader: 'title_qunit',
			qunitBanner: 'box_note banner_qunit',
			qunitToolbar: 'box_qunit toolbar_qunit',
			qunitUserAgent: 'box_qunit user_agent_qunit',
			qunitResult: 'title_qunit',
			qunitTest: 'list_qunit',
			qunitAssert: 'list_qunit_assert',
			qunitFixture: 'box_fixture'
		},
		duration: 500
	}
};