<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PerdaController extends Controller
{
    public function perdaIndex()
    {
        return view('draft-phd.perda.index'); // Pastikan Anda memiliki tampilan draft-phd/perda/index.blade.php
    }
}
