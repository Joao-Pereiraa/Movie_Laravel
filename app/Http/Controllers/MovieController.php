<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Category;
use App\Http\Resources\MovieResource;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use Illuminate\Support\Facades\Cache;

class MovieController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        
      $moviesModel = app(Movie::class);

      $MoviesResource = new MovieResource($moviesModel ->with('category')->paginate('10'));

      return view('movies.index', ['movies' => $MoviesResource]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoryModel = app(Category::class);

        Cache::forget('category');

        $categoriesResource = Cache::remember('category', (60*5), function () use($categoryModel) {
            return CategoryResource::collection($categoryModel->all());
        });
        return view('movies.create', ['categories' => $categoriesResource]);
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function store(StoreMovieRequest $request)
    {
        $data = $request->validated();

        $MovieModel = app(Movie::class);

        $Movie = $MovieModel->create($data);

        if($Movie){
            return redirect()->route('Movies.index')->with('success', 'Filme cadastrado com sucesso!');
        }
        else{
            return redirect()->route('Movies.index')->with('warning', 'Erro ao cadastrar o Filme!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $MovieModel = app(Movie::class);
        $categoryModel = app(Category::class);
        $Movie = $MovieModel->with('category')->find($id);
        $categoriesResource = Cache::remember('category', (60*5), function () use($categoryModel) {
            return CategoryResource::collection($categoryModel->all());
        });
        $MovieResource =  new MovieResource($Movie);
        return view('Movies.edit', compact('MovieResource','categoriesResource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMovieRequest $request, $id)
    {
        $data = $request->validated();

        $MovieModel = app(Movie::class);

        $Movie = $MovieModel->find($id)->update($data);

        if($Movie){
            return redirect()->route('Movies.index')->with('success', 'Filme atualizado com sucesso!');
        }
        else{
            return redirect()->route('Movies.index')->with('warning', 'Erro ao atualizar o Filme!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $MovieModel = app(Movie::class);
        $Movie = $MovieModel->find($id)->delete();

        return redirect()->route('movies.index')->with('warning', 'Livro deletado com sucesso!');
    }
}

