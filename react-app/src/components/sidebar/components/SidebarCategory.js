import React from 'react';
import { useThemeState } from '../../../context/ThemeContext';

const SidebarCategory = ({ data }) => {
    const dataCats = data ? data : [];
    const { site_url } = useThemeState();

    return <div className="sidebar-category">
        <div className="title">
            <i></i>
            <h6>دسته‌بندی‌ها</h6>
            <i className="after-h"></i>
        </div>
        <ul className="category-list">
            {
                dataCats.map((val, i) => {
                    if (val.count > 0) {
                        return <li key={i}>
                            <a href={`${site_url}/category/${val.slug}`}>
                                {val.name}<small>{val.count} مورد</small>
                            </a>
                        </li>
                    }
                    return false;
                })
            }
        </ul>
    </div>;
};

export default SidebarCategory;