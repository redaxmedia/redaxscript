/**
 * @tableofcontents
 *
 * 1. qunit
 */

/* @section 1. qunit */

r.module.qunit =
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
			qunitFixture: '#qunit-fixture'
		},
		classString:
		{
			qunitHeader: 'title_qunit',
			qunitBanner: 'box_note banner_qunit',
			qunitToolbar: 'box_qunit toolbar_qunit',
			qunitUserAgent: 'box_qunit user_agent_qunit',
			qunitResult: 'title_qunit',
			qunitTest: 'list_qunit',
			qunitFixture: 'box_fixture'
		},
		duration: 500
	}
};