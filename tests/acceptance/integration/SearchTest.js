const providerArray = require('../../acceptance-provider/SearchTest.json');

describe('SearchTest', () =>
{
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

	context('general', () =>
	{
		providerArray.map(test =>
		{
			it('search for ' + test.description, () =>
			{
				cy.visit(test.url);
				test.elementArray.map(element => cy.get(element.selector).should('have.text', element.text));
			});
		});
	});
});
