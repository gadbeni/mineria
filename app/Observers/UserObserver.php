<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserEdit;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Registra en user_edits la creación de un usuario (todo como nuevo).
     */
    public function created(User $user)
    {
        $fields = User::auditFields();

        $before = $after = $changed = [];
        foreach ($fields as $key => $label) {
            $newDisp = User::displayAudit($key, $user->{$key});
            $before[$label]  = '—';
            $after[$label]   = $newDisp;
            $changed[$label] = ['antes' => '—', 'despues' => $newDisp];
        }

        UserEdit::create([
            'user_id'   => $user->id,
            'action'    => 'Usuario creado',
            'before'    => $before,
            'after'     => $after,
            'changed'   => $changed,
            'unchanged' => [],
            'edited_by' => Auth::id(),
            'edited_at' => now(),
        ]);
    }

    /**
     * Registra en user_edits cada modificación de un usuario (campos auditados).
     */
    public function updated(User $user)
    {
        $fields      = User::auditFields();
        $changesRaw  = $user->getChanges();
        $changedKeys = array_intersect(array_keys($changesRaw), array_keys($fields));

        if (empty($changedKeys)) {
            return; // no cambió ningún campo auditado
        }

        $before = $after = $changed = $unchanged = [];
        foreach ($fields as $key => $label) {
            $oldDisp = User::displayAudit($key, $user->getOriginal($key));
            $newDisp = User::displayAudit($key, $user->{$key});
            $before[$label] = $oldDisp;
            $after[$label]  = $newDisp;
            if (in_array($key, $changedKeys, true)) {
                $changed[$label] = ['antes' => $oldDisp, 'despues' => $newDisp];
            } else {
                $unchanged[$label] = $newDisp;
            }
        }

        // Acción legible
        $action = 'Usuario modificado';
        if (count($changedKeys) === 1 && in_array('status', $changedKeys, true)) {
            $action = ((int) $user->status === 1) ? 'Usuario activado' : 'Usuario desactivado';
        } elseif (count($changedKeys) === 1 && in_array('password', $changedKeys, true)) {
            $action = 'Contraseña actualizada';
        }

        UserEdit::create([
            'user_id'   => $user->id,
            'action'    => $action,
            'before'    => $before,
            'after'     => $after,
            'changed'   => $changed,
            'unchanged' => $unchanged,
            'edited_by' => Auth::id(),
            'edited_at' => now(),
        ]);
    }
}
