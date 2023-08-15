# Laravel query searcher

## Installation

Require this package with composer using the following command:

```bash
composer require shureban/laravel-searcher
```

Add the following class to the `providers` array in `config/app.php`:

```php
Shureban\LaravelSearcher\SearcherServiceProvider::class,
```

You can also publish the config file to change implementations (i.e. interface to specific class).

```shell
php artisan vendor:publish --provider="Shureban\LaravelSearcher\SearcherServiceProvider"
```

## How to use

By default, place your searchers in app\Http\Searchers folder.

### Example with all searcher variations.

#### getQuery method

Should return query for applying filters. You may modify that
query `Model::query()->with(['relation_1', 'relation_2' => fn() => ...])`

#### getFilters method

Should return associative array where:

- keys must be equal to your request params
- values must be objects related of `\Shureban\LaravelSearcher\Searcher`

#### Example

In that case:

- 'age' - is request parameter age
- 'client_age' - is DB column name
- new Between(...) - filter rule

```php
return ['age' => new Between('client_age')];
```

```php
class YourFirstSearcher extends Searcher
{
    /**
     * @return Builder
     */
    protected function getQuery(): Builder
    {
        return Model::query();
    }

    /**
     * @return ColumnFilter[]
     */
    protected function getFilters(): array
    {
        return [
          //| Request param name | Filter object               | Expected value type
          //-------------------------------------------------------------------------
            // Simple cases
            'is_single'        => new Boolean('is_single'),     // bool
            'age'              => new Between('client_age'),    // array (2 elements)
            'salary'           => new BetweenRange('salary'),   // array (2 elements)
            'birthday'         => new BetweenDates('birthday'), // array (2 elements)
            'id'               => new Equal('id'),              // any
            'created_at'       => new EqualDate('created_at'),  // date
            'height'           => new Gt('height'),             // number
            'max_height'       => new Gte('max_height'),        // number
            'updated_at'       => new GtDate('updated_at'),     // date
            'deleted_at'       => new GteDate('deleted_at'),    // date
            'statuses'         => new In('status'),             // array
            'image_id'         => new IsNull('image_id'),       // bool
            'email'            => new Like('email'),            // mixed
            'foot_size'        => new Lt('foot_size'),          // number
            'max_foot_size'    => new Lte('max_foot_size'),     // number
            'birthday'         => new LtDate('birthday'),       // date
            'hired_at'         => new LteDate('hired_at'),      // date
            'partner_statuses' => new NotIn('partner_status'),  // array
            'only_every_even'  => new Callback(
                fn(Builder $query, mixed $value) => $query->whereRaw('(id % 2 = 0)')
            ),                                                  // mixed


            // Modifier used. That case means, all rows where manager_id is equal to same value or null
            'manager_id' => new OrNull(new Like('manager_id')),
            'full_name'  => new OrEmpty(new Like('full_name')),
            'owner_id'   => new MultipleOr(new Equal('user_id'), new Like('manager_id'), new Relation('brokers', new Equal('id'))),
            // Working with relation modifiers
            'invoice_payouts'          => new Relation('invoices', new Between('amount')),
            'invoice_statuses'         => new Relation('invoices', new In('status')),
            'invoice_payment_method'   => new Relation('invoices', new Like('payment_method')),
            'invoice_process_statuses' => new Relation('invoices', new NotIn('process_status')),
        ];
    }
}
```

### How to change Sorting

For change default sort column, you should override method `sortColumn`.

```php
protected function sortColumn(): ?string
{
    return $this->request->get('sort_column', 'created_at');
}
```

If you need to change order behavior related with some column, do this. Override method `applySortBy`

```php
protected function applySortBy(Builder $query, string $sortColumn, SortType $sortType): Builder
{
    return match ($sortColumn) {
        'your_special_column'   => $query->orderBy('column_1', $sortType)->orderBy('column_2', $sortType),
        default                 => parent::applySortBy($query, $sortColumn, $sortType),
    };
}
```

## Real case

### Your first searcher

```php
namespace App\Http\Searchers;

use Illuminate\Database\Eloquent\Builder;
use Shureban\LaravelSearcher\Filters\Like;
use Shureban\LaravelSearcher\Searcher;

class YourFirstSearcher extends Searcher
{
    /**
     * @return Builder
     */
    protected function getQuery(): Builder
    {
        return Model::query();
    }

    /**
     * @return ColumnFilter[]
     */
    protected function getFilters(): array
    {
        return [
            'id' => new Like('id'),
        ];
    }
}
```

### Request

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\In;
use Shureban\LaravelSearcher\Enums\SortType;

class YourRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id'            => ['int', 'min:1', 'max:2000000000'],
            'sort_column'   => [new In(['id'])],
            'sort_type'     => [new Enum(SortType::class)],
        ];
    }
}
```

### Controller

```php
namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests\YourFirstSearcher;

class YourController extends BaseController
{
    /**
     * @param YourRequest $request
     *
     * @return JsonResponse
     */
    public function __invoke(YourRequest $request): JsonResponse
    {
        $searcher  = new YourFirstSearcher($request);
        // Collection with all models without pagination and slicing by per_page
        $allModels = $searcher->all();
        // Collection contains per_page number of models and with page offset 
        $models    = $searcher->get();
        // Base Laravel LengthAwarePaginator
        $paginator = $searcher->paginate();

        return new JsonResponse($paginator);
    }
}
```
