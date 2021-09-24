<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Menu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $admin_menus = array();
        $pmenus = Menu::where('pid', 17)->orderBy('order_num', 'asc')->get();
        foreach( $pmenus as $pmenu ) {
            $new_pmenu = Menu::get_children($pmenu);
            array_push($admin_menus, $new_pmenu);
        }
        view()->share('admin_menus', $admin_menus);

        $seller_menus = array();
        $pmenus = Menu::where('pid', 1)->orderBy('order_num', 'asc')->get();
        foreach( $pmenus as $pmenu ) {
            $new_pmenu = Menu::get_children($pmenu);
            array_push($seller_menus, $new_pmenu);
        }
        view()->share('seller_menus', $seller_menus);

        $buyer_menus = array();
        $pmenus = Menu::where('pid', 30)->orderBy('order_num', 'asc')->get();
        foreach( $pmenus as $pmenu ) {
            $new_pmenu = Menu::get_children($pmenu);
            array_push($buyer_menus, $new_pmenu);
        }
        view()->share('buyer_menus', $buyer_menus);

    }
}
