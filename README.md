# Da2e FiltrationBundle

[![Build Status](https://travis-ci.org/dmitrya2e/filtration-bundle.svg?branch=dev)](https://travis-ci.org/dmitrya2e/filtration-bundle)

Current status: **WORK IN PROGRESS**.

FiltrationBundle provides a convenient and easy way for creating filtration component/form on your website built with Symfony 2.
Its purpose is to create a standard way and workflow for managing data filtration in Symfony 2 websites. 

The bundle includes following features:

- Built-in integration with Symfony forms to render filters in views
- Auto-execution of applied filters regarding specific filtration handler (e.g. Doctrine ORM)
- Possibility to add custom filters and custom filter adapters
- Possibility to customize standard bundle filters
- Various standard filters (choice, entity, number, date, text) and filter adapters (Doctrine ORM, Sphinx Client, ...)

Plans:

- Add more standard filters (boolean, time, datetime, ...) and more filter adapters (Doctrine ODM, SphinxQL, plain SQL queries, ...)
- Implement validation of filter values
- Implement sorting component
- ...

The first version of the bundle was developed within [AXIOMA web-studio](https://www.axiomadev.com/) - the company where I work.

## Filtration handler adapters

- [Doctrine ORM](https://github.com/dmitrya2e/filtration-doctrine-orm-bundle)
- [Sphinx Client](https://github.com/dmitrya2e/filtration-sphinx-client-bundle)

## Software requirements

- PHP 5.4++
- Symfony 2.3++ (the bundle is tested with Symfony **2.3** and **2.7** versions)

## How to install

[Click here to read a full installation guide](Resources/docs/how-to-install.md)

## Documentation

[Click here to read a full documentation](Resources/docs/index.md)

## License

This bundle is under the [MIT license](LICENSE). 
