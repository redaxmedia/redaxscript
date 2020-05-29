Analytics
=========

> Integrate Google Analytics.


Setup
-----

Define the `trackingId` inside `init.js` file:

```js
rs.modules.Analytics =
{
	optionArray:
	{
		...
		analytics:
		{
			trackingId: 'UA-00000000-0'
			...
		}
	}
};
```


Usage
-----

Syntax for the HTML tag:

```html
<a class="rs-js-track-click" data-category="..." data-action="..." data-label="..." data-value="..."></a>
```
