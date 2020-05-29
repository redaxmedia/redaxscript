Gallery
=======

> Responsive image gallery.


Usage
-----

Syntax for the content tag:

```html
<rs-module>
{
	"Gallery":
	{
		"directory": "images/gallery",
		"options":
		{
			"quality": 80,
			"height": 200,
			"order: "asc"
		}
	}
}
</rs-module>
```

Create fresh thumbs:

```html
<rs-module>
{
	"Gallery":
	{
		"directory": "images/gallery",
		"options":
		{
			"command": "create"
		}
	}
}
</rs-module>
```

Remove outdated thumbs:

```html
<rs-module>
{
	"Gallery":
	{
		"directory": "images/gallery",
		"options":
		{
			"command": "remove"
		}
	}
}
</rs-module>
```
