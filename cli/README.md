laravel-modules-cli
===============
__A CLI tool for managing the modularisation of laravel projects.__

### About

The CLI package is a CLI application built for assisting with module development. It aims to be flexible and not enforce
one code style or project structure on every package. You can supply your own project structure, custom stub files and even
add new files, namespaces, resources, etc along with their stub.

We achieve flexible project structures by defining the structure in an array format that all PHP developers should be familiar
with. An array value means it will generate a directory and a string value means it will search for the matching stub file
and pass the specified stub file information into the blade template. By using blade for stubs we can pass extra information
into the stubs that may not be used by default and automatically have auto-completion features in IDE's, plus static analysis
from some tools.

* [Installation](#installation)
    * [Globally](#global-installation)
    * [Project](#project-installation)
* [Usage](#usage)
    * [Creating a module](#creating-a-module)
* [Issues](#issues)
* [License](#license-information)

### Installation

You can install the CLI tool globally so it is always available to use for creating new projects or include it in your
composer dependencies and keep all your modules in the same project.

#### Global Installation

To install the CLI tool globally make sure you have composer installed on your system then run:
```bash
$ composer global require nxtlvlsoftware/laravel-modules-cli
```

You should now be able to run the `laravel-modules` command anywhere on your system. If the command isn't found make sure
you have the composer bin in your `$PATH`.

#### Project Installation

To install the CLI tool in your project you can add a dev dependency explicitly for the CLI tool:
```bash
$ composer require --dev nxtlvlsoftware/laravel-modules-cli
```

This will add the `laravel-modules` executable to your projects composer bin, you can then add it to your path. You can
also optionally include the commands in your application for development so they're available when running the `artisan`
executable by registering the `\NxtLvlSoftware\LaravelModulesCli\Provider\LaravelModulesCommandServiceProvider` provider.


You can also directly depend on the base laravel-modules package to get the CLI tool installed in dev environments and the
module helper package in dev and production:

```bash
$ composer require nxtlvlsoftware/laravel-modules
```
Using this method you can also register the command service provider to get the module commands available directly in
your project.

### Usage

#### Creating a module

```bash
$ laravel-modules make:module {name}
```
This will create a new module with the specified name, default structure and a root namespace being the module name.

You can specify the namespace with the `--namespace` option:
```bash
$ laravel-modules make:module {name} --namespace Your\NameSpace
```

You can specify a custom project structure file with the `--structure` (`-s`) option:
```bash
$ laravel-modules make:module {name} -s ~/my_module_structure.php
```

See the [default structure file](default_structure.php) for an example.

### Issues

Found a problem with this project? Make sure to open an issue on the [issue tracker](https://github.com/NxtLvLSoftware/laravel-modules/issues) and we'll get it sorted!

## License Information

The content of this repo is & always will be licensed under the [Unlicense](http://unlicense.org/).

> This is free and unencumbered software released into the public domain.
> 
> Anyone is free to copy, modify, publish, use, compile, sell, or
> distribute this software, either in source code form or as a compiled
> binary, for any purpose, commercial or non-commercial, and by any
> means.

__A full copy of the license is available [here](../LICENSE).__

#

__A [NxtLvL Software Solutions](https://github.com/NxtLvLSoftware) product.__