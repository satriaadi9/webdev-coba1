<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function show(){
        $category = 'Smartphone';
        return view('home',[
            'product_name'=>'iPhone 16 Pro',
            'product_category'=>$category
        ]);
    }
}
