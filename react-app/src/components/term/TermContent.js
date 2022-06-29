import React, { useEffect, useState } from 'react';
import ReactPaginate from 'react-paginate';
import { getTermItems } from '../../api/ActionTerm';
import PostCard from '../posts/PostCard';
import ProductCard from '../posts/ProductCard';

const TermContent = () => {
    const dataQuery = JSON.parse(document.getElementById("term-content_root").getAttribute("data-query"));
    const query = (dataQuery) ? { taxonomy: dataQuery.taxonomy, term_id: dataQuery.term_id } : { taxonomy: null, term_id: null }
    const PerPage = 12;
    const [data, setData] = useState([]);
    const [currentPage, setCurrentPage] = useState(0);
    const offset = currentPage * PerPage;
    const pageCount = Math.ceil(data.length / PerPage);
    const currentPageData = data.slice(offset, offset + PerPage);

    useEffect(() => {
        getTermItems(
            query.taxonomy,
            query.term_id,
            (callback, data) => {
                (callback) ? setData(data) : setData(false);
                const termSectionLoading = document.getElementById("term-section-loading");
                if (termSectionLoading)
                    termSectionLoading.classList.add("loading-hidden");
            }
        );
    }, []);

    useEffect(() => window.scrollTo(0, 0), [currentPage]);

    if (data) {
        return <>
            {
                currentPageData.map((value, i) => {
                    if (value.post_type === "products") {
                        return <div key={i} className='col'>
                            <ProductCard postVal={value} />
                        </div>;
                    } else {
                        return <div key={i} className='col'>
                            <PostCard postVal={value} />
                        </div>;
                    }
                })
            }
            <div className='pagination' >
                <ReactPaginate
                    breakLabel="..."
                    nextLabel={<i className='icons-keyboard_double_arrow_left'></i>}
                    onPageChange={({ selected: selectedPage }) => setCurrentPage(selectedPage)}
                    marginPagesDisplayed={2}
                    pageRangeDisplayed={5}
                    pageCount={pageCount}
                    previousLabel={<i className='icons-keyboard_double_arrow_right'></i>}
                    renderOnZeroPageCount={null}
                    disabledClassName="disabled"
                />
            </div>
        </>;
    } else {
        return <p>خطایی پیش آمده !</p>
    }
};

export default TermContent;