import React, { useEffect, useState } from 'react';
import { useThemeState } from '../../../context/ThemeContext';
import { useForm } from 'react-hook-form';
import AlertItem from '../../alerts/AlertItem';
import { postCreateComment, postNewComment } from '../../../api/ActionComments';

const CommentForm = ({ commentsData, options, commentBody, setCommentBody }) => {
    const commentsSectionLoading = document.getElementById("comments-section-loading");
    const { site_url } = useThemeState();
    const { register, handleSubmit, resetField, formState: { errors } } = useForm();
    const [inpPlaceholder, setInpPlaceholder] = useState("ارسال دیدگاه شما");
    const [cancelRep, setCancelRep] = useState(false);
    const [error, setError] = useState([]);
    const [successCreate, setSuccessCreate] = useState(false);

    const onSubmit = (data) => {
        if (commentsSectionLoading)
            commentsSectionLoading.classList.remove("loading-hidden");

        const newData = {
            author: parseInt(localStorage.getItem("user_id")),
            post: options.post,
            ...commentBody,
            ...data
        };

        postNewComment(
            newData,
            (callback, res) => {
                if (callback) {
                    createComment(res);
                } else {
                    setError([...Error, "خطایی پیش آمده !"])
                }
            }
        );
    };

    const createComment = (data) => {
        postCreateComment(
            data,
            (callback, res) => {
                if (callback) {
                    setSuccessCreate(true);
                } else {
                    setError([...Error, "خطایی پیش آمده !"])
                }
                if (commentsSectionLoading)
                    commentsSectionLoading.classList.add("loading-hidden");
                resetField("content");
            }
        );
    }

    const cancelReply = () => {
        setCommentBody({ author: parseInt(localStorage.getItem("user_id")), parent: 0, content: "", post: options.post });
        setInpPlaceholder("ارسال دیدگاه شما");
        setCancelRep(false);
        resetField("content");
    }

    useEffect(() => {
        resetField("content");

        const replyIn = commentsData.filter((val) => {
            if (val.id === commentBody.parent)
                return val;
            return false;
        });
        if (replyIn.length > 0) {
            setInpPlaceholder(`ارسال پاسخ به ${replyIn[0].author_meta.display_name}`);
            setCancelRep(true);
        }
    }, [commentBody]);

    if (options.login) {
        return <>
            <div className='alerts'>
                {(successCreate) ? <AlertItem txt={"دیدگاه شما با موفقیت ثبت شد و پس از تأیید منتشر میشود."} cls={"success"} /> : ""}

                {
                    error.map((val, i) => {
                        return <AlertItem key={i} txt={val} cls={"danger"} />
                    })
                }

                {errors.content?.type === "required" && <AlertItem txt={"دیدگاه شما نمیتواند خالی باشد"} cls={"warning"} />}
            </div>

            <div className='comment_field'>
                <form onSubmit={handleSubmit(onSubmit)}>
                    <div className="input-group">
                        <textarea
                            className='form-control'
                            placeholder={inpPlaceholder}
                            rows={'5'}
                            {...register("content", { required: true })}
                        />

                        <i className="fc-after"></i>
                    </div>

                    <input type="submit" className="btn-primary" value={"ارسال دیدگاه"} />
                    
                    {(cancelRep) ? <button type='button' onClick={cancelReply} className='btn-danger'>لغو</button> : ""}
                </form>
            </div>
        </>;
    } else {
        return <div className='comment_field'>
            <p className=''>برای ارسال دیدگاه <a href={`${site_url}/account`}>وارد</a> شوید.</p>
        </div>;
    }
};

export default CommentForm;