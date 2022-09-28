@echo off

cd /d %~dp0

for %%f in (jquery*.js) do (
  copy /b %%f + lf.txt %%f.tmp
)
copy /b jquery-*.js.tmp + jquery.*.js.tmp jquery.js
del *.tmp

copy jquery.js ..\..\jquery.js
