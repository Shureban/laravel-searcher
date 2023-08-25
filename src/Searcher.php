<?php

namespace Shureban\LaravelSearcher;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Shureban\LaravelSearcher\Enums\SortType;

abstract class Searcher
{
    protected FormRequest $request;

    /**
     * @param FormRequest $request
     */
    public function __construct(FormRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return LengthAwarePaginator|Paginator
     */
    public function paginate(): LengthAwarePaginator|Paginator
    {
        $perPage = $this->request->get(config('searcher.per_page_field_name'), $this->perPage());

        return $this->apply()->paginate($perPage);
    }

    /**
     * @return Model[]|Collection
     */
    public function get(): Collection
    {
        return $this->paginate()->getCollection();
    }

    /**
     * @return Model[]|Collection
     */
    public function all(): Collection
    {
        return $this->apply()->get();
    }

    /**
     * Return query, witch need to apply filter
     *
     * @return Builder
     */
    abstract protected function getQuery(): Builder;

    /**
     * List of filters objects
     * Each key from array needed for getting data from request
     * Example:
     * [
     *     'name' => new Like('full_name')
     * ]
     * Key "name" will be using for getting request data ($request->get('name'))
     * Param "full_name" from Like filter, will be using to apply filter to column 'full_name' in query
     *
     * @return FilterInterface[]
     */
    abstract protected function getFilters(): array;

    /**
     * @return string|null
     */
    protected function sortColumn(): ?string
    {
        return data_get($this->request->all(), config('searcher.sort_column_field_name'));
    }

    /**
     * @return SortType|null
     */
    protected function sortType(): ?SortType
    {
        $sortType = data_get($this->request->all(), config('searcher.sort_type_field_name'));

        return SortType::tryFrom($sortType);
    }

    /**
     * @param Builder  $query
     * @param string   $sortColumn
     * @param SortType $sortType
     *
     * @return Builder
     */
    protected function applySortBy(Builder $query, string $sortColumn, SortType $sortType): Builder
    {
        return $query->orderByRaw(sprintf('(%s) %s NULLS LAST', $sortColumn, $sortType->value));
    }

    /**
     * @return int
     */
    protected function perPage(): int
    {
        return config('searcher.per_page_value');
    }

    /**
     * @return bool
     */
    protected function skipEmptyValues(): bool
    {
        return config('searcher.skip_empty_values');
    }

    /**
     * @return Builder
     */
    private function apply(): Builder
    {
        $query = $this->getQuery();

        foreach ($this->getFilters() as $requestKey => $filter) {
            $hasValue       = $this->request->has($requestKey);
            $isEmpty        = empty($this->request->get($requestKey));
            $itMightBeEmpty = $this->skipEmptyValues() === false;

            if ($hasValue && (!$isEmpty || $itMightBeEmpty)) {
                $filter->apply($query, $this->request->get($requestKey));
            }
        }

        $sortColumn = $this->sortColumn() ?? config('searcher.sort_column_name');
        $sortType   = $this->sortType() ?? config('searcher.sort_type_value');

        return $this->applySortBy($query, $sortColumn, $sortType);
    }
}
