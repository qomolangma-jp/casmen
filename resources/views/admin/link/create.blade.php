@extends('layouts.admin')

@section('title', 'CASMEN｜面接URL送信・発行')

@section('content')
<main>
    <div class="main-container">
        <div class="breadcrumbs">
            <span><a href="{{ route('admin.dashboard') }}">TOP</a> > <a href="{{ route('admin.entry.index') }}">応募者一覧</a> > 面接URL送信・発行</span>
        </div>
        <div class="ray-content registration-content">

            @if(session('interview_url'))
                <!-- Success State -->
                <div class="user-status">
                    <span class="user-icon"><img src="{{ asset('assets/admin/img/user-icon.png') }}" alt="ユーザーアイコン"></span>
                    <span class="user-name">{{ session('entry_name') ?? '応募者' }}</span>
                </div>

                @if(session('success_action') === 'send')
                    <ul class="applicant-contact">
                        @if(session('entry_email'))
                        <li>
                            <span><img src="{{ asset('assets/admin/img/email-icon-gray.png') }}" alt="メールアイコン"></span>
                            <span>{{ session('entry_email') }}</span>
                        </li>
                        @endif
                        @if(session('entry_tel'))
                        <li>
                            <span><img src="{{ asset('assets/admin/img/tel-icon.png') }}" alt="TELアイコン"></span>
                            <span>{{ session('entry_tel') }}</span>
                        </li>
                        @endif
                    </ul>
                    <p class="url-status status-send">面接URLを送信いたしました。</p>
                @else
                    <p class="url-status status-create">面接URLを発行いたしました。</p>
                @endif

                <p class="description">LINEやSNSで送る場合は、下記の面接URLをコピーしてご利用ください。</p>
                <div class="copy-url">
                    <input id="url-display" class="display" type="url" value="{{ session('interview_url') }}" readonly>
                    <span id="copy" onclick="copyToClipboard()">
                        <img src="{{ asset('assets/admin/img/copy-icon.png') }}" alt="コピーアイコン">
                    </span>
                </div>

                <div style="margin-top: 30px; text-align: center;">
                    <a href="{{ route('admin.link.create') }}" class="btn-md btn-blue" style="display: inline-block; text-decoration: none;">続けて発行する</a>
                </div>

            @else
                <!-- Form State -->
                <form method="POST" action="{{ route('admin.link.store') }}" id="url-form">
                    @csrf
                    <input type="hidden" name="action" id="form-action" value="">

                    <section id="apply" class="apply-section">
                        <h2>応募者情報の入力</h2>
                        <div class="apply-inner">
                            <div class="form-item">
                                <div class="name-required">
                                    <label for="name">名前</label>
                                    <span>必須</span>
                                </div>
                                <div class="input-area">
                                    <span>
                                        <img src="{{ asset('assets/admin/img/letter-icon.png') }}" alt="Aのアイコン">
                                    </span>
                                    <input id="name" class="input-field @error('name') is-error @enderror" name="name" type="text" autocomplete="off" value="{{ old('name') }}">
                                </div>
                                @error('name')
                                <div class="error error-name" style="display: flex;">
                                    <span><img src="{{ asset('assets/admin/img/warning-icon.png') }}" alt="警告アイコン"></span>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="flow-btns">
                                <a href="#auto-send" class="btn-lg btn-blue btn-contact">
                                    <div class="btn-txt-left">
                                        <span>＜応募者の連絡先がわかる場合＞</span>
                                        <span>面接URLが自動で送信されます</span>
                                    </div>
                                    <span class="triangle-down">▼</span>
                                </a>
                                <a href="#create-url-send" class="btn-lg btn-green btn-name">
                                    <div class="btn-txt-left">
                                        <span>＜応募者の氏名のみがわかる場合＞</span>
                                        <span>店舗様ご自身で面接URLを送ります</span>
                                    </div>
                                    <span class="triangle-down">▼</span>
                                </a>
                            </div>
                        </div>
                    </section>

                    <section id="auto-send" class="auto-send-section">
                        <div class="auto-send-inner">
                            <h2>面接URL自動送信</h2>
                            <div class="description">
								<p class="copy-url-description">
									メールアドレス、または電話番号をご入力ください。<br>
									※どちらか一方の入力で問題ありません。
								</p>
							</div>
                            <figure>
                                <img src="{{ asset('assets/admin/img/flow_01.png') }}" alt="連絡先入力">
                            </figure>

                            <div class="form-item">
                                <label for="email">メールアドレス</label>
                                <div class="input-area">
                                    <span>
                                        <img src="{{ asset('assets/admin/img/email-icon.png') }}" alt="メールアイコン">
                                    </span>
                                    <input id="email" class="input-field @error('email') is-error @enderror @error('contact_error') is-error @enderror" name="email" type="email" autocomplete="off" value="{{ old('email') }}">
                                </div>
                                @error('email')
                                <div class="error" style="display: flex;">
                                    <span><img src="{{ asset('assets/admin/img/warning-icon.png') }}" alt="警告アイコン"></span>
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
                                    <input id="tel" class="input-field @error('phone') is-error @enderror @error('contact_error') is-error @enderror" name="phone" type="tel" maxlength="13" autocomplete="off" value="{{ old('phone') }}">
                                </div>
                                @error('phone')
                                <div class="error" style="display: flex;">
                                    <span><img src="{{ asset('assets/admin/img/warning-icon.png') }}" alt="警告アイコン"></span>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                                @error('contact_error')
                                <div class="error" style="display: flex;">
                                    <span><img src="{{ asset('assets/admin/img/warning-icon.png') }}" alt="警告アイコン"></span>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>

                            <button id="create-url-btn" type="button" class="btn-md btn-blue" data-iziModal-open=".iziModal-submit">面接URL送信</button>

                        </div>
                    </section>

                    <section id="create-url-send" class="create-url-send-section">
                        <div class="create-url-send-inner">
                            <h2>面接URL発行・送信</h2>
                            <div class="description">
                                <p class="copy-url-description">
                                    メールアドレス・電話番号が未入力の場合は、<br>
                                    面接URLのみが発行されます。<br>
                                    下記に表示される面接URLをコピーし、DM等で応募者へお送りください。
                                </p>
                            </div>
                            <figure>
                                <img src="{{ asset('assets/admin/img/flow_02.png') }}" alt="氏名のみ入力">
                            </figure>
                            <button type="button" onclick="submitIssue()" class="btn-md btn-green">面接URL発行</button>
                        </div>
                    </section>
                </form>
            @endif
        </div>
        {{-- <div class="privacy-policy">
            <a href="{{ route('company.policy') }}" target="_blank">個人情報の取り扱いについて</a>
        </div> --}}
    </div>
</main>

<!-- 面接URL発行モーダル -->
<div id="modal-submit" class="modal iziModal-submit">
    <p class="submit-confirm">面接URLを応募者へ送信しますか？</p>
    <div class="modal-btns submit-modal-btns">
        <button id="cancelled" class="cancelled" data-iziModal-close="">キャンセル</button>
        <button id="submit-confirm-btn" type="button" class="inline-submit-btn">送信</button>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/admin/js/modal.js') }}"></script>
<script>
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
        if (!copyText) return;

        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        navigator.clipboard.writeText(copyText.value).then(function() {
            console.log('URLをコピーしました:', copyText.value);
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>
@endpush
@endsection
