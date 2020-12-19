const providerArray = require('../../acceptance-provider/SearchTest.json');

describe('SearchTest', () =>
{
	beforeEach(() =>
	{
		cy.visit('http://localhost:8000/?l=en');
	});

	before(() =>
	{
		cy.setConfig();
		cy.uninstallDatabase();
		cy.installDatabase();
	});

	after(() =>
	{
		cy.uninstallDatabase();
		cy.resetConfig();
	});

	providerArray.map(test =>
	{
		it('search for ' + test.description, () =>
		{
			cy.visit(test.url);
			test.elementArray.map(element => cy.get(element.selector).should('have.text', element.text));
		});
	});
});
