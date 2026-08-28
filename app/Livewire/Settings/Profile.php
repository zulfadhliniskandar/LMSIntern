<?php

namespace App\Livewire\Settings;

use App\Concerns\ProfileValidationRules;
use Flux\Flux;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Profile settings')]
class Profile extends Component
{
    use ProfileValidationRules;
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public $passport_photo;

    public ?string $current_passport_photo = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->current_passport_photo = $user->passport_photo;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation()
    {
        $user = Auth::user();

        $validated = $this->validate(array_merge($this->profileRules($user->id), [
            'passport_photo' => 'nullable|image|max:3072',
        ]));

        $user->name = $this->name;
        $user->email = $this->email;

        if ($this->passport_photo) {
            if ($user->passport_photo && Storage::disk('public')->exists($user->passport_photo)) {
                Storage::disk('public')->delete($user->passport_photo);
            }

            $path = $this->passport_photo->store('passport_photos', 'public');
            $user->passport_photo = $path;
            $this->current_passport_photo = $path;
            $this->reset('passport_photo');
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Flux::toast(variant: 'success', text: __('Profile updated successfully.'));

        return $this->redirect(route('profile.edit'), navigate: false);
    }

    /**
     * Remove current passport photo.
     */
    public function removePassportPhoto()
    {
        $user = Auth::user();

        if ($user->passport_photo) {
            if (Storage::disk('public')->exists($user->passport_photo)) {
                Storage::disk('public')->delete($user->passport_photo);
            }
            $user->passport_photo = null;
            $user->save();
            $this->current_passport_photo = null;
            $this->reset('passport_photo');

            Flux::toast(variant: 'success', text: __('Passport photo removed.'));

            return $this->redirect(route('profile.edit'), navigate: false);
        }
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Flux::toast(text: __('A new verification link has been sent to your email address.'));
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        $user = Auth::user();

        return $user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        $user = Auth::user();

        return ! $user instanceof MustVerifyEmail || $user->hasVerifiedEmail();
    }
}
