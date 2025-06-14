// Este archivo contiene las rutas específicas para el profile photo upload
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/user/profile-photo', [App\Http\Controllers\ProfileController::class, 'updateProfilePhoto'])->name('user-profile-photo.update');
});
