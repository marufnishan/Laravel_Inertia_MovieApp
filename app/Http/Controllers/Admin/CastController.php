<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CastController extends Controller
{
    public function index()
    {
        $perPage = Request::input('perPage') ?: 5;
        return Inertia::render('Casts/index',[
            'casts' => Cast::query()
            ->when(Request::input('search'),function($query,$search){
                $query->where('name','Like',"%{$search}%");
            })
            ->paginate($perPage)
            ->withQueryString(),
            'filters' => Request::only(['search','perPage'])
        ]);
    } 

    public function store()
    {
        $cast = Cast::where('tmdb_id',Request::input('castTMDBId'))->first();
        if($cast){
            return Redirect::back()->with('flash.banner','Cast Exists .');
        }
        $tmdb_cast = Http::get(config('services.tmdb.endpoint').'person/'. Request::input('castTMDBId') .'?api_key='.config('services.tmdb.secrect').'&language=en-US
        ');

                        if ($tmdb_cast->successful()) {
                        Cast::create([
                        'tmdb_id' => $tmdb_cast['id'],
                        'name'    => $tmdb_cast['name'],
                        'slug'    => Str::slug($tmdb_cast['name']),
                        'poster_path' => $tmdb_cast['profile_path']
                        ]);
                            return Redirect::back()->with('flash.banner','Cast Created .');
                        }
                        else 
                        {
                            return Redirect::back()->with('flash.banner','Api Error .');
                        }
    }
}
