<?php

namespace Modules\ECommerce\Http\Livewire;

use App\Models\Geo\Country;
use App\Models\Geo\Region;
use App\Models\Traits\ComponentPaginatable;
use App\Models\Traits\ModelExtended;
use App\Models\Users\Role;
use App\Models\Users\User;
use Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\ECommerce\Models\ECommerceInvitation;

class RegisterInvitedUsers extends Component
{
    use ModelExtended;
    use ComponentPaginatable;

    public $languages;
    public $invitation;
    public $user_info;
    public $user;
    public $roles;
    public $all_countries;
    public $all_regions_filtered_country_ids = [];

    public function mount()
    {
        $this->languages = returnListSupportedLanguages();

        if (request()->route('token')) {
            $invitation = ECommerceInvitation::returnByToken(request()->route('token'));
            if (! $invitation) {
                return redirect()->route('home');
            }
            $this->invitation = $invitation;
        } else {
            return redirect()->route('home');
        }

        $this->roles = Role::getArrayAllRoles(true, 'ECommerce');

        $this->user_info['email'] = $invitation->email;
        $this->user_info['role_id'] = $this->invitation['role_id'];
        $this->user_info['country'] = null;
        $this->user_info['state'] = null;

        $this->all_countries = Country::returnAllCountrisArrayByIds();
    }

    public function close()
    {
        $this->dispatchBrowserEvent('close-modal');
        return redirect()->route('home');
    }

    public function successUpdate()
    {
        Auth::login($this->user, true);

        $this->dispatchBrowserEvent('close-modal');

        return redirect()->route('e-commerce-orders');
    }

    public function selectedCountryInput()
    {
        if (isset($this->user_info['country'])) {
            $this->all_regions_filtered_country_ids = Region::returnArrayRegionsFilteredByCountryId($this->user_info['country']) ?? [];
            $this->user_info['state'] = null;
            return;
        }
        $this->user_info['state'] = null;
        $this->user_info['country'] = null;
    }

    public function removeContry()
    {
        $this->all_regions_filtered_country_ids = [];
        $this->user_info['state'] = null;
        $this->user_info['country'] = null;
    }

    public function removeState()
    {
        $this->user_info['state'] = null;
    }

    public function createUser()
    {
        $validated_data = $this->validate([
            'user_info.business_name' => ['required', Rule::unique('users', 'business_name')->whereNull('deleted_at')],
            'user_info.firstname' => 'required|string',
            'user_info.lastname' => 'nullable',
            'user_info.phone' => ['nullable', 'min:10', 'max:25', Rule::unique('users', 'phone')->whereNull('deleted_at')],
            'user_info.email' => ['required', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'user_info.password' => 'required|confirmed|min:8',
            'user_info.country' => 'nullable',
            'user_info.state' => 'nullable',
            'user_info.city' => 'nullable',
            'user_info.zip' => 'nullable',
            'user_info.first_address_line' => 'nullable',
            'user_info.second_address_line' => 'nullable',
        ]);

        $validated_data['user_info']['password'] = bcrypt($validated_data['user_info']['password']);
        $validated_data['user_info']['email_verified_at'] = date('Y-m-d H:i:s');

        $user = User::create($validated_data['user_info']);

        $user->assignRole($this->user_info['role_id']);

        $this->user = $user;

        $invitation = $this->invitation;
        $invitation->email = $user->email;
        $invitation->used = true;
        $invitation->save();

        $this->successUpdate();
    }

    public function render()
    {
        return view('e-commerce::livewire.register-invitation-users')->extends('layouts.app');
    }
}
