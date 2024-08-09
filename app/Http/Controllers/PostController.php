<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get list of posts",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="Filter by title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Sort by field",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Order direction (asc, desc)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="group_by",
     *         in="query",
     *         description="Group by field",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     * )
     */

    public function index(Request $request)
    {
        $query = Post::query();

        if ($request->has('filter')) {
            $filter = $request->input('filter');
            $query->where('title', 'like', "%{$filter}%");
        }

        if ($request->has('sort_by') && $request->has('order')) {
            $sortBy = $request->input('sort_by');
            $order = $request->input('order');
            $query->orderBy($sortBy, $order);
        }

        if ($request->has('group_by')) {
            $groupBy = $request->input('group_by');
            $query->groupBy($groupBy);
        }

        $perPage = $request->input('per_page', 15);
        $posts = $query->paginate($perPage);

        return response()->json($posts);
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Create a new post",
     *     tags={"Posts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "text", "slug", "user_id"},
     *             @OA\Property(property="title", type="string", example="My Post Title"),
     *             @OA\Property(property="text", type="string", example="Content of the post"),
     *             @OA\Property(property="slug", type="string", example="my-post-title"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post created successfully"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity - Validation Error"
     *     ),
     * )
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'text' => 'required|string',
            'slug' => 'required|string|max:200|unique:posts,slug',
            'user_id' => 'required|exists:users,id',
        ]);

        $post = Post::create($validated);

        return response()->json(['message' => 'Post created successfully', 'data' => $post], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Get a specific post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */

    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json($post);
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     summary="Update a specific post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Post Title"),
     *             @OA\Property(property="text", type="string", example="Updated content of the post"),
     *             @OA\Property(property="slug", type="string", example="updated-post-title"),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post updated successfully"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     ),
     * )
     */

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:200',
            'text' => 'sometimes|required|string',
            'slug' => 'sometimes|required|string|max:200|unique:posts,slug,' . $id,
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        $post = Post::findOrFail($id);

        $post->update($validated);

        return response()->json(['message' => 'Post updated successfully', 'data' => $post], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Delete a specific post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     ),
     * )
     */

    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
