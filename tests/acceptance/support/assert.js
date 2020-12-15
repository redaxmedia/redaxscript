const LANGUAGE = require('../../../languages/en.json');

Cypress.Commands.add('shouldHaveText',
{
	prevSubject: 'element'
}, (subject, key) => cy.wrap(subject).should('have.text', LANGUAGE[key]));
