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
		cy.get('#db-host').type('build/test.sqlite');
		cy.get('#db-prefix').type('_test');

		cy.get('[for*="Account"]').click();

		cy.get('#admin-name').type('Test');
		cy.get('#admin-user').type('test');
		cy.get('#admin-password').type('aaAA00AAaa');
		cy.get('#admin-email').type('test@redaxscript.com');

		cy.get('button').click();

		cy.get('div.rs-box-note.rs-is-success')
			.should('be.visible')
			.should('have.text', LANGUAGE.installation_completed);
		cy.url().should('eq', 'http://localhost:8000/index.php');
	});
});
