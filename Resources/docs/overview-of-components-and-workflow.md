# Brief overview of all components and workflow

## Introduction

Whole workflow of the filtration bundle consist of several components:
- Filter creator
- Filter option handler
- Filter collection creator
- Filter collection
- Filter executor
- Form creator

## Components overview

### Filter creator

### Filter option handler

### Filter collection creator

### Filter collection

### Filter executor

### Form creator

## Components customization

Each of component is fully customizable, which means that you can override any of them just by redefining a specific parameters in parameters file (app/parameters.yml) or by configuring them in bundle configuration (app/config.yml). 

For example, to override filter collection component, you can just redefine the following parameter:
```yaml
# app/parameters.yml

parameters:
    da2e.filtration.filter.collection.collection_class: Your\Collection\Class
```

For more details on configuration please refer to:
- [Configuration reference](config-reference-config.md)
- [Configuration reference via container parameters](config-reference-parameters.md)
