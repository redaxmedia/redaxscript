const providerArray = require('../../../acceptance-provider/Admin/GroupTest.json');

describe('Admin/GroupTest', () =>
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
				selector: '#alias',
				description: 'alias'
			}
		]
		.map(test =>
		{
			it('empty field ' + test.description + ' has error', () =>
			{
				cy.visit('http://localhost:8000/?p=admin/new/groups');
				cy.get(test.selector)
					.type('-')
					.clear()
					.should('have.class', 'rs-admin-field-note', 'rs-admin-is-error');
			});

			it('incorrect field ' + test.description + ' has warning', () =>
			{
				cy.visit('http://localhost:8000/?p=admin/new/groups');
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
			cy.visit('http://localhost:8000/?p=admin/new/groups');
			cy.get('#name').should('be.visible');
			cy.get('#alias').should('be.visible');
			cy.get('#description').should('be.visible');
			cy.get('#categories').should('be.not.visible');
			cy.get('#articles').should('be.not.visible');
			cy.get('#extras').should('be.not.visible');
			cy.get('#comments').should('be.not.visible');
			cy.get('#groups').should('be.not.visible');
			cy.get('#users').should('be.not.visible');
			cy.get('#modules').should('be.not.visible');
			cy.get('#settings').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="filter"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.not.visible');

			cy.get('label.rs-admin-label-tab[for*="Access"]').click();

			cy.get('#name').should('be.not.visible');
			cy.get('#alias').should('be.not.visible');
			cy.get('#description').should('be.not.visible');
			cy.get('#categories').should('be.visible');
			cy.get('#articles').should('be.visible');
			cy.get('#extras').should('be.visible');
			cy.get('#comments').should('be.visible');
			cy.get('#groups').should('be.visible');
			cy.get('#users').should('be.visible');
			cy.get('#modules').should('be.visible');
			cy.get('#settings').should('be.visible');
			cy.get('label.rs-admin-label-switch[for="filter"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.not.visible');

			cy.get('label.rs-admin-label-tab[for*="Customize"]').click();

			cy.get('#name').should('be.not.visible');
			cy.get('#alias').should('be.not.visible');
			cy.get('#description').should('be.not.visible');
			cy.get('#categories').should('be.not.visible');
			cy.get('#articles').should('be.not.visible');
			cy.get('#extras').should('be.not.visible');
			cy.get('#comments').should('be.not.visible');
			cy.get('#groups').should('be.not.visible');
			cy.get('#users').should('be.not.visible');
			cy.get('#modules').should('be.not.visible');
			cy.get('#settings').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="filter"]').should('be.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.visible');
		});

		it('create action has success', () =>
		{
			cy.visit('http://localhost:8000/?l=en&p=admin/new/groups');
			cy.get('#name').clear().type('Group Three');
			cy.get('#alias').should('have.value', 'group-three');

			cy.get('form.rs-admin-form-group button.rs-admin-button-create').click();

			cy.get('div.rs-admin-box-note.rs-admin-is-success')
				.should('be.visible')
				.shouldHaveText('operation_completed');
			cy.url().should('eq', 'http://localhost:8000/?p=admin/view/groups#row-3');
			cy.get('#row-3')
				.should('be.visible')
				.should('contain.text', 'Group Three');
		});

		it('edit action has success', () =>
		{
			cy.visit('http://localhost:8000/?l=en&p=admin/edit/groups/3');
			cy.get('#name').clear().type('Group Three Mutation');
			cy.get('#alias').should('have.value', 'group-three-mutation');

			cy.get('form.rs-admin-form-group button.rs-admin-button-save').click();

			cy.get('div.rs-admin-box-note.rs-admin-is-success')
				.should('be.visible')
				.shouldHaveText('operation_completed');
			cy.url().should('eq', 'http://localhost:8000/?p=admin/view/groups#row-3');
			cy.get('#row-3')
				.should('be.visible')
				.should('contain.text', 'Group Three Mutation');
		});

		it('delete action has success', () =>
		{
			cy.visit('http://localhost:8000/?l=en&p=admin/edit/groups/3');

			cy.get('form.rs-admin-form-group a.rs-admin-button-delete').click();
			cy.get('div.rs-admin-component-dialog button.rs-admin-button-ok').click();

			cy.get('div.rs-admin-box-note.rs-admin-is-success')
				.should('be.visible')
				.shouldHaveText('operation_completed');
			cy.url().should('eq', 'http://localhost:8000/?p=admin/view/groups');
			cy.get('#row-3').should('not.exist');
		});
	});
});
