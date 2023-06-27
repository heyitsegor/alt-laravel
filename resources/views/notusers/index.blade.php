<table>
    <thead>
        <tr>
            <th><a href="{{ route('users.index', ['sort' => 'name']) }}">Name</a></th>
            <th><a href="{{ route('users.index', ['sort' => 'created_at']) }}">Registration Date</a></th>
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