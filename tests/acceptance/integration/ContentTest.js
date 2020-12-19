const providerArray = require('../../acceptance-provider/ContentTest.json');

describe('ContentTest', () =>
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
		it('visit ' + test.description + ' page', () =>
		{
			cy.visit(test.url,
			{
				failOnStatusCode: false
			});
			test.elementArray.map(element => cy.get(element.selector).should('have.text', element.text));
		});
	});
});
