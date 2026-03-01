<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Contact\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    protected $contactService;
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }


    public function index(Request $request)
    {
        $search = $request->search;
        $pagging = $request->pagging ?? 10;
        $data = $this->contactService->list($search, $pagging);
        return response()->json(['data' => $data], 200);
    }

}
