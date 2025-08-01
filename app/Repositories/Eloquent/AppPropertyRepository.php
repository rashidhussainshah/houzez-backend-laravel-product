<?php

namespace App\Repositories\Eloquent;

use App\Models\Property;
use App\Repositories\AppPropertyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AppPropertyRepository implements AppPropertyRepositoryInterface
{
    /**
     * ## Get Featured Properties
     * Retrieves the latest `is_featured` properties, limiting the result.
     *
     * @param int $limit The number of properties to return
     * @return Collection The collection of featured properties
     */
    public function getFeaturedProperties(int $limit): Collection
    {
        // return Property::where('is_featured', 1)
        //     ->where('property_status','published')
        //     ->latest()
        //     ->take($limit)
        //     ->get();
        return Property::with(['user.profile', 'assignedAgent.profile'])
        ->where('is_featured', 1)
        ->where('property_status','published')
        ->latest()
        ->take($limit)
        ->get();
    }

    /**
     * ## Get Latest Properties
     * Retrieves the latest properties, limiting the result.
     *
     * @param int $limit The number of properties to return
     * @return Collection The collection of latest properties
     */
    public function getLatestProperties(int $limit): Collection
    {
        return Property::where('property_status','published')
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * ## Get filtered properties based on provided criteria.
     *
     * @param string|null $search
     * @param array|null $propertyTypes
     * @param string|null $city
     * @param int|null $maxBedrooms
     * @param float|null $maxPrice
     * @return Collection
     */
    public function getFilteredProperties(
        ?string $search,
        ?array $propertyTypes,
        ?string $city,
        ?int $maxBedrooms,
        ?float $maxPrice
    ): Collection {
        return Property::where('property_status', 'published')
            ->when($search, fn($query) => $query->where('title', 'like', "%$search%"))
            ->when(!empty($propertyTypes), fn($query) => $query->whereIn('type', $propertyTypes))
            ->when($city, fn($query) => $query->where('city', $city))
            ->when($maxBedrooms && $maxBedrooms !== 'any', fn($query) => $query->where('bedrooms', '<=', $maxBedrooms))
            ->when($maxPrice && $maxPrice !== 'any', fn($query) => $query->where('price', '<=', $maxPrice))
            ->get();
    }

    /**
     * ## Find property by slug.
     *
     * @param string $slug
     * @return Property|null
     */
    public function findBySlug(string $slug): ?Property
    {
        return Property::where('slug', $slug)->first();
    }


    /**
     * ## Find property by slug in Demo01.
     *
     * @param string $slug
     * @return Property|null
     */
    public function findBySlugDemo01(string $slug): ?Property
    {
        return Property::where('slug', $slug)->first();
    }

    /**
     * ## Get filtered properties based on provided criteria.
     *
     * @param string|null $search
     * @param array|null $propertyTypes
     * @param string|null $city
     * @param int|null $maxBedrooms
     * @param float|null $maxPrice
     * @return Collection
     */
    public function getFilteredPropertiesDemo01(
        ?string $search,
        ?array $propertyTypes,
        ?array $cities,
        ?int $maxBedrooms,
        ?float $maxPrice,
        ?string $status
    ): Collection {
        return Property::where('property_status', 'published')
            ->when($search, fn($query) => $query->where('title', 'like', "%$search%"))
            ->when(!empty($propertyTypes), fn($query) => $query->whereIn('type', $propertyTypes))
            ->when(!empty($cities), fn($query) => $query->whereIn('city', $cities))
            ->when($maxBedrooms && $maxBedrooms !== 'any', fn($query) => $query->where('bedrooms', '<=', $maxBedrooms))
            ->when($maxPrice && $maxPrice !== 'any', fn($query) => $query->where('price', '<=', $maxPrice))
            ->when($status, fn($query) => $query->where('status', $status))
            ->get();
    }

    /**
     * ## Get Properties by Type
     * Retrieves all published properties by given type.
     *
     * @param string $type The property type
     * @return Collection The collection of properties of given type
     */
    public function getPropertiesByType(string $type): Collection
    {
        return Property::where('property_status', 'published')
            ->where('type', $type)
            ->latest()
            ->get();
    }

}
