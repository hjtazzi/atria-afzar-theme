import React, { useEffect, useState } from 'react';
import { useThemeDispatch, useThemeState } from '../../../context/ThemeContext';
import { setThemeItemMenu, setThemeLoading } from '../../../context/ThemeActions';
import { postSideMenu } from '../../../api/ActionAdmin';
import queryString from 'query-string';
import AlertItem from '../../alerts/AlertItem';
import ItemContent from './ItemContent';

const AdminRoot = () => {
    const { site_url, loading, itemMenu } = useThemeState();
    const themeDispatch = useThemeDispatch();
    const [sideMenu, setSideMenu] = useState({
        user: {
            display_name: "",
            avatar_url: ""
        },
        side_menu: [
            { state: "dashboard", title: "داشبورد", icon: "icons-person" },
        ]
    });
    const [navMenuVisibility, setNavMenuVisibility] = useState(true);
    const [error, setError] = useState([]);

    useEffect(() => {
        postSideMenu(
            { user_id: localStorage.getItem("user_id") },
            (callback, res) => {
                if (callback) {
                    setSideMenu(res);

                    const queryState = queryString.parse(window.location.search);
                    if (queryState.state)
                        setThemeItemMenu(themeDispatch, queryState.state);
                } else {
                    if (res.response.status === 403) {
                        setError(["شما اجازه دسترسی به این بخش را ندارید! لطفا دوباره وارد شوید."])
                        localStorage.clear();
                        setTimeout(() => {
                            window.location.replace(`${site_url}/account?logout=true`);
                        }, 1000);
                    } else {
                        setError([...error, "خطایی پیش آمده !"]);
                    }
                }
                setThemeLoading(themeDispatch, false);
            }
        );
    }, [loading]);

    useEffect(() => {
        window.onresize = () => {
            if (window.innerWidth >= 768)
                setNavMenuVisibility(true)
        }
    });

    return <>
        {(loading) && <div id='profile-content-loading' className='loading'><div className="loader"></div></div>}
        <div className='alerts'>{error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}</div>

        <div className='profile-container container'>
            <div className='side-menu'>
                <div className='side-menu-content'>
                    <div className='side-menu-user'>
                        <div className='user-display' onClick={() => { setThemeItemMenu(themeDispatch, "edit_profile") }}>
                            <figure>
                                <img src={sideMenu.user.avatar_url} alt='' />
                            </figure>
                            <p>{sideMenu.user.display_name}</p>
                            <div className='manage-account'><i className='icons-manage_accounts'></i></div>
                        </div>
                        <hr />
                        <button className='btn show-side-menu' onClick={() => setNavMenuVisibility(!navMenuVisibility)}>
                            <i className='icons-more_vert'></i>
                        </button>
                    </div>

                    <div className='side-menu-nav'>
                        <nav style={(navMenuVisibility) ? { maxHeight: `${(sideMenu.side_menu.length + 1) * 2.85}rem` } : { maxHeight: 0 }}>
                            <ul>
                                {sideMenu.side_menu.map((val, i) => {
                                    return <li key={i} className={(itemMenu === val.state) ? "active" : ""}>
                                        <button className='btn' onClick={() => setThemeItemMenu(themeDispatch, val.state)}>
                                            <i className={val.icon}></i>{val.title}
                                        </button>
                                    </li>
                                })}
                                <li>
                                    <button className='btn' onClick={() => {
                                        localStorage.clear();
                                        window.location.replace(`${site_url}/account?logout=true`);
                                    }}>
                                        <i className='icons-logout'></i>خروج
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <div className='item-menu'>
                <div className='item-content'>
                    <ItemContent itemMenu={itemMenu} sideMenuUser={sideMenu.user} />
                </div>
            </div>
        </div>
    </>;
};

export default AdminRoot;