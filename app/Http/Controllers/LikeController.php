<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
