<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <div class="text-end">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-success text-dark">{{ __('logout') }}</button>
            </form>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" style="height: 200px;">
                    <div class="text-start ms-5 mt-3">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle text-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">Social Media
                            </button>
                            <ul class="dropdown-menu">
                              <li><a href="{{ route('facebook.index') }}" class="dropdown-item">Facebook Authorization</a></li>
                              <li><a class="dropdown-item" href="{{ route('user.page') }}">Facebook Post Sale</a></li>
                              <li><a class="dropdown-item" href="{{ route('facebook.post') }}">Post</a></li>
                              <li><a class="dropdown-item" href="{{ route('auto-reply.create') }}">Auto keyword</a></li>
                            </ul>
                          </div>

                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
