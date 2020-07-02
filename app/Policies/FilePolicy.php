<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    function imageUpload($user){
        return $user->isAdmin;
    }

    function imageModify($user,$id){
        $usecase=DBImage::find($id)->usecase();
        if($usecase->type==Event::class){
            return $user->permissions()->where('event',$usecase->object->id);
        }
        else return $user->isAdmin();
    }
}
