'use strict';

// === インタビュー開始までのカウントダウン ===
if ($('body').hasClass('page-interview-countdown')) {
	let $count = 3;
	$('#start-countdown').text($count);
	const $countdown = setInterval(() => {
		$count--;
		if ($count <= 0) {
			clearInterval($countdown);
			window.location.href = 'interview.html';
			return;
		}
		$('#start-countdown').text($count);
	}, 1000);
}

// === 回答時間のカウントダウン ===
if ($('body').hasClass('page-answer-countdown')) {
	let $answerCount       = 5;
	const $answerCountdown = $('#answer-countdown');
	$answerCountdown.text($answerCount);
	let $moveCount      = 0;
	const $currentIndex = $('#current-index');
	setInterval(() => {
		$answerCount--;
		if ($answerCount <= 0) {
			$moveCount++;

			$currentIndex.css({
				transform: `translateX(${$moveCount * 2.389}rem)`,
			});

			const $questionIndex    = $('#question-index');
			let $questionIndexCount = $questionIndex.text();
			let $currentIndexCount  = $currentIndex.text();

			$questionIndexCount++;
			$currentIndexCount++;
			if ($questionIndexCount >= 25 && $currentIndexCount >= 25) {
				window.location.href = 'confirm.html';
				return;
			}
			$currentIndex.text($currentIndexCount);
			$questionIndex.text($questionIndexCount);
			$answerCount = 5;
		}
		$answerCountdown.text($answerCount);
	}, 1000);
}
