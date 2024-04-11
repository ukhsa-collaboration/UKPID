<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class FormDefinition extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'FormDefinitions';

    protected $guarded = [];

    protected $primaryKey = 'version';

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return $this->primaryKey;
    }
}
