<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function index()
    {
        return view('profile'); // Pastikan Anda memiliki tampilan profile.blade.php
    }

    // Anda bisa menghapus metode show jika tidak diperlukan
} 