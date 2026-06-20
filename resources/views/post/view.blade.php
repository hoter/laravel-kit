<x-layouts::app :title="__('Dashboard')">
    <h2>Post view: {{ $post->title }}</h2>
    @can('update', $post)
    <a href="/posts/{{ $post->id }}/edit">Редактировать</a>
    @endcan
    @can('delete', $post)
    <a href="/posts/{{ $post->id }}/delete">Удалить</a>
    @endcan
    @can('edit-settings')
    <button>Одобрить комментарий</button>
    @endcan
</x-layouts::app>
