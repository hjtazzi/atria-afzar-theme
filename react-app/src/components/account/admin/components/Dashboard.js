import React, { useEffect, useState } from 'react';

const Dashboard = ({sideMenuUser}) => {
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        setLoading(false);
    }, []);

    return <>
        {(loading) && <div className="section-loading"><div className="loader"></div></div>}
        <div className="title">
            <i></i>
            <h6><i className="icons-person"></i>داشبورد</h6>
            <i className="after-h"></i>
        </div>

        <div className='item-dashboard'>
            <p>{sideMenuUser.display_name} عزیز خوش آمدید.</p>
        </div>
    </>;
};

export default Dashboard;