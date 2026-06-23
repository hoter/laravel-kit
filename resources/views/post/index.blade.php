<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Posts') }}</title>
    @include('partials.head')
</head>
<body class="bg-white dark:bg-zinc-800 min-h-screen">
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">{{ __('Posts') }}</h1>
        @forelse ($posts as $post)
            <div class="border-b py-4">
                <h2 class="text-lg font-semibold">{{ $post->title }}</h2>
                <p class="text-zinc-600">{{ $post->getShortContent() }}</p>
            </div>
        @empty
            <p>{{ __('No posts found.') }}</p>
        @endforelse
    </div>
</body>
</html>
