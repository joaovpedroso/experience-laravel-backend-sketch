<?php

namespace App\Http\Controllers\Home;

use App\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'DESC')->limit(5)->get();
        return view('backend.dashboard.index', compact('contacts'));
    }
}
