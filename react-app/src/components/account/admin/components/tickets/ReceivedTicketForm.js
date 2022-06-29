import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { postNewReceivedTicket } from '../../../../../api/ActionAdmin';
import AlertItem from '../../../../alerts/AlertItem';

const ReceivedTicketForm = ({ userID }) => {
    const { register, resetField, handleSubmit, formState: { errors } } = useForm();
    const [loadingP, setLoadingP] = useState(false);
    const [error, setError] = useState([]);
    const [successNewTicket, setSuccessNewTicket] = useState(false);

    const onNewTicket = (data) => {
        setLoadingP(true);

        postNewReceivedTicket(
            { ...data, id: userID },
            (callback, res) => {
                if (callback) {
                    if (res.success) {
                        setSuccessNewTicket(true);
                    } else {
                        setError([...res.error]);
                    }
                } else {
                    setError([...error, "خطایی پیش آمده!"]);
                }
                setLoadingP(false);
                resetField("newTicket");
            }
        );
    };

    return <>
        {(loadingP) && <div className="section-loading"><div className="loader"></div></div>}

        <div className='alerts'>
            {(successNewTicket) && <AlertItem txt={"تیکت شما با موفقیت ارسال شد."} cls={"success"} />}

            {error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}

            {errors.newTicket?.type === "required" && <AlertItem txt={"تیکت شما نمیتواند خالی باشد."} cls={"warning"} />}
        </div>

        {(!successNewTicket) &&
            <form onSubmit={handleSubmit(onNewTicket)}>
                <div className="input-group">
                    <textarea
                        className='form-control'
                        placeholder="ارسال تیکت جدید"
                        rows={'2'}
                        {...register("newTicket", { required: true })}
                    />

                    <i className="fc-after"></i>
                </div>

                <input type="submit" className="btn-success" value={"ارسال"} />
            </form>
        }
    </>;
};

export default ReceivedTicketForm;