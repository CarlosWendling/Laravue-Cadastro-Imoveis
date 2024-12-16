<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ImovelController extends Controller
{
    function index () {
        return Inertia::render('Imoveis');
    }
}
