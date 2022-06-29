import React, { useState } from 'react';
import { useThemeState } from '../../../context/ThemeContext';

const SidebarSearch = ({ data }) => {
    const [search, setSearch] = useState("");
    const { site_url } = useThemeState();

    const filterSearch = data ? data.filter((val) => {
        if (search.replace(/\s+/g, " ").trim() === "") {
            return false;
        } else if (val.title.toLowerCase().includes(search.toLowerCase())) {
            return val;
        }
        return false;
    }) : [];

    return <div className='sidebar-search'>
        <form role="search" method="get" action={site_url} className="input-group">
            <button className="form-submit" type="submit"><i className="icons-search"></i></button>
            <input className="form-control fc-r" placeholder="جستجو..." type="text" name="s" id="search-form-control" required
                onChange={(e) => setSearch(e.target.value)} />
            <i className="fc-after"></i>
        </form>
        <ul className="search-res">
            {
                filterSearch.map((val, i) => {
                    return <li key={i}>
                        <a href={val.permalink} title={val.title}>{val.title}</a>
                    </li>
                })
            }
        </ul>
    </div>;
}

export default SidebarSearch;