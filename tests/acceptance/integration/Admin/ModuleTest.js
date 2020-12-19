const providerArray = require('../../../acceptance-provider/Admin/ModuleTest.json');

describe('Admin/ModuleTest', () =>
{
	before(() =>
	{
		cy.setConfig();
		cy.uninstallDatabase();
		cy.installDatabase();
		cy.installTestDummy();
	});

	beforeEach(() =>
	{
		cy.login();
	});

	after(() =>
	{
		cy.uninstallTestDummy();
		cy.uninstallDatabase();
		cy.resetConfig();
	});

	afterEach(() =>
	{
		cy.logout();
	});

	providerArray.map(test =>
	{
		it('visit ' + test.description + ' page', () =>
		{
			cy.visit(test.url);
			test.elementArray.map(element => cy.get(element.selector).should('have.text', element.text));
		});
	});
});
