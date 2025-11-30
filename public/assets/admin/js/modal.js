'use strict';

// === フォーカスを外す関数 ===
function dropFocus() {
	if (document.activeElement) {
		document.activeElement.blur();
	}
}

$(function () {
	// === 不採用モーダル ===
	$('.iziModal-rejected').iziModal({
		onClosing: dropFocus,
	});

	// ==== 通過モーダル ===
	$('.iziModal-passed').iziModal({
		onClosing: dropFocus,
	});

	// === 面接URL送信モーダル ===
	$('.iziModal-submit').iziModal({
		onClosing: dropFocus,
	});
});
