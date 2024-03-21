<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuditRequest;
use App\Http\Requests\StoreCodeTableRequest;
use App\Http\Requests\UpdateCodeTableRequest;
use App\Http\Resources\AuditResource;
use App\Http\Resources\CodeTableResource;
use App\Models\Audit;
use App\Models\CodeTable;
use App\Traits\AuditableController;

class CodeTableController extends Controller
{
    use AuditableController;

    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(CodeTable::class);
    }

    /**
     * Get all code tables.
     */
    public function index()
    {
        $codeTables = CodeTable::all();

        return CodeTableResource::collection($codeTables);
    }

    /**
     * Store a new code table.
     */
    public function store(StoreCodeTableRequest $request)
    {
        $codeTable = CodeTable::create($request->validated());

        return new CodeTableResource($codeTable);
    }

    /**
     * Get a code table.
     */
    public function show(CodeTable $codeTable)
    {
        return new CodeTableResource($codeTable->load('codes'));
    }

    /**
     * Update a code table.
     */
    public function update(UpdateCodeTableRequest $request, CodeTable $codeTable)
    {
        $codeTable->update($request->validated());

        return new CodeTableResource($codeTable);
    }

    /**
     * Get audit logs related to code tables.
     *
     * @response \Illuminate\Http\Resources\Json\AnonymousResourceCollection<App\Http\Resources\AuditResource>
     */
    public function audits(AuditRequest $request)
    {
        $validated = $request->validated();

        $audits = self::auditFiltersAndOrder(Audit::where('auditable_type', CodeTable::class), $validated);

        return AuditResource::collection($audits->paginate());
    }

    /**
     * Get audit logs relating to a code tables.
     *
     * @response \Illuminate\Http\Resources\Json\AnonymousResourceCollection<App\Http\Resources\AuditResource>
     */
    public function audit(AuditRequest $request, CodeTable $codeTable)
    {
        $validated = $request->validated();

        $audits = self::auditFiltersAndOrder($codeTable->audits(), $validated);

        return AuditResource::collection($audits->paginate());
    }
}
