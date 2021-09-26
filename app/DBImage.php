<?php

namespace App;

use Illuminate\Database\Eloquent\Location;
use Intervention\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DBImage extends Model
{
    protected $table = 'images';

    use HasFactory;


    public static function saveimg($image,$id=null){

        if($id!=null)  $imageobj=DBImage::find($id);

        if($imageobj==null){ // if id not specified, or wrong
            $imageobj = new DBImage;
            $imageobj->save(); // get new id
        }

        $imageobj->location='/images/'.$imageobj->id; // save image path for later load
        $image->save('/images/'.$imageobj->id); //
        $imageobj->save();
        return $imageobj->id;
    }

    public static function loadimg($id){
        return DBImage::find($id)->image();
    }

    public function image(){
        return \Intervention\Image::make($this->location);
    }

    /*public function saveimg(){
        DBImage::saveimg($this->image(),$this->id);
    }
    public function loadimg(){
        $this->image();
    }*/

    public function usecase(){

        $curr=Event::where('image_id',$this->id);
        if($curr){
            return [
                'type' => Event::class,
                'object' =>$curr->get(),
            ];
        } else return;
    }

}
