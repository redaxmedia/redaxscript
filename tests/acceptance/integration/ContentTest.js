const providerArray = require('../../acceptance-provider/ContentTest.json');

describe('ContentTest', () =>
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
			it('visit ' + test.description + ' page', () =>
			{
				cy.visit(test.url,
				{
					failOnStatusCode: false
				});
				test.elementArray.map(element => cy.get(element.selector)
					.should('be.visible')
					.should('have.text', element.text));
			});
		});
	});

	context('behaviour', () =>
	{
		it('toggle more button', () =>
		{
			cy.visit('http://localhost:8000/?p=home');
			cy.get('hr.rs-break-more').should('not.exist');
			cy.get('h3.rs-title-comment').should('not.exist');
			cy.get('div.rs-box-comment').should('not.exist');
			cy.get('form.rs-form-comment').should('not.exist');

			cy.get('a.rs-link-more').click();

			cy.get('a.rs-link-more').should('not.exist');
			cy.get('hr.rs-break-more').should('be.visible');
			cy.get('h3.rs-title-comment').should('be.visible');
			cy.get('div.rs-box-comment').should('be.visible');
			cy.get('form.rs-form-comment').should('be.visible');
		});
	});
});
