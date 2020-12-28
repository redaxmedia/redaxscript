const providerArray = require('../../../acceptance-provider/Admin/CategoryTest.json');

describe('Admin/CategoryTest', () =>
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
				selector: '#title',
				description: 'title'
			},
			{
				selector: '#alias',
				description: 'alias'
			}
		]
		.map(test =>
		{
			it('empty field ' + test.description + ' has error', () =>
			{
				cy.visit('http://localhost:8000/?p=admin/new/categories');
				cy.get(test.selector)
					.type('-')
					.clear()
					.should('have.class', 'rs-admin-field-note', 'rs-admin-is-error');
			});

			it('incorrect field ' + test.description + ' has warning', () =>
			{
				cy.visit('http://localhost:8000/?p=admin/new/categories');
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
			cy.visit('http://localhost:8000/?p=admin/new/categories');
			cy.get('#title').should('be.visible');
			cy.get('#alias').should('be.visible');
			cy.get('#description').should('be.visible');
			cy.get('#keywords').should('be.visible');
			cy.get('#robots').should('be.visible');
			cy.get('#language').should('be.not.visible');
			cy.get('#template').should('be.not.visible');
			cy.get('#sibling').should('be.not.visible');
			cy.get('#parent').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.not.visible');
			cy.get('#rank').should('be.not.visible');
			cy.get('#access').should('be.not.visible');
			cy.get('#date').should('be.not.visible');

			cy.get('label.rs-admin-label-tab[for*="General"]').click();

			cy.get('#title').should('be.not.visible');
			cy.get('#alias').should('be.not.visible');
			cy.get('#description').should('be.not.visible');
			cy.get('#keywords').should('be.not.visible');
			cy.get('#robots').should('be.not.visible');
			cy.get('#language').should('be.visible');
			cy.get('#template').should('be.visible');
			cy.get('#sibling').should('be.visible');
			cy.get('#parent').should('be.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.not.visible');
			cy.get('#rank').should('be.not.visible');
			cy.get('#access').should('be.not.visible');
			cy.get('#date').should('be.not.visible');

			cy.get('label.rs-admin-label-tab[for*="Customize"]').click();

			cy.get('#title').should('be.not.visible');
			cy.get('#alias').should('be.not.visible');
			cy.get('#description').should('be.not.visible');
			cy.get('#keywords').should('be.not.visible');
			cy.get('#robots').should('be.not.visible');
			cy.get('#language').should('be.not.visible');
			cy.get('#template').should('be.not.visible');
			cy.get('#sibling').should('be.not.visible');
			cy.get('#parent').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.visible');
			cy.get('#rank').should('be.visible');
			cy.get('#access').should('be.visible');
			cy.get('#date').should('be.visible');
		});

		it('create action has success', () =>
		{
			cy.visit('http://localhost:8000/?l=en&p=admin/new/categories');
			cy.get('#title').clear().type('Category Two');
			cy.get('#alias').should('have.value', 'category-two');

			cy.get('form.rs-admin-form-category button.rs-admin-button-create').click();

			cy.get('div.rs-admin-box-note.rs-admin-is-success')
				.should('be.visible')
				.shouldHaveText('operation_completed');
			cy.url().should('eq', 'http://localhost:8000/?p=admin/view/categories#row-2');
			cy.get('#row-2 a')
				.eq(0)
				.should('be.visible')
				.should('have.text', 'Category Two');
		});
	});
});
