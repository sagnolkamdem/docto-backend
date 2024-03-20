<?php

namespace Modules\Core\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AbstractFilters
{
    /**
     * HTTP Request for the query params.
     *
     * @var Request
     */
    protected Request $request;

    /**
     * The Eloquent builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected Builder $builder;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected array $filters = [];

    /**
     * Create a new ThreadFilters instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get all filters and make a new instance.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function filter(Builder $builder): Builder
    {
        foreach ($this->getFilters() as $filter => $value) {
            $this->resolverFilter($filter)->filter($builder, $value);
        }

        return $builder;
    }

    /**
     * Add Filters to current filter class.
     *
     * @param array $filters
     * @return $this
     */
    public function add(array $filters): self
    {
        $this->filters = array_merge($this->filters, $filters);

        return $this;
    }

    /**
     * Get the Filter instance Class.
     *
     * @param $filter
     * @return mixed
     */
    public function resolverFilter($filter)
    {
        return new $this->filters[$filter];
    }

    /**
     * Fetch all relevant filters from the request.
     *
     * @return array
     */
    public function getFilters(): array
    {
        return array_filter($this->request->only(array_keys($this->filters)));
    }
}
