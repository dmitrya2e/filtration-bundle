<?php

namespace Da2e\FiltrationBundle\Filter\Executor;

use Da2e\FiltrationBundle\Exception\Filter\Executor\FilterExecutorException;
use Da2e\FiltrationBundle\Filter\Collection\CollectionInterface;
use Da2e\FiltrationBundle\Filter\Filter\CustomApplyFilterInterface;
use Da2e\FiltrationBundle\Filter\Filter\CustomTransformValuesInterface;
use Da2e\FiltrationBundle\Filter\Filter\FilterInterface;

/**
 * The default filter executor class.
 * Used for executing the applied filters.
 *
 * @package Da2e\FiltrationBundle\Filter\Executor
 */
class FilterExecutor implements FilterExecutorInterface
{
    /**
     * Contains available filtration handler types (set via bundle configuration).
     *
     * @var array [ handler_type|string => handler_class_fqn|string, ... ]
     */
    protected $availableHandlerTypes = [];

    /**
     * Contains registered filtration handlers.
     *
     * @var array|object[]|mixed[] $registeredHandlers
     */
    protected $registeredHandlers = [];

    /**
     * @param array $availableHandlerTypes Available filtration handler types (set via bundle configuration)
     */
    public function __construct(array $availableHandlerTypes = [])
    {
        $this->availableHandlerTypes = $availableHandlerTypes;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(CollectionInterface $collection, array $handlers = [])
    {
        if (count($handlers) > 0) {
            $this->registerHandlers($handlers);
        }

        /** @var FilterInterface[] $collection */
        foreach ($collection as $filter) {
            $handlerType = $filter->getType();

            if (!array_key_exists($handlerType, $this->registeredHandlers)) {
                throw new FilterExecutorException(sprintf(
                    'No registered handler for filter type "%s".', $handlerType
                ));
            }

            $handler = $this->registeredHandlers[$handlerType];

            $this->transformValues($filter);
            $this->applyFilter($filter, $handler);
        }

        return $this;
    }

    /**
     * Registers specific filtration handler.
     *
     * @param object|mixed $handler Any supported resource, which can handle filtration (QueryBuilder, ...)
     * @param string|bool  $type    The type of the handler (custom or FilterHandlerModel::HANDLER_*)
     *
     * @see \Da2e\FiltrationBundle\Model\FilterHandlerModel constants for available default handler types
     *
     * @return static
     * @throws FilterExecutorException on handler errors
     */
    public function registerHandler($handler, $type = false)
    {
        if (!is_object($handler)) {
            throw new FilterExecutorException('Handler must be an object.');
        }

        if ($type === false) {
            // Try to guess handler type by class FQN.
            $type = $this->guessHandlerType($handler);

            if ($type === false) {
                throw new FilterExecutorException('Could not guess handler type. Please, provide the type for the handler manually.');
            }
        }

        if (!array_key_exists($type, $this->availableHandlerTypes)) {
            throw new FilterExecutorException(sprintf(
                'Handler type "%s" is not allowed (available handler types: [%s]).',
                $type, implode(', ', array_keys($this->availableHandlerTypes))
            ));
        }

        if (!array_key_exists($type, $this->registeredHandlers)) {
            $this->registeredHandlers[$type] = $handler;
        }

        return $this;
    }

    /**
     * Registers different filtration handlers.
     *
     * @param array|object[]|mixed[] $handlers
     *
     * @return $this
     */
    public function registerHandlers(array $handlers)
    {
        foreach ($handlers as $type => $handler) {
            if (!is_string($type)) {
                // Probably, non-string $type is a numeric index, which means,
                // that the handler array was defined as [ $handler1, $handler2, ... ] without handler types.
                // So try to guess the type.
                $type = $this->guessHandlerType($handler);
            }

            $this->registerHandler($handler, $type);
        }

        return $this;
    }

    /**
     * Applies filtration.
     * If the filter has custom function for filtration applying, than it will be used.
     *
     * @param FilterInterface|CustomApplyFilterInterface $filter
     * @param object|mixed                               $handler Any supported resource, which can handle filtration
     *
     * @return static
     */
    protected function applyFilter(FilterInterface $filter, $handler)
    {
        if (!($filter instanceof CustomApplyFilterInterface) || !is_callable($filter->getApplyFilterFunction())) {
            return $filter->applyFilter($handler);
        }

        return call_user_func($filter->getApplyFilterFunction(), $filter, $handler);
    }

    /**
     * Transforms filter values if a custom function is defined.
     *
     * @param FilterInterface|CustomTransformValuesInterface $filter
     *
     * @return static
     */
    protected function transformValues($filter)
    {
        if (!($filter instanceof CustomTransformValuesInterface)) {
            return $filter;
        }

        $callableFunction = $filter->getTransformValuesFunction();

        if (is_callable($callableFunction)) {
            return call_user_func($callableFunction, $filter);
        }

        return $filter;
    }

    /**
     * Guesses handler type by handler object class FQN.
     * Basically, it checks if the handler class name exists in the available handler types.
     *
     * @param object|mixed $handler Any supported resource, which can handle filtration
     *
     * @return false|string False if the type can not be guessed
     */
    protected function guessHandlerType($handler)
    {
        if (!is_object($handler)) {
            return false;
        }

        foreach ($this->availableHandlerTypes as $type => $classFQN) {
            if ($handler instanceof $classFQN) {
                return $type;
            }
        }

        return false;
    }
}
