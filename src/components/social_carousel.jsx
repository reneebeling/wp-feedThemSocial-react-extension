import React, { Component } from 'react';
import Carousel from '@brainhubeu/react-carousel';
import '@brainhubeu/react-carousel/lib/style.css';

export default class MyCarousel extends Component {
	constructor(props) {
		super(props);

		this.state = {
			instaDataSlides: [],
            moveMap: "",
        };
    }

    componentDidMount = () => {
        if(typeof LJCustomScriptsSocialMedia  !== 'undefined' && LJCustomScriptsSocialMedia["nonce"]){
            let wp_REST_API_URL = '/wp-json/instagram/data/v1/meta-and-links/?_wpnonce=' + FTSInstaExtension["nonce"];
            fetch(wp_REST_API_URL)
                .then(res => res.json())
                .then(instaData => {
                    if(instaData["data"] && instaData["data"]["status"] === 418){
                        var elem = document.getElementById("wp-social-carousel");
                        elem.parentNode.parentNode.remove();
                    } else {
                        if (process.env.NODE_ENV !== 'production') {
                            console.log("instaData: " + instaData)
                        }
                        let instaDataSlides = instaData.map((insta_media) => {
                        var insertTag = "";
                            return(
                                <a key={insta_media["id"].toString()} title={insta_media["caption"]}  href={insta_media["permalink"]} >
                                    { insta_media["media_type"] === "VIDEO" && !window.navigator.userAgent.match(/Version\/[\d\.]+.*Safari/)
                                    ? <video class="lj-social-carousel" autoplay="autoplay" muted="true" volume="0" loop="loop" >
                                        <source src={insta_media["media_url"]} type="video/mp4" />
                                    </video>
                                    : <img className="lj-social-carousel" src={insta_media["media_url"]} />
                                    }
                                    <div class="lj-social-carousel-background-instagram">
                                    </div>
                                </a>
                            )                        
                        })
                        this.setState({
                            instaDataSlides: instaDataSlides
                        })
                        if (process.env.NODE_ENV !== 'production') {
                            console.log("new instagram data state: ", this.state.instaDataSlides);
                        }
                        this.setState(prevState => {
                            return {moveMap: prevState.moveMap !== "inactive" ? prevState.moveMap + "inactive" : prevState.moveMap}
                        });                        
                    }
                })
                .catch(function(res){
                        console.error(res);
                    }
                );
        }
    }
    render() {
        return (
            <div >                
                {this.state.instaDataSlides.length > 0 ?
                    <div className={'lj-frame ' + this.state.moveMap} >
                        <div className={'lj-frame-opacity-background ' + this.state.moveMap} >
                            <h2 className="block-title"><span>Instagram</span ></h2 >
                            <Carousel   
                                slidesPerPage={5}
                                autoPlay={5000}
                                infinite
                                animationSpeed={3000}
                                slides = {this.state.instaDataSlides} 
                                breakpoints={{
                                    540: {
                                        slidesPerPage: 2,
                                        arrows: false
                                    },
                                    800: {
                                        slidesPerPage: 3,
                                        arrows: false
                                    },
                                    900: {
                                        slidesPerPage: 4,
                                        arrows: false
                                    }
                                }}
                            >
                            </Carousel>                    
                        </div>
                    </div>
                    : <div id="wave"><span className="dot"></span><span className="dot"></span><span className="dot"></span></div> }
            </div >
            
        );
    }
}