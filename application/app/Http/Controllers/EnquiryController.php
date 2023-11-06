<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;
use App\Http\Resources\EnquiryResource;
use App\Http\Resources\EnquiryCollection;

class EnquiryController extends Controller
{
    public function index()
    {
        $enquiries = Enquiry::paginate(10);
        return new EnquiryCollection($enquiries);
    }

    public function store(Request $request)
    {
        // todo validate
        // $validatedData = $request->validate($request->toArray());
        $enquiry = Enquiry::create($request->toArray());
        return new EnquiryResource($enquiry);
    }

    public function show(Enquiry $enquiry)
    {
        return new EnquiryResource($enquiry);
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        // todo validate
        // $validatedData = $request->validate($request->toArray());
        $enquiry->update($request->toArray());
        return new EnquiryResource($enquiry);
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();
        return response()->json(null, 204); // No content
    }
}
