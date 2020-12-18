describe('recover', () =>
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
		cy.visit('http://localhost:8000/?l=en&p=login/recover');
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
				.shouldHaveText('recovery');
		});

		it('content title should have text', () =>
		{
			cy.get('h2.rs-title-content')
				.should('be.visible')
				.shouldHaveText('recovery');
		});
	});

	context('validation', () =>
	{
		[
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
		it('recover action has error as email is unknown', () =>
		{
			cy.get('#email').clear().type('unknown@redaxscript.com');

			cy.get('form.rs-form-recover button.rs-button-submit').click();

			cy.get('div.rs-box-note.rs-is-error')
				.should('be.visible')
				.shouldHaveText('email_unknown');
		});

		it('recover action has success', () =>
		{
			cy.get('#email').clear().type('test@redaxscript.com');

			cy.get('form.rs-form-recover button.rs-button-submit').click();

			cy.get('div.rs-box-note.rs-is-success')
				.should('be.visible')
				.shouldContainText('recovery_sent');
			cy.url().should('eq', 'http://localhost:8000/?p=login');
		});
	});
});
