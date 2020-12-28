describe('CommentTest', () =>
{
	before(() =>
	{
		cy.setConfig();
		cy.uninstallDatabase();
		cy.installDatabase();
	});

	beforeEach(() =>
	{
		Cypress.Cookies.preserveOnce('PHPSESSID');
		cy.visit('http://localhost:8000/?l=en&p=home/welcome');
	});

	after(() =>
	{
		cy.uninstallDatabase();
		cy.resetConfig();
	});

	context('validation', () =>
	{
		[
			{
				selector: '#author',
				description: 'author'
			},
			{
				selector: '#email',
				description: 'email'
			}
		]
		.map(test =>
		{
			it('empty field ' + test.description + ' has error', () =>
			{
				cy.get(test.selector)
					.type('-')
					.clear()
					.should('have.class', 'rs-field-note', 'rs-is-error');
			});

			it('incorrect field ' + test.description + ' has warning', () =>
			{
				cy.get(test.selector)
					.clear()
					.type('-')
					.should('have.class', 'rs-field-note', 'rs-is-warning');
			});
		});


		[
			{
				selector: '#url',
				description: 'url'
			}
		]
		.map(test =>
		{
			it('incorrect field ' + test.description + ' has warning', () =>
			{
				cy.get(test.selector)
					.clear()
					.type('-')
					.should('have.class', 'rs-field-note', 'rs-is-warning');
			});
		});

		[
			{
				selector: 'div.rs-box-visual-editor',
				description: 'visual editor'
			}
		]
		.map(test =>
		{
			it('empty box ' + test.description + ' has error', () =>
			{
				cy.get(test.selector)
					.type('-')
					.clear()
					.should('have.class', 'rs-field-note', 'rs-is-error');
			});
		});
	});

	context('behaviour', () =>
	{
		it('comment action has success', () =>
		{
			cy.get('#author').clear().type('Test');
			cy.get('#email').clear().type('test@redaxmedia.com');
			cy.get('div.rs-box-visual-editor').clear().type('Justo duo dolores et ea rebum.');

			cy.get('form.rs-form-comment button.rs-button-submit').click();

			cy.get('div.rs-box-note.rs-is-success')
				.should('be.visible')
				.shouldHaveText('comment_sent');
			cy.url().should('eq', 'http://localhost:8000/?p=home/welcome#comment-2');
			cy.get('h3.rs-title-comment')
				.eq(0)
				.should('have.text', 'Test');
			cy.get('div.rs-box-comment')
				.eq(0)
				.should('have.text', 'Justo duo dolores et ea rebum.');
		});
	});
});
