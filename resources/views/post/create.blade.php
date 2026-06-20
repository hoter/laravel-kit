<x-layouts::app :title="__('Dashboard')">
    <div class="container">
        <h1>Создание поста</h1>

        {{-- Вывод всех ошибок --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('posts.store') }}">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Заголовок *</label>
                <input type="text"
                       class="form-control @error('title') is-invalid @enderror"
                       id="title"
                       name="title"
                       value="{{ old('title') }}">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label">Slug (опционально)</label>
                <input type="text"
                       class="form-control @error('slug') is-invalid @enderror"
                       id="slug"
                       name="slug"
                       value="{{ old('slug') }}">
                @error('slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Содержание *</label>
                <textarea class="form-control @error('content') is-invalid @enderror"
                          id="content"
                          name="content"
                          rows="10">{{ old('content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox"
                       class="form-check-input"
                       id="is_published"
                       name="is_published"
                       value="1"
                       {{ old('is_published') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_published">Опубликовать сразу</label>
            </div>

            <button type="submit" class="btn btn-primary">Создать пост</button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</x-layouts::app>
