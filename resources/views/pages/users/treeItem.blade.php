<li data-jstree='{"icon" : "far fa-user"}'>
    {{ $user['name'] }} ({{ $user['email'] }})
    <ul>
        @if (!empty($user['children']))
            @foreach ($user['children'] as $child)
                @include('pages/users/treeItem', ['user' => $child])
            @endforeach
        @endif
    </ul>
</li>
