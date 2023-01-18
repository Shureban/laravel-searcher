<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default per page
    |--------------------------------------------------------------------------
    |
    | How much elements should be found by default
    |
    */

    'per_page' => 20,

    /*
    |--------------------------------------------------------------------------
    | Default sort column
    |--------------------------------------------------------------------------
    |
    | Default column for sorting if request parameter is empty
    |
    */

    'sort_column' => 'id',

    /*
    |--------------------------------------------------------------------------
    | Should skip empty fields
    |--------------------------------------------------------------------------
    |
    | Sometimes we get empty values, and we should have opportunity to skip them.
    | If value is true, all empty values will be filtered.
    |
    */

    'skip_empty_values' => true,

    /*
    |--------------------------------------------------------------------------
    | Default sort type
    |--------------------------------------------------------------------------
    |
    | Default sorting type if request parameter is empty
    |
    */

    'sort_type' => Shureban\LaravelSearcher\Enums\SortType::Desc,
];
