<div x-data="{ showPassword: false }" class="container mx-auto mt-5">
    <div class="flex justify-center">
        <div class="w-full max-w-md">
            <div class="bg-white shadow-lg rounded-lg">
                <div class="bg-gray-100 p-4 rounded-t-lg">
                    <h4 class="text-xl font-semibold">Login</h4>
                </div>

                <div class="p-6">
                    @if(session()->has('error'))
                        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session()->has('message'))
                        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="login">
                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" wire:model="email" placeholder="Enter your email" required>
                            @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" id="password" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" wire:model="password" placeholder="Enter your password" required>
                                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3" @click="showPassword = !showPassword">
                                    <span x-show="!showPassword" class="text-gray-600">Show</span>
                                    <span x-show="showPassword" class="text-gray-600">Hide</span>
                                </button>
                            </div>
                            @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-4 flex items-center">
                            <input type="checkbox" id="remember" class="form-checkbox h-4 w-4 text-blue-500" wire:model="remember">
                            <label class="ml-2 text-sm text-gray-700" for="remember">Remember Me</label>
                        </div>

                        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mt-3">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
