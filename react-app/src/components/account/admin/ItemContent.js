import React from 'react';

import Dashboard from './components/Dashboard';
import EditProfile from './components/EditProfile';
import EditUsers from './components/EditUsers';
import Order from './components/Order';
import Posts from './components/Posts';
import Products from './components/Products';
import ReceivedTickets from './components/ReceivedTickets';
import ShoppingCart from './components/ShoppingCart';
import Store from './components/Store';
import Tickets from './components/Tickets';

const ItemContent = React.memo(
    ({ itemMenu, sideMenuUser }) => {
        switch (itemMenu) {
            case "shopping_cart":
                return <ShoppingCart />
            case "order":
                return <Order />
            case "tickets":
                return <Tickets />
            case "edit_profile":
                return <EditProfile />
            case "posts":
                return <Posts />
            case "products":
                return <Products />
            case "received_tickets":
                return <ReceivedTickets />
            case "create_order":
                return <h1>create_order</h1>
            case "edit_users":
                return <EditUsers />
            case "store":
                return <Store />
            default:
                return <Dashboard sideMenuUser={sideMenuUser} />
        }
    }
);

export default ItemContent;