import React, { useState } from 'react';
import ReactPaginate from 'react-paginate';
import Comment from './Comment';

const CommentBox = ({ commentsData, options, setCommentBody }) => {
    const depth = parseInt(options.depth);
    const commentsParent = (commentsData.length > 0) ? commentsData.filter((val) => {
        if (val.parent === 0)
            return val;
        return false;
    }) : [];

    const PerPage = 6;
    const [currentPage, setCurrentPage] = useState(0);
    const offset = currentPage * PerPage;
    const pageCount = Math.ceil(commentsParent.length / PerPage);
    const currentPageData = commentsParent.slice(offset, offset + PerPage);

    const pageChange = ({ selected: selectedPage }) => {
        setCurrentPage(selectedPage);
        window.scrollTo(0, document.getElementById("post-comments").offsetTop);
    }

    if (commentsData.length > 0) {
        return <>
            {
                currentPageData.map((val, i) => {
                    return <Comment key={val.id} commentsData={commentsData} itemData={{ ...val, inDepth: depth - 1 }} options={options} setCommentBody={setCommentBody} />
                })
            }
            <div className='pagination' >
                <ReactPaginate
                    breakLabel="..."
                    nextLabel={<i className='icons-keyboard_double_arrow_left'></i>}
                    onPageChange={pageChange}
                    marginPagesDisplayed={2}
                    pageRangeDisplayed={3}
                    pageCount={pageCount}
                    previousLabel={<i className='icons-keyboard_double_arrow_right'></i>}
                    renderOnZeroPageCount={null}
                    disabledClassName="disabled"
                />
            </div>
        </>
    } else {
        return <p className='comments-empty'>
            دیدگاهی وجود ندارد.
        </p>
    }
};

export default CommentBox;