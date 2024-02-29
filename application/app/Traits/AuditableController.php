<?php

namespace App\Traits;

trait AuditableController
{
    protected static function auditFiltersAndOrder(mixed $auditQuery, mixed $validated)
    {
        if (array_key_exists('owner', $validated)) {
            $auditQuery = $auditQuery->byOwner($validated['owner']);
        }

        if (array_key_exists('field', $validated)) {
            $auditQuery = $auditQuery->byField($validated['field']);
        }

        if (array_key_exists('date_from', $validated)) {
            $auditQuery = $auditQuery->afterDate($validated['date_from']);
        }

        if (array_key_exists('date_to', $validated)) {
            $auditQuery = $auditQuery->beforeDate($validated['date_to']);
        }

        if (array_key_exists('event', $validated)) {
            $auditQuery = $auditQuery->byEvent($validated['event']);
        }

        $order = $validated['order'] ?? 'desc';
        $orderBy = $validated['order_by'] ?? 'date';

        $orderBy = match ($orderBy) {
            'id' => 'auditable_id',
            default => 'created_at',
        };

        $auditQuery = $auditQuery->orderBy($orderBy, $order);

        return $auditQuery;
    }
}
