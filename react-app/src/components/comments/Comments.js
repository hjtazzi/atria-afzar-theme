import React, { useEffect, useState } from 'react';
import { getCommentsItems } from '../../api/ActionComments';
import CommentForm from './components/CommentForm';
import CommentBox from './components/CommentBox';

const Comments = () => {
    const data_options = document.getElementById("post-comments_root").getAttribute("data-options");
    const options = (data_options) ? JSON.parse(data_options) : { post: 0, user: 0, login: false, depth: 0 };
    const [data, setData] = useState([]);
    const [newCommentBody, setNewCommentBody] = useState({ author: parseInt(localStorage.getItem("user_id")), parent: 0, content: "", post: options.post });

    useEffect(() => {
        getCommentsItems(
            options.post,
            (callback, data) => {
                (callback) ? setData(data) : setData(false);
                const commentsSectionLoading = document.getElementById("comments-section-loading");
                if (commentsSectionLoading)
                    commentsSectionLoading.classList.add("loading-hidden");
            }
        );
    }, []);

    if (data) {
        return <>
            <CommentForm commentsData={data} options={options} commentBody={newCommentBody} setCommentBody={setNewCommentBody} />
            <CommentBox commentsData={data} options={options} setCommentBody={setNewCommentBody} />
        </>;
    } else {
        return <p>خطایی پیش آمده !</p>;
    }
};

export default Comments;