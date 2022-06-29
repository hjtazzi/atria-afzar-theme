import React, { useState } from 'react';

const AlertItem = ({ txt, cls }) => {
    const [close, setClose] = useState(false);

    const onClose = () => setClose(true);

    if (close)
        return null;

    if (cls === "danger") {
        return <div className="alert-item">
            <div className="alert-content danger">
                <span><i className="icons-highlight_off"></i></span>
                <p>{txt}</p>
                <div className="icon-close"><button className="btn" onClick={onClose}><i className="icons-close"></i></button></div>
            </div>
        </div>;
    } else if (cls === "warning") {
        return <div className="alert-item">
            <div className="alert-content warning">
                <span><i className="icons-error_outline"></i></span>
                <p>{txt}</p>
                <div className="icon-close"><button className="btn" onClick={onClose}><i className="icons-close"></i></button></div>
            </div>
        </div>
    } else if (cls === "success") {
        return <div className="alert-item">
            <div className="alert-content success">
                <span><i className="icons-task_alt"></i></span>
                <p>{txt}</p>
                <div className="icon-close"><button className="btn" onClick={onClose}><i className="icons-close"></i></button></div>
            </div>
        </div>;
    } else {
        return "";
    }
};

export default AlertItem;