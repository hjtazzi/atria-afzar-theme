import React, { useEffect, useState } from 'react';
import queryString from 'query-string';
import { getUserOrder } from '../../../../api/ActionAdmin';
import AlertItem from '../../../alerts/AlertItem';

const Order = () => {
    const querySearch = queryString.parse(window.location.search);
    const [order, setOrder] = useState([]);
    const [error, setError] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        getUserOrder((callback, res) => {
            if (callback) {
                setOrder(res);
            } else {
                setError(["خطایی در بارگیری اطلاعات شما پیش آمده! لطفا دوباره امتحان کنید."]);
            }
            setLoading(false);
        });
    }, []);

    return <>
        {(loading) && <div className="section-loading"><div className="loader"></div></div>}
        <div className="title">
            <i></i>
            <h6><i className="icons-local_mall"></i>سفارشات</h6>
            <i className="after-h"></i>
        </div>

        <div className='alerts'>
            {(querySearch.success) &&
                ((querySearch.success === "true") ? <AlertItem txt={`سفارش ${querySearch.id} با موفقت ثبت شد.`} cls={"success"} />
                    : <AlertItem txt={"خطایی در ثبت سفارش پیش آمد."} cls={"danger"} />)}

            {error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}
        </div>

        <div className='item-order'>
            <div className='order-head'>
                <div className='oh'>
                    <p>#</p>
                </div>
                <div className='oh'>
                    <p>شماره سفارش</p>
                </div>
                <div className='oh'>
                    <p>کد رهگیری</p>
                </div>
                <div className='oh'>
                    <p>وضعیت</p>
                </div>
                <div className='oh'>
                    <p>زمان پرداخت</p>
                </div>
                <div className='oh'><p><i className='icons-info'></i></p></div>
            </div>
            {(order.length > 0) ? <>
                {order.map((val, i) => {
                    return <OrderItem key={i} val={val} i={i} />
                })}
            </>
                : <p>سفارشی ثبت نشده است.</p>}
        </div>
    </>
};

const OrderItem = React.memo(
    ({ val, i }) => {
        const [active, setActive] = useState(false);

        return <div className={(active) ? "order active" : "order"}>
            <div className='order-info'>
                <div className='oi'>
                    <p>{i + 1}</p>
                </div>
                <div className='oi'>
                    <p>{val.order_id}</p>
                </div>
                <div className='oi'>
                    <p>{val.track_id}</p>
                </div>
                <div className='oi'>
                    <p>{val.status_no}</p>
                </div>
                <div className='oi'>
                    <p>{val.pay_date}</p>
                </div>
                <div className='oi'>
                    <button className='btn-primary' title='جزئیات' onClick={() => setActive(!active)}>
                        <i className='icons-keyboard_arrow_down'></i>
                    </button>
                </div>
            </div>

            <div className='order-details' style={{ maxHeight: (active) && "24rem" }}>
                {val.objects.map((v, j) => {
                    return <div key={j} className='od'>
                        <div className='obj'>
                            <p>{j + 1}</p>
                        </div>

                        <div className='obj'>
                            <p>
                                <a href={v.link} target='_blank' rel="noreferrer" title={v.title}>
                                    {v.title}
                                </a>
                            </p>
                        </div>

                        <div className='obj'>
                            <p>{(parseInt(v.price) > 0) ? <>{v.price} <small>تومان</small></> : "رایگان"}</p>
                        </div>

                        {(v.dl_link) && <div className='obj'>
                            <p>
                                <a href={v.dl_link} target='_blank' rel="noreferrer" className='btn-primary' title='دریافت'>
                                    <i className='icons-file_download'></i>
                                </a>
                            </p>
                        </div>}

                    </div>
                })}

                <div className='od-foot'>
                    <div className='od-f'>
                        <p>
                            <b>قابل پرداخت:</b>
                            <val>{val.amount}</val>
                            <small> تومان</small>
                        </p>
                    </div>
                    <div className='od-f'>
                        <p>
                            <b>توضیحات: </b>
                            {(val.details) ? val.details : "-"}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    }
);

export default Order;