import React, { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { postSendResetPass } from '../../../../api/ActionResetPass';
import { useThemeState } from '../../../../context/ThemeContext';
import AlertItem from '../../../alerts/AlertItem';

const AuthResetPass = () => {
    const { site_url } = useThemeState();
    const authSectionLoading = document.getElementById("auth-section-loading");
    const { register, handleSubmit, formState: { errors } } = useForm();
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState([]);
    const [successResetPass, setSuccessResetPass] = useState(false);

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
        setSuccessResetPass(false);
        postSendResetPass(
            data,
            (callback, res) => {
                if (callback) {
                    if (res.success) {
                        setError([]);
                        setSuccessResetPass(true);
                    } else {
                        setError([...error, ...res.error]);
                    }
                } else {
                    setError([...error, "خطایی پیش آمده !"]);
                }
                setLoading(false);
            }
        );
    };

    return <>
        <div className='alerts'>
            {(successResetPass) ? <AlertItem txt={"برای بازیابی رمز عبور پست الکترونیک خود را بررسی کنید."} cls={"success"} /> : ""}

            {error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}

            {errors.usernameOrEmail?.type === "required" && <AlertItem txt={"نام کاربری یا پست الکترونیک نمیتواند خالی باشد"} cls={"warning"} />}
            {errors.usernameOrEmail?.type === "minLength" && <AlertItem txt={"نام کاربری یا پست الکترونیک نمیتواند کمتر از 3 حرف باشد"} cls={"warning"} />}
        </div>

        <form onSubmit={handleSubmit(onSubmit)}>
            <div className="input-group">
                <input
                    type='text'
                    className='form-control'
                    placeholder='* نام کاربری یا پست الکترونیک'
                    {...register("usernameOrEmail", { required: true, minLength: 3 })}
                />
                <i className="fc-after"></i>
            </div>

            <input type="submit" className="btn-primary" value={"فراموشی رمز"} />
        </form>

        <div className='btns'>
            <p><a href={`${site_url}/account`}><i className='icons-person'></i>ورود</a></p>
            <p><a href={`${site_url}/account?register`}><i className='icons-person_add'></i>عضویت</a></p>
        </div>
    </>;
};

export default AuthResetPass;