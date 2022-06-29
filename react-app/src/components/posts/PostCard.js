import React, { useEffect, useState } from 'react';

const PostCard = React.memo(
    ({ postVal }) => {
        const imgSrc = postVal["thumbnail_url"];
        const [onloadImg, setOnloadImg] = useState(false);
        const loaded = (onloadImg === true) ? "lazyloaded" : "lazyloading";

        useEffect(() => {
            setOnloadImg(false);
        }, [imgSrc]);

        return <div id={postVal.postID} className="post-card">
            <div className="post-attachment">
                <a href={postVal.permalink} title={postVal.title}>
                    <img className={loaded}
                        src={imgSrc}
                        alt={postVal.title}
                        onLoad={() => setOnloadImg(true)} />
                    <div className="lazy-preload">
                        <div className="lazy-loader"></div>
                    </div>
                </a>
            </div>
            <div className="post-title">
                <a href={postVal.permalink} title={postVal.title}>
                    <h3>{postVal.title}</h3>
                </a>
            </div>
            <div className="post-excerpt">
                <p>{postVal.excerpt}</p>
            </div>
            <div className="post-more">
                <a href={postVal.permalink} title={postVal.title}>ادامه مطلب</a>
            </div>
        </div>;
    }
);

export default PostCard;