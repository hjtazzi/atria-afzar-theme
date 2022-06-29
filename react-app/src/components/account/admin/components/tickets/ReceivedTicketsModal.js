import React, { useEffect, useRef } from 'react';
import ReceivedTicketForm from './ReceivedTicketForm';
import ReceivedTicketItem from './ReceivedTicketItem';

const ReceivedTicketsModal = ({ data, show, reload }) => {
    const ticketsContentRef = useRef(null);

    const hideModal = () => {
        show({ show: false, data: [] });
        reload(true);
    };

    useEffect(() => {
        ticketsContentRef.current.scrollTop = ticketsContentRef.current.scrollHeight;
    }, []);

    return <div className='received-tickets-modal'>

        <div className="title">
            <i></i>
            <h6><i className="icons-chat_bubble_outline"></i>{data.user.display_name}</h6>
            <i className="after-h"></i>
            <button className='btn-primary' onClick={hideModal}><i className='icons-arrow_back'></i></button>
        </div>

        <div className='item-tickets'>
            <div className='tickets-content' ref={ticketsContentRef}>
                {data.tickets.map((val, i) => <ReceivedTicketItem key={i} ticket={val} />)}
            </div>
            <div className='tickets-form'>
                <ReceivedTicketForm userID={data.user.id} />
            </div>
        </div>
    </div>;
};

export default ReceivedTicketsModal;