<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CollectionController extends Controller
{
    protected $score=0;

    public function score(Request $request)
    {
        $validator=Validator()->make($request->all(),[
            'name'=>'required',

        ]);
        if($validator->fails())
        {
            return response()->json(['message'=>'failed','data'=>$validator->errors()],400);

        }

        $json=Http::get("https://api.github.com/users/$request->name/events/public")->json();
        $data=collect($json);
        $data->each(function ($array)  {

            if($array['type']=='PushEvent')
            {
                $this->score=$this->score+10;
            }
            elseif($array['type']=='PullRequestEvent ')
            {
                $this->score=$this->score+5;
            }
            elseif($array['type']=='IssueCommentEvent ')
            {
                $this->score=$this->score+4;
            }
            else
            {
                $this->score=$this->score+1;
            }

        });
        return $this->score;


    }
}
