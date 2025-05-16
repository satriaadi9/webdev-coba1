<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FirstController extends Controller
{
    //
    public function sum(){
        $a = 15;
        $b = 7;
        $jumlah = $a+$b;
        return "Jumlah: ".$jumlah;
    }

    public function sum2(Request $request){
        $a = $request->param1;
        $b = $request->param2;
        $jumlah = $a+$b;
        return "Jumlah: ".$jumlah;
    }
}
