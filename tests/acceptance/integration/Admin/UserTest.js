const providerArray = require('../../../acceptance-provider/Admin/UserTest.json');

describe('Admin/UserTest', () =>
{
	before(() =>
	{
		cy.setConfig();
		cy.uninstallDatabase();
		cy.installDatabase();
	});

	beforeEach(() =>
	{
		cy.login();
	});

	after(() =>
	{
		cy.uninstallDatabase();
		cy.resetConfig();
	});

	afterEach(() =>
	{
		cy.logout();
	});

	context('general', () =>
	{
		providerArray.map(test =>
		{
			it('visit ' + test.description + ' page', () =>
			{
				cy.visit(test.url);
				test.elementArray.map(element => cy.get(element.selector)
					.should('be.visible')
					.should('have.text', element.text));
			});
		});
	});

	context('validation', () =>
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
				cy.visit('http://localhost:8000/?p=admin/new/users');
				cy.get(test.selector)
					.type('-')
					.clear()
					.should('have.class', 'rs-admin-field-note', 'rs-admin-is-error');
			});

			it('incorrect field ' + test.description + ' has warning', () =>
			{
				cy.visit('http://localhost:8000/?p=admin/new/users');
				cy.get(test.selector)
					.clear()
					.type('-')
					.should('have.class', 'rs-admin-field-note', 'rs-admin-is-warning');
			});
		});
	});

	context('behaviour', () =>
	{
		it('toggle content of tab', () =>
		{
			cy.visit('http://localhost:8000/?p=admin/new/users');
			cy.get('#name').should('be.visible');
			cy.get('#user').should('be.visible');
			cy.get('#description').should('be.visible');
			cy.get('#password').should('be.visible');
			cy.get('#email').should('be.visible');
			cy.get('#language').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.not.visible');
			cy.get('#groups').should('be.not.visible');

			cy.get('label.rs-admin-label-tab[for*="General"]').click();

			cy.get('#name').should('be.not.visible');
			cy.get('#user').should('be.not.visible');
			cy.get('#description').should('be.not.visible');
			cy.get('#password').should('be.not.visible');
			cy.get('#email').should('be.not.visible');
			cy.get('#language').should('be.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.not.visible');
			cy.get('#groups').should('be.not.visible');

			cy.get('label.rs-admin-label-tab[for*="Customize"]').click();

			cy.get('#name').should('be.not.visible');
			cy.get('#user').should('be.not.visible');
			cy.get('#description').should('be.not.visible');
			cy.get('#password').should('be.not.visible');
			cy.get('#email').should('be.not.visible');
			cy.get('#language').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.visible');
			cy.get('#groups').should('be.visible');
		});

		it('create action has success', () =>
		{
			cy.visit('http://localhost:8000/?l=en&p=admin/new/users');
			cy.get('#name').clear().type('User Two');
			cy.get('#user').clear().type('user-two');
			cy.get('#password').clear().type('aaAA00AAaa');
			cy.get('#email').clear().type('test@redaxscript.com');

			cy.get('form.rs-admin-form-user button.rs-admin-button-create').click();

			cy.get('div.rs-admin-box-note.rs-admin-is-success')
				.should('be.visible')
				.shouldHaveText('operation_completed');
			cy.url().should('eq', 'http://localhost:8000/?p=admin/view/users#row-2');
			cy.get('#row-2')
				.should('be.visible')
				.should('contain.text', 'User Two');
		});
	});
});
