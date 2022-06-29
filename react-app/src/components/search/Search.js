import React, { useEffect, useState } from 'react';
import { getSearchItems } from '../../api/ActionSearch';
import SearchItems from './components/SearchItems';

const Search = () => {
    const searchContentRoot = document.getElementById("search-content_root");
    const dataSearch = searchContentRoot.getAttribute("data-search") ? searchContentRoot.getAttribute("data-search") : "";
    const [data, setData] = useState([]);
    const [search, setSearch] = useState(dataSearch);

    const filterSearch = (data.length > 0) ? data.filter((val) => {
        const valueT = val.title.toLowerCase();
        const valueE = val.excerpt.toLowerCase();
        const valueS = search.toLowerCase();

        if (search.replace(/\s+/g, " ").trim() === "") {
            return false;
        } else if (valueT.includes(valueS) || valueE.includes(valueS)) {
            return val;
        }
        return false;
    }) : [];

    useEffect(() => {
        getSearchItems((callback, data) => {
            (callback) ? setData(data) : setData(false);
            const searchSectionLoading = document.getElementById("search-section-loading");
            if (searchSectionLoading)
                searchSectionLoading.classList.add("loading-hidden");
        });
    }, []);

    if (data) {
        return <>
            <div className="search-inp">
                <div className="input-group">
                    <button disabled className="form-submit"><i className="icons-search"></i></button>
                    <input className="form-control" placeholder="جستجو..." type="text" onChange={(e) => setSearch(e.target.value)} value={search} />
                    <i className="fc-after"></i>
                </div>
            </div>
            <div className="search-res row c-1 c-md-2 c-lg-3 c-xl-4">
                <SearchItems data={filterSearch} />
            </div>
        </>
    } else {
        return <p>خطایی پیش آمده !</p>
    }
};

export default Search;