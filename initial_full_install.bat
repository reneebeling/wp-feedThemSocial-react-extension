cd %~dp0
call npm install -g webpack
call npm install -g webpack-cli
call npm install
call npm run build
if not exist %~dp0\release\wp-feedThemSocial-react-extension\plugin-extensions\js mkdir %~dp0\release\wp-feedThemSocial-react-extension\plugin-extensions\js
if exist %~dp0\dist\social-media-carousel.js copy %~dp0\dist\social-media-carousel.js %~dp0\release\wp-feedThemSocial-react-extension\plugin-extensions\js
if exist %~dp0\php\ copy %~dp0\php\ %~dp0\release\wp-feedThemSocial-react-extension\plugin-extensions
if exist %~dp0\wp-feedThemSocial-react-extension.php copy %~dp0\wp-feedThemSocial-react-extension.php %~dp0\release\wp-feedThemSocial-react-extension\
if exist %~dp0\wp-feedThemSocial-react-extension.php copy %~dp0\readme.txt %~dp0\release\wp-feedThemSocial-react-extension\