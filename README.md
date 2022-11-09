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

You can also publish the config file to change implementations (ie. interface to specific class).

```shell
php artisan vendor:publish --provider="Shureban\LaravelSearcher\SearcherServiceProvider"
```

## How to use

By default, place your searchers in app\Http\Searchers folder.

### Example with all searcher variations.

#### getQuery method

Should return query for applying filters. You may modify that query `Model::query()->with(['relation_1', 'relation_2' => fn() => ...])`

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
     * @return Filter[]
     */
    protected function getFilters(): array
    {
        return [
            // Simple cases
            'age'              => new Between('client_age'),    // number
            'salary'           => new BetweenRange('salary'),   // number
            'id'               => new Equal('id'),              // any
            'created_at'       => new EqualDate('created_at'),  // date
            'height'           => new Gt('height'),             // number
            'max_height'       => new Gte('max_height'),        // number
            'updated_at'       => new GtDate('updated_at'),     // date
            'deleted_at'       => new GteDate('deleted_at'),    // date
            'statuses'         => new In('status'),             // array
            'image_id'         => new IsNull('image_id'),       // bool
            'email'            => new Like('email'),            // any
            'foot_size'        => new Lt('foot_size'),          // number
            'max_foot_size'    => new Lte('max_foot_size'),     // number
            'birthday'         => new LtDate('birthday'),       // date
            'hired_at'         => new LteDate('hired_at'),      // date
            'partner_statuses' => new NotIn('partner_status'),  // array

            // Modifier used. That case means, all rows where manager_id is equal to same value or null
            'manager_id' => new OrNull(new Like('manager_id')), // any

            // Working with relations
            'invoice_payouts'          => new RelationBetween('invoices', 'amount'),        // number
            'invoice_statuses'         => new RelationIn('invoices', 'status'),             // array
            'invoice_payment_method'   => new RelationLike('invoices', 'payment_method'),   // any
            'invoice_process_statuses' => new RelationNotIn('invoices', 'process_status'),  // array
        ];
    }
}
```

### Real case

#### Your first searcher

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
     * @return Filter[]
     */
    protected function getFilters(): array
    {
        return [
            'id' => new Like('id'),
        ];
    }
}
```

#### Request

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

#### Controller

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
