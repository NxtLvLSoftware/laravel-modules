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