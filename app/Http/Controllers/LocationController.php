<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationStoreRequest;
use App\Http\Requests\LocationUpdateRequest;
use App\Http\Resources\LocationCollection;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    /**
     * @var Location
     */
    protected Location $location;

    /**
     * @param Location $location
     */
    public function __construct(Location $location)
    {
        $this->location = $location;
        $this->authorizeResource(Location::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $locations = Location::query()
            ->get();

        return (new LocationCollection($locations))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LocationStoreRequest $request
     * @return JsonResponse
     */
    public function store(LocationStoreRequest $request): JsonResponse
    {
        $location = auth()->user()->locations()
            ->create(
                $request->validated()
            );

        return (new LocationResource($location))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Location $location
     * @return JsonResponse
     */
    public function show(Location $location): JsonResponse
    {
        return (new LocationResource($location))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LocationUpdateRequest $request
     * @param \App\Models\Location $location
     * @return JsonResponse
     */
    public function update(LocationUpdateRequest $request, Location $location): JsonResponse
    {
        $location->update(
            $request->validated()
        );

        return (new LocationResource($location))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Location $location
     * @return JsonResponse
     */
    public function destroy(Location $location): JsonResponse
    {
        $location->delete();

        return response()
            ->json([], JsonResponse::HTTP_NO_CONTENT);
    }
}
