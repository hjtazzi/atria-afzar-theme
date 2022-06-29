import React, { useEffect, useState } from 'react';
import { getSidebarItems } from '../../api/ActionSidebar';
import SidebarCategory from './components/SidebarCategory';
import SidebarPosts from './components/SidebarPosts';
import SidebarSearch from './components/SidebarSearch';

const Sidebar = () => {
    const [data, setData] = useState({ "search": [], "category": [], "posts": [] });

    useEffect(() => {
        getSidebarItems((callback, data) => {
            (callback) ? setData(data) : setData(false);
            const sidebarSectionLoading = document.getElementById("sidebar-section-loading");
            if (sidebarSectionLoading)
                sidebarSectionLoading.classList.add("loading-hidden");
        });
    }, []);

    if (data) {
        return <>
            <SidebarSearch data={data.search} />
            <SidebarCategory data={data.category} />
            <SidebarPosts data={data.posts} />
        </>
    } else {
        return <p>خطایی پیش آمده !</p>
    }
};

export default Sidebar;