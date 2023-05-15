<?php

namespace App\Http\Controllers;

use App\Http\Resources\FilmResource;
use Illuminate\Http\Request;
use App\Models\Serie;
use App\Http\Resources\SerieResource;
use App\Models\FilmM;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class Series extends Controller

{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['getseries','getseriebyid','getseriesbycat']]);
    // }


    public function addserie(Request $request){
        $request->validate([
            'titre' => 'required',
            'description' => 'required',
            'thumbnail_url' => 'required',
            'réalisateur' => 'required',
            'langue' => 'required',
            'nbepisodes' => 'required',
            'categorie_id' => 'required',
        ]);


        $imgname = Str::random(20).'.'.$request->file('thumbnail_url')->getClientOriginalExtension();
     
   $s= new Serie();
    $s->titre=$request->titre;
    $s->description=$request->description;
    $s->thumbnail_url=url(Storage::url('thumbnail/'.$imgname));
    $s->réalisateur=$request->réalisateur;
    $s->langue=$request->langue;
    $s->nbepisodes=$request->nbepisodes;
    $s->categorie_id=$request->categorie_id;
    $s->save();

    Storage::disk('thumbnail')->put($imgname, file_get_contents($request->file('thumbnail_url')));
        return response()->json($s,200);
    }

//     public function addserie(Request $r){
//         print_r("ajout");
//         $r->validate([
//             'titre' => 'required',
//             'description' => 'required',
//             'thumbnail_url' => 'required',
//             'réalisateur' => 'required',
//             'langue' => 'required',
//             'nbepisodes' => 'required',
//             'categorie_id' => 'required',
//         ]);
//         $imgname = Str::random(20).'.'.$r->file('thumbnail_url')->getClientOriginalExtension();
//    $s= new Serie();
//     $s->titre=$r->titre;
//     $s->description=$r->description;
//     $s->thumbnail_url=$r->url(Storage::url('thumbnail/'.$imgname));
//     $s->réalisateur=$r->réalisateur;
//     $s->langue=$r->langue;
//     $s->nbepisodes=$r->nbepisodes;
//     $s->categorie_id=$r->categorie_id;
//     $s->save();






//     Storage::disk('thumbnail')->put($imgname, file_get_contents($r->file('thumbnail_url')));

// print_r($s);
//         return response()->json("add",200);
//     }
    public function getseries(Request $r){

        $SearchBytitle=$r->query('SearchBytitle');
        $nbPerPage=$r->query('nbPerPage');
        $sortby=$r->query('sortby');
        $sorttype=$r->query('sorttype');

        $series=Serie::where('titre','like','%'.$SearchBytitle.'%')->orderBy($sortby,$sorttype)->paginate($nbPerPage);

        
return SerieResource::collection($series);
    }
    public function getseriebyid($id){
        $serie=Serie::find($id);
        return new SerieResource($serie);
    }
    public function updateserie(Request $request,Serie $serie){
        $serie->update($request->all());
        return response()->json($serie,200);
    }
    public function deleteserie($id){
        $serie=Serie::find($id);
        $serie->delete();
        return response()->json(['message'=>'deleted'],200);
    }
    public function getseriesbycat($id){
        $series=DB::table('series')->where('categorie_id',$id)->take(4)->get();
        return response()->json($series,200);
    }

public function getSaisonsFromEpisodes($id){
 
// select distinct(saison) from episodes where serie_id=1
$episodes=DB::table('episodes')->where('serie_id',$id)->distinct()->get('numero_saison');

return response()->json($episodes,200);


}


public function getByCategorie($id,Request $r){
    $SearchBytitle=$r->query('SearchBytitle');
   

if($id==0){
    $series=Serie::where('titre','like','%'.$SearchBytitle.'%')->paginate(4);
}else{
    $series=Serie::where('categorie_id',$id)->where('titre','like','%'.$SearchBytitle.'%')->paginate(4);
}
return SerieResource::collection($series);
}



public function searchSeriesOrFilms(Request $r){
    $SearchBytitle=$r->query('SearchBytitle');
    $series=Serie::where('titre','like','%'.$SearchBytitle.'%')->paginate(4);
    $films=FilmM::where('titre','like','%'.$SearchBytitle.'%')->paginate(4);
    return response()->json(['series'=>SerieResource::collection($series),'films'=>FilmResource::collection($films)],200);


}
}
