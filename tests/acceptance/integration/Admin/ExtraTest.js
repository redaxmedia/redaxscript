const providerArray = require('../../../acceptance-provider/Admin/ExtraTest.json');

describe('Admin/ExtraTest', () =>
{
	before(() =>
	{
		cy.setConfig();
		cy.uninstallDatabase();
		cy.installDatabase();
	});

	beforeEach(() =>
	{
		cy.login();
	});

	after(() =>
	{
		cy.uninstallDatabase();
		cy.resetConfig();
	});

	afterEach(() =>
	{
		cy.logout();
	});

	context('general', () =>
	{
		providerArray.map(test =>
		{
			it('visit ' + test.description + ' page', () =>
			{
				cy.visit(test.url);
				test.elementArray.map(element => cy.get(element.selector).should('have.text', element.text));
			});
		});
	});
});
