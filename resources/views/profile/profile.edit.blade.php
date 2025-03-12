<!-- filepath: resources/views/profile/edit.blade.php -->
<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('patch')
    
    <!-- Existing fields -->
    
    <!-- Profile Image -->
    <div class="mt-4">
        <x-input-label for="profile_image" :value="__('Profile Picture')" />
        
        @if(Auth::user()->profile_image)
            <div class="mt-2">
                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                     class="rounded-full w-24 h-24 object-cover" alt="Current Profile Picture">
            </div>
        @endif
        
        <x-text-input id="profile_image" name="profile_image" type="file" class="mt-1 block w-full" />
        <x-input-error class="mt-2" :messages="$errors->get('profile_image')" />
    </div>
    
    <!-- Submit button -->
    <div class="flex items-center gap-4 mt-4">
        <x-primary-button>{{ __('Save') }}</x-primary-button>
    </div>
</form>