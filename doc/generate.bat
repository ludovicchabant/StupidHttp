@echo off
setlocal

set THIS_DIR=%~dp0

if not defined PHP_PEAR_BIN_DIR (
    echo Can't find the PHP executable. Do you have PEAR installed?
    exit /b 1
)
set PHP="%PHP_PEAR_BIN_DIR%\php.exe"

if not defined PHP_DOC (
    set PHP_DOC="%PHP_PEAR_BIN_DIR%\..\PhpDocumentor\phpdoc"
)
if not exist %PHP_DOC% (
  echo Please define the PHP_DOC environment variable.
  exit /b 1
)

%PHP% %PHP_DOC% -c "%THIS_DIR%stupidhttp.ini"
