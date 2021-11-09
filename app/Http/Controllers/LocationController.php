<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationCollection;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $location = $this->location->create(
            $request->except(['_token'])
        );

        return (new LocationResource($location))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
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
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Location $location
     * @return JsonResponse
     */
    public function update(Request $request, Location $location): JsonResponse
    {
        $location->update(
            $request->except(['_token'])
        );

        return (new LocationResource($location))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
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
            ->json([], Response::HTTP_NO_CONTENT);
    }
}
