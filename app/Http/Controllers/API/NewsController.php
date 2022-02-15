<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\NewsUpdateRequest;
use Illuminate\Http\Request;
use App\Http\Requests\NewsPostRequest;
use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{


    public function index(Request $request)
    {
        $news = News::latest();
        if ($request->sort) {
            $news = $news->where('variation', $request->sort);
        }
        if ($request->search) {
            $news = $news->whereLike(['title', 'title_ar', 'text', 'text_ar'], $request->search);
        }
        $news = $news->paginate(10);
        return $this->respondWithSuccess(['data' => $news]);
    }

    public function news(Request $request)
    {
        $news = News::latest()->where('variation', 'news');
        if ($request->search) {
            $news = $news->whereLike(['title', 'title_ar', 'text', 'text_ar',], $request->search);
        }
        $news = $news->paginate(10);
        return $this->respondWithSuccess(['data' => $news]);
    }

    public function post(Request $request)
    {
        $news = News::latest()->where('variation', '!=', 'news');
        if ($request->sort) {
            $news = $news->where('variation', $request->sort);
        }
        $news = $news->paginate(10);
        return $this->respondWithSuccess(['data' => $news]);
    }

    public function show(Request $request, News $news)
    {
        return $this->respondWithSuccess(['data' => $news]);
    }

    public function store(NewsPostRequest $request)
    {
        $image = moveFile($request->image);
        $data = $request->all();
        $data['image'] = $image;
        $data['language'] = 'both';
        $news = News::create($data);
        return $this->respondWithSuccess(['data' => $news]);
    }

    public function update(NewsUpdateRequest $request, $id)
    {
        $news = News::findOrFail($id);
        $data = $request->all();
        if ($request->hasFile('image')) {
            $image = moveFile($request->image);
            $data['image'] = $image;
        }
        $news->update($data);
        $news->save();
        return $this->respondWithSuccess(['data' => $news]);
    }

    public function destroy(Request $request, News $news)
    {
        $news->delete();
        return $this->respondWithSuccess(['data' => $news, 'message' => 'Deleted successfully']);
    }
}
