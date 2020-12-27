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
				test.elementArray.map(element => cy.get(element.selector).should('have.text', element.text));
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
				cy.visit('http://localhost:8000?p=admin/new/comments');
				cy.get(test.selector)
					.type('-')
					.clear()
					.should('have.class', 'rs-admin-field-note', 'rs-admin-is-error');
			});
		});
	});
});
