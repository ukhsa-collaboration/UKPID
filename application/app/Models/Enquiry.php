<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Enquiry extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'Enquiries';

    protected $guarded = [];

    protected $primaryKey = 'key';

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return $this->primaryKey;
    }
}
