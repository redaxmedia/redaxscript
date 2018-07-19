/* javascript enabled */

document.documentElement.classList.add('rs-is-js');

/* document ready */

window.onload = () =>
{
	document.documentElement.classList.add('rs-is-ready');
};

/* create namespace */

window.rs =
{
	templates: {},
	modules: {}
};