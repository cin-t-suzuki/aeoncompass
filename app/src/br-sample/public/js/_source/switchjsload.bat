@echo off

:prepare
rem ===========================================================================
rem   �O����
rem ===========================================================================

  cd /d %~dp0
  set file_config=..\..\..\config\config.xml

:main
rem ===========================================================================
rem   �又��
rem ===========================================================================

  if exist %file_config% (

    rem ����̂݃o�b�N�A�b�v�t�@�C�����쐬
    if not exist %file_config%.bak (
      copy /y %file_config% %file_config%.bak 2>&1 > nul
    )

    rem �ݒ�t�@�C����ύX
    php tools\switchjsload.php %file_config%
    
  ) else (

    echo [NG] not found %file_config%

  )

rem ===========================================================================
rem   �㏈��
rem ===========================================================================

  echo.
  echo.

pause
