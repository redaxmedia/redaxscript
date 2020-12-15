describe('login', () =>
{
	beforeEach(() =>
	{
		Cypress.Cookies.defaults(
		{
			preserve:
			[
				'PHPSESSID'
			]
		});
		cy.visit('http://localhost:8000/index.php?l=en&p=login');
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

	it('test login', () =>
	{
		cy.get('#user').clear().type('test');
		cy.get('#password').clear().type('aaAA00AAaa');

		cy.get('button.rs-button-submit').click();

		cy.get('ul.rs-admin-list-panel').should('be.visible');
		cy.url().should('eq', 'http://localhost:8000/?p=admin');
	});
});
