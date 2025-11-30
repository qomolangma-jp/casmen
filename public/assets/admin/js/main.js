'use strict';

// === 不採用モーダル ===
$(function () {
	$('.iziModal-rejected').iziModal();
});

// ==== 通過モーダル ===
$(function () {
	$('.iziModal-passed').iziModal();
});

// === 面接URL送信モーダル ===
$(function () {
	$('.iziModal-submit').iziModal();
});

// === ハンバーガーメニューが開いているとき、背景を暗くする
$('#open').on('click', () => {
	$('body').toggleClass('is-open');
});

// === コピー機能・トースト通知 ===
$('#copy').on('click', () => {
    const $displayUrl = $('#url-display').val();

    if (!$displayUrl) {
        alert('コピーできるURLがありません');
        return;
    }

    navigator.clipboard.writeText($displayUrl).then(() => {
        const $copyUrl = $('.copy-url');
        $copyUrl.addClass('show-copy');

        setTimeout(() => {
            $copyUrl.removeClass('show-copy');
        }, 1000);

    }).catch(() => {
        alert('コピーできませんでした');
    });
});

// === パスワードの表示・非表示 ===
$('.visible-icons img').on('click', function () {
	const $formItem = $(this).closest('.password');
	const $passwordInput = $formItem.find('input[type="password"], input[type="text"]');
	const $visibleIcon = $formItem.find('.visible');
	const $invisibleIcon = $formItem.find('.invisible');

	if ($passwordInput.attr('type') === 'password') {
		// パスワードを表示
		$passwordInput.attr('type', 'text');
		$visibleIcon.show();
		$invisibleIcon.hide();
	} else {
		// パスワードを非表示
		$passwordInput.attr('type', 'password');
		$visibleIcon.hide();
		$invisibleIcon.show();
	}
});

// 初期表示：visibleアイコンを隠す
$('.visible-icons .visible').hide();
