<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    
    /* Route::get('/dashboard', function () {
        return Inertia::render('Home', [
            'profiles' => Profile::with('thumbnail')->withCount('files')->get()
        ]);
    })->name('dashboard'); */

    Route::resource('accounts', AccountController::class);
    Route::resource('media', MediaController::class);
    
    //Route::resource('files', FileController::class);

    /*Route::get('/accounts/{account}/thumbnail', function (Account $account) {
        return Inertia::render('Accounts/Thumbnail', [
            'account' => $account,
            'images' => $account->files()->where('mime_type', 'like', 'image/%')->get(),
        ]);
    })->name('accounts.thumbnail');

    Route::match(['put', 'patch'], '/accounts/{account}/thumbnail', function (Request $request, Account $account) {
        $account->update($request->all());
        return redirect()->route('accounts.show', $account->id);
    })->name('accounts.updateThumbnail');*/

    /* Route::get('/profiles', function () {
        return Inertia::render('Profiles', ['profiles' => Profile::withCount('files')->get()]);
    })->name('profiles.index');
    
    Route::get('/profiles-old/{profile}', function (Profile $profile) {
        return view('profile')->withProfile($profile);
    });
    
    Route::get('/profiles/{profile}', function (Profile $profile) {
        $profile->load(['files','files.types']);
        return Inertia::render('Profile', ['profile' => $profile]);
    })->name('profiles.show'); */

    /* Route::post('/profiles/{profile}/files', function (Profile $profile, Request $request) {

    
        return 0;
    })->name('files.store'); */
    
    /* Route::get('/files', function (Request $request) {
        $typeId = intval($request->input('type_id'));
        $images = File::whereHas('types', function ($q) use ($typeId) {
            $q->whereIn('types.id', [$typeId]);
        })->get();
        return Inertia::render('Files', ['files' => $images]);
    }); */
});


require __DIR__.'/settings.php';
