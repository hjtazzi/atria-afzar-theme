import React, { useEffect, useState } from 'react';
import { getEditUsers } from '../../../../api/ActionAdmin';
import AlertItem from '../../../alerts/AlertItem';
import EditUser from './EditUser';

const EditUsers = () => {
    const [users, setUsers] = useState([]);
    const [error, setError] = useState([]);
    const [loading, setLoading] = useState(true);
    const [searchUser, setSearchUser] = useState("");

    const filterSearch = (users.length > 0) ? users.filter((val) => {
        const user = val.username.toLowerCase();

        if (searchUser.replace(/\s+/g, " ").trim() === "") {
            return true;
        } else if (user.includes(searchUser)) {
            return val;
        }
        return false;
    }) : [];

    useEffect(() => {
        getEditUsers((callback, res) => {
            if (callback) {
                setUsers(res);
            } else {
                setError(["خطایی در بارگیری اطلاعات پیش آمده!"]);
            }
            setLoading(false);
        });
    }, []);

    return <>
        {(loading) && <div className="section-loading"><div className="loader"></div></div>}

        <div className="title">
            <i></i>
            <h6><i className="icons-people_outline"></i>ویرایش کاربران</h6>
            <i className="after-h"></i>
        </div>

        <div className='alerts'>{error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}</div>

        <div className='item-edit-users'>
            <div className='edit-users-search'>
                <div className='input-group'>
                    <input
                        type='text'
                        role='search'
                        className='form-control'
                        placeholder={`جستجو کاربران (از ${users.length} کاربر)`}
                        onChange={(e) => setSearchUser(e.target.value)}
                    />
                    <i className="fc-after"></i>
                </div>
            </div>
            <div className='edit-users'>
                {(filterSearch.length > 0) ?
                    filterSearch.map((val, i) => {
                        return <EditUser key={i} user={val} />
                    }) :
                    <p style={{ margin: "1.25rem 0" }}>کاربری با این نام کاربری وجود ندارد</p>}
            </div>
        </div>
    </>;
};

export default EditUsers;