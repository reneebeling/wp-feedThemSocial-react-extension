# WordPress Plugin ReactJS extensions to the Feed Them Social Wordpress Plugin
Used to display social media data fetched with [Feed Them Social](https://wordpress.org/plugins/feed-them-social/) backend in order to visualize a carousel based on ReactJS
## Usage

1. Upload the extracted `wp-feedThemSocial-react-extension` [release]() folder to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

## Developement

### Requirements for changes

Install the module bundler Webpack v4+ , webpack-cli  ***globally***.
```
npm install -g webpack
npm install -g webpack-cli
```

### Installation
Install all dependencies:
```
  initial_full_install.bat
```
Build release:
```
  build_release.bat
```

Try the local dev server:
```
  start_dev_server.bat
```