describe('RegisterTest', () =>
{
	beforeEach(() =>
	{
		Cypress.Cookies.preserveOnce('PHPSESSID');
		cy.visit('http://localhost:8000/?l=en&p=register');
	});

	before(() =>
	{
		cy.setConfig();
		cy.uninstallDatabase();
		cy.installDatabase();
		cy.setSetting('registration', 1);
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
				.shouldHaveText('registration');
		});

		it('content title should have text', () =>
		{
			cy.get('h2.rs-title-content')
				.should('be.visible')
				.shouldHaveText('account_create');
		});
	});

	context('interaction', () =>
	{
		[
			{
				selector: '#name',
				description: 'name'
			},
			{
				selector: '#user',
				description: 'user'
			},
			{
				selector: '#password',
				description: 'password'
			},
			{
				selector: '#email',
				description: 'email'
			}
		]
		.map(test =>
		{
			it('empty field ' + test.description + ' has error', () =>
			{
				cy.get(test.selector)
					.type('-')
					.clear()
					.should('have.class', 'rs-is-error');
			});

			it('incorrect field ' + test.description + ' has warning', () =>
			{
				cy.get(test.selector)
					.clear()
					.type('-')
					.should('have.class', 'rs-is-warning');
			});
		});
	});

	context('interaction', () =>
	{
		it('register action has error as user already exists', () =>
		{
			cy.get('#name').clear().type('Test');
			cy.get('#user').clear().type('test');
			cy.get('#password').clear().type('aaAA00AAaa');
			cy.get('#email').clear().type('test@redaxscript.com');

			cy.get('form.rs-form-register button.rs-button-submit').click();

			cy.get('div.rs-box-note.rs-is-error')
				.should('be.visible')
				.shouldHaveText('user_exists');
		});

		it('register action has success', () =>
		{
			cy.get('#name').clear().type('User Two');
			cy.get('#user').clear().type('user-two');
			cy.get('#password').clear().type('aaAA00AAaa');
			cy.get('#email').clear().type('test@redaxscript.com');

			cy.get('form.rs-form-register button.rs-button-submit').click();

			cy.get('div.rs-box-note.rs-is-success')
				.should('be.visible')
				.shouldHaveText('registration_completed');
			cy.url().should('eq', 'http://localhost:8000/?p=login');
		});
	});
});
