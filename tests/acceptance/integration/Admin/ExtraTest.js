const providerArray = require('../../../acceptance-provider/Admin/ExtraTest.json');

describe('Admin/ExtraTest', () =>
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
				cy.visit('http://localhost:8000/?p=admin/new/extras');
				cy.get(test.selector)
					.type('-')
					.clear()
					.should('have.class', 'rs-admin-field-note', 'rs-admin-is-error');
			});

			it('incorrect field ' + test.description + ' has warning', () =>
			{
				cy.visit('http://localhost:8000/?p=admin/new/extras');
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
			cy.visit('http://localhost:8000/?p=admin/new/extras');
			cy.get('#title').should('be.visible');
			cy.get('#alias').should('be.visible');
			cy.get('div.rs-admin-box-visual-editor').should('be.visible');
			cy.get('#language').should('be.not.visible');
			cy.get('#sibling').should('be.not.visible');
			cy.get('#category').should('be.not.visible');
			cy.get('#article').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="headline"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.not.visible');
			cy.get('#rank').should('be.not.visible');
			cy.get('#access').should('be.not.visible');
			cy.get('#date').should('be.not.visible');

			cy.get('label.rs-admin-label-tab[for*="General"]').click();

			cy.get('#title').should('be.not.visible');
			cy.get('#alias').should('be.not.visible');
			cy.get('div.rs-admin-box-visual-editor').should('be.not.visible');
			cy.get('#language').should('be.visible');
			cy.get('#sibling').should('be.visible');
			cy.get('#category').should('be.visible');
			cy.get('#article').should('be.visible');
			cy.get('label.rs-admin-label-switch[for="headline"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.not.visible');
			cy.get('#rank').should('be.not.visible');
			cy.get('#access').should('be.not.visible');
			cy.get('#date').should('be.not.visible');

			cy.get('label.rs-admin-label-tab[for*="Customize"]').click();

			cy.get('#title').should('be.not.visible');
			cy.get('#alias').should('be.not.visible');
			cy.get('div.rs-admin-box-visual-editor').should('be.not.visible');
			cy.get('#language').should('be.not.visible');
			cy.get('#sibling').should('be.not.visible');
			cy.get('#category').should('be.not.visible');
			cy.get('#article').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="headline"]').should('be.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.visible');
			cy.get('#rank').should('be.visible');
			cy.get('#access').should('be.visible');
			cy.get('#date').should('be.visible');
		});

		it('create action has success', () =>
		{
			cy.visit('http://localhost:8000/?l=en&p=admin/new/extras');
			cy.get('#title').clear().type('Extra Seven');
			cy.get('#alias').should('have.value', 'extra-seven');
			cy.get('div.rs-admin-box-visual-editor').clear().type('Extra Seven');

			cy.get('form.rs-admin-form-extra button.rs-admin-button-create').click();

			cy.get('div.rs-admin-box-note.rs-admin-is-success')
				.should('be.visible')
				.shouldHaveText('operation_completed');
			cy.url().should('eq', 'http://localhost:8000/?p=admin/view/extras#row-7');
			cy.get('#row-7')
				.should('be.visible')
				.should('contain.text', 'Extra Seven');
		});

		it('edit action has success', () =>
		{
			cy.visit('http://localhost:8000/?l=en&p=admin/edit/extras/7');
			cy.get('#title').clear().type('Extra Seven Mutation');
			cy.get('#alias').should('have.value', 'extra-seven-mutation');
			cy.get('div.rs-admin-box-visual-editor').clear().type('Extra Seven Mutation');

			cy.get('form.rs-admin-form-extra button.rs-admin-button-save').click();

			cy.get('div.rs-admin-box-note.rs-admin-is-success')
				.should('be.visible')
				.shouldHaveText('operation_completed');
			cy.url().should('eq', 'http://localhost:8000/?p=admin/view/extras#row-7');
			cy.get('#row-7')
				.should('be.visible')
				.should('contain.text', 'Extra Seven Mutation');
		});

		it('delete action has success', () =>
		{
			cy.visit('http://localhost:8000/?l=en&p=admin/edit/extras/2');

			cy.get('form.rs-admin-form-extra a.rs-admin-button-delete').click();
			cy.get('div.rs-admin-component-dialog button.rs-admin-button-ok').click();

			cy.get('div.rs-admin-box-note.rs-admin-is-success')
				.should('be.visible')
				.shouldHaveText('operation_completed');
			cy.url().should('eq', 'http://localhost:8000/?p=admin/view/extras');
			cy.get('#row-2').should('not.exist');
		});
	});
});
