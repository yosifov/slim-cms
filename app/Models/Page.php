<?php

namespace App\Models;

class Page extends Model
{
    /**
     * Model unique key
     *
     * @var int
     */
    protected int $id;

    /**
     * Model accessible fields
     *
     * @var array
     */
    protected static array $fields = [
        'title',
        'subtitle',
        'description'
    ];
}
