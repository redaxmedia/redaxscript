const LANGUAGE = require('../../../languages/en.json');

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
			.should('have.text', LANGUAGE.installation);
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

		cy.get('button.rs-button-submit').click();

		cy.get('div.rs-box-note.rs-is-success')
			.should('be.visible')
			.should('have.text', LANGUAGE.installation_completed);
		cy.url(
		{
			timeout: 3000
		}).should('eq', 'http://localhost:8000/index.php');
	});
});
