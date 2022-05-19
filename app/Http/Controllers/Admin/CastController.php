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
        return Inertia::render('Casts/index',[
            'casts' => Cast::paginate(5),
            'filters' => Request::only(['search','perPage'])
        ]);
    } 

    public function store()
    {
        $cast = Cast::where('tmdb_id',Request::input('castTMDBId'))->first();
        if($cast){
            return Redirect::route('admin.casts.index')->with('flash.banner','Cast Exists .');
        }
        $tmdb_cast = Http::get('https://api.themoviedb.org/3/person/'. Request::input('castTMDBId') .'?api_key=006906749cec020af929c3acc97f61ca&language=en-US
        ');

                        if ($tmdb_cast->successful()) {
                        Cast::create([
                        'tmdb_id' => $tmdb_cast['id'],
                        'name'    => $tmdb_cast['name'],
                        'slug'    => Str::slug($tmdb_cast['name']),
                        'poster_path' => $tmdb_cast['profile_path']
                        ]);
                            return Redirect::route('admin.casts.index')->with('flash.banner','Cast Created .');
                        }
                        else 
                        {
                            return Redirect::route('admin.casts.index')->with('flash.banner','Api Error .');
                        }
    }
}
