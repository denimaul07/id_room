<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Member\MemberService;
use Illuminate\Http\Request;

class MemberController extends Controller
{

    protected $memberService;
    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }


    public function index(Request $request)
    {
        $search = $request->search;
        $pagging = $request->pagging ?? 10;
        $data = $this->memberService->list($search, $pagging);
        return response()->json(['data' => $data], 200);
    }

}
