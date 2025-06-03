<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\ConfirmsPasswords;
use Laravel\Jetstream\Jetstream;
use Livewire\Component;

class LogoutOtherBrowserSessionsForm extends Component
{
    use ConfirmsPasswords;

    /**
     * Indicates if logout is being confirmed.
     *
     * @var bool
     */
    public $confirmingLogout = false;

    /**
     * The user's password.
     *
     * @var string
     */
    public $password = '';

    /**
     * Confirm that the user would like to log out from other browser sessions.
     *
     * @return void
     */
    public function confirmLogout()
    {
        $this->password = '';
        
        $this->confirmingLogout = true;
    }

    /**
     * Log out from other browser sessions.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return void
     */
    public function logoutOtherBrowserSessions(StatefulGuard $guard)
    {
        if (! Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('Esta contraseña no coincide con nuestros registros.')],
            ]);
        }

        $guard->logoutOtherDevices($this->password);

        $this->deleteOtherSessionRecords();

        $this->confirmingPassword = false;
        $this->confirmingLogout = false;
        $this->password = '';

        $this->dispatch('loggedOut');
    }

    /**
     * Delete the other browser session records from storage.
     *
     * @return void
     */
    protected function deleteOtherSessionRecords()
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', Auth::user()->getAuthIdentifier())
            ->where('id', '!=', request()->session()->getId())
            ->delete();
    }

    /**
     * Get the current sessions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSessionsProperty()
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                    ->where('user_id', Auth::user()->getAuthIdentifier())
                    ->orderBy('last_activity', 'desc')
                    ->get()
        )->map(function ($session) {
            return (object) [
                'agent' => $this->createAgentArray($session),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    /**
     * Create an agent array from the given session.
     *
     * @param  mixed  $session
     * @return array
     */
    protected function createAgentArray($session)
    {
        $agent = $session->user_agent;

        // Determinamos el sistema operativo
        $platform = 'Desconocido';
        if (strpos($agent, 'Windows') !== false) $platform = 'Windows';
        elseif (strpos($agent, 'Mac') !== false) $platform = 'Mac';
        elseif (strpos($agent, 'Linux') !== false) $platform = 'Linux';
        elseif (strpos($agent, 'Android') !== false) $platform = 'Android';
        elseif (strpos($agent, 'iOS') !== false) $platform = 'iOS';

        // Determinamos el navegador
        $browser = 'Desconocido';
        if (strpos($agent, 'Chrome') !== false) $browser = 'Chrome';
        elseif (strpos($agent, 'Firefox') !== false) $browser = 'Firefox';
        elseif (strpos($agent, 'Safari') !== false) $browser = 'Safari';
        elseif (strpos($agent, 'Edge') !== false) $browser = 'Edge';
        elseif (strpos($agent, 'Opera') !== false) $browser = 'Opera';

        return (object) [
            'is_desktop' => !$this->isMobile($agent),
            'platform' => $platform,
            'browser' => $browser
        ];
    }

    /**
     * Determine if the user agent is a mobile device.
     *
     * @param  string  $userAgent
     * @return bool
     */
    protected function isMobile($userAgent)
    {
        $mobiles = [
            'mobile', 'android', 'iphone', 'ipad', 'tablet', 'phone'
        ];

        foreach ($mobiles as $mobile) {
            if (stripos($userAgent, $mobile) !== false) {
                return true;
            }
        }

        return false;
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

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('profile.logout-other-browser-sessions-form');
    }
}
