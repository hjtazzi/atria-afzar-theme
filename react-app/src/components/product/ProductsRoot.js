import React, { useEffect, useState } from 'react';
import { getProductRoot, postCartItem } from '../../api/ActionAdmin';
import AlertItem from '../alerts/AlertItem';
import { useThemeState } from '../../context/ThemeContext';

const ProductsRoot = () => {
    const data_options = document.getElementById("product_root").getAttribute("data-options");
    const options = (data_options) ? JSON.parse(data_options) : { post: 0, login: false };
    const { site_url } = useThemeState();
    const [loading, setLoading] = useState(true);
    const [successAddCart, setSuccessAddCart] = useState(false);
    const [product, setProduct] = useState({ have_post: false, have_cart: false });
    const [error, setError] = useState([]);

    useEffect(() => {
        getProductRoot(
            options.post,
            (callback, res) => {
                if (callback) {
                    if (res.success) {
                        const price = new Intl.NumberFormat().format(parseInt(res.product.price));
                        const tPrice = new Intl.NumberFormat().format(parseInt(res.product.tPrice));
                        setProduct({ ...res.product, fPrice: price, fTprice: tPrice });
                    } else {
                        setError(res.error);
                    }
                } else {
                    setError(["خطایی در بارگذاری مشخصات محصول پیش آمده!"]);
                }
                setLoading(false);
            }
        );
    }, []);

    const onAddShopping = () => {
        setLoading(true);
        postCartItem(
            options.post,
            (callback, res) => {
                if (callback) {
                    if (res.success) {
                        setProduct({ ...product, have_cart: true });
                        setSuccessAddCart(true);
                    } else {
                        setError(res.error);
                    }
                } else {
                    setError(["خطایی پیش آمد!"]);
                }
                setLoading(false);
            }
        );
    }

    return <>
        <div className='product-content'>
            {(loading) && <div className="section-loading"><div className="loader"></div></div>}

            <div className='alerts'>
                {(successAddCart) && <AlertItem txt={"با موفقیت به سبد خرید اضافه شد."} cls={"success"} />}

                {error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}
            </div>

            {(product.have_post) &&
                <div className='product-info'>
                    {(product.discount > 0) &&
                        <div className="product-discount">
                            <span>{product.fPrice}</span>
                            <var>{`-${product.discount}%`}</var>
                        </div>}

                    <div className="product-price">
                        <span>{(product.price > 0) ? <>{product.fTprice} <small>تومان</small></> : "رایگان"}</span>
                    </div>

                    {(product.have_cart === false) ? <div className='product-shopping'>
                        {(options.login) ? <button className='btn-success' onClick={onAddShopping}><i className='icons-add_shopping_cart'></i> افزودن به سبد خرید</button>
                            : <p>برای افزودن محصول به سبد خرید، <a href={`${site_url}/account`}>وارد</a> حساب کاربری خود شوید.</p>}
                    </div>
                        : <div className='product-cart'>
                            <p>قبلا به <a href={`${site_url}/account?state=shopping_cart`}>سبد خرید</a> شما اضافه شده است.</p>
                        </div>}
                </div>}
        </div>
    </>
};

export default ProductsRoot;