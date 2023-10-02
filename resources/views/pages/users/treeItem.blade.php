<li data-jstree='{"icon" : "far fa-user"}'>
    <span class="badge bg-info">{{ $level == 1 ? 'Self' : 'Generation-' . $level - 1 }}
        {{-- ({{ $user['children']->count() }}) --}}
    </span> {{ $user['name'] }}
    ({{ $user['email'] }})
    <ul>
        @if (!empty($user['children']))
            @foreach ($user['children'] as $child)
                @include('pages/users/treeItem', ['user' => $child, 'level' => $level + 1])
            @endforeach
        @endif
    </ul>
</li>
