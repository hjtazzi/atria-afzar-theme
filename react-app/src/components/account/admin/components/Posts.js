import React, { useEffect, useState } from 'react';
import ReactPaginate from 'react-paginate';
import { getAuthorPosts } from '../../../../api/ActionAdmin';
import { useThemeState } from '../../../../context/ThemeContext';
import AlertItem from '../../../alerts/AlertItem';

const Posts = () => {
    const { site_url } = useThemeState();
    const [posts, setPosts] = useState({ have_posts: false, found_posts: 0, posts: [] });
    const [error, setError] = useState([]);
    const [loading, setLoading] = useState(true);

    const PerPage = 10;
    const [currentPage, setCurrentPage] = useState(0);
    const offset = currentPage * PerPage;
    const pageCount = Math.ceil(posts.posts.length / PerPage);
    const currentPageData = posts.posts.slice(offset, offset + PerPage);

    useEffect(() => {
        getAuthorPosts(
            "post",
            (callback, res) => {
                if (callback) {
                    setPosts(res);
                } else {
                    setError(["خطایی در بارگیری اطلاعات شما پیش آمده! لطفا دوباره امتحان کنید."]);
                }
                setLoading(false);
            });
    }, []);

    return <>
        {(loading) && <div className="section-loading"><div className="loader"></div></div>}

        <div className="title">
            <i></i>
            <h6><i className="icons-chat"></i>نوشته‌های من</h6>
            <i className="after-h"></i>
        </div>

        <div className='alerts'>{error.map((val, i) => <AlertItem key={i} txt={val} cls={"danger"} />)}</div>

        <div className='item-posts'>
            <table className='table'>
                <thead>
                    <tr>
                        <th scope='col'>#</th>
                        <th scope='col'>عنوان</th>
                        <th scope='col'>دسته‌ها</th>
                        <th scope='col'>برچسب‌ها</th>
                        <th scope='col'>وضعیت</th>
                        <th scope='col'>بازدید</th>
                    </tr>
                </thead>

                <tbody>
                    {(posts.have_posts) ?
                        currentPageData.map((val, i) => {
                            return <tr key={i}>
                                <th>{i + 1}</th>

                                <td><p>
                                    <a href={val.post_short_link} target="_blank" rel="noreferrer">
                                        {val.post_title}
                                    </a>
                                </p></td>

                                <td className='post-keywords'><p>
                                    {val.post_cats.map((v, j) => {
                                        return <span key={j}>
                                            <a href={`${site_url}/category/${v.cat_slug}`}>
                                                {v.cat_name}
                                            </a>
                                        </span>;
                                    })}
                                </p></td>

                                <td className='post-keywords'><p>
                                    {val.post_keywords.map((v, j) => {
                                        return <span key={j}>
                                            <a href={`${site_url}/tag/${v.tag_slug}`}>
                                                {v.tag_name}
                                            </a>
                                        </span>;
                                    })}
                                </p></td>

                                <td>
                                    <p>{val.post_status}</p>
                                    <p>{val.post_date}</p>
                                </td>

                                <td>{val.post_view}</td>
                            </tr>;
                        }) :
                        <tr>
                            <td colSpan={6} className='posts-not-found'><p>چیزی برای نمایش وجود ندارد!</p></td>
                        </tr>}
                </tbody>
            </table>

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
        </div>
    </>
};

export default Posts;