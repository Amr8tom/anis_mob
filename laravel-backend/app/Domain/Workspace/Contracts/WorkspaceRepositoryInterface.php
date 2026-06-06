<?php

declare(strict_types=1);

namespace App\Domain\Workspace\Contracts;

use App\Models\Workspace;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WorkspaceRepositoryInterface
{
    /**
     * @return LengthAwarePaginator<int, Workspace>
     */
    public function paginateActive(?string $filter, ?float $latitude, ?float $longitude, int $perPage): LengthAwarePaginator;

    public function findActiveWithDetails(string $id, ?float $latitude, ?float $longitude): Workspace;
}
