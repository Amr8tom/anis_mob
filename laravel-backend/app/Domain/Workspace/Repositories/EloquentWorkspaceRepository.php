<?php

declare(strict_types=1);

namespace App\Domain\Workspace\Repositories;

use App\Domain\Workspace\Contracts\WorkspaceRepositoryInterface;
use App\Enums\SessionStatus;
use App\Enums\SessionType;
use App\Enums\VisitStatus;
use App\Enums\WorkspaceStatus;
use App\Models\Workspace;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final class EloquentWorkspaceRepository implements WorkspaceRepositoryInterface
{
    private const HAVERSINE = '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))';

    private const NEARBY_RADIUS_KM = 50;

    public function paginateActive(?string $filter, ?float $latitude, ?float $longitude, int $perPage): LengthAwarePaginator
    {
        $query = $this->baseQuery($latitude, $longitude);

        if ($filter === 'openNow') {
            $query->where('status', WorkspaceStatus::OPEN->value);
        }

        if ($filter === 'nearby' && $latitude !== null && $longitude !== null) {
            $this->applyBoundingBox($query, $latitude, $longitude, self::NEARBY_RADIUS_KM);
            $query->orderBy('distance_km');
        } else {
            $query->orderBy('name');
        }

        return $query->paginate($perPage);
    }

    private function applyBoundingBox(Builder $query, float $latitude, float $longitude, float $radiusKm): void
    {
        $latitudeDelta = $radiusKm / 111.0;
        $longitudeDelta = $radiusKm / max(111.0 * cos(deg2rad($latitude)), 0.01);

        $query
            ->whereBetween('latitude', [$latitude - $latitudeDelta, $latitude + $latitudeDelta])
            ->whereBetween('longitude', [$longitude - $longitudeDelta, $longitude + $longitudeDelta]);
    }

    public function findActiveWithDetails(string $id, ?float $latitude, ?float $longitude): Workspace
    {
        return $this->baseQuery($latitude, $longitude)->whereKey($id)->firstOrFail();
    }

    /**
     * @return Builder<Workspace>
     */
    private function baseQuery(?float $latitude, ?float $longitude): Builder
    {
        $query = Workspace::query()
            ->visibleToVisitors()
            ->withCount(['visits as current_occupancy' => fn (Builder $q) => $q->where('status', VisitStatus::CHECKED_IN->value)])
            ->with([
                'drinks',
                'sessions' => fn ($q) => $q
                    ->where('type', SessionType::STUDY_GROUP->value)
                    ->whereIn('status', [SessionStatus::UPCOMING->value, SessionStatus::IN_PROGRESS->value])
                    ->with('host')
                    ->withCount('participants')
                    ->orderBy('start_time'),
            ]);

        if ($latitude !== null && $longitude !== null) {
            // withCount() already added "workspaces.*"; only append the computed distance
            // so the occupancy count column is preserved.
            $query->selectRaw(self::HAVERSINE.' as distance_km', [$latitude, $longitude, $latitude]);
        }

        return $query;
    }
}
