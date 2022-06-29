import React, { useEffect, useState } from 'react';
import queryString from 'query-string';
import { useThemeState } from '../../../../context/ThemeContext';
import { useForm } from 'react-hook-form';
import AlertItem from '../../../alerts/AlertItem';
import { postChangePass } from '../../../../api/ActionResetPass';

const AuthChangePass = () => {
    const { site_url } = useThemeState();
    const authSectionLoading = document.getElementById("auth-section-loading");
    const { register, handleSubmit, formState: { errors } } = useForm();
    const [queryParams, setQueryParams] = useState({ key: null, user: null });
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState([]);
    const [passI, setPassI] = useState("password");
    const showPass = () => (passI === "password") ? setPassI("text") : setPassI("password");
    const [successChangePass, setSuccessChangePass] = useState(false);

    useEffect(() => {
        setLoading(false);
        setQueryParams(queryString.parse(window.location.search));
    }, []);

    if (loading) {
        if (authSectionLoading)
            authSectionLoading.classList.remove("loading-hidden")
    } else {
        if (authSectionLoading)
            authSectionLoading.classList.add("loading-hidden")
    }

    const onSubmit = (data) => {
        const passValue = data.password;
        const confPassValue = data.confirmPassword;

        if (passValue !== confPassValue) {
            setError([...error, "تکرار رمز عبور نامعتبر است."]);
            return;
        }
        if (queryParams.key === null || queryParams.user === null) {
            setError([...error, "پارامترهای صفحه نامعتبر است! لطفا دوباره امتحان کنید"]);
            return;
        }

        setLoading(true);
        postChangePass(
            {
                password: data.password,
                key: queryParams.key,
                user: queryParams.user
            },
            (callback, res) => {
                if (callback) {
                    if (res.success) {
                        setError([]);
                        setSuccessChangePass(true);
                        setTimeout(() => {
                            window.location.replace(`${site_url}/account`);
                        }, 1000);
                    } else {
                        setError([...res.error]);
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
            {(successChangePass) ? <AlertItem txt={"تغییر رمز عبور شما با موفقیت انجام شد"} cls={"success"} /> : ""}

            {error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}

            {errors.password?.type === "required" && <AlertItem txt={"رمز عبور نمیتواند خالی باشد"} cls={"warning"} />}
            {errors.password?.type === "minLength" && <AlertItem txt={"رمز عبور نمیتواند کمتر از 8 کارکتر باشد"} cls={"warning"} />}
            {errors.password?.type === "pattern" && <AlertItem txt={"رمز عبور باید حداقل از 8 کارکتر و از کارکترهای A-Z و a-z و 0-9 تشکیل شده باشد"} cls={"warning"} />}

            {errors.confirmPassword?.type === "required" && <AlertItem txt={"تکرار رمز عبور نمیتواند خالی باشد"} cls={"warning"} />}
        </div>

        <form onSubmit={handleSubmit(onSubmit)}>
            <div className="input-group inp-pass">
                <input
                    type={passI}
                    className='form-control'
                    placeholder='* رمز عبور'
                    aria-required='true'
                    title='رمز عبور باید حداقل از 8 کارکتر و از کارکترهای A-Z و a-z و 0-9 تشکیل شده باشد'
                    {...register("password", { required: true, minLength: 8, pattern: /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/i })}
                />
                <button className="form-submit" type="button" onClick={showPass}>
                    {(passI === "password") ? <i className='icons-visibility'></i> : <i className='icons-visibility_off'></i>}
                </button>
                <i className="fc-after"></i>
            </div>

            <div className="input-group">
                <input
                    type={passI}
                    className='form-control'
                    placeholder='* تکرار رمز عبور'
                    aria-required='true'
                    {...register("confirmPassword", { required: true })}
                />
                <i className="fc-after"></i>
            </div>

            <input type="submit" className="btn-primary" value={"بازیابی رمز"} />
        </form>

        <div className='btns'>
            <p><a href={`${site_url}/account`}><i className='icons-person'></i>ورود</a></p>
            <p><a href={`${site_url}/account?register`}><i className='icons-person_add'></i>عضویت</a></p>
        </div>
    </>;
};

export default AuthChangePass;