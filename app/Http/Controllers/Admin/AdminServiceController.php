<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ModificationField;
use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminServiceController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index()
    {
        $services = Service::latest()->get();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:services',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('services')->ignore($service->id)],
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }

    /**
     * Display the specified service details (fields and prices).
     */
    public function show(Service $service)
    {
        $service->load('modificationFields', 'servicePrices');
        $activeFields = $service->modificationFields()->where('is_active', 1)->get();
        // User types for price configuration
        $userTypes = ['user', 'agent', 'admin'];
        return view('admin.services.show', compact('service', 'userTypes', 'activeFields'));
    }

    // --- Field Management ---

    public function storeField(Request $request, Service $service)
    {
        $validated = $request->validate([
            'field_name' => 'required|string|max:150',
            'field_code' => 'required|string|max:50|unique:modification_fields',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $service->modificationFields()->create($validated);

        return back()->with('success', 'Field added successfully.');
    }

    public function updateField(Request $request, Service $service, ModificationField $field)
    {
        $validated = $request->validate([
            'field_name' => 'required|string|max:150',
            'field_code' => ['required', 'string', 'max:50', Rule::unique('modification_fields')->ignore($field->id)],
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $field->update($validated);

        return back()->with('success', 'Field updated successfully.');
    }
    
    public function destroyField(Service $service, ModificationField $field)
    {
         $field->delete();
         return back()->with('success', 'Field deleted successfully.');
    }


    // --- Price Management ---

    public function storePrice(Request $request, Service $service)
    {
        $validated = $request->validate([
            'modification_field_id' => 'nullable|exists:modification_fields,id',
            'user_type' => 'required|in:user,agent,admin',
            'price' => 'required|numeric|min:0',
        ]);

        // Use updateOrCreate to handle both create and update logic for prices
        // Unique constraint is on [service_id, modification_field_id, user_type]
        ServicePrice::updateOrCreate(
            [
                'service_id' => $service->id,
                'modification_field_id' => $request->modification_field_id,
                'user_type' => $request->user_type,
            ],
            [
                'price' => $request->price
            ]
        );

        return back()->with('success', 'Price updated successfully.');
    }
}
