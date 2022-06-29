import React, { useEffect, useState } from 'react';

const ProductCard = React.memo(
  ({ postVal }) => {
    const imgSrc = postVal.thumbnail_url;
    const [onloadImg, setOnloadImg] = useState(false);
    const loaded = (onloadImg === true) ? "lazyloaded" : "lazyloading";
    const price = parseInt(postVal.price);
    const tPrice = new Intl.NumberFormat().format(price);

    useEffect(() => {
      setOnloadImg(false);
    }, [imgSrc]);

    return <div id={postVal.postID} className="product-card">
      <div className="product-attachment">
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
      <div className="product-title">
        <a href={postVal.permalink} title={postVal.title}>
          <h3>{postVal.title}</h3>
        </a>
        <i></i>
      </div>
      <div className="product-excerpt">
        <p>{postVal.excerpt}</p>
      </div>
      <div className="product-price">
        <span>{(price > 0) ? <>{tPrice} <small>تومان</small></> : <>رایگان</>}</span>
      </div>
      <div className="product-info">
        <span><i className="icons-star_black"></i>4.5</span>
        <span><i className="icons-local_grocery_store"></i>{postVal.selling}</span>
      </div>
      {(parseInt(postVal.discount) > 0) ? <span className="product-discount">{postVal.discount}%<span>تخفیف</span></span> : ""}
    </div>;
  }
);

export default ProductCard;