const providerArray = require('../../acceptance-provider/ConsoleTest.json');

describe('ConsoleTest', () =>
{
	before(() =>
	{
		cy.setConfig();
		cy.uninstallDatabase();
		cy.installDatabase();
	});

	after(() =>
	{
		cy.uninstallDatabase();
		cy.resetConfig();
	});

	providerArray.map(test =>
	{
		it('run ' + test.description + ' command', () =>
		{
			cy.visit(test.url);
			cy.get('input.rs-field-text').clear().type(test.commandArray.run).type('{ENTER}');
			test.elementArray.map(element => cy.get(element.selector).should('contain.text', element.text));
		});
	});
});
