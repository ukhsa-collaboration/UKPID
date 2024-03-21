<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Code extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'default' => 'bool',
    ];

    protected $fillable = [
        'code_table_id',
        'name',
        'code',
        'default',
        'additional_data',
    ];

    /**
     * Get the code table that the code is for.
     */
    public function codeTable(): BelongsTo
    {
        return $this->belongsTo(CodeTable::class);
    }
}
