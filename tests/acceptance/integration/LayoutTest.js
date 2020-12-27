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
		it('default layout with teaser', () =>
		{
			cy.visit('http://localhost:8000/?l=en');
			cy.get('#header').should('be.visible');
			cy.get('#teaser').should('be.visible');
			cy.get('#content').should('be.visible');
			cy.get('#sidebar').should('be.visible');
			cy.scrollTo('bottom');
			cy.get('#footer').should('be.visible');
		});

		it('default layout without teaser', () =>
		{
			cy.visit('http://localhost:8000/?l=en&p=home/welcome');
			cy.get('#header').should('be.visible');
			cy.get('#teaser').should('not.exist');
			cy.get('#content').should('be.visible');
			cy.get('#sidebar').should('be.visible');
			cy.scrollTo('bottom');
			cy.get('#footer').should('be.visible');
		});

		it('wide layout without teaser', () =>
		{
			cy.visit('http://localhost:8000/?l=en&t=wide');
			cy.get('#header').should('be.visible');
			cy.get('#teaser').should('not.exist');
			cy.get('#content').should('be.visible');
			cy.get('#sidebar').should('not.exist');
			cy.scrollTo('bottom');
			cy.get('#footer').should('be.visible');
		});

		it('wide layout without teaser and sidebar', () =>
		{
			cy.visit('http://localhost:8000/?l=en&p=home/welcome&t=wide');
			cy.get('#header').should('be.visible');
			cy.get('#teaser').should('not.exist');
			cy.get('#content').should('be.visible');
			cy.get('#sidebar').should('not.exist');
			cy.scrollTo('bottom');
			cy.get('#footer').should('be.visible');
		});
	});
});
