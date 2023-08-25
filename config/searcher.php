<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default per page value
    |--------------------------------------------------------------------------
    |
    | Default number of elements for paginate request
    |
    */

    'per_page_value' => 20,

    /*
    |--------------------------------------------------------------------------
    | Default per page field name
    |--------------------------------------------------------------------------
    |
    | Default name of per_page field in request
    |
    */

    'per_page_field_name' => 'per_page',

    /*
    |--------------------------------------------------------------------------
    | Default sort column name
    |--------------------------------------------------------------------------
    |
    | Default column for sorting if request parameter is empty
    | This value related with column in your DB
    |
    */

    'sort_column_name' => 'id',

    /*
    |--------------------------------------------------------------------------
    | Default sort column
    |--------------------------------------------------------------------------
    |
    | Default name of sort filed in request
    |
    */

    'sort_column_field_name' => 'sort_column',

    /*
    |--------------------------------------------------------------------------
    | Default sort type
    |--------------------------------------------------------------------------
    |
    | Default sorting type if request parameter is empty
    |
    */

    'sort_type_value' => Shureban\LaravelSearcher\Enums\SortType::Desc,

    /*
    |--------------------------------------------------------------------------
    | Default sort type field name
    |--------------------------------------------------------------------------
    |
    | Default name of sort type filed in request
    |
    */

    'sort_type_field_name' => 'sort_type',

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
];
