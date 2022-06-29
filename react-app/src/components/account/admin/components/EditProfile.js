import React, { useEffect, useState } from 'react';
import { setThemeLoading } from '../../../../context/ThemeActions';
import { useThemeDispatch } from '../../../../context/ThemeContext';
import { useForm } from 'react-hook-form';
import { getEditProfile, putEditProfile } from '../../../../api/ActionAdmin';

import AlertItem from '../../../alerts/AlertItem';
import EditPass from './EditPass';

const EditProfile = () => {
    const themeDispatch = useThemeDispatch();
    const { register, handleSubmit, formState: { errors } } = useForm();
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState([]);
    const [successEditProfile, setSuccessEditProfile] = useState(false);
    const [data, setData] = useState({ success: false, user: [] });
    const [gender, setGender] = useState("not_set");

    useEffect(() => {
        getEditProfile((callback, res) => {
            if (callback) {
                setData(res);
                setGender(res.user.user_gender);
            } else {
                setError(["خطایی در بارگیری اطلاعات شما پیش آمده! لطفا دوباره امتحان کنید."]);
            }
            setLoading(false);
        });
    }, []);

    const onSubmitEdit = (data) => {
        setLoading(true);
        setSuccessEditProfile(false);
        setError([]);
        putEditProfile(
            { ...data, gender: gender },
            (callback, res) => {
                if (callback) {
                    if (res.success) {
                        setSuccessEditProfile(true);
                    } else {
                        setError([...res.error])
                    }
                } else {
                    setError("خطایی در ذخیره تغییرات پیش آمده!");
                }
                setLoading(false);
                setThemeLoading(themeDispatch, true);
            }
        );
    };

    return <>
        {(loading) && <div className="section-loading"><div className="loader"></div></div>}

        <div className="title">
            <i></i>
            <h6><i className="icons-manage_accounts"></i>ویرایش مشخصات</h6>
            <i className="after-h"></i>
        </div>

        <div className='alerts'>
            {(successEditProfile) ? <AlertItem txt={"تغییرات با موفقیت ذخیره شدند."} cls={"success"} /> : ""}

            {error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}
        </div>

        {(data.success) &&
            <>
                <div className='alerts'>
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

                    {errors.gender?.type === "required" && <AlertItem txt={"جنسیت نمیتواند خالی باشد"} cls={"warning"} />}
                </div>

                <div className='item-edit-profile'>
                    <div className='edit-profile'>
                        <form onSubmit={handleSubmit(onSubmitEdit)}>

                            <div className="input-group fc-disabled">
                                <label htmlFor='username'>نام کاربری</label>
                                <input
                                    type='text'
                                    className='form-control'
                                    id='username'
                                    placeholder='نام کاربری'
                                    defaultValue={data.user.user_login}
                                    disabled={true}
                                />
                                <i className="fc-after"></i>
                            </div>

                            <div className="input-group">
                                <input
                                    type='email'
                                    className='form-control'
                                    placeholder='پست الکترونیک *'
                                    aria-required='true'
                                    {...register("email", { required: true, value: data.user.user_email })}
                                />
                                <i className="fc-after"></i>
                            </div>

                            <div className="input-group">
                                <input
                                    type='text'
                                    className='form-control'
                                    placeholder='* نام'
                                    aria-required='true'
                                    {...register("fName", { required: true, minLength: 3, maxLength: 32, value: data.user.first_name })}
                                />
                                <i className="fc-after"></i>
                            </div>

                            <div className="input-group">
                                <input
                                    type='text'
                                    className='form-control'
                                    placeholder='* نام خانوادگی'
                                    aria-required='true'
                                    {...register("lName", { required: true, minLength: 3, maxLength: 32, value: data.user.last_name })}
                                />
                                <i className="fc-after"></i>
                            </div>

                            <div className="input-group">
                                <input
                                    type='tel'
                                    className='form-control'
                                    placeholder='تلفن همراه *'
                                    aria-required='true'
                                    {...register("phoneNumber", { required: true, maxLength: 11, pattern: /[0-9]{11}/i, value: data.user.phone_number })}
                                />
                                <i className="fc-after"></i>
                            </div>

                            <div className="check-group">
                                <input
                                    type="radio"
                                    className="form-check"
                                    id="gender-male"
                                    value="male"
                                    checked={(gender === "male") && true}
                                    {...register("gender", { required: true, onChange: (e) => setGender(e.target.value) })}
                                />
                                <label htmlFor='gender-male' className="form-check-label">مرد</label>

                                <input
                                    type="radio"
                                    className="form-check"
                                    id="gender-female"
                                    value="female"
                                    checked={(gender === "female") && true}
                                    {...register("gender", { required: true, onChange: (e) => setGender(e.target.value) })}
                                />
                                <label htmlFor='gender-female' className="form-check-label">زن</label>
                            </div>

                            <div className='input-group'>
                                <textarea
                                    className='form-control'
                                    placeholder='درباره'
                                    rows={3}
                                    {...register("description", { value: data.user.description })}
                                />
                                <i className="fc-after"></i>
                            </div>

                            <input type="submit" className="btn-primary" value={"ذخیره تغییرات"} />
                        </form>
                    </div>
                </div>
            </>
        }
        <div className='clearfix space'></div>

        <EditPass />
    </>;
};

export default EditProfile;