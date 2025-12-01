<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Concerns\InteractsWithServices;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use HandlesMediaUploads;
    use InteractsWithServices;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Service::class);

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

        $services = $query->paginate(15)->withQueryString();

        return view('dashboard.services.index', [
            'services' => $services,
            'categories' => Service::CATEGORIES,
            'filters' => $request->only(['search', 'category', 'status', 'featured', 'sort', 'direction']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Service::class);

        $establishments = auth()->user()?->establishments()->pluck('name', 'id') ?? collect();

        return view('dashboard.services.create', [
            'categories' => Service::CATEGORIES,
            'establishments' => $establishments,
        ]);
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $this->authorize('create', Service::class);

        $data = $this->prepareServicePayload($request->validated());
        $data['slug'] = $this->generateServiceSlug($data['name']);

        $service = Service::create($data);

        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Service created successfully.');
    }

    public function show(Service $service): View
    {
        $this->authorize('view', $service);

        $service->load('establishment');

        return view('dashboard.services.show', [
            'service' => $service,
            'categories' => Service::CATEGORIES,
        ]);
    }

    public function edit(Service $service): View
    {
        $this->authorize('update', $service);

        $establishments = auth()->user()?->establishments()->pluck('name', 'id') ?? collect();

        return view('dashboard.services.edit', [
            'service' => $service,
            'categories' => Service::CATEGORIES,
            'establishments' => $establishments,
        ]);
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $data = $this->prepareServicePayload($request->validated(), $service);

        if (isset($data['name']) && $data['name'] !== $service->name) {
            $data['slug'] = $this->generateServiceSlug($data['name'], $service->id);
        }

        $service->update($data);

        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->authorize('delete', $service);

        $this->deleteStoredFiles($service->images);
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Service removed successfully.');
    }

}

