<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * レンタルサーバー用軽量版動画処理サービス
 * FFmpegが使用できない環境での代替実装
 */
class VideoProcessingServiceLite
{
    /**
     * 質問オーバーレイ付き動画を作成（軽量版）
     * 実際の動画処理は行わず、メタデータのみ管理
     */
    public function addQuestionOverlay($inputPath, $outputPath, $questionText, $duration = 5)
    {
        try {
            // FFmpegが使用できないため、元動画をそのままコピー
            if (Storage::exists($inputPath)) {
                Storage::copy($inputPath, $outputPath);

                // 質問テキストをメタデータとして保存
                $metadataPath = str_replace('.webm', '_metadata.json', $outputPath);
                $metadata = [
                    'question' => $questionText,
                    'duration' => $duration,
                    'created_at' => now()->toISOString(),
                    'original_path' => $inputPath
                ];

                Storage::put($metadataPath, json_encode($metadata, JSON_UNESCAPED_UNICODE));

                Log::info("動画メタデータ保存完了: {$outputPath}");
                return true;
            }

            Log::error("入力動画が見つかりません: {$inputPath}");
            return false;

        } catch (\Exception $e) {
            Log::error("動画処理エラー: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 複数動画の結合（軽量版）
     * 個別動画のまま管理し、再生時にJavaScriptで連続再生
     */
    public function concatVideos($videoPaths, $outputPath)
    {
        try {
            // 動画リストをメタデータとして保存
            $concatMetadata = [
                'videos' => $videoPaths,
                'output_path' => $outputPath,
                'created_at' => now()->toISOString(),
                'type' => 'concat_playlist'
            ];

            $metadataPath = str_replace('.webm', '_playlist.json', $outputPath);
            Storage::put($metadataPath, json_encode($concatMetadata, JSON_UNESCAPED_UNICODE));

            // 最初の動画を出力パスにコピー（プレビュー用）
            if (!empty($videoPaths) && Storage::exists($videoPaths[0])) {
                Storage::copy($videoPaths[0], $outputPath);
            }

            Log::info("動画プレイリスト作成完了: {$outputPath}");
            return true;

        } catch (\Exception $e) {
            Log::error("動画結合エラー: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 動画の再生リストを取得
     */
    public function getVideoPlaylist($outputPath)
    {
        $metadataPath = str_replace('.webm', '_playlist.json', $outputPath);

        if (Storage::exists($metadataPath)) {
            $metadata = json_decode(Storage::get($metadataPath), true);
            return $metadata['videos'] ?? [];
        }

        return [$outputPath]; // 単一動画として返す
    }
}
