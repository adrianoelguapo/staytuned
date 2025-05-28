<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Validate and create a newly registered user.
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'username'              => ['required', 'string', 'max:255', 'unique:users'],
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // Regla de contraseña: al menos 8 caracteres y confirmation
            'password'              => ['required', 'string', Password::min(8), 'confirmed'],
            'profile_photo'         => ['nullable', 'image', 'max:2048'], // opcional, solo imágenes <=2MB
        ])->validate();

        // Procesar la subida de la foto, si existe
        $photoPath = null;
        if (isset($input['profile_photo']) && $input['profile_photo'] instanceof UploadedFile) {
            // Guardar bajo storage/app/public/profile-photos
            $photo      = $input['profile_photo'];
            $filename   = Str::uuid() . '.' . $photo->getClientOriginalExtension();
            $photoPath  = $photo->storeAs('profile-photos', $filename, 'public');
        }

        return User::create([
            'username'            => $input['username'],
            'name'                => $input['name'],
            'bio'                 => $input['bio'] ?? '',
            'email'               => $input['email'],
            'password'            => $input['password'],
            'profile_photo_path'  => $photoPath,
        ]);
    }
}
