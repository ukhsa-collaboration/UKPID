<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuditRequest;
use App\Http\Requests\StoreCodeRequest;
use App\Http\Requests\UpdateCodeRequest;
use App\Http\Resources\AuditResource;
use App\Http\Resources\CodeResource;
use App\Models\Audit;
use App\Models\Code;
use App\Traits\AuditableController;

class CodeController extends Controller
{
    use AuditableController;

    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Code::class);
    }

    /**
     * Get all codes.
     */
    public function index()
    {
        $codes = Code::all();

        return CodeResource::collection($codes);
    }

    /**
     * Store a new code.
     */
    public function store(StoreCodeRequest $request)
    {
        $code = Code::create($request->validated());

        return new CodeResource($code->load('codeTable'));
    }

    /**
     * Get a code.
     */
    public function show(Code $code)
    {
        return new CodeResource($code->load('codeTable'));
    }

    /**
     * Update a code.
     */
    public function update(UpdateCodeRequest $request, Code $code)
    {
        $code->update($request->validated());

        return new CodeResource($code->load('codeTable'));
    }

    /**
     * Get audit logs related to codes.
     *
     * @response \Illuminate\Http\Resources\Json\AnonymousResourceCollection<App\Http\Resources\AuditResource>
     */
    public function audits(AuditRequest $request)
    {
        $validated = $request->validated();

        $audits = self::auditFiltersAndOrder(Audit::where('auditable_type', Code::class), $validated);

        return AuditResource::collection($audits->paginate());
    }

    /**
     * Get audit logs relating to a codes.
     *
     * @response \Illuminate\Http\Resources\Json\AnonymousResourceCollection<App\Http\Resources\AuditResource>
     */
    public function audit(AuditRequest $request, Code $code)
    {
        $validated = $request->validated();

        $audits = self::auditFiltersAndOrder($code->audits(), $validated);

        return AuditResource::collection($audits->paginate());
    }
}
