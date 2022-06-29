import React from 'react';
import Portals from '../../Portals';
import AuthChangePass from './components/AuthChangePass';
import AuthLogin from './components/AuthLogin';
import AuthRegister from './components/AuthRegister';
import AuthResetPass from './components/AuthResetPass';

const AuthenticationRoot = () => {
    const auth_login_root = document.getElementById("auth-login_root");
    const auth_register_root = document.getElementById("auth-register_root");
    const auth_reset_pass_root = document.getElementById("auth-reset-pass_root");
    const auth_change_pass_root = document.getElementById("auth-change-pass_root");

    return <>
        <Portals container={auth_login_root}><AuthLogin /></Portals>
        <Portals container={auth_register_root}><AuthRegister /></Portals>
        <Portals container={auth_reset_pass_root}><AuthResetPass /></Portals>
        <Portals container={auth_change_pass_root}><AuthChangePass /></Portals>
    </>
};

export default AuthenticationRoot;