<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VideoProcessingService
{
    /**
     * 動画にテキストオーバーレイを追加
     *
     * @param string $inputPath 入力動画ファイルパス
     * @param string $questionText 質問テキスト
     * @param int $questionNumber 質問番号
     * @return string|null 処理済み動画のパス
     */
    public function addQuestionOverlay($inputPath, $questionText, $questionNumber)
    {
        try {
            // FFmpegの実行ファイルパス（環境に応じて調整）
            $ffmpegPath = $this->getFFmpegPath();

            if (!$ffmpegPath) {
                Log::error('FFmpegが見つかりません');
                return null;
            }

            // 入力ファイルの絶対パス
            $inputFullPath = storage_path('app/public/' . $inputPath);

            // 出力ファイル名を生成（MP4形式に変更）
            $pathInfo = pathinfo($inputPath);
            $outputFileName = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_with_text.mp4';
            $outputFullPath = storage_path('app/public/' . $outputFileName);

            // テキストオーバーレイのFFmpegコマンドを生成
            $questionDisplayText = "Q.{$questionNumber} {$questionText}";

            // 日本語テキストの処理を改善
            $escapedText = $questionDisplayText;

            // UTF-8エンコーディングを確実にする
            if (!mb_check_encoding($escapedText, 'UTF-8')) {
                $escapedText = mb_convert_encoding($escapedText, 'UTF-8', 'auto');
            }

            // 改行文字や制御文字を削除
            $escapedText = preg_replace('/[\r\n\t]+/', ' ', $escapedText);
            $escapedText = trim($escapedText);

            // FFmpegの特殊文字をエスケープ（日本語対応版）
            $escapedText = str_replace([
                "'", ":", "\\", "%", "[", "]"
            ], [
                "\\'", "\\:", "\\\\", "\\%", "\\[", "\\]"
            ], $escapedText);

            // 日本語文字の安全なエスケープ
            $escapedText = addcslashes($escapedText, "\x00..\x1f");

            // フォントファイルのパス
            $fontPath = $this->getFontPath();

            // フォントパスがある場合は使用、ない場合はデフォルトフォント
            if (!empty($fontPath)) {
                $fontOption = ":fontfile='" . $fontPath . "'";
            } else {
                $fontOption = ""; // デフォルトフォントを使用
            }

            // まず入力動画の長さを取得
            $durationOutput = [];
            $durationCommand = sprintf('"%s" -i "%s" 2>&1 | grep "Duration"', $ffmpegPath, $inputFullPath);
            exec($durationCommand, $durationOutput);

            $videoDuration = 5; // デフォルト5秒

            // 動画の実際の長さを解析
            foreach ($durationOutput as $line) {
                if (preg_match('/Duration: (\d{2}):(\d{2}):(\d{2})\.(\d{2})/', $line, $matches)) {
                    $hours = intval($matches[1]);
                    $minutes = intval($matches[2]);
                    $seconds = intval($matches[3]);
                    $centiseconds = intval($matches[4]);
                    $videoDuration = $hours * 3600 + $minutes * 60 + $seconds + $centiseconds / 100;
                    Log::info("動画の実際の長さ: {$videoDuration}秒");
                    break;
                }
            }

            // 字幕を画面下部に表示（動画全体の長さに合わせて表示）
            // UTF-8対応のFFmpegコマンド
            $command = sprintf(
                '"%s" -i "%s" -vf "drawtext=text=\'%s\'%s:fontsize=20:fontcolor=white:box=1:boxcolor=black@0.9:boxborderw=6:x=(w-text_w)/2:y=h-text_h-40:enable=\'between(t,0,%.2f)\':expansion=none" -c:v libx264 -c:a aac -preset fast -crf 23 -movflags +faststart "%s"',
                $ffmpegPath,
                $inputFullPath,
                $escapedText,
                $fontOption,
                $videoDuration,
                $outputFullPath
            );            Log::info("FFmpegコマンド実行: {$command}");

            // コマンド実行
            $output = [];
            $returnCode = 0;
            exec($command . ' 2>&1', $output, $returnCode);

            if ($returnCode === 0 && file_exists($outputFullPath)) {
                Log::info("動画処理成功: {$outputFileName}");
                return $outputFileName;
            } else {
                Log::error("FFmpeg実行エラー: " . implode("\n", $output));
                return null;
            }

        } catch (\Exception $e) {
            Log::error("動画処理エラー: " . $e->getMessage());
            return null;
        }
    }

    /**
     * 複数の動画を結合
     *
     * @param array $videoPaths 動画ファイルパスの配列
     * @param string $outputPath 出力ファイルパス
     * @return bool
     */
    public function concatVideos($videoPaths, $outputPath)
    {
        try {
            $ffmpegPath = $this->getFFmpegPath();

            if (!$ffmpegPath || empty($videoPaths)) {
                return false;
            }

            // 一時的なファイルリストを作成
            $listPath = storage_path('app/temp_video_list.txt');
            $listContent = '';
            $validVideos = 0;

            foreach ($videoPaths as $index => $videoPath) {
                $fullPath = storage_path('app/public/' . $videoPath);
                if (file_exists($fullPath)) {
                    $listContent .= "file '" . $fullPath . "'\n";
                    $validVideos++;
                    Log::info("結合対象動画{$index}: {$videoPath} (存在確認済み)");
                } else {
                    Log::warning("結合対象動画{$index}が見つかりません: {$videoPath}");
                }
            }

            if ($validVideos === 0) {
                Log::error("結合可能な動画ファイルがありません");
                return false;
            }

            file_put_contents($listPath, $listContent);
            Log::info("結合リストファイル作成: {$validVideos}個の動画");

            $outputFullPath = storage_path('app/public/' . $outputPath);

            // H.264/AACに変換して互換性を確保
            $command = sprintf(
                '"%s" -f concat -safe 0 -i "%s" -c:v libx264 -c:a aac -preset fast -crf 23 "%s"',
                $ffmpegPath,
                $listPath,
                $outputFullPath
            );

            Log::info("動画結合コマンド: {$command}");

            $output = [];
            $returnCode = 0;
            exec($command . ' 2>&1', $output, $returnCode);

            // 一時ファイルを削除
            if (file_exists($listPath)) {
                unlink($listPath);
            }

            if ($returnCode === 0 && file_exists($outputFullPath)) {
                Log::info("動画結合成功: {$outputPath}");
                return true;
            } else {
                Log::error("動画結合エラー: " . implode("\n", $output));
                return false;
            }

        } catch (\Exception $e) {
            Log::error("動画結合エラー: " . $e->getMessage());
            return false;
        }
    }

    /**
     * FFmpegのパスを取得
     *
     * @return string|null
     */
    private function getFFmpegPath()
    {
        // 一般的なFFmpegのパス
        $possiblePaths = [
            'ffmpeg', // PATH環境変数に設定されている場合
            'C:\ffmpeg\bin\ffmpeg.exe', // Windows用の一般的なパス
            '/usr/bin/ffmpeg', // Linux用
            '/usr/local/bin/ffmpeg', // macOS用
        ];

        foreach ($possiblePaths as $path) {
            $output = [];
            $returnCode = 0;
            exec('"' . $path . '" -version 2>&1', $output, $returnCode);

            if ($returnCode === 0) {
                return $path;
            }
        }

        return null;
    }

    /**
     * フォントファイルのパスを取得
     *
     * @return string
     */
    private function getFontPath()
    {
        // 日本語に対応したフォントパスを優先順で指定
        $fontPaths = [
            '/usr/share/fonts/opentype/noto/NotoSansCJK-Regular.ttc',
            '/usr/share/fonts/opentype/noto/NotoSerifCJK-Regular.ttc',
            '/usr/share/fonts/opentype/noto/NotoSansCJK-Bold.ttc',
            '/usr/share/fonts/truetype/fonts-japanese-gothic.ttf',
            '/usr/share/fonts/opentype/ipafont-gothic/ipagp.ttf',
            '/usr/share/fonts/opentype/ipafont-gothic/ipag.ttf',
            '/usr/share/fonts/opentype/unifont/unifont_jp.otf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/System/Library/Fonts/Arial Unicode MS.ttf',
            '/usr/share/fonts/TTF/arial-unicode-ms.ttf',
        ];

        foreach ($fontPaths as $path) {
            if (file_exists($path)) {
                Log::info("Video processing: Using font: " . $path);
                return $path;
            }
        }

        Log::warning("Video processing: No Japanese font found, using fallback");
        return '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf'; // フォールバック
    }
}
