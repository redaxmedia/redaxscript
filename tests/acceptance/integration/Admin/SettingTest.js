const providerArray = require('../../../acceptance-provider/Admin/SettingTest.json');

describe('Admin/SettingTest', () =>
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

	context('behaviour', () =>
	{
		it('toggle content of accordion', () =>
		{
			cy.visit('http://localhost:8000/?p=admin/edit/settings');
			cy.get('#language').should('be.visible');
			cy.get('#template').should('be.visible');
			cy.get('#title').should('be.not.visible');
			cy.get('#author').should('be.not.visible');
			cy.get('#copyright').should('be.not.visible');
			cy.get('#description').should('be.not.visible');
			cy.get('#keywords').should('be.not.visible');
			cy.get('#robots').should('be.not.visible');
			cy.get('#email').should('be.not.visible');
			cy.get('#smtp').should('be.not.visible');
			cy.get('#subject').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="notification"]').should('be.not.visible');
			cy.get('#charset').should('be.not.visible');
			cy.get('#locale').should('be.not.visible');
			cy.get('#divider').should('be.not.visible');
			cy.get('#zone').should('be.not.visible');
			cy.get('#time').should('be.not.visible');
			cy.get('#date').should('be.not.visible');
			cy.get('#homepage').should('be.not.visible');
			cy.get('#limit').should('be.not.visible');
			cy.get('#order').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="pagination"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="registration"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="verification"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="recovery"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="moderation"]').should('be.not.visible');
			cy.get('#captcha').should('be.not.visible');

			cy.get('label.rs-admin-label-accordion[for*="Metadata"]').click();

			cy.get('#language').should('be.not.visible');
			cy.get('#template').should('be.not.visible');
			cy.get('#title').should('be.visible');
			cy.get('#author').should('be.visible');
			cy.get('#copyright').should('be.visible');
			cy.get('#description').should('be.visible');
			cy.get('#keywords').should('be.visible');
			cy.get('#robots').should('be.visible');
			cy.get('#email').should('be.not.visible');
			cy.get('#smtp').should('be.not.visible');
			cy.get('#subject').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="notification"]').should('be.not.visible');
			cy.get('#charset').should('be.not.visible');
			cy.get('#locale').should('be.not.visible');
			cy.get('#divider').should('be.not.visible');
			cy.get('#zone').should('be.not.visible');
			cy.get('#time').should('be.not.visible');
			cy.get('#date').should('be.not.visible');
			cy.get('#homepage').should('be.not.visible');
			cy.get('#limit').should('be.not.visible');
			cy.get('#order').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="pagination"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="registration"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="verification"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="recovery"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="moderation"]').should('be.not.visible');
			cy.get('#captcha').should('be.not.visible');

			cy.get('label.rs-admin-label-accordion[for*="Contact"]').click();

			cy.get('#language').should('be.not.visible');
			cy.get('#template').should('be.not.visible');
			cy.get('#title').should('be.not.visible');
			cy.get('#author').should('be.not.visible');
			cy.get('#copyright').should('be.not.visible');
			cy.get('#description').should('be.not.visible');
			cy.get('#keywords').should('be.not.visible');
			cy.get('#robots').should('be.not.visible');
			cy.get('#email').should('be.visible');
			cy.get('#smtp').should('be.visible');
			cy.get('#subject').should('be.visible');
			cy.get('label.rs-admin-label-switch[for="notification"]').should('be.visible');
			cy.get('#charset').should('be.not.visible');
			cy.get('#locale').should('be.not.visible');
			cy.get('#divider').should('be.not.visible');
			cy.get('#zone').should('be.not.visible');
			cy.get('#time').should('be.not.visible');
			cy.get('#date').should('be.not.visible');
			cy.get('#homepage').should('be.not.visible');
			cy.get('#limit').should('be.not.visible');
			cy.get('#order').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="pagination"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="registration"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="verification"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="recovery"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="moderation"]').should('be.not.visible');
			cy.get('#captcha').should('be.not.visible');

			cy.get('label.rs-admin-label-accordion[for*="Formatting"]').click();

			cy.get('#language').should('be.not.visible');
			cy.get('#template').should('be.not.visible');
			cy.get('#title').should('be.not.visible');
			cy.get('#author').should('be.not.visible');
			cy.get('#copyright').should('be.not.visible');
			cy.get('#description').should('be.not.visible');
			cy.get('#keywords').should('be.not.visible');
			cy.get('#robots').should('be.not.visible');
			cy.get('#email').should('be.not.visible');
			cy.get('#smtp').should('be.not.visible');
			cy.get('#subject').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="notification"]').should('be.not.visible');
			cy.get('#charset').should('be.visible');
			cy.get('#locale').should('be.visible');
			cy.get('#divider').should('be.visible');
			cy.get('#zone').should('be.visible');
			cy.get('#time').should('be.visible');
			cy.get('#date').should('be.visible');
			cy.get('#homepage').should('be.not.visible');
			cy.get('#limit').should('be.not.visible');
			cy.get('#order').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="pagination"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="registration"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="verification"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="recovery"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="moderation"]').should('be.not.visible');
			cy.get('#captcha').should('be.not.visible');

			cy.get('label.rs-admin-label-accordion[for*="Contents"]').click();

			cy.get('#language').should('be.not.visible');
			cy.get('#template').should('be.not.visible');
			cy.get('#title').should('be.not.visible');
			cy.get('#author').should('be.not.visible');
			cy.get('#copyright').should('be.not.visible');
			cy.get('#description').should('be.not.visible');
			cy.get('#keywords').should('be.not.visible');
			cy.get('#robots').should('be.not.visible');
			cy.get('#email').should('be.not.visible');
			cy.get('#smtp').should('be.not.visible');
			cy.get('#subject').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="notification"]').should('be.not.visible');
			cy.get('#charset').should('be.not.visible');
			cy.get('#locale').should('be.not.visible');
			cy.get('#divider').should('be.not.visible');
			cy.get('#zone').should('be.not.visible');
			cy.get('#time').should('be.not.visible');
			cy.get('#date').should('be.not.visible');
			cy.get('#homepage').should('be.visible');
			cy.get('#limit').should('be.visible');
			cy.get('#order').should('be.visible');
			cy.get('label.rs-admin-label-switch[for="pagination"]').should('be.visible');
			cy.get('label.rs-admin-label-switch[for="registration"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="verification"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="recovery"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="moderation"]').should('be.not.visible');
			cy.get('#captcha').should('be.not.visible');

			cy.get('label.rs-admin-label-accordion[for*="Users"]').click();

			cy.get('#language').should('be.not.visible');
			cy.get('#template').should('be.not.visible');
			cy.get('#title').should('be.not.visible');
			cy.get('#author').should('be.not.visible');
			cy.get('#copyright').should('be.not.visible');
			cy.get('#description').should('be.not.visible');
			cy.get('#keywords').should('be.not.visible');
			cy.get('#robots').should('be.not.visible');
			cy.get('#email').should('be.not.visible');
			cy.get('#smtp').should('be.not.visible');
			cy.get('#subject').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="notification"]').should('be.not.visible');
			cy.get('#charset').should('be.not.visible');
			cy.get('#locale').should('be.not.visible');
			cy.get('#divider').should('be.not.visible');
			cy.get('#zone').should('be.not.visible');
			cy.get('#time').should('be.not.visible');
			cy.get('#date').should('be.not.visible');
			cy.get('#homepage').should('be.not.visible');
			cy.get('#limit').should('be.not.visible');
			cy.get('#order').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="pagination"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="registration"]').should('be.visible');
			cy.get('label.rs-admin-label-switch[for="verification"]').should('be.visible');
			cy.get('label.rs-admin-label-switch[for="recovery"]').should('be.visible');
			cy.get('label.rs-admin-label-switch[for="moderation"]').should('be.not.visible');
			cy.get('#captcha').should('be.not.visible');

			cy.get('label.rs-admin-label-accordion[for*="Security"]').click();

			cy.get('#language').should('be.not.visible');
			cy.get('#template').should('be.not.visible');
			cy.get('#title').should('be.not.visible');
			cy.get('#author').should('be.not.visible');
			cy.get('#copyright').should('be.not.visible');
			cy.get('#description').should('be.not.visible');
			cy.get('#keywords').should('be.not.visible');
			cy.get('#robots').should('be.not.visible');
			cy.get('#email').should('be.not.visible');
			cy.get('#smtp').should('be.not.visible');
			cy.get('#subject').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="notification"]').should('be.not.visible');
			cy.get('#charset').should('be.not.visible');
			cy.get('#locale').should('be.not.visible');
			cy.get('#divider').should('be.not.visible');
			cy.get('#zone').should('be.not.visible');
			cy.get('#time').should('be.not.visible');
			cy.get('#date').should('be.not.visible');
			cy.get('#homepage').should('be.not.visible');
			cy.get('#limit').should('be.not.visible');
			cy.get('#order').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="pagination"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="registration"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="verification"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="recovery"]').should('be.not.visible');
			cy.get('label.rs-admin-label-switch[for="moderation"]').should('be.visible');
			cy.get('#captcha').should('be.visible');
		});
	});
});
