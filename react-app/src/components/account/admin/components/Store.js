import React, { useEffect, useState } from 'react';
import { getPayments } from '../../../../api/ActionAdmin';
import AlertItem from '../../../alerts/AlertItem';

const Store = () => {
    const [store, setStore] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState([]);

    useEffect(() => {
        getPayments((callback, res) => {
            if (callback) {
                setStore(...[res]);
            } else {
                setError(["خطایی پیش آمد!"]);
            }
            setLoading(false);
        });
    }, []);

    return <>
        {(loading) && <div className="section-loading"><div className="loader"></div></div>}

        <div className="title">
            <i></i>
            <h6><i className="icons-storefront"></i>فروشگاه</h6>
            <i className="after-h"></i>
        </div>

        <div className='alerts'>{error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}</div>

        <div className='item-store'>
            <div className='store-item'>
                <div className='si'>نام کاربری</div>
                <div className='si'>شماره سفارش</div>
                <div className='si'>قیمت</div>
                <div className='si'>وضعیت</div>
                <div className='si'>تاریخ</div>
                <div className='si'>خطاها</div>
                <div className='si'>pr_id</div>
            </div>

            {store.map((val, i) => {
                return <div key={i} className='store-item'>
                    <div className='si'>{val.user_info.user}</div>
                    <div className='si'>{val.order_id}</div>
                    <div className='si'>{new Intl.NumberFormat().format(parseInt(val.amount))} <small>ريال</small></div>
                    <div className='si'>{val.status_no}</div>
                    <div className='si'>{val.pay_date}</div>
                    <div className='si'>{(val.errors) ? val.errors : "..."}</div>
                    <div className='si'>{val.objects.map((v, j) => <span key={j}>{v.pr_id} - </span>)}</div>
                </div>
            })}
        </div>
    </>
};

export default Store;