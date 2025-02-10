<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\PropertyRequest;
use App\Http\Resources\Property\DashboardPropertyResource;
use App\Http\Resources\Property\EditPropertyResource;
use App\Models\Property;
use App\Repositories\PropertyRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use PHPUnit\Exception;

class PropertyController extends Controller
{
    protected $propertyRepository;

    /**
     * Inject the PropertyRepository into the controller.
     *
     * @param PropertyRepositoryInterface $propertyRepository
     */
    public function __construct(PropertyRepositoryInterface $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * ## Get User Properties
     *
     * Retrieves all properties associated with a given user.
     *
     * @param int $userId The ID of the user whose properties are to be retrieved.
     * @return Collection A collection of Property models.
     */
    public function getUserProperties(): JsonResponse
    {
        $properties = $this->propertyRepository->getUserProperties(Auth::id());

        return response()->json([
            'status' => 'success',
            'properties' => DashboardPropertyResource::collection($properties),
        ], 200);
    }
    /**
     * ## Create or Update Property
     *
     * - If an ID is provided, it updates the existing property.
     * - If no ID is provided, it creates a new property.
     * - Ensures the authenticated user is the owner before updating.
     * - Returns a JSON response with success or error messages.
     *
     * @param PropertyRequest $request
     * @param int|null $id
     * @return JsonResponse
     */
    public function createOrUpdate(PropertyRequest $request, $id = null): JsonResponse
    {
        try {
            $data = $request->validated();
            $property = $this->propertyRepository->createOrUpdate($data, $id);

            return response()->json([
                'message' => $id ? 'Property updated successfully.' : 'Property created successfully.',
                'property' => $property,
            ], $id ? 200 : 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * ## Edit Property
     *
     * Fetches a specific property for editing.
     * Ensures that the authenticated user is the owner of the property.
     *
     * @param int $propertyId
     * @return JsonResponse
     */
    public function edit(int $propertyId): JsonResponse
    {
        try {
            $property = $this->propertyRepository->getPropertyForEdit($propertyId);

            return response()->json([
                'status' => 'success',
                'property' => new EditPropertyResource($property),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

}
