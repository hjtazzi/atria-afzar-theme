import ReactDOM from 'react-dom';

const Portals = ({ children, container }) => {
    if (container) {
        return ReactDOM.createPortal(children, container);
    } else {
        return "";
    }
};

export default Portals;