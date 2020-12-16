const PROVIDER = require('../../acceptance-provider/ContentTest.json');

describe('content', () =>
{
	beforeEach(() =>
	{
		cy.visit('http://localhost:8000/?l=en');
	});

	before(() =>
	{
		cy.setConfig();
		cy.uninstallDatabase();
		cy.wait(3000);
		cy.installDatabase();
	});

	after(() =>
	{
		cy.uninstallDatabase();
		cy.resetConfig();
	});

	PROVIDER.map(test =>
	{
		it('test ' + test.description, () =>
		{
			cy.visit(test.url,
			{
				failOnStatusCode: false
			});
			test.elementArray.map(element => cy.get(element.selector).should('have.text', element.text));
		});
	});
});
