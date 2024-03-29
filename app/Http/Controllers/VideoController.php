<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ResponseTrait;
use App\Models\Video;
use App\Models\Comment;

class VideoController extends Controller
{
    use ResponseTrait;

    public function list($id)
    {
        $video = Video::with('comments')->findOrFail($id);
        return $this->returnResponse(true, 'Video', $video);
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'  => 'required|max:40',
        ]);

        if ($validation->fails())
            return $this->returnResponse(false, 'Validation Error', $validation->errors());

        $video = Video::create($request->only(['name']));
        return $this->returnResponse(true, 'Video Created Successfully', $video);
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id'    => 'required|exists:videos,id',
            'name'  => 'max:40',
        ]);

        if ($validation->fails())
            return $this->returnResponse(false, 'Validation Error', $validation->errors());

        $video = Video::where('id', $request->id)->first();
        if ($request->name) {
            $video->update([
                'name'  => $request->name
            ]);
            return $this->returnResponse(true, 'Video Name Updated Successfully');
        }
    }


    public function delete($id)
    {
        $video =Video::findOrFail($id);
        $video->comments()->delete();
        $video->delete();
        return $this->returnResponse(true, 'Video Deleted Successfully');
    }


    public function comment(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'video_id'      => 'required|exists:videos,id',
            'body'          => 'required'
        ]);

        if ($validation->fails())
            return $this->returnResponse(false, 'Validation Error', $validation->errors());

        $video = Video::find($request->video_id);
        $comment = new Comment;
        $comment->body = $request->body;
        $video->comments()->save($comment);

        return $this->returnResponse(true, 'Comment Added', $comment);
    }
}
