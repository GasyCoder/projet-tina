<x-app-layout>
    <div class="min-h-screen bg-gray-50 pt-20 pb-6 px-4 md:px-6">
        <div class="max-w-xl mx-auto space-y-6">
            <div class="p-6 bg-white shadow rounded-lg">
                @include('profile.partials.update-profile-information-form')
            </div>
            <div class="p-6 bg-white shadow rounded-lg">
                @include('profile.partials.update-password-form')
            </div>
            <div class="p-6 bg-white shadow rounded-lg">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
