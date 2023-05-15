<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FilmM;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\FilmResource;
use Illuminate\Support\Facades\Storage;
class Film extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['getfilm','getfilmbyid','getfilmsbycat','getByCategorie']]);
    // }



    public function addfilm(Request $r){

        $r->validate([
            'titre' => 'required',
            'description' => 'required',
            'thumbnial_url' => 'required',
            'réalisateur' => 'required',
            'langue' => 'required',
            'categorie_id' => 'required',
            'url' => 'required',
            'duration' => 'required',
        ]);
        $videoname = Str::random(20).'.'.$r->file('url')->getClientOriginalExtension();
        $imgname = Str::random(20).'.'.$r->file('thumbnial_url')->getClientOriginalExtension();
        $film=new FilmM();
        $film->titre=$r->titre;
        $film->description=$r->description;
        $film->thumbnial_url=Storage::url('thumbnail/'.$imgname);
        $film->réalisateur=$r->réalisateur;
        $film->langue=$r->langue;
        $film->categorie_id=$r->categorie_id;
        $film->url=Storage::url($videoname);
        $film->duration=$r->duration;
        $film->save();

        Storage::disk('thumbnail')->put($imgname, file_get_contents($r->file('thumbnial_url')));
        Storage::disk('videos')->put($videoname, file_get_contents($r->file('url')));

        return response()->json($film,200);






    }
    public function getfilm(Request $r){
        $SearchBytitle=$r->query('SearchBytitle');
        $nbPerPage=$r->query('nbPerPage');
        $sortby=$r->query('sortby');
        $sorttype=$r->query('sorttype');

        //with search by title contains
      //$film=FilmM::orderBy($sortby,$sorttype)->paginate($nbPerPage)

        //with search by title contains
        $film=FilmM::where('titre','like','%'.$SearchBytitle.'%')->orderBy($sortby,$sorttype)->paginate($nbPerPage);
    return FilmResource::collection($film);
    }
    public function getfilmbyid($id){
        $film=FilmM::find($id);
        return new FilmResource($film);
    }
    public function updatefilm($id,   Request $r){
print_r($id);
        $r->validate([
            
            'titre' => 'required',
            'description' => 'required',
            'thumbnial_url' => 'required',
            'réalisateur' => 'required',
            'langue' => 'required',
            'categorie_id' => 'required',
            'url' => 'required',
            'duration' => 'required',
        ]);
        $videoname = Str::random(20).'.'.$r->file('url')->getClientOriginalExtension();
        $imgname = Str::random(20).'.'.$r->file('thumbnial_url')->getClientOriginalExtension();
        Storage::disk('public')->delete(basename($r->url));
        Storage::disk('thumbnail')->delete(basename($r->thumbnial_url));
        $film=FilmM::find($id);
        print_r("film ".$film);
        $film->titre=$r->titre;
        $film->description=$r->description;
        $film->thumbnial_url=Storage::url('thumbnail/'.$imgname);
        $film->réalisateur=$r->réalisateur;
        $film->langue=$r->langue;
        $film->categorie_id=$r->categorie_id;
        $film->url=Storage::url($videoname);
        $film->duration=$r->duration;
        $film->save();


        // Storage::disk('public')->put($videoname, file_get_contents($r->file('url')));
        // Storage::disk('thumbnail')->put($imgname, file_get_contents($r->file('thumbnial_url')));
        // return response()->json(['message'=>'updated'],200);

    }
    public function deletefilm($id){
        $film=FilmM::find($id);
        Storage::disk('public')->delete(basename($film->url));
        Storage::disk('thumbnail')->delete(basename($film->thumbnial_url));
        $film->delete();
        return response()->json(['message'=>'deleted'],200);
    }
    public function getfilmsbycat($id){
//take only 4 films
        $films=FilmM::where('categorie_id',$id)->orderBy('created_at','desc')->take(4)->get();
        return FilmResource::collection($films);
    }

    public function getLatestFilms(){
        // order by add date desc return only 4 films

        $films=FilmM::orderBy('created_at','desc')->take(4)->get();
    
        
            return response()->json($films,200);
        }


public function getfilmsbyIdcat($id){

    // if ($id==0) {
    //     $films=FilmM::orderBy('created_at','desc')->paginate(8);    
    // }else{
    //     $films=FilmM::where('categorie_id',$id)->orderBy('created_at','desc')->paginate(8);
    // }

    // return FilmResource::collection($films);
}
public function getByCategorie($id,Request $r){
    $SearchBytitle=$r->query('SearchBytitle');
    
if ($id==0) {
    $films=FilmM::where('titre','like','%'.$SearchBytitle.'%')->orderBy('created_at','desc')->paginate(4);
}else{
    $films=FilmM::where('categorie_id',$id)->where('titre','like','%'.$SearchBytitle.'%')->orderBy('created_at','desc')->paginate(4);
}
return FilmResource::collection($films);
}

    
}
