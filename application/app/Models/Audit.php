<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Audit extends \OwenIt\Auditing\Models\Audit
{
    /**
     * Filter Audit logs by owner.
     */
    public function scopeByOwner(Builder $query, User $user): void
    {
        $query->where('user_type', $user::class)
            ->where('user_id', $user->id);
    }

    /**
     * Filter Audit logs before date.
     */
    public function scopeBeforeDate(Builder $query, string $datetime): void
    {
        $datetime = Carbon::parse($datetime, 'Europe/London');
        $query->where('created_at', '<', $datetime);
    }

    /**
     * Filter Audit logs after date.
     */
    public function scopeAfterDate(Builder $query, string $datetime): void
    {
        $datetime = Carbon::parse($datetime, 'Europe/London');
        $query->where('created_at', '>', $datetime);
    }

    /**
     * Filter Audit logs where the specified field was changed.
     */
    public function scopeByField(Builder $query, string $field): void
    {
        // Changed values are stored as a JSON key:value object, e.g: {"name": "John Smith", "email": "john@acme.org"}
        // We use a wildcard to match the field name, surrounded with double-quotes,
        // as this should never exist in the value (because in the value the quotes will be escaped).
        $query->where('old_values', 'LIKE', '%"'.$field.'"%')
            ->orWhere('new_values', 'LIKE', '%"'.$field.'"%');
    }

    /**
     * Filter Audit logs by event.
     */
    public function scopeByEvent(Builder $query, string $event): void
    {
        $query->where('event', $event);
    }
}
