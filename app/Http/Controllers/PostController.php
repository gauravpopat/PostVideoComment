<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ResponseTrait;
use App\Models\Post;
use App\Models\Comment;

class PostController extends Controller
{
    use ResponseTrait;

    public function list($id)
    {
        $post = Post::with('comments')->findOrFail($id);
        return $this->returnResponse(true,'Post',$post);
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'name'  => 'required|max:40',
        ]);

        if($validation->fails())
            return $this->returnResponse(false,'Validation Error',$validation->errors());

        $post = Post::create($request->only(['name']));
        return $this->returnResponse(true,'Post Created Successfully',$post);
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'id'    => 'required|exists:posts,id',
            'name'  => 'max:40',
        ]);

        if($validation->fails())
            return $this->returnResponse(false,'Validation Error',$validation->errors());

        $post = Post::where('id',$request->id)->first();
        if($request->name){
            $post->update([
                'name'  => $request->name
            ]);
            return $this->returnResponse(true,'Post Name Updated Successfully');
        }
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->comments()->delete();
        $post->delete();
        return $this->returnResponse(true,'Post Deleted Successfully');
    }

    public function comment(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'post_id'   => 'required|exists:posts,id',
            'body'      => 'required'
        ]);

        if($validation->fails())
            return $this->returnResponse(false,'Validation Error',$validation->errors());

        $post = Post::find($request->post_id);
        $comment = new Comment;
        $comment->body = $request->body;
        $post->comments()->save($comment);

        return $this->returnResponse(true,'Comment Added',$comment);
    }
}
