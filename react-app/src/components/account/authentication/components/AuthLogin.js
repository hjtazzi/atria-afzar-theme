import React, { useEffect, useState } from 'react';
import { useThemeState } from '../../../../context/ThemeContext';
import { useForm } from 'react-hook-form';
import { postLogin, postSignon, postToken } from '../../../../api/ActionLogin';
import AlertItem from '../../../alerts/AlertItem';

const AuthLogin = () => {
    const { site_url } = useThemeState();
    const authSectionLoading = document.getElementById("auth-section-loading");
    const { register, handleSubmit, formState: { errors } } = useForm();
    const [loading, setLoading] = useState(true);
    const [successSignon, setSuccessSignon] = useState(false);
    const [passI, setPassI] = useState("password");
    const [error, setError] = useState([]);
    const showPass = () => (passI === "password") ? setPassI("text") : setPassI("password");

    useEffect(() => {
        setLoading(false);
    }, []);

    if (loading) {
        if (authSectionLoading)
            authSectionLoading.classList.remove("loading-hidden")
    } else {
        if (authSectionLoading)
            authSectionLoading.classList.add("loading-hidden")
    }

    const onSubmit = (data) => {
        setLoading(true);
        postLogin(
            data,
            (callback, res) => {
                if (callback) {
                    if (res.success) {
                        loginChecked(data)
                    } else {
                        setError([...error, res.error]);
                        setLoading(false);
                    }
                } else {
                    setError([...error, "خطایی پیش آمده !"]);
                    setLoading(false);
                }
            }
        );
    }

    const loginChecked = (data) => {
        setError([]);
        postToken(
            data,
            (callback, res) => {
                if (callback) {
                    const token = res.token;
                    const userID = res.user_id;

                    if (token && userID) {
                        localStorage.setItem("auth_token", token);
                        localStorage.setItem("user_id", userID);
                        signon(data);
                    } else {
                        setError([...error, "خطایی پیش آمده !"]);
                        setLoading(false);
                    }
                } else {
                    setError([...error, "خطایی پیش آمده !"]);
                    setLoading(false);
                }
            }
        );
    };

    const signon = (data) => {
        postSignon(
            data,
            (callback, res) => {
                if (callback) {
                    if (res.success) {
                        setSuccessSignon(true);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        setError([...error, "خطایی پیش آمده !"]);
                    }
                } else {
                    setError([...error, "خطایی پیش آمده !"]);
                }
                setLoading(false);
            }
        );
    }

    return <>
        <div className='alerts'>
            {(successSignon) ? <AlertItem txt={"با موفقیت وارد شدید."} cls={"success"} /> : ""}

            {error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}

            {errors.username?.type === "required" && <AlertItem txt={"نام کاربری یا پست الکترونیک نمیتواند خالی باشد"} cls={"warning"} />}
            {errors.username?.type === "minLength" && <AlertItem txt={"نام کاربری یا پست الکترونیک نمیتواند کمتر از 3 حرف باشد"} cls={"warning"} />}

            {errors.password?.type === "required" && <AlertItem txt={"رمز عبور نمیتواند خالی باشد"} cls={"warning"} />}
            {errors.password?.type === "minLength" && <AlertItem txt={"رمز عبور نمیتواند کمتر از 8 حرف باشد"} cls={"warning"} />}
        </div>

        <form onSubmit={handleSubmit(onSubmit)}>
            <div className="input-group">
                <input
                    type='text'
                    className='form-control'
                    placeholder='نام کاربری یا پست الکترونیک'
                    {...register("username", { required: true, minLength: 3 })}
                />
                <i className="fc-after"></i>
            </div>

            <div className="input-group inp-pass">
                <input
                    type={passI}
                    className='form-control'
                    placeholder='رمز عبور'
                    {...register("password", { required: true, minLength: 8 })}
                />
                <button className="form-submit" type="button" onClick={showPass}>
                    {(passI === "password") ? <i className='icons-visibility'></i> : <i className='icons-visibility_off'></i>}
                </button>
                <i className="fc-after"></i>
            </div>

            <div className="check-group">
                <input
                    type="checkbox"
                    className="form-check"
                    id='remember'
                    {...register("remember", {})}
                />
                <label htmlFor='remember' className="form-check-label">مرا به خاطر بسپار</label>
            </div>

            <input type="submit" className="btn-primary" value={"ورود"} />
        </form>

        <div className='btns'>
            <p><a href={`${site_url}/account?register`}><i className='icons-person_add'></i>عضویت</a></p>
            <p><a href={`${site_url}/account?reset-pass`}><i className='icons-manage_accounts'></i>فراموشی رمز عبور</a></p>
        </div>
    </>
};

export default AuthLogin;