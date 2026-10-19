<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * Historial de todo lo que ha ido pasando en el sistema.
     */
    public function index(): View
    {
        $registros = ActivityLog::latest()->paginate(20);

        return view('actividad.index', compact('registros'));
    }
}
