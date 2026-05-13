@extends('admin.layouts.app')

@section('title', 'About Us')
@section('breadcrumb', 'Home / Advance / About us')
@section('header', 'About us')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <form method="post" action="{{ route('admin.about.update') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Rich Text Editor</label>
                <textarea id="content" name="content" rows="16" class="w-full rounded-lg border border-slate-200 focus:border-sky-500 focus:ring-sky-500">{{ old('content', $page->content) }}</textarea>
            </div>

            <button type="submit" class="px-4 py-2 rounded-lg bg-sky-600 text-white font-semibold hover:bg-sky-700">Simpan</button>
        </form>
    </div>

    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            menubar: false,
            height: 420,
            plugins: 'lists link image table code',
            toolbar: 'undo redo | fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | link image | code',
        });
    </script>
@endsection

