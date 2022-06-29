import React, { useEffect, useRef, useState } from 'react';
import { getTickets } from '../../../../api/ActionAdmin';
import AlertItem from '../../../alerts/AlertItem';
import TicketForm from './tickets/TicketForm';
import TicketItem from './tickets/TicketItem';

const Tickets = () => {
    const [tickets, setTickets] = useState([]);
    const [error, setError] = useState([]);
    const [loading, setLoading] = useState(true);
    const [reload, setReload] = useState(false);
    const ticketsContentRef = useRef(null);

    useEffect(() => {
        getTickets((callback, res) => {
            if (callback) {
                setTickets([...res]);
            } else {
                setError(["خطایی در بارگیری اطلاعات شما پیش آمده! لطفا دوباره امتحان کنید."]);
            }
            setLoading(false);
            if (reload)
                setReload(false)
            ticketsContentRef.current.scrollTop = ticketsContentRef.current.scrollHeight;
        });
    }, [reload]);

    return <>
        {(loading) && <div className="section-loading"><div className="loader"></div></div>}

        <div className="title">
            <i></i>
            <h6><i className="icons-question_answer"></i>تیکت‌ها</h6>
            <i className="after-h"></i>
        </div>

        <div className='alerts'>{error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}</div>

        <div className='item-tickets'>
            <div className='tickets-content' ref={ticketsContentRef}>
                {tickets.map((val, i) => <TicketItem key={i} ticket={val} />)}
            </div>
            <div className='tickets-form'>
                <TicketForm reloadTickets={setReload} />
            </div>
        </div>
    </>
};

export default Tickets;