<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tags",
     *     summary="Get list of tags",
     *     tags={"Tags"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     * )
     */

    public function index()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }

    /**
     * @OA\Post(
     *     path="/api/tags",
     *     summary="Create a new tag",
     *     tags={"Tags"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Technology")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tag created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tag created successfully"),
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
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        $tag = Tag::create($validated);

        return response()->json(['message' => 'Tag created successfully', 'data' => $tag], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/tags/{id}",
     *     summary="Get a specific tag",
     *     tags={"Tags"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the tag to fetch",
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
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json(['message' => 'Tag not found'], 404);
        }

        return response()->json($tag);
    }

    /**
     * @OA\Put(
     *     path="/api/tags/{id}",
     *     summary="Update a specific tag",
     *     tags={"Tags"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the tag to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Tag Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tag updated successfully"),
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
            'name' => 'sometimes|required|string|max:255|unique:tags,name,' . $id,
        ]);

        $tag = Tag::findOrFail($id);

        $tag->update($validated);

        return response()->json(['message' => 'Tag updated successfully', 'data' => $tag], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/tags/{id}",
     *     summary="Delete a specific tag",
     *     tags={"Tags"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the tag to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tag deleted successfully")
     *         )
     *     ),
     * )
     */

    public function destroy($id)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json(['message' => 'Tag not found'], 404);
        }

        $tag->delete();

        return response()->json(['message' => 'Tag deleted successfully']);
    }
}
