<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnquiryRequest;
use App\Http\Requests\UpdateEnquiryRequest;
use App\Http\Resources\EnquiryCollection;
use App\Http\Resources\EnquiryResource;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EnquiryController extends Controller
{
    /**
     * @return EnquiryCollection
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Enquiry::class);

        $query = Enquiry::query()->select(['key', 'agentName', 'contactName', 'enquiryDate', 'enquiryTime']);

        if ($request->has('field')) {
            $query->where('field', 'like', '%'.$request->input('field').'%');
        }

        $perPage = $request->input('perPage', 100);

        $enquiries = $query->paginate($perPage)->appends([
            'field' => $request->input('field'),
            'perPage' => $perPage,
        ]);

        return new EnquiryCollection($enquiries);
    }

    /**
     * @return EnquiryResource
     */
    public function store(StoreEnquiryRequest $request)
    {
        Gate::authorize('create', Enquiry::class);

        $enquiry = Enquiry::create(array_merge($request->validated(), ['author' => $request->user()->email]));

        return new EnquiryResource($enquiry);
    }

    /**
     * @return EnquiryResource
     */
    public function show(Enquiry $enquiry)
    {
        Gate::authorize('view', $enquiry);

        return new EnquiryResource($enquiry);
    }

    /**
     * @return EnquiryResource
     */
    public function update(UpdateEnquiryRequest $request, Enquiry $enquiry)
    {
        Gate::authorize('update', $enquiry);
        $enquiry->update($request->validated());

        return new EnquiryResource($enquiry);
    }
}
