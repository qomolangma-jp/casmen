@extends('layouts.admin')

@section('title', 'CASMEN｜面接URL発行')

@push('css')
<style>
    .accordion-header {
        width: 100%;
        padding: 1.5rem;
        border-radius: 0.5rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        color: #fff;
        font-weight: bold;
        position: relative;
    }
    .accordion-header.blue {
        background-color: #4d9aff;
    }
    .accordion-header.green {
        background-color: #4ad561;
    }
    .accordion-content {
        display: none;
        padding: 2rem;
        border: 1px solid #ddd;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
        background-color: #fff;
    }
    .accordion-content.active {
        display: block;
    }
    .arrow-icon {
        width: 0;
        height: 0;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-top: 8px solid #fff;
        transition: transform 0.3s;
    }
    .accordion-header.active .arrow-icon {
        transform: rotate(180deg);
    }
    .section-title {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 1rem;
        color: #333;
    }
    .section-desc {
        font-size: 1.4rem;
        margin-bottom: 2rem;
        color: #666;
    }
</style>
@endpush

@section('content')
<main>
    <div class="main-container">
        <div class="breadcrumbs">
            <span><a href="{{ route('admin.dashboard') }}">TOP</a> > <a href="{{ route('admin.entry.index') }}">応募者一覧</a> > 面接URL発行</span>
        </div>
        <div class="ray-content registration-content">
            <h2>応募者情報の入力</h2>

            @if ($errors->has('error'))
                <div class="error-message" style="color: #e02424; background-color: #fde8e8; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                    {{ $errors->first('error') }}
                </div>
            @endif
            @if ($errors->has('contact_error'))
                <div class="error-message" style="color: #e02424; background-color: #fde8e8; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                    {{ $errors->first('contact_error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.link.store') }}" id="url-form">
                @csrf
                <input type="hidden" name="action" id="form-action" value="">

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
                        <div class="name-error">
                            <span><img src="{{ asset('assets/admin/img/warning-icon.png') }}" alt="警告アイコン" style="width: 2rem; margin-right: 4px;"></span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Blue Accordion -->
                <div class="accordion-header blue" onclick="toggleAccordion('section-contact', this)">
                    <span>&lt;応募者の連絡先がわかる場合&gt;<br>面接URLが自動で送信されます</span>
                    <div class="arrow-icon"></div>
                </div>
                <div id="section-contact" class="accordion-content">
                    <div class="section-title">面接URL自動送信</div>
                    <p class="section-desc">メールや電話番号を登録すると、面接URLが自動で送信されます。</p>
                    <img src="{{ asset('assets/admin/img/flow.png') }}" alt="URL発行フロー" style="width: 100%; margin-bottom: 2rem;">

                    <div class="form-item">
                        <label for="email">メールアドレス</label>
                        <div class="input-area">
                            <span>
                                <img src="{{ asset('assets/admin/img/email-icon.png') }}" alt="メールアイコン">
                            </span>
                            <input id="email" name="email" type="email" autocomplete="off" value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <div class="name-error">
                                <span><img src="{{ asset('assets/admin/img/warning-icon.png') }}" alt="警告アイコン" style="width: 2rem; margin-right: 4px;"></span>
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
                            <input id="tel" name="phone" type="tel" maxlength="13" autocomplete="off" value="{{ old('phone') }}">
                        </div>
                        @error('phone')
                            <div class="name-error">
                                <span><img src="{{ asset('assets/admin/img/warning-icon.png') }}" alt="警告アイコン" style="width: 2rem; margin-right: 4px;"></span>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    @if(session('success_action') === 'send')
                        <button type="button" class="create-url" disabled style="background-color: #ccc; cursor: default; margin-top: 2rem;">面接URLを発行送信いたしました。</button>
                    @else
                        <button type="button" class="create-url" style="background-color: #4d9aff; margin-top: 2rem;" data-iziModal-open=".iziModal-submit">面接URL送信</button>
                    @endif
                </div>

                <!-- Green Accordion -->
                <div class="accordion-header green" onclick="toggleAccordion('section-issue', this)">
                    <span>&lt;応募者の指名のみがわかる場合&gt;<br>店舗様ご自身で面接URLを送ります</span>
                    <div class="arrow-icon"></div>
                </div>
                <div id="section-issue" class="accordion-content">
                    <div class="section-title">面接URL発行・送信</div>
                    <p class="section-desc">メールアドレス・電話番号が未入力の場合は、面接URLのみが発行されます。<br>下記に表示される面接URLをコピーし、DM等で応募者へお送りください。</p>
                    <img src="{{ asset('assets/admin/img/flow.png') }}" alt="URL発行フロー" style="width: 100%; margin-bottom: 2rem;">

                    @if(session('success_action') === 'issue')
                        <button type="button" class="create-url" disabled style="background-color: #ccc; cursor: default; margin-top: 2rem;">面接URLを発行いたしました。</button>
                    @else
                        <button type="button" class="create-url" style="background-color: #4ad561; margin-top: 2rem;" onclick="submitIssue()">面接URL発行</button>
                    @endif
                </div>

            </form>

            @if(session('interview_url'))
            <p class="copy-url-description" style="margin-top: 3rem;">LINEやSNSで送る場合は、下記の面接URLをコピーしてご利用ください。</p>
            <div class="copy-url">
                <input id="url-display" class="display" type="url" placeholder="発行された面接URLがここに表示されます。" readonly value="{{ session('interview_url') }}">
                <span id="copy" onclick="copyToClipboard()">
                    <img src="{{ asset('assets/admin/img/copy-icon.png') }}" alt="コピーアイコン">
                </span>
            </div>
            @endif
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
    function toggleAccordion(id, header) {
        var content = document.getElementById(id);
        if (content.style.display === "block") {
            content.style.display = "none";
            header.classList.remove('active');
        } else {
            // Close all others
            document.querySelectorAll('.accordion-content').forEach(function(el) {
                el.style.display = 'none';
            });
            document.querySelectorAll('.accordion-header').forEach(function(el) {
                el.classList.remove('active');
            });

            content.style.display = "block";
            header.classList.add('active');
        }
    }

    // Open the section that was active if there's an error or success
    @if(session('success_action') === 'send' || $errors->has('email') || $errors->has('phone') || $errors->has('contact_error'))
        document.addEventListener('DOMContentLoaded', function() {
            toggleAccordion('section-contact', document.querySelector('.accordion-header.blue'));
        });
    @elseif(session('success_action') === 'issue')
        document.addEventListener('DOMContentLoaded', function() {
            toggleAccordion('section-issue', document.querySelector('.accordion-header.green'));
        });
    @else
        // Default open blue
        document.addEventListener('DOMContentLoaded', function() {
            toggleAccordion('section-contact', document.querySelector('.accordion-header.blue'));
        });
    @endif

    // Handle modal submit button click (Send Action)
    $(document).on('click', '#submit-confirm-btn', function() {
        // ボタンを無効化して二重送信防止
        $(this).prop('disabled', true);

        $('#form-action').val('send');
        $('#url-form').submit();
    });

    // Handle Issue button click
    function submitIssue() {
        $('#form-action').val('issue');
        $('#url-form').submit();
    }

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
