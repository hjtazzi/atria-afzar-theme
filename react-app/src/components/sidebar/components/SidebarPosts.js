import React from 'react';

const SidebarPosts = ({ data }) => {
    const dataPosts = data ? data : [];

    return <div className="sidebar-recent-posts">
        <div className="title">
            <i></i>
            <h6>نوشته‌های اخیر</h6>
            <i className="after-h"></i>
        </div>
        <ul className="posts-list">
            {
                dataPosts.map((val, i) => {
                    return <li key={i}>
                        <a href={val.permalink} title={val.title}>{val.title}</a>
                    </li>
                })
            }
        </ul>
    </div>;
};

export default SidebarPosts;