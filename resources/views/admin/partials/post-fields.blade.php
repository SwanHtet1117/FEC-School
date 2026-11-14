<label class="post-field">
    <span>Title <span class="post-required" aria-hidden="true">*</span></span>
    <input type="text" name="title" value="{{ old('title', $post?->title) }}" maxlength="255" required>
</label>
<label class="post-field">
    <span>Category <span class="post-required" aria-hidden="true">*</span></span>
    <select name="category" required>
        <option value="">Select a category</option>
        @foreach (['News', 'Announcement', 'Event'] as $category)
            <option value="{{ $category }}" @selected(old('category', $post?->category ?? request()->query('category')) === $category)>{{ $category }}</option>
        @endforeach
    </select>
</label>
<label class="post-field">
    <span>Publication date <span class="post-required" aria-hidden="true">*</span></span>
    <input type="date" name="published_at" value="{{ old('published_at', $post?->published_at?->format('Y-m-d') ?? today()->toDateString()) }}" required>
</label>
<label class="post-field">
    <span>Image <small>(optional; JPG, PNG, or WebP, up to 5 MB)</small></span>
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
    @if ($post)
        <img class="post-current-image" src="{{ $post->image_url }}" alt="{{ $post->alt_text }}">
    @endif
</label>
<label class="post-field">
    <span>Image description <span class="post-required" aria-hidden="true">*</span></span>
    <input type="text" name="alt_text" value="{{ old('alt_text', $post?->alt_text) }}" maxlength="255" required>
</label>
<label class="post-field">
    <span>Location <small>(optional; for events)</small></span>
    <input type="text" name="location" value="{{ old('location', $post?->location) }}" maxlength="255">
</label>
<label class="post-field">
    <span>Department <small>(optional; for announcements)</small></span>
    <input type="text" name="department" value="{{ old('department', $post?->department) }}" maxlength="255">
</label>
<label class="post-field post-field-wide">
    <span>Short summary <span class="post-required" aria-hidden="true">*</span></span>
    <textarea name="excerpt" rows="3" maxlength="500" required>{{ old('excerpt', $post?->excerpt) }}</textarea>
</label>
<label class="post-field post-field-wide">
    <span>Full content <span class="post-required" aria-hidden="true">*</span> <small>Line breaks are preserved when published.</small></span>
    <textarea name="body" rows="7" maxlength="20000" required>{{ old('body', $post?->body) }}</textarea>
</label>
