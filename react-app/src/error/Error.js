import React from 'react';

const Error = () => {
    const site_url = localStorage.getItem("site_url");

    return <div id="error" className='error'>
        <div>
            <i className="icons-warning_amber"></i>
            <p className="title">خطایی پیش آمده لطفا دوباره امتحان کنید !</p>
            <a href={site_url} className="btn-primary" title="بازگشت به صفحه اصلی">بازگشت به خانه <i className="icons-arrow_back_ios"></i></a>
        </div>
    </div>
};

export default Error;