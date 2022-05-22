# Laravel prometheus metrics

## Installation

Require this package with composer using the following command:

```bash
composer require shureban/laravel-prometheus
```

Add the following class to the `providers` array in `config/app.php`:

```php
Shureban\LaravelPrometheus\PrometheusServiceProvider::class,
```

You can also publish the config file to change implementations (ie. interface to specific class).

```shell
php artisan vendor:publish --provider="Shureban\LaravelPrometheus\PrometheusServiceProvider"
```

Update `.env` config, change REDIS_CLIENT from redis to predis:

```text
REDIS_CLIENT=predis
```

## Creating metric class

### CLI supporting

You may create metrics via CLI commands

```bash
# Creating counter metric CustomCounterMetricName
php artisan make:counter CustomCounterMetricName --name={name} --labels={label_1,label_2,label_N} --description={description} --dynamic

# Creating gauge metric CustomGaugeMetricName
php artisan make:gauge CustomGaugeMetricName --name={name} --labels={label_1,label_2,label_N} --description={description} --dynamic
```

| Option      | Alias | Required | Description                              |
| ----------- |:-----:|:--------:| -----------------------------------------|
| name        |       | false    | Name of the metric                       |
| label       |       | false    | The metric labels list (comma separated) |
| description |       | false    | The metric description                   |
| dynamic     | d     | false    | The metric description                   |

### Manual

Create folder, where you will contain your custom metrics classes (for example `app/Prometheus`). Realise constructor
with metric static params.

```php
namespace App\Prometheus;

use Shureban\LaravelPrometheus\Counter;
use Shureban\LaravelPrometheus\Attributes\Name;
use Shureban\LaravelPrometheus\Attributes\Labels;

class AuthCounter extends Counter
{
    public function __construct()
    {
        $name   = new Name('auth');
        $labels = new Labels(['event']);
        $help   = 'Counter of auth events';

        parent::__construct($name, $labels, $help);
    }
}
```

## Usages

### General metrics flow

Using DI (or not), increase the metric value.

```php
use App\Prometheus\AuthCounter;

class RegisterController extends Controller
{
    public function __invoke(..., AuthCounter $counter): Response
    {
        // Registration new user logic
    
        $counter->withLabelsValues(['registration'])->inc();
    }
}
```

Or, if you have static list of events, you may realize following flow:

```php
namespace App\Prometheus\Counters;

use Shureban\LaravelPrometheus\Counter;
use Shureban\LaravelPrometheus\Attributes\Name;
use Shureban\LaravelPrometheus\Attributes\Labels;

class AuthCounter extends Counter
{
    public function __construct()
    {
        //... 
    }
    
    public function registration(): void 
    {
        $this->withLabelsValues(['registration'])->inc();
    }
}
```

This way helps you encapsulate logic with labels, and the code seems pretty

```php
use App\Prometheus\AuthCounter;

class RegisterController extends Controller
{
    public function __invoke(..., AuthCounter $counter): Response
    {
        // Registration new user logic
    
        $counter->registration();
    }
}
```

### Dynamic metrics flow

Dynamic flow may help you attach more labels with different sizes

```php
use App\Prometheus\AuthCounter;
use Shureban\LaravelPrometheus\Attributes\Labels;

class RegisterController extends Controller
{
    public function __invoke(..., DynamicAuthCounter $counter): Response
    {
        // Registration new user logic
    
        $counter->withLabels(Labels::newFromArray(['event' => 'registration', 'country' => 'US']))->inc();
        $counter->withLabels(Labels::newFromArray(['event' => 'registration', 'country' => 'US', 'browser' => 'chrome']))->inc();
        $counter->withLabels(Labels::newFromCollection($user->only(['country', 'browser'])))->inc();
    }
}
```

## Rendering

Render metrics data in text format

### Using config

In `config/prometheus.php`, find `web_route` param and set preferred route path. Default is `/prometheus/metrics`. 

### Manual

For render metrics by route, you need to provide next code:

```php
$renderer = new RenderTextFormat();

return response($renderer->render(), Response::HTTP_OK, ['Content-Type' => RenderTextFormat::MIME_TYPE]);
```

of using string type hinting

```php
return response(new RenderTextFormat(), Response::HTTP_OK, ['Content-Type' => RenderTextFormat::MIME_TYPE]);
```

