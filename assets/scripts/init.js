/* polyfill */

if (window.NodeList && !NodeList.prototype.forEach)
{
	NodeList.prototype.forEach = Array.prototype.forEach;
}

/* javascript enabled */

document.documentElement.classList.add('rs-is-js');

/* document ready */

window.addEventListener('load', () => document.documentElement.classList.add('rs-is-ready'));

/* create namespace */

window.rs =
{
	templates: {},
	modules: {}
};
