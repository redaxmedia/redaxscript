const LANGUAGE = require('../../../languages/en.json');

Cypress.Commands.add('shouldHaveText',
{
	prevSubject: 'element'
}, (subject, key) => cy.wrap(subject).should('have.text', LANGUAGE[key]));

Cypress.Commands.add('shouldContainText',
{
	prevSubject: 'element'
}, (subject, key) => cy.wrap(subject).should('contain.text', LANGUAGE[key]));
