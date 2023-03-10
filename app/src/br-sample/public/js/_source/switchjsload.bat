@echo off

:prepare
rem ===========================================================================
rem   前処理
rem ===========================================================================

  cd /d %~dp0
  set file_config=..\..\..\config\config.xml

:main
rem ===========================================================================
rem   主処理
rem ===========================================================================

  if exist %file_config% (

    rem 初回のみバックアップファイルを作成
    if not exist %file_config%.bak (
      copy /y %file_config% %file_config%.bak 2>&1 > nul
    )

    rem 設定ファイルを変更
    php tools\switchjsload.php %file_config%
    
  ) else (

    echo [NG] not found %file_config%

  )

rem ===========================================================================
rem   後処理
rem ===========================================================================

  echo.
  echo.

pause
