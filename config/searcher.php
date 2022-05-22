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
    | Default sort type
    |--------------------------------------------------------------------------
    |
    | Default sorting type if request parameter is empty
    |
    */

    'sort_type' => Shureban\LaravelSearcher\Enums\SortType::Desc,
];
