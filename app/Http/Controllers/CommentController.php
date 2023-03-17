<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    use ResponseTrait;
    
    public function list($id)
    {
        $comment = Comment::findOrFail($id);
        return $this->returnResponse(true,'Comment:',$comment);
    }

    public function update($id,Request $request)
    {
        $validation = Validator::make($request->all(),[
            'body'  => 'max:255'
        ]);

        if($validation->fails())
            return $this->returnResponse(false,'Validation Error',$validation->errors());

        if($request->body){
            Comment::findOrFail($id)->update([
                'body'  => $request->body
            ]);
            return $this->returnResponse(true,'Comment Updated Successfully');
        }
        
    }

    public function delete($id)
    {
        Comment::findOrFail($id)->delete();
        return $this->returnResponse('true','Comment Deleted Successfully');
    }
}
