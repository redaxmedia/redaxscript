const providerArray = require('../../../acceptance-provider/Admin/ModuleTest.json');

describe('Admin/ModuleTest', () =>
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
			}
		]
		.map(test =>
		{
			it('empty field ' + test.description + ' has error', () =>
			{
				cy.visit('http://localhost:8000/?p=admin/edit/modules/1');
				cy.get(test.selector)
					.type('-')
					.clear()
					.should('have.class', 'rs-admin-field-note', 'rs-admin-is-error');
			});

			it('incorrect field ' + test.description + ' has warning', () =>
			{
				cy.visit('http://localhost:8000/?p=admin/edit/modules/1');
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
			cy.visit('http://localhost:8000/?p=admin/edit/modules/1');
			cy.get('#name').should('be.visible');
			cy.get('#description').should('be.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.not.visible');
			cy.get('#access').should('be.not.visible');

			cy.get('label.rs-admin-label-tab[for*="Customize"]').click();

			cy.get('#name').should('be.not.visible');
			cy.get('#description').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.visible');
			cy.get('#access').should('be.visible');
		});

		it('install action has success', () =>
		{
			cy.visit('http://localhost:8000/?l=en&p=admin/view/modules');

			cy.get('ul.rs-admin-list-control a[href*="install/modules/TestDummy"').click();

			cy.url().should('eq', 'http://localhost:8000/?p=admin/view/modules#row-12');
			cy.get('#row-12')
				.should('be.visible')
				.should('contain.text', 'Test Dummy');
		});

		it('uninstall action has success', () =>
		{
			cy.visit('http://localhost:8000/?l=en&p=admin/view/modules');

			cy.get('ul.rs-admin-list-control a[href*="uninstall/modules/TestDummy"').click();
			cy.get('div.rs-admin-component-dialog button.rs-admin-button-ok').click();

			cy.url().should('eq', 'http://localhost:8000/?p=admin/view/modules');
			cy.get('#row-12').should('not.exist');
		});
	});
});
