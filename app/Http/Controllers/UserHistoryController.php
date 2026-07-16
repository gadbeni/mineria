<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserHistoryController extends Controller
{
    /**
     * Muestra el historial de modificaciones de un usuario (timeline).
     */
    public function show($id)
    {
        if (!auth()->user()->hasPermission('browse_users')) {
            abort(403, 'No tienes permiso para ver el historial de usuarios.');
        }

        $user = User::withTrashed()->findOrFail($id);

        $edits = $user->edits()->with('editedBy')->get();

        return view('users.history', compact('user', 'edits'));
    }
}
