<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Post::with('author');

        // Фильтрация по статусу публикации
        if ($request->has('published')) {
            $query->where('is_published', $request->boolean('published'));
        }

        // Поиск по заголовку
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => PostResource::collection($posts),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
        ]);
    }

    public function show(Post $post): JsonResponse
    {
        $post->load('author');

        return response()->json([
            'data' => new PostResource($post),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|min:50',
        ]);

        $post = $request->user()->posts()->create($validated);

        return response()->json([
            'data' => new PostResource($post),
            'message' => 'Post created successfully',
        ], 201);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        if ($post->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You are not authorized to update this post',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|max:255',
            'content' => 'sometimes|min:50',
        ]);

        $post->update($validated);

        return response()->json([
            'data' => new PostResource($post),
            'message' => 'Post updated successfully',
        ]);
    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        if ($post->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'You are not authorized to delete this post',
            ], 403);
        }

        $post->delete();

        return response()->json(null, 204);
    }
}
