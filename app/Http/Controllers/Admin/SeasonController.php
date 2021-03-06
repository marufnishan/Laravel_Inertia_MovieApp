<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Season;
use App\Models\TvShow;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class SeasonController extends Controller
{
    public function index (TvShow $tvShow)
    {
        $perPage = Request::input('perPage') ?: 5;
        return Inertia::render('TvShows/Seasons/index',[
            'seasons' => Season::query()
            ->where('tv_show_id',$tvShow->id)
            ->when(Request::input('search'),function($query,$search){
                $query->where('name','Like',"%{$search}%");
            })
            ->paginate($perPage)
            ->withQueryString(),
            'filters' => Request::only(['search','perPage']),
            'tvShow' => $tvShow
        ]);
    } 
}
