<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'url', 'pid', 'order_num', 'icon'];

    public static function get_children ($pmenu) {
        $children = Menu::where('pid', $pmenu->id)->orderBy('order_num', 'asc')->get();

        $new_pmenu = array(
            "id"=>$pmenu->id,
            "name"=> $pmenu->name,
            "url"=> $pmenu->url,
            "pid"=> $pmenu->pid,
            "icon" => $pmenu->icon,
            "order_num"=> $pmenu->order_num,
            "children"=> $children
        );

        return $new_pmenu;
    }
}
