@echo off
REM MySQL データベース 復元スクリプト
set /p BACKUP_FILE="復元するバックアップファイル名を入力してください: "

if not exist %BACKUP_FILE% (
    echo ファイルが見つかりません: %BACKUP_FILE%
    pause
    exit /b 1
)

echo Restoring from: %BACKUP_FILE%
docker exec -i casmen-mysql-1 mysql -u root -ppassword --default-character-set=utf8mb4 laravel < %BACKUP_FILE%

if %ERRORLEVEL% equ 0 (
    echo Restore completed successfully!
) else (
    echo Restore failed!
)

pause
