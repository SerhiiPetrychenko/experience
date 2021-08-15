<div class="w-full py-2"
x-data="{
    show: true,
}"
x-on:close-modal.window="show = false">

    {{-- Create modal --}}
    <div x-show="show" tabindex="0" class="opacity-0 z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed" :class="{ 'opacity-100': show }">
        <div  class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
            <form wire:submit.prevent="createUser" class="bg-white rounded shadow-lg border flex flex-col">
                <button type="button" wire:click="close" class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>

                <div class="px-4 md:px-6 py-3 text-xl font-bold">{{ getTranslationsInBase('user') }}</div>

                <div class="flex flex-wrap px-4 md:px-6 flex-grow">
                    <div class="w-full lg:w-1/2 mb-4 lg:pr-2">
                        @include('components.form.input', [
                            'required' => true,
                            'autofocus' => true,
                            'star' => true,
                            'type' => 'text',
                            'title' => getTranslationsInBase('business_name'),
                            'field' => 'user_info.business_name'
                        ])

                        @include('components.form.input', [
                            'required' => true,
                            'star' => true,
                            'type' => 'text',
                            'title' => getTranslationsInBase('firstname'),
                            'field' => 'user_info.firstname'
                        ])

                        @include('components.form.input', [
                            'type' => 'text',
                            'title' => getTranslationsInBase('lastname'),
                            'field' => 'user_info.lastname'
                        ])

                        @include('components.form.input', [
                            'required' => true,
                            'readonly' => true,
                            'type' => 'email',
                            'star' => true,
                            'title' => getTranslationsInBase('email_address'),
                            'field' => 'user_info.email'
                        ])

                        @include('components.form.input', [
                            'type' => 'text',
                            'title' => getTranslationsInBase('phone'),
                            'field' => 'user_info.phone'
                        ])

                        <div class="flex flex-col mb-6">
                            <label class="block text-sm font-medium text-gray-700 leading-5 items-center mb-2">
                                {{getTranslationsInBase('role')}} <span class="text-red-600">*</span>
                            </label>

                            @include('components.form.select', [
                                'disabled' =>true,
                                'required' => true,
                                'class' => 'w-full-important',
                                'values' => $roles,
                                'return_field' => 'id',
                                'option' => 'title',
                                'field' => "user_info.role_id",
                                'add_first_empty_field' => true
                            ])
                        </div>

                        @include('components.form.input-password', [
                            'required' => true,
                            'star' => true,
                            'type' => 'password',
                            'title' => getTranslationsInBase('password'),
                            'field' => 'user_info.password'
                        ])

                        @include('components.form.input-password', [
                            'required' => true,
                            'star' => true,
                            'type' => 'password',
                            'title' => getTranslationsInBase('confirm_password'),
                            'field' => 'user_info.password_confirmation'
                        ])
                    </div>

                    <div class="w-full lg:w-1/2 mb-4 lg:pl-2">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 leading-5">
                                {{ getTranslationsInBase('country') }}
                            </label>
                            @if(! isset($user_info['country']))
                                @include('components.form.select', [
                                    'important_border' => 'border-gray-300',
                                    'important_width' => 'w-full',
                                    'class' => 'mt-1',
                                    'values' => $all_countries,
                                    'option' => 'name',
                                    'field' => 'user_info.country',
                                    'onChangeCallback' => 'selectedCountryInput',
                                    'add_first_empty_field' => true,
                                    'id' => 'select-country',
                                ])
                            @else
                                <div class="w-full flex justify-between items-center">
                                    <div class="flex items-center py-2">
                                        {{$all_countries[$user_info['country']]['name']}}
                                    </div>

                                    <button type="button" class="flex justify-center px-6 py-2 text-sm font-medium text-white  border border-transparent rounded-md  focus:outline-none transition duration-150 ease-in-out bg-pink-600 focus:border-pink-700 hover:bg-pink-500 focus:shadow-outline-pink active:bg-pink-700"
                                    wire:click="removeContry">
                                        X
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 leading-5">
                                {{ getTranslationsInBase('state') }}
                            </label>

                            @if(! isset($user_info['state']))
                                @include('components.form.select', [
                                    'disabled' => (count($all_regions_filtered_country_ids) <= 0) ? true : null,
                                    'important_border' => 'border-gray-300',
                                    'important_width' => 'w-full',
                                    'class' => 'mt-1',
                                    'values' => $all_regions_filtered_country_ids,
                                    'option' => 'name',
                                    'field' => 'user_info.state',
                                    'add_first_empty_field' => true,
                                    'id' => 'select-state',
                                ])
                            @else
                                <div class="w-full flex justify-between items-center">
                                    <div class="flex items-center py-3">
                                        {{$all_regions_filtered_country_ids[$user_info['state']]['name']}}
                                    </div>

                                    <button type="button" class="flex justify-center px-6 py-2 text-sm font-medium text-white  border border-transparent rounded-md  focus:outline-none transition duration-150 ease-in-out bg-pink-600 focus:border-pink-700 hover:bg-pink-500 focus:shadow-outline-pink active:bg-pink-700"
                                    wire:click="removeState">
                                        X
                                    </button>
                                </div>
                            @endif
                        </div>

                        @include('components.form.input', [
                           'required' => null,
                           'type' => 'text',
                           'title' => getTranslationsInBase('city'),
                           'field' => 'user_info.city'
                        ])

                        @include('components.form.input', [
                           'required' => null,
                           'type' => 'text',
                           'title' => getTranslationsInBase('zip'),
                           'field' => 'user_info.zip'
                        ])

                        @include('components.form.input', [
                            'type' => 'text',
                            'title' => getTranslationsInBase('first_address_line'),
                            'field' => 'user_info.first_address_line'
                        ])

                        @include('components.form.input', [
                            'type' => 'text',
                            'title' => getTranslationsInBase('second_address_line'),
                            'field' => 'user_info.second_address_line'
                        ])
                    </div>
                </div>

                <div class="flex justify-around px-4 md:px-6 py-3">
                    <button
                    type="button"
                    wire:click="close"
                    class="inline-block font-normal text-center px-3 py-2 leading-normal text-base rounded cursor-pointer text-white bg-gray-600 mr-2">
                        {{ getTranslationsInBase('close') }}
                    </button>

                    <button
                    type="submit"
                    class="inline-block font-normal text-center px-3 py-2 leading-normal text-base rounded cursor-pointer text-white bg-blue-600">
                        {{ getTranslationsInBase('save') }}
                    </button>
                </div>
            </form>
        </div>
        <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed bg-black opacity-50"></div>
    </div>
</div>
