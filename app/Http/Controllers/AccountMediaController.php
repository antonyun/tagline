<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Media;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Account $account)
    {
        $query = $account->media()->with('categories'); // eager load categories if needed

        if ($request->has('category_id')) {
            $categoryId = $request->input('category_id');

            // Filter media by category via many-to-many pivot
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        $media = $query->paginate(40);

        return Inertia::render('accounts/Media', [
            'media' => $media,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
