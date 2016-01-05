# Configuration reference via parameters

[Return to the documentation index page](index.md)

```yaml
# app/config/parameters.yml

parameters:
    # FQN of the filter chain class
    da2e.filtration.filter.chain.filter_chain_class: Da2e\FiltrationBundle\Filter\Chain\FilterChain
    
    # FQN of the filter collection class
    da2e.filtration.filter.collection.collection_class: Da2e\FiltrationBundle\Filter\Collection\Collection
    
    # FQN of the filter collection creator class
    da2e.filtration.filter.collection.creator.collection_creator_class: Da2e\FiltrationBundle\Filter\Collection\Creator\CollectionCreator
    
    # FQN of the filter creator class
    da2e.filtration.filter.creator.filter_creator_class: Da2e\FiltrationBundle\Filter\Creator\FilterCreator
    
    # FQN of the filter option handler class
    da2e.filtration.filter.filter_option.filter_option_handler_class: Da2e\FiltrationBundle\Filter\FilterOption\FilterOptionHandler
    
    # FQN of the filter executor class
    da2e.filtration.filter.executor.filter_executor_class: Da2e\FiltrationBundle\Filter\Executor\FilterExecutor
    
    # FQN of the filter super manager class
    da2e.filtration.manager.filter_super_manager_class: Da2e\FiltrationBundle\Manager\FilterSuperManager
    
    # FQN of the form creator class
    da2e.filtration.form.creator.form_creator_class: Da2e\FiltrationBundle\Form\Creator\FormCreator
    
    # FQN of the filter form type class
    # Form type is used in form creator component for binding the filter to the root form
    da2e.filtration.form.type.filter_type_class: Da2e\FiltrationBundle\Form\Type\FilterType

    # Configuration for the form creator component
    # Must have a key "form_filter_type_class", which must contain a fully-qualified name of the filter form type class
    da2e.filtration.form.creator.form_creator_config:
        form_filter_type_class: %da2e.filtration.form.type.filter_type_class%
```

You can always override the services themselves, but is is recommended to do only in really necessary cases. Service list:

- da2e.filtration.filter.chain.filter_chain
- da2e.filtration.filter.creator.filter_creator
- da2e.filtration.filter.filter_option.filter_option_handler
- da2e.filtration.filter.collection.creator.collection_creator
- da2e.filtration.filter.executor.filter_executor
- da2e.filtration.manager.filter_super_manager
- da2e.filtration.form.creator.form_creator

To see full service definition, please check the [Resources/config/services.yml file](/Resources/config/services.yml).
