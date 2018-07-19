/* webp support */

if (document.createElement('canvas').toDataURL('image/webp').indexOf('data:image/webp') > -1)
{
	document.documentElement.classList.add('rs-has-webp');
}