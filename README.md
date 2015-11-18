# Da2e FiltrationBundle

[![Build Status](https://travis-ci.org/dmitrya2e/filtration-bundle.svg?branch=dev)](https://travis-ci.org/dmitrya2e/filtration-bundle)

Current status: **WORK IN PROGRESS**.

FiltrationBundle provides a convenient and easy way for creating filtration component/form on your website built with Symfony 2.
The bundle includes the following features:

- Built-in integration with Symfony forms to render the filters in views
- Auto-execution of applied filters regarding specific filtration handler (e.g. Doctrine ORM)
- Possibility to add custom filters and custom filtration handlers
- Possibility to customize standard bundle filters
- The bundle is packaged with a various standard filters (choice, entity, number, date, text)
- The bundle supports various standard filter adapters (Doctrine ORM, Sphinx API)

Plans:

- Add more standard filters (boolean, time, datetime, ...) and more standard filter adapters (Doctrine ODM, SphinxQL, plain SQL queries, ...)
- Implement sorting component
- ...

## Software requirements:

- PHP 5.4++
- Symfony 2.3++ (the bundle is tested with Symfony **2.3** and **2.7** versions)

## How to install

[Click here to read a full installation guide](Resources/docs/how-to-install.md)

## Documentation

[Click here to read a full documentation](Resources/docs/index.md)

## License

This bundle is under the [MIT license](LICENSE).
