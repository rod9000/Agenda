<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->paginate(15);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:100|unique:services,name',
            'duration_min' => 'required|integer|min:15',
            'price'        => 'required|numeric|min:0',
            'estimated_product_cost' => 'nullable|numeric|min:0',
            'color_hex'    => 'nullable|string|max:7',
            'description'  => 'nullable|string',
        ]);

        $data['active'] = $request->boolean('active', true);

        Service::create($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Procedimento cadastrado com sucesso!');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:100|unique:services,name,' . $service->id,
            'duration_min' => 'required|integer|min:15',
            'price'        => 'required|numeric|min:0',
            'estimated_product_cost' => 'nullable|numeric|min:0',
            'color_hex'    => 'nullable|string|max:7',
            'description'  => 'nullable|string',
        ]);

        $data['active'] = $request->boolean('active', true);

        $service->update($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Procedimento atualizado com sucesso!');
    }

    public function destroy(Service $service)
    {
        $service->appointments()->delete();
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', 'Procedimento excluído com sucesso!');
    }

    public function sync()
    {
        $defaults = config('services_default');
        $created = 0;
        $updated = 0;

        foreach ($defaults as $data) {
            $service = Service::where('name', $data['name'])->first();
            if ($service) {
                $service->update($data);
                $updated++;
            } else {
                Service::create($data);
                $created++;
            }
        }

        $names = array_column($defaults, 'name');
        $deactivated = Service::whereNotIn('name', $names)->where('active', true)->update(['active' => false]);

        return redirect()->route('admin.services.index')
            ->with('success', "Sincronizado! $created criado(s), $updated atualizado(s)" . ($deactivated ? ", $deactivated desativado(s)" : ''));
    }
}
