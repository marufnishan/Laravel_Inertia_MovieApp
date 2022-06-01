<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    public function index()
    {
        $perPage = Request::input('perPage') ?: 5;
        return Inertia::render('Genres/index',[
            'genres' => Genre::query()
            ->when(Request::input('search'),function($query,$search){
                $query->where('title','Like',"%{$search}%");
            })
            ->paginate($perPage)
            ->withQueryString(),
            'filters' => Request::only(['search','perPage'])
        ]);
    } 

    public function store()
    {
        
        $tmdb_genres = Http::get(config('services.tmdb.endpoint').'genre/movie/list?api_key='.config('services.tmdb.secrect').'&language=en-US');

        if($tmdb_genres->successful())
        {
            $tmdb_genres_json = $tmdb_genres->json();
            foreach($tmdb_genres_json as $single_tmdb_genre){
                foreach($single_tmdb_genre as $tgenre){
                    $genre = Genre::where('tmdb_id',$tgenre['id'])->first();
                    if(!$genre){
                        Genre::create([
                            'tmdb_id' => $tgenre['id'],
                            'title' => $tgenre['name'],
                            'slug'    => Str::slug($tgenre['name']),
                        ]);
                    }
                }
            }

            return Redirect::back()->with('flash.banner','Genre created .');
        }
        return Redirect::back()->with('flash.banner','Api Error .');
    }
}
