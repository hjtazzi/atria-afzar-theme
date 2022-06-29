import React, { useEffect } from 'react';
import { putReadedTicket } from '../../../../../api/ActionAdmin';

const ReceivedTicketItem = ({ ticket }) => {
    useEffect(() => {
        if (parseInt(ticket.role) === 0 && parseInt(ticket.status) === 0) {
            putReadedTicket(
                { row_id: parseInt(ticket.id) },
                (callback, res) => { }
            );
        }
    }, []);

    return <div className={(parseInt(ticket.role) === 0) ? "ticket ticket-req" : "ticket ticket-res"}>
        <div className='ticket-content'>
            <div className='ticket-context'>
                <p>{ticket.content}</p>
            </div>

            <div className='ticket-info'>
                {(parseInt(ticket.role) === 1) &&
                    <i className={(parseInt(ticket.status) === 0) ? "icon icons-done" : "icon readed icons-done_all"}></i>}
                <span className='date'>{ticket.date}</span>
            </div>
        </div>
    </div>
};

export default ReceivedTicketItem;