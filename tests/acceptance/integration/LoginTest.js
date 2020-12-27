describe('LoginTest', () =>
{
	beforeEach(() =>
	{
		Cypress.Cookies.preserveOnce('PHPSESSID');
		cy.visit('http://localhost:8000/?l=en&p=login');
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

	context('general', () =>
	{
		it('breadcrumb item should have text', () =>
		{
			cy.get('ul.rs-list-breadcrumb li')
				.should('be.visible')
				.shouldHaveText('login');
		});

		it('content title should have text', () =>
		{
			cy.get('h2.rs-title-content')
				.should('be.visible')
				.shouldHaveText('login');
		});
	});

	context('validation', () =>
	{
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
			it('empty field ' + test.description + ' has error', () =>
			{
				cy.get(test.selector)
					.type('-')
					.clear()
					.should('have.class', 'rs-field-note', 'rs-is-error');
			});

			it('incorrect field ' + test.description + ' has warning', () =>
			{
				cy.get(test.selector)
					.clear()
					.type('-')
					.should('have.class', 'rs-field-note', 'rs-is-warning');
			});
		});
	});

	context('behaviour', () =>
	{
		it('login action has error as user is incorrect', () =>
		{
			cy.get('#user').clear().type('invalid');
			cy.get('#password').clear().type('aaAA00AAaa');

			cy.get('form.rs-form-login button.rs-button-submit').click();

			cy.get('div.rs-box-note.rs-is-error')
				.should('be.visible')
				.shouldHaveText('login_incorrect');
			cy.url().should('eq', 'http://localhost:8000/?l=en&p=login');
		});

		it('login action has error as password is incorrect', () =>
		{
			cy.get('#user').clear().type('test');
			cy.get('#password').clear().type('bbBB00BBbb');

			cy.get('form.rs-form-login button.rs-button-submit').click();

			cy.get('div.rs-box-note.rs-is-error')
				.should('be.visible')
				.shouldHaveText('login_incorrect');
			cy.url().should('eq', 'http://localhost:8000/?l=en&p=login');
		});

		it('login action has success', () =>
		{
			cy.get('#user').clear().type('test');
			cy.get('#password').clear().type('aaAA00AAaa');

			cy.get('form.rs-form-login button.rs-button-submit').click();

			cy.get('ul.rs-admin-list-panel').should('be.visible');
			cy.get('div.rs-admin-box-dock').should('be.visible');
			cy.url().should('eq', 'http://localhost:8000/?p=admin');
		});

		it('logout action has success', () =>
		{
			cy.get('ul.rs-admin-list-panel a.rs-admin-link-panel-logout').click();

			cy.get('ul.rs-admin-list-panel').should('not.exist');
			cy.get('div.rs-admin-box-dock').should('not.exist');
			cy.url().should('eq', 'http://localhost:8000/?p=login');
		});
	});
});
