<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Profile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::with('thumbnail')->paginate(40);

        return Inertia::render('accounts/Index', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('accounts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'legacy_id' => 'nullable|integer',
            'name' => 'nullable|string|max:255',
            'name_latin' => 'nullable|string|max:255',
            'ethnicity' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',
            'year_of_birth' => 'nullable|integer|min:1900|max:' . date('Y'),
            'date_of_birth' => 'nullable|date',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'measurement_size' => 'nullable|numeric|min:0',
            'position' => 'nullable|string|max:255',
            'preferences' => 'nullable|string',
            'has_face_photo' => 'boolean',
            'has_body_photo' => 'boolean',
            'has_dick_photo' => 'boolean',
            'has_anus_photo' => 'boolean',
            'has_cum_photo' => 'boolean',
            'has_had_sex' => 'boolean',
            'phone_number' => 'nullable|string|max:20',
            'social_profiles' => 'nullable|json',
            'comment' => 'nullable|string',
            'extra' => 'nullable|string',
        ]);

        $account = Account::create();

        // Optional: decode social_profiles JSON into array/object before storing
        if (isset($validated['social_profiles'])) {
            $validated['social_profiles'] = json_decode($validated['social_profiles'], true);
        }

        // Create Profile linked to Account
        $profile = Profile::create(array_merge(
            $validated,
            ['account_id' => $account->id]
        ));

        return redirect()
            ->route('accounts.show', $account->id)
            ->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        $account->load('profile', 'thumbnail');

        return Inertia::render('accounts/Show', [
            'account' => $account,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        //
    }
}
