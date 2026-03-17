<?php

// app/Http/Controllers/DashboardRouter.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardRouter extends Controller
{

    public function __invoke(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // permitir avanzar solo si el campo `status` del usuario es true
        if ($user->status == "false") {
            return response()->view('inactiva', ['user' => $user, 'message' => 'Cuenta inactiva.'], 403);
        }

        return match (true) {
            $user->hasRole('Admin') => redirect()->route('admin.dashboard'),
            $user->hasRole('Profesor') => redirect()->route('profesor.dashboard'),
            $user->hasRole('Estudiante') => redirect()->route('estudiante.dashboard'),
            default => redirect()->route('#'),
        };
    }
}
