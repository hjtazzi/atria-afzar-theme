import React, { useEffect, useState } from 'react';
import { deleteCartItem, getShoppingCart, postCreatePayment } from '../../../../api/ActionAdmin';
import { setThemeItemMenu } from '../../../../context/ThemeActions';
import { useThemeDispatch } from '../../../../context/ThemeContext';
import AlertItem from '../../../alerts/AlertItem';

const ShoppingCart = () => {
    const themeDispatch = useThemeDispatch();
    const [cart, setCart] = useState({ cart_items: [], final_price: 0 });
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState([]);

    useEffect(() => {
        getShoppingCart((callback, res) => {
            if (callback) {
                setCart(res);
            } else {
                setError(["خطایی در بارگیری اطلاعات شما پیش آمده! لطفا دوباره امتحان کنید."]);
            }
            setLoading(false);
        });
    }, []);

    const removeCartItem = (row) => {
        setLoading(true);
        deleteCartItem(
            row,
            (callback, res) => {
                if (!callback) {
                    setError([...res.error]);
                }

                getShoppingCart((callback, res) => {
                    if (callback) {
                        setCart(res);
                    } else {
                        setError(["خطایی در بارگیری اطلاعات شما پیش آمده! لطفا دوباره امتحان کنید."]);
                    }
                    setLoading(false);
                });
            }
        );
    }

    const submitPayment = () => {
        setLoading(true);
        setError([]);
        postCreatePayment(
            {},
            (callback, res) => {
                if (callback) {
                    if (res.success) {
                        if (res.redirect) {
                            setLoading(true);
                            window.location.assign(res.link);
                        } else {
                            setThemeItemMenu(themeDispatch, "order");
                        }
                    } else {
                        setError(res.error);
                    }
                } else {
                    setError(["خطایی در ایجاد پرداخت پیش آمده!  لطفا دوباره امتحان کنید."]);
                }
                setLoading(false);
            }
        );
    }

    return <>
        {(loading) && <div className="section-loading"><div className="loader"></div></div>}

        <div className="title">
            <i></i>
            <h6><i className="icons-local_grocery_store"></i>سبد خرید</h6>
            <i className="after-h"></i>
        </div>

        <div className='alerts'>{error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}</div>

        <div className='item-shopping-cart'>
            {(cart.cart_items.length > 0) ? <>
                {cart.cart_items.map((val, i) => {
                    const price = new Intl.NumberFormat().format(parseInt(val.product.price));
                    const tPrice = new Intl.NumberFormat().format(parseInt(val.product.tPrice));

                    return <div key={i} className='cart-item'>
                        <figure className='cart-item-thumbnail'>
                            <a href={val.product.short_link} target='_blank' rel="noreferrer" title={val.product.title}>
                                <img src={val.product.thumbnail_url} alt={val.product.title} />
                            </a>
                        </figure>

                        <div className='cart-item-info'>
                            <div className='item-title'>
                                <h6>
                                    <a href={val.product.short_link} target='_blank' rel="noreferrer" title={val.product.title}>
                                        {val.product.title}
                                    </a>
                                </h6>
                            </div>

                            <div className='product'>
                                {(val.product.discount > 0) &&
                                    <div className="discount">
                                        <span>{price}</span>
                                        <var>{`-${val.product.discount}%`}</var>
                                    </div>}

                                <div className='price'>
                                    <span>{(val.product.tPrice > 0) ? <>{tPrice} <small>تومان</small></> : "رایگان"}</span>
                                </div>
                            </div>
                        </div>

                        <div className='cart-item-remove'>
                            <button className='btn-danger' onClick={() => removeCartItem(val.id)} title='حذف'>
                                <i className='icons-remove_shopping_cart'></i>
                            </button>
                        </div>
                    </div>;
                })}

                <div className='cart-foot'>
                    <p>
                        <b>قیمت نهایی:
                            {(parseInt(cart.final_price) > 0) ?
                                <span>{new Intl.NumberFormat().format(parseInt(cart.final_price))} <small>تومان</small></span>
                                : <span>رایگان</span>}
                        </b>
                    </p>
                    <button className='btn-success' onClick={submitPayment}>
                        <i className='icons-payment'></i> پرداخت
                    </button>
                </div>
            </>
                : <p>سبد خرید شما خالی است.</p>}
        </div>
    </>
};

export default ShoppingCart;