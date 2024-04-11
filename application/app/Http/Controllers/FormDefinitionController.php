<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFormDefinition;
use App\Http\Resources\FormDefinitionResource;
use App\Models\FormDefinition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class FormDefinitionController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', FormDefinition::class);

        return FormDefinitionResource::collection(FormDefinition::all());
    }

    public function show(FormDefinition $formDefinition)
    {
        try {
            Gate::authorize('view', $formDefinition);

            return new FormDefinitionResource($formDefinition);
        } catch (\Exception $e) {
            Log::error('Failed to save form definition', ['error' => $e->getMessage()]);

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreFormDefinition $request)
    {
        try {
            Gate::authorize('create', FormDefinition::class);

            $latestVersion = (int) (FormDefinition::latest()->first()['version'] ?? 0);
            $requiredDesktopVersion = $request->input('requiredDesktopVersion');

            $formDefinition = new FormDefinition();
            $formDefinition->fill([
                'version' => (string) ($latestVersion + 1),
                'requiredDesktopVersion' => $requiredDesktopVersion,
                'definition' => $request->all(),
            ]);
            $formDefinition->save();

            return response()->json(['message' => 'Form definition saved successfully'], 201);

        } catch (\Exception $e) {
            Log::error('Failed to save form definition', ['error' => $e->getMessage()]);

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * At this point, the validation has already occurred - see \App\Http\Requests\StoreFormDefinition::withValidator
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateForm(StoreFormDefinition $request)
    {
        try {
            Gate::authorize('create', FormDefinition::class);

            return response()->json(['message' => 'Validation passed successfully.']);
        } catch (\Exception $e) {
            Log::error('Failed to validate form', ['error' => $e->getMessage()]);

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Updates the required desktop version only
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(FormDefinition $formDefinition)
    {
        try {
            Gate::authorize('update', $formDefinition);

            $validatedData = request()->validate(['requiredDesktopVersion' => 'required|numeric']);
            $formDefinition->requiredDesktopVersion = (string) $validatedData['requiredDesktopVersion'];
            $formDefinition->save();

            return response()->json('Required desktop version set successfully');

        } catch (\Exception $e) {
            Log::error('Failed to save required desktop version', ['error' => $e->getMessage()]);

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
