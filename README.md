
Extended Template features for Contao
==============

This extension provides extended template features like using layouts, sections and partial templates without using any
different template engine. This features are heavily inspired by [Plates](https://github.com/php-loep/Plates) which is
inspired by [Twig](http://twig.sensiolabs.org/).

Features
------------

* Works with Contao frontend and backend templates
* No need for another template language
* Template layouts and inheritance
* Template sections and subtemplates


### Layouts

Layouts allows you to define layouts which are used as an outer view of the current template. It works like the
[`layout()`](http://platesphp.com/layouts) function of Plates.

Instead of calling `layout()` directly you have to use `$this->__tpl->layout($name)` or for Contao 3.2 you can also use
`$this->__layout($name)`. To access the unrelated data in the layout template use `this->__tpl->child()` or for Contao 3.2
`this->__child()`

```php
<?php // fe_custom.html5

$this->__tpl->layout('fe_page');
$this->footer = 'Custom footer';

?>
```

### Sections

Sections allows you to render content and assign them to a variable. It works like the
[sections](http://platesphp.com/sections) used by Plates. You have to use the alternate calls again:
`$this->__tpl->start($name)` and `$this->__tpl->end()` or for Contao 3.2 `$this->__start($name)` and `$this->__end()`.

```php
<?php // fe_custom.html5 $this->__tpl->layout('fe_page'); ?>
<?php $this->__tpl->start('footer'); ?>
Custom footer
<?php $this->__tpl->end(); ?>

?>
```

### Nesting

Last but not least the nesting feature of Plates is implemented as well. See the [documentation](http://platesphp.com/nesting)
of Plates. The implemenation differs from Plates. The attributes of the current template are not passed to the nested
one by default. You have to pass them by your own.

There is also a third parameter provided, there you can disable the auto output. So you can assign the return to an var.

**The template being inserted**
```php
<?php // logo.html5 ?>
<a href="<?= $href; ?>" title="<?= $title; ?>"><?= $label; ?></a>
?>
```

** Main template**
```php
<?php // fe_custom.html5

$this->insert('logo' array('title' => $this->logoTitle, 'href' => 'logoHref', 'label' => \Image::getHtml($this->logoSrc)));
$link = $this->insert('link', array(), false);
?>
```

### Reserved template vars

TemplateExtended uses some template vars. So they are reserved and should not being used by other extensions/modules.

* `__tpl` stores the current `TemplateExtended\Template`
* `__start()` routes to `TemplateExtended\Template::start` (Contao 3.2 only)
* `__end()` routes to `TemplateExtended\Template::end` (Contao 3.2 only)
* `__layout()` routes to `TemplateExtended\Template::layout` (Contao 3.2 only)
* `__insert()` routes to `TemplateExtended\Template::insert` (Contao 3.2 only)


Install
----------

You can easily install the extension using the [Composer client](http://c-c-a.org/ueber-composer) by installing
`netzmacht/contao-template-extended`