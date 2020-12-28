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
				test.elementArray.map(element => cy.get(element.selector)
					.should('be.visible')
					.should('have.text', element.text));
			});
		});
	});

	context('behaviour', () =>
	{
		it('search for welcome via form', () =>
		{
			cy.visit('http://localhost:8000/?l=en');
			cy.get('input.rs-field-search').clear().type('welcome');

			cy.get('button.rs-button-search').click();

			cy.url().should('eq', 'http://localhost:8000/?p=search/welcome');
			cy.get('h2.rs-title-result')
				.should('be.visible')
				.shouldHaveText('articles');
			cy.get('ol.rs-list-result a.rs-link-result')
				.should('be.visible')
				.should('have.text', 'Welcome');
		});
	});
});
