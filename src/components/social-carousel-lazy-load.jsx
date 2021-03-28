import React, { Suspense } from 'react';
import ReactDOM from 'react-dom';
const SocialCarousel = React.lazy(() => import('./social_carousel.jsx'));
import { lazyload } from 'react-lazyload';

@lazyload({
  height: 400,
  once: true,
  offset: 1000
})
export default class SocialCarouselLazyLoad extends React.Component {
    render() {
        return (
            <div>
                <Suspense fallback={<div id="wave"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>}>
                    <SocialCarousel />
                </Suspense>
            </div>
        );
    }
}