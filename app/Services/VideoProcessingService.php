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
     * 動画に字幕（VTT）を焼き付ける
     *
     * @param string $videoPath 動画ファイルのパス（S3またはローカルの相対パス）
     * @param string $vttPath 字幕ファイルのパス（S3またはローカルの相対パス）
     * @param bool $overwrite 元のファイルを上書きするかどうか
     * @return string|bool 成功した場合は保存先パス、失敗した場合はfalse
     */
    public function burnSubtitles($videoPath, $vttPath, $overwrite = true)
    {
        try {
            $ffmpegPath = $this->getFFmpegPath();
            if (!$ffmpegPath) {
                Log::error('FFmpegが見つかりません');
                return false;
            }

            $disk = config('filesystems.default');
            $isS3 = $disk === 's3';

            // 一時ディレクトリの作成
            $tempDir = storage_path('app/temp_subtitles_' . uniqid());
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // ファイル名の取得
            $videoFileName = basename($videoPath);
            $vttFileName = basename($vttPath);
            $outputFileName = 'burned_' . $videoFileName;

            // ローカルパス
            $localVideoPath = $tempDir . '/' . $videoFileName;
            $localVttPath = $tempDir . '/' . $vttFileName;
            $localOutputPath = $tempDir . '/' . $outputFileName;

            // ファイルの取得（ダウンロード）
            if ($isS3) {
                if (!Storage::disk('s3')->exists($videoPath) || !Storage::disk('s3')->exists($vttPath)) {
                    Log::error("S3上のファイルが見つかりません: {$videoPath}, {$vttPath}");
                    $this->cleanupTempDir($tempDir);
                    return false;
                }
                file_put_contents($localVideoPath, Storage::disk('s3')->get($videoPath));
                file_put_contents($localVttPath, Storage::disk('s3')->get($vttPath));
            } else {
                $sourceVideoPath = storage_path('app/public/' . $videoPath);
                $sourceVttPath = storage_path('app/public/' . $vttPath);

                if (!file_exists($sourceVideoPath) || !file_exists($sourceVttPath)) {
                    Log::error("ローカルファイルが見つかりません: {$sourceVideoPath}, {$sourceVttPath}");
                    $this->cleanupTempDir($tempDir);
                    return false;
                }
                copy($sourceVideoPath, $localVideoPath);
                copy($sourceVttPath, $localVttPath);
            }

            // FFmpegでの字幕焼き付け
            // Windowsパス対策: パス区切りをスラッシュにし、コロンをエスケープ
            $ffmpegVttPath = str_replace('\\', '/', $localVttPath);
            $ffmpegVttPath = str_replace(':', '\\:', $ffmpegVttPath);

            // フォント設定（日本語対応のため）
            $fontPath = $this->getFontPath();

            // OS判定
            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

            if ($isWindows) {
                // Windowsの場合は標準フォント（MS Gothic）を指定
                // パスではなくフォントファミリー名を指定する方が確実
                $subtitlesFilter = "subtitles='{$ffmpegVttPath}':force_style='FontName=MS Gothic,FontSize=20'";
            } else {
                // Linux等の場合
                // Windowsパスの場合、FFmpegのfilter内ではエスケープが必要
                $fontPathEscaped = str_replace('\\', '/', $fontPath);
                $fontPathEscaped = str_replace(':', '\\:', $fontPathEscaped);

                // force_styleを使ってフォントを指定
                $subtitlesFilter = "subtitles='{$ffmpegVttPath}':force_style='FontName={$fontPathEscaped},FontSize=20'";
            }

            $command = sprintf(
                '"%s" -i "%s" -vf "%s" -c:a copy "%s"',
                $ffmpegPath,
                $localVideoPath,
                $subtitlesFilter,
                $localOutputPath
            );

            Log::info("字幕焼き付けコマンド: {$command}");

            $output = [];
            $returnCode = 0;
            exec($command . ' 2>&1', $output, $returnCode);

            if ($returnCode !== 0 || !file_exists($localOutputPath)) {
                Log::error("FFmpeg字幕焼き付けエラー: " . implode("\n", $output));
                $this->cleanupTempDir($tempDir);
                return false;
            }

            // 保存先の決定
            if ($overwrite) {
                $targetPath = $videoPath;
            } else {
                $pathInfo = pathinfo($videoPath);
                // 拡張子を維持しつつ _burned を付与
                $targetPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_burned.' . $pathInfo['extension'];
            }

            // ファイルをアップロード／コピー
            if ($isS3) {
                Storage::disk('s3')->put($targetPath, file_get_contents($localOutputPath));
            } else {
                copy($localOutputPath, storage_path('app/public/' . $targetPath));
            }

            Log::info("字幕焼き付け完了: {$targetPath}");

            // クリーンアップ
            $this->cleanupTempDir($tempDir);

            return $targetPath;

        } catch (\Exception $e) {
            Log::error("字幕焼き付け例外: " . $e->getMessage());
            if (isset($tempDir)) {
                $this->cleanupTempDir($tempDir);
            }
            return false;
        }
    }

    /**
     * 一時ディレクトリを削除
     */
    private function cleanupTempDir($dir) {
        if (!is_dir($dir)) return;
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            unlink("$dir/$file");
        }
        rmdir($dir);
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
            // Windows Fonts
            'C:\Windows\Fonts\msgothic.ttc',
            'C:\Windows\Fonts\meiryo.ttc',
            'C:\Windows\Fonts\arial.ttf',
            // Linux Fonts
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
