<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Admin extends Model
{

    protected $parentMenuId = 0;
    protected $MenuId = 0;

    public function can($permission)
    {
        return static::user()->can($permission);
    }

    public function user()
    {
        return Auth::user();
    }

    public function userId()
    {
        return Auth::id();
    }
    public function userCid()
    {
        return Auth::user()['cid'];
    }

    public function menus()
    {
        $user = $this->user();
        return Menu::getUserMenu($user);
    }

    public function allMenus()
    {
        return Menu::all();
    }

    public function permissions()
    {
        return Permission::controllerPermissions();
    }

    public function hasRole($roles)
    {
        return $this->user()->hasRole($roles);
    }

    public function guest() {
        return Auth::guest();
    }

    public function setMenuId ($pmid, $mid)
    {
        $this->parentMenuId = $pmid;
        $this->MenuId = $mid;
    }

    public function getParentMenuId()
    {
        return $this->parentMenuId;
    }

    public function getMenuId()
    {
        return $this->MenuId;
    }
    public function getProjectFolder(){
        $id = $this->userId();
        $directories = Material::where('user_id',$id)->groupBy('folder')->pluck('folder');
        return  $directories;
    }
}

