# Configuration reference

[Return to the documentation index page](index.md)

```yaml
# app/config/config.yml

da2e_filtration:
    # a prototype of custom or overrided filtration handlers with the following structure
    # this parameter is not required
    handlers:
        handler_name: # the key name is not important
            name: handler_name # this name is important and it is used as filtration handler name
            class: \Fully\Qualified\Name\Of\Filtration\Handler
        # ...
```
