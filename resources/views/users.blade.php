<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    <a
                                        href="{{ route('users', ['sort' => 'name', 'sort_order' => $sortOrder === 'asc' && $sort === 'name' ? 'desc' : 'asc']) }}">
                                        Name
                                    </a>
                                </th>
                                <th>
                                    <a
                                        href="{{ route('users', ['sort' => 'name', 'sort_order' => $sortOrder === 'asc' && $sort === 'name' ? 'desc' : 'asc']) }}">
                                        Registration Date/Time
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
