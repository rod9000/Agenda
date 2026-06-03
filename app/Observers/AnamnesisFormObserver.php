<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\AnamnesisForm;

class AnamnesisFormObserver
{
    public function created(AnamnesisForm $form)
    {
        $form->load('customer');
        ActivityLog::log('created', $form,
            "Ficha de anamnese para '{$form->customer?->name}' foi cadastrada.",
            null, $form->toArray());
    }

    public function updated(AnamnesisForm $form)
    {
        $old = $form->getOriginal();
        $changes = [];
        foreach ($form->getChanges() as $key => $value) {
            if ($key !== 'updated_at') {
                $changes[] = "$key: {$old[$key]} → $value";
            }
        }
        if ($changes) {
            $form->load('customer');
            ActivityLog::log('updated', $form,
                "Ficha de anamnese de '{$form->customer?->name}' foi atualizada: " . implode(', ', $changes),
                $old, $form->toArray());
        }
    }

    public function deleted(AnamnesisForm $form)
    {
        $form->load('customer');
        ActivityLog::log('deleted', $form,
            "Ficha de anamnese de '{$form->customer?->name}' foi removida.",
            $form->toArray(), null);
    }
}
