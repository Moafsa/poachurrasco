<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\ApiResponses;
use App\Http\Controllers\Concerns\InteractsWithServices;
use App\Http\Controllers\Controller;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use ApiResponses;
    use InteractsWithServices;

    public function index(Request $request): JsonResponse
    {
        $query = Service::with('establishment')
            ->whereHas('establishment', fn ($q) => $q->where('user_id', auth()->id()));

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->string('status')->toString() === 'active');
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->filled('sort')) {
            $sort = $request->string('sort')->toString();
            $direction = $request->string('direction', 'asc')->toString() === 'desc' ? 'desc' : 'asc';

            if (in_array($sort, ['price', 'duration_minutes', 'capacity', 'view_count'], true)) {
                $query->orderBy($sort, $direction);
            }
        } else {
            $query->latest();
        }

        $services = $query->paginate($request->integer('per_page', 15))->withQueryString();

        return $this->paginatedResponse($services);
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        $data = $this->prepareServicePayload($request->validated());
        $data['slug'] = $this->generateServiceSlug($data['name']);

        $service = Service::create($data);

        return $this->createdResponse($service->load('establishment'));
    }

    public function show(Service $service): JsonResponse
    {
        $this->authorize('view', $service);

        return $this->successResponse($service->load('establishment'));
    }

    public function update(UpdateServiceRequest $request, Service $service): JsonResponse
    {
        $data = $this->prepareServicePayload($request->validated(), $service);

        if (isset($data['name']) && $data['name'] !== $service->name) {
            $data['slug'] = $this->generateServiceSlug($data['name'], $service->id);
        }

        $service->update($data);

        return $this->successResponse($service->fresh('establishment'));
    }

    public function destroy(Service $service): JsonResponse
    {
        $this->authorize('delete', $service);
        $this->deleteStoredFiles($service->images);
        $service->delete();

        return $this->deletedResponse();
    }
}












