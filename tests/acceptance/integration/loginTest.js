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

	it('setup', () =>
	{
		cy.setConfig();
		cy.uninstallDatabase();
		cy.installDatabase();
	});

	it('test login', () =>
	{
		cy.get('#user').type('test');
		cy.get('#password').type('aaAA00AAaa');

		cy.get('button.rs-button-submit').click();

		cy.get('div.rs-box-note.rs-is-error').should('be.not.visible');
		cy.url().should('eq', 'http://localhost:8000/?p=admin');
	});
});
