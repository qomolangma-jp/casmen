'use strict';

// === 回答時間のカウントダウン ===
$(function () {
	let timeCount              = 9;
	let questionIncrementCount = 1;
	let questionDecrementCount = 11;

	const allQuestions = 12;
	const $currentTime = $('#current-time');

	function startCountdown() {
		setInterval(() => {
			timeCount--;

			if (timeCount >= 0) {
				$currentTime.text(timeCount);
			}

			if (timeCount < 0) {
				questionIncrementCount++;
				questionDecrementCount--;

				if (questionIncrementCount > allQuestions && questionDecrementCount < 0) {
					window.location.href = 'confirm.html';
					return;
				}

				$('#question-increment').text(questionIncrementCount);
				$('#question-decrement').text(questionDecrementCount);

				timeCount = 9;
				$currentTime.text(timeCount);
			}
		}, 1000);
	}

	startCountdown();
});
