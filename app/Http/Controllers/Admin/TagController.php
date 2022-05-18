<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request ;
use Inertia\Inertia;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $perPage = Request::input('perPage') ?: 5;
        return Inertia::render('Tags/index',[
            'tags' => Tag::query()
                            ->when(Request::input('search'),function($query,$search){
                                $query->where('tag_name','Like',"%{$search}%");
                            })
                            ->paginate($perPage)
                            ->withQueryString(),
            'filters' => Request::only(['search','perPage'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Tags/create');
    }

    public function store()
    {
        Tag::create([
            'tag_name' => Request::input('tagName'),
            'slug' => Str::slug(Request::input('tagName'))
        ]);

        return Redirect::route('admin.tags.index')->with('flash.banner','Tag Created .');
    }
}
