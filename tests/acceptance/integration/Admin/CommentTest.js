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
		//cy.uninstallDatabase();
		//cy.resetConfig();
	});

	afterEach(() =>
	{
		cy.logout();
	});

	providerArray.map(test =>
	{
		it('visit ' + test.description + ' page', () =>
		{
			cy.visit(test.url);
			test.elementArray.map(element => cy.get(element.selector).should('have.text', element.text));
		});
	});
});
