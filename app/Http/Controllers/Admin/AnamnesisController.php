<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnamnesisForm;
use App\Models\Customer;
use Illuminate\Http\Request;

class AnamnesisController extends Controller
{
    public function index(Request $request)
    {
        $query = AnamnesisForm::with('customer', 'answeredBy');

        if ($search = $request->get('search')) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%");
            });
        }

        $forms = $query->latest()->paginate(15);

        return view('admin.anamnesis.index', compact('forms'));
    }

    public function create(Request $request)
    {
        $customers = Customer::orderBy('name')->get();
        $selectedCustomerId = $request->get('customer_id');

        return view('admin.anamnesis.create', compact('customers', 'selectedCustomerId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'heart_problem' => 'boolean',
            'high_pressure' => 'boolean',
            'low_pressure' => 'boolean',
            'diabetes' => 'boolean',
            'epilepsy' => 'boolean',
            'cancer' => 'boolean',
            'autoimmune' => 'boolean',
            'kidney_disease' => 'boolean',
            'hepatitis' => 'boolean',
            'hiv' => 'boolean',
            'pregnant' => 'boolean',
            'skin_disease' => 'boolean',
            'keloids' => 'boolean',
            'isotretinoin' => 'boolean',
            'cosmetic_procedure' => 'boolean',
            'recent_surgery' => 'boolean',
            'pacemaker' => 'boolean',
            'dental_implants' => 'boolean',
            'allergies' => 'boolean',
            'medications' => 'boolean',
            'medical_treatment' => 'boolean',
            'topical_medication' => 'boolean',
            'allergy_description' => 'nullable|string',
            'medication_description' => 'nullable|string',
            'medical_treatment_description' => 'nullable|string',
            'observation' => 'nullable|string',
            'consent' => 'accepted',
        ]);

        $data['answered_by'] = auth()->id();
        $data['answered_at'] = now();
        $data['consent'] = true;

        AnamnesisForm::create($data);

        return redirect()->route('admin.anamnesis.index')
            ->with('success', 'Ficha de anamnese cadastrada com sucesso!');
    }

    public function show(AnamnesisForm $anamnesis)
    {
        $anamnesis->load('customer', 'answeredBy');

        return view('admin.anamnesis.show', compact('anamnesis'));
    }

    public function edit(AnamnesisForm $anamnesis)
    {
        $customers = Customer::orderBy('name')->get();
        $anamnesis->load('customer');

        return view('admin.anamnesis.edit', compact('anamnesis', 'customers'));
    }

    public function update(Request $request, AnamnesisForm $anamnesis)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'heart_problem' => 'boolean',
            'high_pressure' => 'boolean',
            'low_pressure' => 'boolean',
            'diabetes' => 'boolean',
            'epilepsy' => 'boolean',
            'cancer' => 'boolean',
            'autoimmune' => 'boolean',
            'kidney_disease' => 'boolean',
            'hepatitis' => 'boolean',
            'hiv' => 'boolean',
            'pregnant' => 'boolean',
            'skin_disease' => 'boolean',
            'keloids' => 'boolean',
            'isotretinoin' => 'boolean',
            'cosmetic_procedure' => 'boolean',
            'recent_surgery' => 'boolean',
            'pacemaker' => 'boolean',
            'dental_implants' => 'boolean',
            'allergies' => 'boolean',
            'medications' => 'boolean',
            'medical_treatment' => 'boolean',
            'topical_medication' => 'boolean',
            'allergy_description' => 'nullable|string',
            'medication_description' => 'nullable|string',
            'medical_treatment_description' => 'nullable|string',
            'observation' => 'nullable|string',
            'consent' => 'accepted',
        ]);

        $data['consent'] = true;

        $anamnesis->update($data);

        return redirect()->route('admin.anamnesis.index')
            ->with('success', 'Ficha de anamnese atualizada com sucesso!');
    }

    public function destroy(AnamnesisForm $anamnesis)
    {
        $anamnesis->delete();

        return redirect()->route('admin.anamnesis.index')
            ->with('success', 'Ficha de anamnese removida com sucesso!');
    }
}
