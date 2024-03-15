<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuditRequest;
use App\Http\Requests\StoreSourceOfEnquiryRequest;
use App\Http\Requests\UpdateSourceOfEnquiryRequest;
use App\Http\Resources\AuditResource;
use App\Http\Resources\SourceOfEnquiryResource;
use App\Models\Audit;
use App\Models\SourceOfEnquiry;
use App\Traits\AuditableController;

class SourceOfEnquiryController extends Controller
{
    use AuditableController;

    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(SourceOfEnquiry::class);
    }

    /**
     * Get all sources of enquiry.
     */
    public function index()
    {
        $sources = SourceOfEnquiry::all();

        return SourceOfEnquiryResource::collection($sources);
    }

    /**
     * Store a new source of enquiry.
     */
    public function store(StoreSourceOfEnquiryRequest $request)
    {
        $sourceOfEnquiry = SourceOfEnquiry::create($request->validated());

        return new SourceOfEnquiryResource($sourceOfEnquiry);
    }

    /**
     * Get a source of enquiry.
     */
    public function show(SourceOfEnquiry $sourceOfEnquiry)
    {
        return new SourceOfEnquiryResource($sourceOfEnquiry);
    }

    /**
     * Update a source of enquiry.
     */
    public function update(UpdateSourceOfEnquiryRequest $request, SourceOfEnquiry $sourceOfEnquiry)
    {
        $sourceOfEnquiry->update($request->validated());

        return new SourceOfEnquiryResource($sourceOfEnquiry);
    }

    /**
     * Get audit logs related to sources of enquiry.
     *
     * @response \Illuminate\Http\Resources\Json\AnonymousResourceCollection<App\Http\Resources\AuditResource>
     */
    public function audits(AuditRequest $request)
    {
        $validated = $request->validated();

        $audits = self::auditFiltersAndOrder(Audit::where('auditable_type', SourceOfEnquiry::class), $validated);

        return AuditResource::collection($audits->paginate());
    }

    /**
     * Get audit logs relating to a sources of enquiry.
     *
     * @response \Illuminate\Http\Resources\Json\AnonymousResourceCollection<App\Http\Resources\AuditResource>
     */
    public function audit(AuditRequest $request, SourceOfEnquiry $sourceOfEnquiry)
    {
        $validated = $request->validated();

        $audits = self::auditFiltersAndOrder($sourceOfEnquiry->audits(), $validated);

        return AuditResource::collection($audits->paginate());
    }
}
