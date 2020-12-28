const providerArray = require('../../../acceptance-provider/Admin/CommentTest.json');

describe('Admin/CommentTest', () =>
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
				selector: 'div.rs-admin-box-visual-editor',
				description: 'visual editor'
			}
		]
		.map(test =>
		{
			it('empty box ' + test.description + ' has error', () =>
			{
				cy.visit('http://localhost:8000/?p=admin/new/comments');
				cy.get(test.selector)
					.type('-')
					.clear()
					.should('have.class', 'rs-admin-field-note', 'rs-admin-is-error');
			});
		});
	});

	context('behaviour', () =>
	{
		it('toggle content of tab', () =>
		{
			cy.visit('http://localhost:8000/?p=admin/new/comments');
			cy.get('#url').should('be.visible');
			cy.get('div.rs-admin-box-visual-editor').should('be.visible');
			cy.get('#language').should('be.not.visible');
			cy.get('#article').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.not.visible');
			cy.get('#rank').should('be.not.visible');
			cy.get('#access').should('be.not.visible');
			cy.get('#date').should('be.not.visible');

			cy.get('label.rs-admin-label-tab[for*="General"]').click();

			cy.get('#url').should('be.not.visible');
			cy.get('div.rs-admin-box-visual-editor').should('be.not.visible');
			cy.get('#language').should('be.visible');
			cy.get('#article').should('be.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.not.visible');
			cy.get('#rank').should('be.not.visible');
			cy.get('#access').should('be.not.visible');
			cy.get('#date').should('be.not.visible');

			cy.get('label.rs-admin-label-tab[for*="Customize"]').click();

			cy.get('#url').should('be.not.visible');
			cy.get('div.rs-admin-box-visual-editor').should('be.not.visible');
			cy.get('#language').should('be.not.visible');
			cy.get('#article').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="status"]').should('be.visible');
			cy.get('#rank').should('be.visible');
			cy.get('#access').should('be.visible');
			cy.get('#date').should('be.visible');
		});
	});
});
