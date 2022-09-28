@echo off
setlocal enabledelayedexpansion

:prepare
rem ===========================================================================
rem   前処理
rem ===========================================================================

  cd /d %~dp0
  set ng=0
  set file_orderlist=orderlist.txt
  set dir_temp=temp\
  if not exist %dir_temp% mkdir %dir_temp%
  del /q %dir_temp%* > nul 2>&1

:main
rem ===========================================================================
rem   主処理
rem ===========================================================================

  for /d %%d in (*.js) do (

    echo.
    echo %%d
    
    rem ロード順序定義ファイルを確認
    rem ロード順の判断材料がないので実装版の作成は行いません。
    if not exist %%d\%file_orderlist% (
      echo     [NG] not found %file_orderlist%
      set /a ng+=1
    ) else (
      
      rem 実装版と開発版のタイムスタンプを確認
      rem 実装版を作成した以降に開発版が編集されていなければ
      rem 再度、作成しません。
      set n=-1
      for %%o in (..\%%d) do (
        set n=0
        for /f %%n in (%%d\%file_orderlist%) do (
          for %%s in (%%d\%%n) do (
            if "%%~to" lss "%%~ts" set /a n+=1
          )
        )
      )
      if "!n!" == "0" (
        echo     [  ] not modified
      ) else (
        call :compress_and_merge_and_deploy %%d
      )
    )
  )
  
  goto :end

:compress_and_merge_and_deploy
:compress

  rem 開発版を圧縮
  set e=0
  for /f %%s in (%1\%file_orderlist%) do (
    if exist %1\%%s (
      java -jar tools\yuicompressor-2.4.7.jar -o %dir_temp%%%s.min %1\%%s 2> %1\%%s.log
      if "!errorlevel!" == "0" (
        echo     [  ] compress succeed %%s
        del /q %1\%%s.log
      ) else (
        echo     [NG] compress failed %%s
        set /a e+=1
      )
    ) else (
      echo     [NG] not found %%s
      set /a e+=1
    )
  )
  if not "!e!" == "0" (
    set /a ng+=1
    goto :eof
  )
  
:merge
  
  rem 圧縮版を併合
  if exist %dir_temp%%1 del /q %dir_temp%%1
  for /f %%s in (%1\%file_orderlist%) do (
    type %dir_temp%%%s.min >> %dir_temp%%1
    if "!errorlevel!" == "0" (
      echo     [  ] merge succeed %%s
    ) else (
      echo     [NG] merge failed %%s
      set /a ng+=1
      goto :eof
    )
  )

:deploy

  rem 実装版として配置
  rem 現在の実装版と新しい実装版と比較して内容が同じであれば配置
  rem は行いません。
  if exist ..\%1 (
    fc %dir_temp%%1 ..\%1 > nul 2>&1
    if "!errorlevel!" == "0" (
      set deploy=0
    ) else (
      set deploy=1
    )
  ) else (
    set deploy=1
  )
  
  if "!deploy!" == "1" (
    copy /y %dir_temp%%1 ../%1 > nul 2>&1
    if "!errorlevel!" == "0" (
      echo     [  ] deploy succeed
    ) else (
      echo     [NG] deploy failed %1
      set /a ng+=1
    )
  ) else (
    echo     [  ] not modified
  )
  goto :eof

:end
rem ===========================================================================
rem   後処理
rem ===========================================================================

  rem 結果に応じてメッセージを表示
  echo.
  if "%ng%" == "0" (
    echo complete
  ) else (
    echo check [NG] and retry
  )
  
endlocal
pause
