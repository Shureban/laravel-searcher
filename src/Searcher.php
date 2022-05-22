<?php

namespace Shureban\LaravelSearcher;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\Paginator;
use Shureban\LaravelSearcher\Enums\SortType;
use Shureban\LaravelSearcher\Filters\Filter;

abstract class Searcher
{
    private FormRequest $request;

    /**
     * @param FormRequest $request
     */
    public function __construct(FormRequest $request)
    {
        $this->request = $request;
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
     * @return Filter[]
     */
    abstract protected function getFilters(): array;

    /**
     * @return Builder
     */
    private function apply(): Builder
    {
        $query = $this->getQuery();

        foreach ($this->getFilters() as $requestKey => $filter) {
            if ($this->request->has($requestKey)) {
                $filter->apply($query, $this->request->get($requestKey));
            }
        }

        $sortColumn = $this->sortColumn() ?? config('searcher.sort_column');
        $sortType   = $this->sortType() ?? config('searcher.sort_type');

        return $this->applySortBy($query, $sortColumn, $sortType);
    }

    /**
     * @return string|null
     */
    protected function sortColumn(): ?string
    {
        return $this->request->get('sort_column');
    }

    /**
     * @return SortType|null
     */
    protected function sortType(): ?SortType
    {
        return SortType::tryFrom($this->request->get('sort_type'));
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
        return config('searcher.per_page');
    }

    /**
     * @return LengthAwarePaginator|Paginator
     */
    public function paginate(): LengthAwarePaginator|Paginator
    {
        $perPage = $this->request->get('per_page', $this->perPage());

        return $this->apply()->paginate($perPage);
    }

    /**
     * @return Model[]|Collection
     */
    public function get(): Collection
    {
        return $this->apply()->get();
    }
}
