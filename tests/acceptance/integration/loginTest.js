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
		cy.visit('http://localhost:8000/?l=en&p=login');
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

	[
		{
			selector: '#user',
			description: 'user'
		},
		{
			selector: '#password',
			description: 'password'
		}
	]
	.map(test =>
	{
		it('test empty ' + test.description, () =>
		{
			cy.get(test.selector)
				.type('-')
				.clear()
				.should('have.class', 'rs-is-error');
		});

		it('test incorrect ' + test.description, () =>
		{
			cy.get(test.selector)
				.clear()
				.type('-')
				.should('have.class', 'rs-is-warning');
		});
	});

	it('test incorrect login via user', () =>
	{
		cy.get('#user').clear().type('invalid');
		cy.get('#password').clear().type('aaAA00AAaa');

		cy.get('form.rs-form-login button.rs-button-submit').click();

		cy.get('div.rs-box-note.rs-is-error')
			.should('be.visible')
			.shouldHaveText('login_incorrect');
		cy.url().should('eq', 'http://localhost:8000/?l=en&p=login');
	});

	it('test incorrect login via password', () =>
	{
		cy.get('#user').clear().type('test');
		cy.get('#password').clear().type('bbBB00BBbb');

		cy.get('form.rs-form-login button.rs-button-submit').click();

		cy.get('div.rs-box-note.rs-is-error')
			.should('be.visible')
			.shouldHaveText('login_incorrect');
		cy.url().should('eq', 'http://localhost:8000/?l=en&p=login');
	});

	it('test login', () =>
	{
		cy.get('#user').clear().type('test');
		cy.get('#password').clear().type('aaAA00AAaa');

		cy.get('form.rs-form-login button.rs-button-submit').click();

		cy.get('ul.rs-admin-list-panel').should('be.visible');
		cy.get('div.rs-admin-box-dock').should('be.visible');
		cy.url().should('eq', 'http://localhost:8000/?p=admin');
	});
});
