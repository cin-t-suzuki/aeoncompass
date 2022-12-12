<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayOutController extends Controller
{
    public function create()
    {
        return view('play-out.post.create');
    }
    public function store(Request $request)
    {
        // $validated = $request->validate([
        //     'title' => 'required|max:255',
        //     'body' => 'required',
        // ]);
        $input = $request->all();
        $rules = [
            'title' => [
                'required',
                'max:255',
            ],
            'regex' => [
                'regex:/\A[^a]*\z/',
            ],
            'body' => 'required',
        ];
        $messages = [
            'required' => ':attribute は必須です（カスタム）',
            'max' => ':attribute が長すぎます（カスタム）',
            'regex' => '[:attribute] 正規表現違反。'
        ];
        $attributes = [
            'title' => 'タイトル（カスタム）',
            'body' => '本文（カスタム）',
        ];

        $validator = Validator::make(
            $input,
            $rules,
            $messages,
            $attributes,
        );
        // $validator->validate();
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        return 'OK';
    }
}
