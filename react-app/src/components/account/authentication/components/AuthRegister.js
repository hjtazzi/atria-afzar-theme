import React, { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { postRegister } from '../../../../api/ActionRegisterUser';
import { postSignon, postToken } from '../../../../api/ActionLogin';
import { useThemeState } from '../../../../context/ThemeContext';
import AlertItem from '../../../alerts/AlertItem';

const AuthRegister = () => {
    const { site_url } = useThemeState();
    const authSectionLoading = document.getElementById("auth-section-loading");
    const { register, handleSubmit, formState: { errors } } = useForm();
    const [loading, setLoading] = useState(true);
    const [passI, setPassI] = useState("password");
    const [error, setError] = useState([]);
    const showPass = () => (passI === "password") ? setPassI("text") : setPassI("password");
    const [successCreate, setSuccessCreate] = useState(false);
    const [successCreateUserTxt, setSuccessCreateUserTxt] = useState("");

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
        const usernameValue = data.username;
        const fNameValue = data.fName;
        const lNameValue = data.lName;
        const passValue = data.password;
        const confPassValue = data.confirmPassword;

        if (passValue !== confPassValue) {
            setError([...error, "تکرار رمز عبور نامعتبر است."]);
            return;
        }
        if (usernameValue.includes(" ")) {
            setError([...error, "نام کاربری نمیتواند شامل فاصله باشد"]);
            return;
        }
        if (fNameValue.replace(/\s+/g, " ").trim() === "") {
            setError([...error, "نام نمیتواند خالی باشد"]);
            return;
        }
        if (fNameValue.replace(/\s+/g, " ").trim().length < 3) {
            setError([...error, "نام نمیتواند کمتر از 3 حرف باشد"]);
            return;
        }
        if (lNameValue.replace(/\s+/g, " ").trim() === "") {
            setError([...error, "نام خانوادگی نمیتواند خالی باشد"]);
            return;
        }

        setLoading(true);
        postRegister(
            data,
            (callback, res) => {
                if (callback) {
                    registerCheck(res);
                } else {
                    setError(["خطایی پیش آمده !"]);
                }
                setLoading(false);
            }
        );
    };

    const registerCheck = (data) => {
        setError([]);
        if (data.success) {
            setSuccessCreate(true);
            setSuccessCreateUserTxt(`${data.user.display_name} ثبت نام شما با موفقیت انجام شد.`);

            setTimeout(() => {
                setLoading(true);
                const signonData = {
                    username: data.user.username,
                    password: data.user.password,
                    remember: data.user.remember,
                };
                loginChecked(signonData);
            }, 1000);
        } else {
            setError([...data.error]);
        }
    };

    const loginChecked = (data) => {
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
                        window.location.reload();
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
    }

    return <>
        <div className='alerts'>
            {(successCreate) ? <AlertItem txt={successCreateUserTxt} cls={"success"} /> : ""}

            {error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}

            {errors.username?.type === "required" && <AlertItem txt={"نام کاربری نمیتواند خالی باشد"} cls={"warning"} />}
            {errors.username?.type === "minLength" && <AlertItem txt={"نام کاربری نمیتواند کمتر از 3 حرف باشد"} cls={"warning"} />}
            {errors.username?.type === "maxLength" && <AlertItem txt={"نام کاربری نمیتواند بیشتر از 32 حرف باشد"} cls={"warning"} />}

            {errors.email?.type === "required" && <AlertItem txt={"پست الکترونیک نمیتواند خالی باشد"} cls={"warning"} />}

            {errors.fName?.type === "required" && <AlertItem txt={"نام نمیتواند خالی باشد"} cls={"warning"} />}
            {errors.fName?.type === "minLength" && <AlertItem txt={"نام نمیتواند کمتر از 3 حرف باشد"} cls={"warning"} />}
            {errors.fName?.type === "maxLength" && <AlertItem txt={"نام نمیتواند بیشتر از 32 حرف باشد"} cls={"warning"} />}

            {errors.lName?.type === "required" && <AlertItem txt={"نام خانوادگی نمیتواند خالی باشد"} cls={"warning"} />}
            {errors.lName?.type === "minLength" && <AlertItem txt={"نام خانوادگی نمیتواند کمتر از 3 حرف باشد"} cls={"warning"} />}
            {errors.lName?.type === "maxLength" && <AlertItem txt={"نام خانوادگی نمیتواند بیشتر از 32 حرف باشد"} cls={"warning"} />}

            {errors.phoneNumber?.type === "required" && <AlertItem txt={"تلفن همراه نمیتواند خالی باشد"} cls={"warning"} />}
            {errors.phoneNumber?.type === "pattern" && <AlertItem txt={"تلفن همراه به درستی وارد نشده (به شکل 09123456789 وارد شود)"} cls={"warning"} />}
            {errors.phoneNumber?.type === "maxLength" && <AlertItem txt={"تلفن همراه نمیتواند بیشتر از 11 شماره باشد"} cls={"warning"} />}

            {errors.password?.type === "required" && <AlertItem txt={"رمز عبور نمیتواند خالی باشد"} cls={"warning"} />}
            {errors.password?.type === "minLength" && <AlertItem txt={"رمز عبور نمیتواند کمتر از 8 کارکتر باشد"} cls={"warning"} />}
            {errors.password?.type === "pattern" && <AlertItem txt={"رمز عبور باید حداقل از 8 کارکتر و از کارکترهای A-Z و a-z و 0-9 تشکیل شده باشد"} cls={"warning"} />}

            {errors.confirmPassword?.type === "required" && <AlertItem txt={"تکرار رمز عبور نمیتواند خالی باشد"} cls={"warning"} />}
        </div>

        <form onSubmit={handleSubmit(onSubmit)}>

            <div className="input-group">
                <input
                    type='text'
                    className='form-control'
                    placeholder='* نام کاربری'
                    aria-required='true'
                    {...register("username", { required: true, minLength: 3, maxLength: 32 })}
                />
                <i className="fc-after"></i>
            </div>

            <div className="input-group">
                <input
                    type='email'
                    className='form-control'
                    placeholder='پست الکترونیک *'
                    aria-required='true'
                    {...register("email", { required: true })}
                />
                <i className="fc-after"></i>
            </div>

            <div className="input-group">
                <input
                    type='text'
                    className='form-control'
                    placeholder='* نام'
                    aria-required='true'
                    {...register("fName", { required: true, minLength: 3, maxLength: 32 })}
                />
                <i className="fc-after"></i>
            </div>

            <div className="input-group">
                <input
                    type='text'
                    className='form-control'
                    placeholder='* نام خانوادگی'
                    aria-required='true'
                    {...register("lName", { required: true, minLength: 3, maxLength: 32 })}
                />
                <i className="fc-after"></i>
            </div>

            <div className="input-group">
                <input
                    type='tel'
                    className='form-control'
                    placeholder='تلفن همراه *'
                    aria-required='true'
                    {...register("phoneNumber", { required: true, maxLength: 11, pattern: /[0-9]{11}/i })}
                />
                <i className="fc-after"></i>
            </div>

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

            <div className="check-group">
                <input
                    type="checkbox"
                    className="form-check"
                    id='remember'
                    {...register("remember", {})}
                />
                <label htmlFor='remember' className="form-check-label">مرا به خاطر بسپار</label>
            </div>

            <input type="submit" className="btn-primary" value={"ثبت نام"} />
        </form>

        <div className='btns'>
            <p><a href={`${site_url}/account`}><i className='icons-person'></i>ورود</a></p>
            <p><a href={`${site_url}/account?reset-pass`}><i className='icons-manage_accounts'></i>فراموشی رمز عبور</a></p>
        </div>
    </>;
};

export default AuthRegister;