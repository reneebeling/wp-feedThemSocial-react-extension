import React from 'react';
import ReactDOM from 'react-dom';
import './src/components/style/fts-react-extension.scss';
import SocialCarouselLazyLoad from './src/components/social-carousel-lazy-load.jsx';

const rootElmementSocialCarouselId = 'wp-social-carousel';
 
if (process.env.NODE_ENV !== 'production') {
   console.log('Development mode is activated.');
   console.log('Root element "%s" will be created.', rootElmementSocialCarouselId);  
   let rootElmement = document.createElement('div');
   rootElmement.setAttribute("id", rootElmementSocialCarouselId);
   document.body.appendChild(rootElmement);
}

if (document.getElementById(rootElmementSocialCarouselId)) {
    ReactDOM.render(<SocialCarouselLazyLoad />, document.getElementById(rootElmementSocialCarouselId));   
}
else
{
    console.log('Root element "%s" does not exist.', rootElmementSocialCarouselId);  
}

window.onbeforeunload = function () {window.scrollTo(0, 0);}