import React, { useState } from 'react';
import ReactPaginate from 'react-paginate';
import PostCard from '../../posts/PostCard';
import ProductCard from '../../posts/ProductCard';

const SearchItems = ({ data }) => {
    const PerPage = 12;
    const [currentPage, setCurrentPage] = useState(0);
    const offset = currentPage * PerPage;
    const pageCount = Math.ceil(data.length / PerPage);
    const currentPageData = data.slice(offset, offset + PerPage);

    if (data.length <= 0) {
        return <p>چیزی برای نمایش وجود ندارد !</p>
    } else {
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
        </>
    }
};

export default SearchItems;