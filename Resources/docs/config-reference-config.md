# Configuration reference

```yaml
# app/config/config.yml

da2e_filtration:
    handlers:
        # a prototype of filtration handlers with the following structure:
        handler_name: # the key name is not important
            name: handler_name # this name is important and it is used as filtration handler name
            class: \Fully\Qualified\Name\Of\Filtration\Handler
        # ...
```
