describe('installation', () =>
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
		cy.visit('http://localhost:8000/install.php?l=en');
	});

	before(() =>
	{
		cy.uninstallDatabase();
		cy.resetConfig();
	});

	after(() =>
	{
		cy.uninstallDatabase();
		cy.resetConfig();
	});

	it('title is present', () =>
	{
		cy.get('h2.rs-title-content')
			.should('be.visible')
			.shouldHaveText('installation');
	});

	it('test behaviour', () =>
	{
		cy.get('#db-type').should('be.visible');
		cy.get('#db-host').should('be.visible');
		cy.get('#db-prefix').should('be.visible');
		cy.get('#admin-name').should('be.not.visible');
		cy.get('#admin-user').should('be.not.visible');
		cy.get('#admin-password').should('be.not.visible');
		cy.get('#admin-email').should('be.not.visible');

		cy.get('[for*="Account"]').click();

		cy.get('#db-type').should('be.not.visible');
		cy.get('#db-host').should('be.not.visible');
		cy.get('#db-prefix').should('be.not.visible');
		cy.get('#admin-name').should('be.visible');
		cy.get('#admin-user').should('be.visible');
		cy.get('#admin-password').should('be.visible');
		cy.get('#admin-email').should('be.visible');
	});

	it('test empty database host', () =>
	{
		cy.get('#db-host').clear();

		cy.get('form.rs-form-install button.rs-button-submit').click();

		cy.get('#db-host').should('have.class', 'rs-is-error');
	});

	[
		{
			selector: '#admin-name',
			description: 'admin name'
		},
		{
			selector: '#admin-user',
			description: 'admin user'
		},
		{
			selector: '#admin-password',
			description: 'admin password'
		},
		{
			selector: '#admin-email',
			description: 'admin email'
		}
	]
	.map(test =>
	{
		it('test empty ' + test.description, () =>
		{
			cy.get('[for*="Account"]').click();

			cy.get(test.selector)
				.type('-')
				.clear()
				.should('have.class', 'rs-is-error');
		});

		it('test incorrect ' + test.description, () =>
		{
			cy.get('[for*="Account"]').click();

			cy.get(test.selector)
				.clear()
				.type('-')
				.should('have.class', 'rs-is-warning');
		});
	});

	it('test install', () =>
	{
		cy.get('#db-type').select('sqlite');
		cy.get('#db-host').clear().type('build/test.sqlite');
		cy.get('#db-prefix').clear().type('_test');

		cy.get('[for*="Account"]').click();

		cy.get('#admin-name').clear().type('Test');
		cy.get('#admin-user').clear().type('test');
		cy.get('#admin-password').clear().type('aaAA00AAaa');
		cy.get('#admin-email').clear().type('test@redaxscript.com');

		cy.get('form.rs-form-install button.rs-button-submit').click();

		cy.get('div.rs-box-note.rs-is-success')
			.should('be.visible')
			.shouldHaveText('installation_completed');
		cy.url().should('eq', 'http://localhost:8000/index.php');
	});
});
