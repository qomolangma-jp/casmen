@extends('layouts.admin')

@section('title', 'CASMEN｜面接URL発行')

@section('content')
<main>
    <div class="main-container">
        <div class="breadcrumbs">
            <span><a href="{{ route('admin.dashboard') }}">TOP</a> > <a href="{{ route('admin.entry.index') }}">応募者一覧</a> > 面接URL発行</span>
        </div>
        <div class="ray-content registration-content">
            <h2>面接URL発行</h2>
            <p>メールや電話番号を登録すると、面接URLが自動で送信されます。</p>
            <img src="{{ asset('assets/admin/img/flow.png') }}" alt="URL発行フロー">
            
            <form method="POST" action="{{ route('admin.link.store') }}" id="url-form">
                @csrf
                <div class="form-item">
                    <div class="name-required">
                        <label for="name">名前</label>
                        <span>必須</span>
                    </div>
                    <div class="input-area">
                        <span>
                            <img src="{{ asset('assets/admin/img/letter-icon.png') }}" alt="Aのアイコン">
                        </span>
                        <input id="name" name="name" type="text" autocomplete="off" value="{{ old('name') }}" required>
                    </div>
                    @error('name')
                        <div class="name-error" style="display:flex; color: #e02424; font-size: 12px; margin-top: 4px;">
                            <span><img src="{{ asset('assets/admin/img/warning-icon.png') }}" alt="警告アイコン" style="width: 14px; margin-right: 4px;"></span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div class="form-item">
                    <label for="email">メールアドレス</label>
                    <div class="input-area">
                        <span>
                            <img src="{{ asset('assets/admin/img/email-icon.png') }}" alt="メールアイコン">
                        </span>
                        <input id="email" name="email" type="email" autocomplete="off" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="name-error" style="display:flex; color: #e02424; font-size: 12px; margin-top: 4px;">
                            <span><img src="{{ asset('assets/admin/img/warning-icon.png') }}" alt="警告アイコン" style="width: 14px; margin-right: 4px;"></span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div class="form-item">
                    <label for="tel">電話番号</label>
                    <div class="input-area">
                        <span>
                            <img src="{{ asset('assets/admin/img/phone-icon.png') }}" alt="電話アイコン">
                        </span>
                        {{-- Controller expects 'phone', so we use name='phone' --}}
                        <input id="tel" name="phone" type="tel" maxlength="13" autocomplete="off" value="{{ old('phone') }}" required>
                    </div>
                    @error('phone')
                        <div class="name-error" style="display:flex; color: #e02424; font-size: 12px; margin-top: 4px;">
                            <span><img src="{{ asset('assets/admin/img/warning-icon.png') }}" alt="警告アイコン" style="width: 14px; margin-right: 4px;"></span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                @if(session('interview_url'))
                    <button id="create-url-btn" type="button" class="create-url" disabled style="background-color: #ccc; cursor: default;">面接URLを発行送信いたしました。</button>
                @else
                    <button id="create-url-btn" type="button" class="create-url" data-iziModal-open=".iziModal-submit">面接URL発行</button>
                @endif
            </form>

            <p class="copy-url-description">LINEやSNSで送る場合は、下記の面接URLをコピーしてご利用ください。</p>
            <div class="copy-url">
                <input id="url-display" class="display" type="url" placeholder="発行された面接URLがここに表示されます。" readonly value="{{ session('interview_url') }}">
                <span id="copy" onclick="copyToClipboard()">
                    <img src="{{ asset('assets/admin/img/copy-icon.png') }}" alt="コピーアイコン">
                </span>
            </div>
        </div>
        <div class="privacy-policy">
            <a href="#">個人情報の取り扱いについて</a>
        </div>
    </div>

    <!-- 面接URL発行モーダル -->
    <div id="modal-submit" class="modal iziModal-submit">
        <p class="submit-confirm">面接URLを応募者へ送信しますか？</p>
        <div class="modal-btns submit-modal-btns">
            <button id="cancelled" class="cancelled" data-iziModal-close="">キャンセル</button>
            <button id="submit-confirm-btn" type="button" class="inline-submit-btn">送信</button>
        </div>
    </div>
</main>

@push('scripts')
<script src="{{ asset('assets/admin/js/modal.js') }}"></script>
<script>
    // Handle modal submit button click
    $(document).on('click', '#submit-confirm-btn', function() {
        $('#url-form').submit();
    });

    // Copy to clipboard function
    function copyToClipboard() {
        var copyText = document.getElementById("url-display");
        if (!copyText.value) return;
        
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        navigator.clipboard.writeText(copyText.value).then(function() {
            alert("URLをコピーしました: " + copyText.value);
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>
@endpush
@endsection