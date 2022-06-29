import React, { useEffect, useState } from 'react';
import { getReceivedTickets } from '../../../../api/ActionAdmin';
import AlertItem from '../../../alerts/AlertItem';
import ReceivedTicketsModal from './tickets/ReceivedTicketsModal';

const ReceivedTickets = () => {
    const [receivedTickets, setReceivedTickets] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState([]);
    const [ticketsModal, setTicketsModal] = useState({ show: false, data: [] });
    const [reload, setReload] = useState(false);

    useEffect(() => {
        getReceivedTickets((callback, res) => {
            if (callback) {
                setReceivedTickets([...res]);
            } else {
                setError(["خطایی در بارگیری اطلاعات پیش آمده! لطفا دوباره امتحان کنید."]);
            }
            setLoading(false);
            if (reload)
                setReload(false)
        });
    }, [reload]);

    return <>
        {(loading) && <div className="section-loading"><div className="loader"></div></div>}

        <div className='alerts'>{error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}</div>

        <div className="title">
            <i></i>
            <h6><i className="icons-chat_bubble_outline"></i>تیکت‌های دریافتی</h6>
            <i className="after-h"></i>
        </div>

        <div className='item-received-tickets'>
            <div className='users-received-tickets'>
                {receivedTickets.map((val, i) => {
                    const tickets = val.tickets;
                    let newTicket = 0;
                    tickets.map((v, i) => {
                        if (parseInt(v.role) === 0 && parseInt(v.status) === 0)
                            newTicket++;
                        return i;
                    });

                    return <div key={i} className='user-received-tickets' onClick={() => setTicketsModal({ show: true, data: val })}>
                        <figure>
                            <img src={val.user.avatar_url} alt='' />
                        </figure>
                        <div className='user-info'>
                            <p className='display_name'>{val.user.display_name}</p>
                            <p className='username'>{val.user.username}</p>
                        </div>
                        {(newTicket > 0) &&
                            <span className='new-ticket'>{newTicket}</span>}
                    </div>;
                })}
            </div>
        </div>

        {(ticketsModal.show) && <ReceivedTicketsModal data={ticketsModal.data} show={setTicketsModal} reload={setReload} />}
    </>;
};

export default ReceivedTickets;