<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\ConfirmsPasswords;
use Laravel\Jetstream\Contracts\DeletesUsers;
use Livewire\Component;

class DeleteUserForm extends Component
{
    use ConfirmsPasswords;

    /**
     * The user's password for confirmation.
     *
     * @var string
     */
    public $password = '';

    /**
     * Indicates if user deletion is being confirmed.
     *
     * @var bool
     */
    public $confirmingUserDeletion = false;

    /**
     * Confirm that the user would like to delete their account.
     *
     * @return void
     */
    public function confirmUserDeletion()
    {
        $this->resetErrorBag();
        $this->password = '';
        $this->dispatch('confirming-delete-user');
        $this->confirmingUserDeletion = true;
    }

    /**
     * Delete the current user.
     *
     * @param  \Laravel\Jetstream\Contracts\DeletesUsers  $deleter
     * @return void
     */
    public function deleteUser(DeletesUsers $deleter)
    {
        $deleter->delete(Auth::user()->fresh());

        Auth::logout();

        if (is_null(Auth::user())) {
            session()->invalidate();
            session()->regenerateToken();

            return redirect('/');
        }

        return redirect('/');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('profile.delete-user-form');
    }

    /**
     * Start confirming a password.
     *
     * @param  string  $confirmableId
     * @return void
     */
    public function startConfirmingPassword(string $confirmableId)
    {
        $this->confirmingPassword = true;
        $this->confirmableId = $confirmableId;
    }
}
