import React from 'react';
import 'moment/locale/fa';
import Moment from 'react-moment';

const Comment = ({ itemData, commentsData, options, setCommentBody }) => {
    const authorMeta = itemData.author_meta;
    const depth = itemData.inDepth;
    const commentsParent = (commentsData.length > 0) ? commentsData.filter((val) => {
        if (val.parent === itemData.id)
            return val;
        return false;
    }) : [];

    const commentChildren = commentsParent.map((val, i) => {
        return <Comment key={val.id} commentsData={commentsData} itemData={{ ...val, inDepth: depth - 1 }} options={options} setCommentBody={setCommentBody} />
    });

    const replyOnClick = () => {
        window.scrollTo(0, document.getElementById("post-comments").offsetTop);
        setCommentBody({ ...{ parent: itemData.id } })
    };

    return <div className='comment'>
        <div className='comment-inner'>
            <div className='avatar'>
                <figure>
                    <img src={authorMeta.avatar_url} alt='' />
                </figure>
            </div>
            <div className='content'>
                <div className='name'>
                    <AuthorDisplayName role={authorMeta.role} pageUrl={authorMeta.page_url} name={authorMeta.display_name} author_id={itemData.author_id} user_id={options.user} />
                </div>
                <div className='date'>
                    <Moment locale='fa' fromNow>
                        {itemData.date}
                    </Moment>
                </div>
                <div className='rendered'>
                    <p>{itemData.content}</p>
                </div>
                <div className='reply'>
                    <CommentReply depth={depth} isLogin={options.login} name={authorMeta.display_name} replyOnClick={replyOnClick} />
                </div>
            </div>
        </div>

        {commentChildren}
    </div>
};

const AuthorDisplayName = ({ role, pageUrl, name, author_id, user_id }) => {
    const displayName = (author_id === user_id) ? "شما" : name;
    if (role !== "subscriber") {
        return <a href={pageUrl} title={`ارسال شده توسط ${displayName}`}>
            <span>{displayName} :</span>
        </a>;
    } else {
        return <span title={`ارسال شده توسط ${displayName}`}>{displayName} :</span>;
    }
};

const CommentReply = ({ depth, isLogin, name, replyOnClick }) => {
    if (depth > 0) {
        const titleDT = `ارسال پاسخ به ${name}`;
        if (isLogin === false)
            return <button className='btn-primary' disabled='true'>پاسخ</button>
        return <button className='btn-primary' title={titleDT} onClick={replyOnClick}>پاسخ</button>
    }
    return "";
};

export default Comment;