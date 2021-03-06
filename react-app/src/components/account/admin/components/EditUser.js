import React, { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { putEditUser } from '../../../../api/ActionAdmin';
import AlertItem from '../../../alerts/AlertItem';

const EditUser = React.memo(
    ({ user }) => {
        const [userData, setUserData] = useState(user);
        const { register, reset, handleSubmit, formState: { errors } } = useForm({
            defaultValues: {
                email: userData.user_email,
                fName: userData.first_name,
                lName: userData.last_name,
                phoneNumber: userData.phone_number,
                avatar_url: userData.avatar_url
            }
        });
        const [loading, setLoading] = useState(false);
        const [divActive, setDivActive] = useState(false);
        const [error, setError] = useState([]);
        const [successEdit, setSuccessEdit] = useState(false);

        useEffect(() => {
            setUserData(user);
        }, [user]);

        const editUserOnClick = () => {
            setDivActive(!divActive);
        }

        const cancelSubmit = () => {
            setDivActive(false);
            setTimeout(() => {
                setSuccessEdit(false);
                reset({
                    email: userData.user_email,
                    fName: userData.first_name,
                    lName: userData.last_name,
                    phoneNumber: userData.phone_number,
                    avatar_url: userData.avatar_url
                });
            }, 350);
        }

        const onSubmitEditUser = (data) => {
            setLoading(true);
            setSuccessEdit(false);
            putEditUser(
                { ...data, user_id: user.id },
                (callback, res) => {
                    if (callback) {
                        if (res.success) {
                            setSuccessEdit(true);
                            setUserData({
                                ...user,
                                ...data,
                                id: user.id,
                                display_name: `${data.fName} ${data.lName}`
                            });
                            setTimeout(() => {
                                cancelSubmit();
                            }, 2000);
                        } else {
                            setError([...res.error]);
                        }
                    } else {
                        setError(["?????????? ?????? ??????!"])
                    }
                    setLoading(false);
                }
            );
        }

        return <>
            {(loading) && <div className="section-loading"><div className="loader"></div></div>}

            <div className={(divActive) ? "edit-user active" : "edit-user"}>
                <div className='user-info' onClick={editUserOnClick}>
                    <div className='user-display'>
                        <figure><img src={userData.avatar_url} alt='' /></figure>
                        <div className='user-info'>
                            <p className='display_name'>{userData.display_name}</p>
                            <p className='username'>{userData.username}</p>
                        </div>
                    </div>
                </div>

                <div className='user-edit-form' style={{ maxHeight: (divActive) && 600 }}>

                    <div className='alerts'>
                        {(successEdit) && <AlertItem txt={"?????????????? ???? ???????????? ?????????? ????."} cls={"success"} />}

                        {error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}

                        {errors.email?.type === "required" && <AlertItem txt={"?????? ?????????????????? ???????????????? ???????? ????????"} cls={"warning"} />}

                        {errors.fName?.type === "required" && <AlertItem txt={"?????? ???????????????? ???????? ????????"} cls={"warning"} />}
                        {errors.fName?.type === "minLength" && <AlertItem txt={"?????? ???????????????? ???????? ???? 3 ?????? ????????"} cls={"warning"} />}
                        {errors.fName?.type === "maxLength" && <AlertItem txt={"?????? ???????????????? ?????????? ???? 32 ?????? ????????"} cls={"warning"} />}

                        {errors.lName?.type === "required" && <AlertItem txt={"?????? ???????????????? ???????????????? ???????? ????????"} cls={"warning"} />}
                        {errors.lName?.type === "minLength" && <AlertItem txt={"?????? ???????????????? ???????????????? ???????? ???? 3 ?????? ????????"} cls={"warning"} />}
                        {errors.lName?.type === "maxLength" && <AlertItem txt={"?????? ???????????????? ???????????????? ?????????? ???? 32 ?????? ????????"} cls={"warning"} />}

                        {errors.phoneNumber?.type === "required" && <AlertItem txt={"???????? ?????????? ???????????????? ???????? ????????"} cls={"warning"} />}
                        {errors.phoneNumber?.type === "pattern" && <AlertItem txt={"???????? ?????????? ???? ?????????? ???????? ???????? (???? ?????? 09123456789 ???????? ??????)"} cls={"warning"} />}
                        {errors.phoneNumber?.type === "maxLength" && <AlertItem txt={"???????? ?????????? ???????????????? ?????????? ???? 11 ?????????? ????????"} cls={"warning"} />}
                    </div>

                    <form onSubmit={handleSubmit(onSubmitEditUser)}>

                        <div className="input-group">
                            <input
                                type='email'
                                className='form-control'
                                placeholder='?????? ?????????????????? *'
                                aria-required='true'
                                {...register("email", { required: true })}
                            />
                            <i className="fc-after"></i>
                        </div>

                        <div className="input-group">
                            <input
                                type='text'
                                className='form-control'
                                placeholder='* ??????'
                                aria-required='true'
                                {...register("fName", { required: true, minLength: 3, maxLength: 32 })}
                            />
                            <i className="fc-after"></i>
                        </div>

                        <div className="input-group">
                            <input
                                type='text'
                                className='form-control'
                                placeholder='* ?????? ????????????????'
                                aria-required='true'
                                {...register("lName", { required: true, minLength: 3, maxLength: 32 })}
                            />
                            <i className="fc-after"></i>
                        </div>

                        <div className="input-group">
                            <input
                                type='tel'
                                className='form-control'
                                placeholder='???????? ?????????? *'
                                aria-required='true'
                                {...register("phoneNumber", { required: true, maxLength: 11, pattern: /[0-9]{11}/i })}
                            />
                            <i className="fc-after"></i>
                        </div>

                        <div className="input-group">
                            <input
                                type='url'
                                className='form-control'
                                placeholder='????????????'
                                {...register("avatar_url", {})}
                            />
                            <i className="fc-after"></i>
                        </div>

                        <div className='btns'>
                            <input type="submit" className="btn-success" value={"????????????"} />
                            <input type="button" className="btn-danger" value={"??????"} onClick={cancelSubmit} />
                        </div>
                    </form>
                </div>
            </div>
        </>
    }
);

export default EditUser;