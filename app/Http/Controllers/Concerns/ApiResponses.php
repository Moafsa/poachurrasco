<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    protected function successResponse(mixed $data, array $meta = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => (object) $meta,
        ], $status);
    }

    protected function createdResponse(mixed $data, array $meta = []): JsonResponse
    {
        return $this->successResponse($data, $meta, 201);
    }

    protected function paginatedResponse(LengthAwarePaginator $paginator): JsonResponse
    {
        return $this->successResponse($paginator->items(), [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
        ]);
    }

    protected function deletedResponse(): JsonResponse
    {
        return response()->json(['success' => true], 204);
    }
}












