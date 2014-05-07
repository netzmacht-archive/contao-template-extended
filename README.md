
Extended Template features for Contao
==============

This extension provides extended template features like using layouts, sections and partial templates without using any
different template engine. This extension was developed as a proof of concept to show how to implement this feature in
Contao. After template inheritance will be introduced in Contao 3.3 it was refactored to backport template inheritance
to Contao 3.2 using the same syntax as in Contao 3.2.

Features
------------

* Backports features explained [here](https://github.com/contao/core/issues/6508#issuecomment-41476802) to Contao 3.2
* Add support for multiple template inheritance as proposed in [contao/core#6934](https://github.com/contao/core/pull/6934/)
* Blacklist or whitelist configuration to decide in which template the feature can be used
* The default behavior is to blacklist templates which should not have

Known limitations
----------

This extension uses the feature of callables introduced by [contao/core#6176](https://github.com/contao/core/commit/fcc44be87ed7d7f68769f9a4058248174f7d453e).
That means that followings variable names are used. If your template uses this names for templates vars, there will be
a conflict.

* `block`, `endblock`, `insert`, `extend`, `parent` for provided methods
* `__helper` for assigned template helper

Install
----------

You can easily install the extension using the [Composer client](http://c-c-a.org/ueber-composer) by installing
`netzmacht/contao-template-extended`