<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class CastController extends Controller
{
    public function index()
    {
        return Inertia::render('Casts/index',[
            'casts' => Cast::paginate(5),
            'filters' => Request::only(['search','perPage'])
        ]);
    } 
}
