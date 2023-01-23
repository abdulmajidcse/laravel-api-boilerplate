<?php

namespace App\Http\Controllers\Auth;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResource
    {
        $categories = Category::latest()->paginate(20);
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:180'],
            'parent_id' => ['nullable', 'integer', 'min:1', 'exists:App\Models\Category,id'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->getMessageBag(), 422);
        }

        $data = $validator->safe()->all();

        // create a new slug
        $data['slug'] = Str::slug($data['name']);
        // update slug if exists
        if (Category::where('slug', $data['slug'])->first()) {
            $lastCategory = Category::latest()->first();
            $data['slug'] = Str::slug($data['name'] . ' ' . $lastCategory?->id + 1 ?: 1);
        }

        Category::create($data);

        return response()->json(['message' => 'Success'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category): JsonResource
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:180'],
            'parent_id' => ['nullable', 'integer', 'min:1', 'exists:App\Models\Category,id'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->getMessageBag(), 422);
        }

        $data = $validator->safe()->all();

        // create a new slug
        $data['slug'] = Str::slug($data['name']);
        // update slug if exists
        if (Category::where('id', '!=', $category->id)->where('slug', $data['slug'])->first()) {
            $data['slug'] = Str::slug($data['name'] . ' ' . $category->id);
        }

        $category->update($data);

        return response()->json(['message' => 'Success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json(['message' => 'Success']);
    }
}
