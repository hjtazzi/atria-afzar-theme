import React, { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { putEditPassword } from '../../../../api/ActionAdmin';
import { useThemeState } from '../../../../context/ThemeContext';
import AlertItem from '../../../alerts/AlertItem';

const EditPass = () => {
    const { site_url } = useThemeState();
    const { register, handleSubmit, formState: { errors } } = useForm();
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState([]);
    const [successEditPass, setSuccessEditPass] = useState(false);
    const [passI, setPassI] = useState("password");
    const showPass = () => (passI === "password") ? setPassI("text") : setPassI("password");

    const onEditPassSubmit = (data) => {

        if (data.password !== data.confirmPassword) {
            setError([...error, "تکرار رمز عبور نامعتبر است."]);
            return;
        }

        if (data.password === data.currentPassword) {
            setError([...error, "رمز ورود فعلی و رمز ورود جدید نمیتواند با هم برابر باشد."]);
            return;
        }

        setLoading(true);
        setError([]);
        putEditPassword(
            data,
            (callback, res) => {
                if (callback) {
                    if (res.success) {
                        setSuccessEditPass(true);
                        setTimeout(() => {
                            window.localStorage.clear();
                            window.location.replace(`${site_url}/account`);
                        }, 1500);
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
        {(loading) && <div className="section-loading"><div className="loader"></div></div>}

        <div className="title">
            <i></i>
            <h6><i className="icons-admin_panel_settings"></i>ویرایش رمز عبور</h6>
            <i className="after-h"></i>
        </div>

        <div className='alerts'>
            {(successEditPass) ? <AlertItem txt={"رمز عبور با موفقیت تغییر کرد. لطفا دوباره وارد شوید."} cls={"success"} /> : ""}

            {error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}

            {errors.currentPassword?.type === "required" && <AlertItem txt={"رمز عبور فعلی نمیتواند خالی باشد"} cls={"warning"} />}

            {errors.password?.type === "required" && <AlertItem txt={"رمز عبور جدید نمیتواند خالی باشد"} cls={"warning"} />}
            {errors.password?.type === "minLength" && <AlertItem txt={"رمز عبور جدید نمیتواند کمتر از 8 کارکتر باشد"} cls={"warning"} />}
            {errors.password?.type === "pattern" && <AlertItem txt={"رمز عبور جدید باید حداقل از 8 کارکتر و از کارکترهای A-Z و a-z و 0-9 تشکیل شده باشد"} cls={"warning"} />}

            {errors.confirmPassword?.type === "required" && <AlertItem txt={"تکرار رمز عبور جدید نمیتواند خالی باشد"} cls={"warning"} />}
        </div>

        <div className='item-edit-profile'>
            <div className='edit-password'>
                <form onSubmit={handleSubmit(onEditPassSubmit)}>

                    <div className="input-group inp-pass">
                        <input
                            type={passI}
                            className='form-control'
                            placeholder='* رمز عبور فعلی'
                            aria-required='true'
                            {...register("currentPassword", { required: true })}
                        />
                        <button className="form-submit" type="button" onClick={showPass}>
                            {(passI === "password") ? <i className='icons-visibility'></i> : <i className='icons-visibility_off'></i>}
                        </button>
                        <i className="fc-after"></i>
                    </div>

                    <div className="input-group inp-pass">
                        <input
                            type={passI}
                            className='form-control'
                            placeholder='* رمز عبور جدید'
                            aria-required='true'
                            title='رمز عبور باید حداقل از 8 کارکتر و از کارکترهای A-Z و a-z و 0-9 تشکیل شده باشد'
                            {...register("password", { required: true, minLength: 8, pattern: /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/i })}
                        />
                        <i className="fc-after"></i>
                    </div>

                    <div className="input-group">
                        <input
                            type={passI}
                            className='form-control'
                            placeholder='* تکرار رمز عبور جدید'
                            aria-required='true'
                            {...register("confirmPassword", { required: true })}
                        />
                        <i className="fc-after"></i>
                    </div>

                    <input type="submit" className="btn-primary" value={"ذخیره تغییرات"} />
                </form>
            </div>
        </div>
    </>;
};

export default EditPass;