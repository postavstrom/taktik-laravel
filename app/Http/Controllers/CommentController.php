<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/comments",
     *     summary="Get list of comments",
     *     tags={"Comments"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     * )
     */

    public function index()
    {
        $cacheKey = 'comments_cache1';
        $comments = Cache::remember($cacheKey, now()->addSecond(), function () {
            Log::info('Loading comments from the database');
            return Comment::all();
        });
        return response()->json($comments);
    }

    /**
     * @OA\Post(
     *     path="/api/comments",
     *     summary="Create a new comment",
     *     tags={"Comments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"text", "user_id", "post_id"},
     *             @OA\Property(property="text", type="string", example="This is a comment."),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="post_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Comment created successfully"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     * )
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
        ]);

        $comment = Comment::create($validated);

        return response()->json(['message' => 'Comment created successfully', 'data' => $comment], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/comments/{id}",
     *     summary="Get a specific comment",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the comment to fetch",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     * )
     */

    public function show($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        return response()->json($comment);
    }

    /**
     * @OA\Put(
     *     path="/api/comments/{id}",
     *     summary="Update a specific comment",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the comment to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="text", type="string", example="Updated comment text"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="post_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Comment updated successfully"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     * )
     */

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'text' => 'sometimes|required|string',
            'user_id' => 'sometimes|required|exists:users,id',
            'post_id' => 'sometimes|required|exists:posts,id',
        ]);

        $comment = Comment::findOrFail($id);

        $comment->update($validated);

        return response()->json(['message' => 'Comment updated successfully', 'data' => $comment], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/comments/{id}",
     *     summary="Delete a specific comment",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the comment to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Comment deleted successfully")
     *         )
     *     ),
     * )
     */

    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
