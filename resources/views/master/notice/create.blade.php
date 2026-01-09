<x-master-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('お知らせ作成') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        .ck-editor__editable {
            min-height: 400px;
        }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- 戻るボタン -->
                    <div class="mb-6">
                        <a href="{{ route('master.notice.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            ← 一覧に戻る
                        </a>
                    </div>

                    <!-- エラーメッセージ -->
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- フォーム -->
                    <form method="POST" action="{{ route('master.notice.store') }}">
                        @csrf

                        <!-- タイトル -->
                        <div class="mb-6">
                            <x-input-label for="title" :value="__('タイトル')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                          :value="old('title')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <!-- カテゴリ -->
                        <div class="mb-6">
                            <x-input-label for="category_id" :value="__('カテゴリ')" />
                            <select id="category_id" name="category_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">カテゴリを選択してください</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}"
                                            {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <!-- 本文 -->
                        <div class="mb-6">
                            <x-input-label for="content" :value="__('本文')" />
                            <textarea id="content" name="content" rows="10"
                                      class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('content') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            <p class="mt-2 text-sm text-gray-600">※ HTML形式で記述できます。画像のアップロードも可能です。</p>
                        </div>

                        <!-- 送信ボタン -->
                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('作成') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        let editorInstance;
        ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                        'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                        'alignment', '|',
                        'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                        'undo', 'redo', '|',
                        'sourceEditing'
                    ]
                },
                language: 'ja',
                image: {
                    toolbar: [
                        'imageTextAlternative', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', 'linkImage'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn', 'tableRow', 'mergeTableCells'
                    ]
                }
            })
            .then(editor => {
                editorInstance = editor;
                console.log('CKEditor初期化成功');
                console.log('画像アップロードURL:', '{{ route('master.notice.upload-image') }}');

                // 画像アップロードのカスタムアダプターを設定
                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    return {
                        upload: () => {
                            return loader.file.then(file => {
                                console.log('画像アップロード開始:', file.name);
                                return new Promise((resolve, reject) => {
                                    const formData = new FormData();
                                    formData.append('upload', file);

                                    fetch('{{ route('master.notice.upload-image') }}', {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        credentials: 'same-origin'
                                    })
                                    .then(response => {
                                        console.log('サーバーレスポンス:', response.status, response.statusText);
                                        if (!response.ok) {
                                            return response.text().then(text => {
                                                console.error('エラーレスポンス:', text);
                                                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                                            });
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        console.log('アップロード結果:', data);
                                        if (data.uploaded) {
                                            resolve({
                                                default: data.url
                                            });
                                        } else {
                                            reject(data.error?.message || 'アップロードに失敗しました');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('アップロードエラー:', error);
                                        reject(error.message || 'アップロードに失敗しました');
                                    });
                                });
                            });
                        }
                    };
                };
            })
            .catch(error => {
                console.error('CKEditor初期化エラー:', error);
            });

        // フォーム送信時にCKEditorの内容をtextareaに反映
        document.querySelector('form').addEventListener('submit', function(e) {
            if (editorInstance) {
                const content = editorInstance.getData();
                if (!content || content.trim() === '') {
                    e.preventDefault();
                    alert('本文を入力してください。');
                    return false;
                }
            }
        });
    </script>
    @endpush
</x-master-layout>
