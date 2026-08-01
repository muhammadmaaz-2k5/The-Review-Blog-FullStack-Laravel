import React, { useState, useEffect } from 'react';

const Comments = ({ articleId, initialComments, initialCaptchaQuestion, allowComments, isLoggedIn, currentUser }) => {
    const [comments, setComments] = useState(initialComments || []);
    const [captchaQuestion, setCaptchaQuestion] = useState(initialCaptchaQuestion || '');
    const [replyingTo, setReplyingTo] = useState(null);
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState(null);
    
    // Main comment form state
    const [mainForm, setMainForm] = useState({
        content: '',
        name: currentUser?.name || '',
        email: currentUser?.email || '',
        captcha_answer: ''
    });

    // Reply form state
    const [replyForm, setReplyForm] = useState({
        content: '',
        name: currentUser?.name || '',
        email: currentUser?.email || '',
        captcha_answer: ''
    });

    const commentTree = comments; // Already nested from backend

    const refreshCaptcha = async () => {
        try {
            const response = await fetch('/comments/captcha', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();
            if (data.success) {
                setCaptchaQuestion(data.question);
            }
        } catch (error) {
            console.error('Error fetching captcha:', error);
        }
    };

    const showMessage = (text, type = 'success') => {
        setMessage({ text, type });
        setTimeout(() => setMessage(null), 5000);
    };

    const submitComment = async (e, parentId = null) => {
        e.preventDefault();
        setLoading(true);
        setMessage(null);

        const formData = parentId ? replyForm : mainForm;
        const endpoint = parentId 
            ? `/articles/${articleId}/comments/${parentId}/reply` 
            : `/articles/${articleId}/comments`;

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                if (data.pending) {
                    showMessage(data.message, 'success');
                } else {
                    showMessage(data.message, 'success');
                    if (parentId) {
                        const newReply = data.reply;
                        const updatedComments = comments.map(comment => {
                            if (comment.id === parentId) {
                                return {
                                    ...comment,
                                    replies: [...(comment.replies || []), newReply]
                                };
                            }
                            return comment;
                        });
                        setComments(updatedComments);
                    } else {
                        const newComment = data.comment;
                        setComments([newComment, ...comments]);
                    }
                }
                
                // Reset form
                if (parentId) {
                    setReplyForm({ ...replyForm, content: '', captcha_answer: '' });
                    setReplyingTo(null);
                } else {
                    setMainForm({ ...mainForm, content: '', captcha_answer: '' });
                }
                
                // Get a new captcha since the session cleared the old one
                refreshCaptcha();
            } else {
                if (data.errors && data.errors.captcha_answer) {
                    showMessage(data.errors.captcha_answer[0], 'error');
                } else {
                    showMessage(data.message || 'Error submitting comment', 'error');
                }
                refreshCaptcha(); // Refresh just in case
            }
        } catch (error) {
            showMessage('Network error occurred. Please try again.', 'error');
            console.error('Error:', error);
        } finally {
            setLoading(false);
        }
    };

    const getAvatarUrl = (comment) => {
        if (comment.avatar) return comment.avatar;
        return null;
    };

    const getInitials = (name) => {
        return name ? name.charAt(0).toUpperCase() : 'A';
    };

    const renderCommentForm = (isReply = false, parentId = null) => {
        if (!allowComments) {
            return <div className="p-4 bg-gray-50 dark:bg-bg-card rounded-lg text-center text-gray-500">Comments are disabled for this article.</div>;
        }

        const formState = isReply ? replyForm : mainForm;
        const setFormState = isReply ? setReplyForm : setMainForm;

        return (
            <form onSubmit={(e) => submitComment(e, parentId)} className={isReply ? "mt-4 ml-8 sm:ml-12 border-l-2 border-accent pl-4" : "mb-8 bg-white dark:bg-bg-card rounded-xl border border-gray-200 dark:border-border-secondary p-4 sm:p-6 shadow-sm"}>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label className="block text-sm font-semibold text-gray-700 dark:text-white mb-2" style={{ fontFamily: "'Poppins', sans-serif" }}>Name <span className="text-red-500">*</span></label>
                        <input type="text" required value={formState.name} onChange={(e) => setFormState({...formState, name: e.target.value})} disabled={isLoggedIn && currentUser?.name}
                               className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:bg-bg-card-hover dark:border-border-primary dark:text-white" />
                    </div>
                    <div>
                        <label className="block text-sm font-semibold text-gray-700 dark:text-white mb-2" style={{ fontFamily: "'Poppins', sans-serif" }}>Email <span className="text-red-500">*</span></label>
                        <input type="email" required value={formState.email} onChange={(e) => setFormState({...formState, email: e.target.value})} disabled={isLoggedIn && currentUser?.email}
                               className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:bg-bg-card-hover dark:border-border-primary dark:text-white" />
                    </div>
                </div>
                
                <div className="mb-4">
                    <label className="block text-sm font-semibold text-gray-700 dark:text-white mb-2" style={{ fontFamily: "'Poppins', sans-serif" }}>Comment <span className="text-red-500">*</span></label>
                    <textarea required rows={isReply ? "3" : "4"} value={formState.content} onChange={(e) => setFormState({...formState, content: e.target.value})}
                              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:bg-bg-card-hover dark:border-border-primary dark:text-white resize-y"></textarea>
                </div>
                
                <div className="mb-4">
                    <label className="block text-sm font-semibold text-gray-700 dark:text-white mb-2" style={{ fontFamily: "'Poppins', sans-serif" }}>CAPTCHA: {captchaQuestion} = ? <span className="text-red-500">*</span></label>
                    <input type="number" required value={formState.captcha_answer} onChange={(e) => setFormState({...formState, captcha_answer: e.target.value})}
                           className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:bg-bg-card-hover dark:border-border-primary dark:text-white" />
                </div>
                
                <div className="flex gap-2">
                    <button type="submit" disabled={loading}
                            className="px-6 py-2 bg-accent hover:bg-red-700 text-white font-bold rounded-lg transition-all shadow-lg shadow-accent/20 disabled:opacity-50">
                        {loading ? 'Posting...' : (isReply ? 'Post Reply' : 'Post Comment')}
                    </button>
                    {isReply && (
                        <button type="button" onClick={() => setReplyingTo(null)} className="px-4 py-2 text-gray-500 hover:text-gray-700 font-semibold transition-colors">Cancel</button>
                    )}
                </div>
            </form>
        );
    };

    const renderCommentNode = (node, isChild = false) => {
        return (
            <div key={node.id} className={`${isChild ? 'mt-3 pl-4 sm:pl-10 border-l-2 border-gray-100 dark:border-border-primary' : 'bg-white dark:bg-bg-card rounded-lg border border-gray-200 dark:border-border-secondary p-3 sm:p-4'}`}>
                <div className="flex items-start gap-2 sm:gap-4">
                    <div className="flex-shrink-0">
                        {getAvatarUrl(node) ? (
                            <img src={getAvatarUrl(node)} alt={node.name} className="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover" />
                        ) : (
                            <div className="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-accent flex items-center justify-center text-white font-semibold text-xs sm:text-sm">
                                {getInitials(node.name)}
                            </div>
                        )}
                    </div>
                    <div className="flex-1">
                        <div className="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-1">
                            <h4 className="font-semibold text-gray-900 dark:text-white text-xs sm:text-sm" style={{ fontFamily: "'Poppins', sans-serif" }}>
                                {node.name}
                            </h4>
                            {node.is_author && (
                                <span className="px-1.5 py-0.5 bg-blue-100 text-blue-800 rounded text-xs dark:bg-blue-900/20 dark:text-blue-400" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: "500" }}>
                                    Author
                                </span>
                            )}
                            <span className="text-xs text-gray-500 dark:text-text-secondary" style={{ fontFamily: "'Poppins', sans-serif" }}>
                                {node.created_at}
                            </span>
                        </div>
                        <p className="text-gray-700 dark:text-text-primary mb-2 whitespace-pre-line text-sm break-words" style={{ fontFamily: "'Poppins', sans-serif", lineHeight: "1.6" }}>
                            {node.content}
                        </p>
                        
                        {allowComments && (
                            <button onClick={() => setReplyingTo(replyingTo === node.id ? null : node.id)} className="text-sm text-accent hover:text-accent-light font-semibold transition-colors" style={{ fontFamily: "'Poppins', sans-serif" }}>
                                Reply
                            </button>
                        )}
                    </div>
                </div>

                {replyingTo === node.id && renderCommentForm(true, node.id)}

                {node.replies && node.replies.length > 0 && (
                    <div className="mt-2">
                        {node.replies.map(reply => renderCommentNode(reply, true))}
                    </div>
                )}
            </div>
        );
    };

    return (
        <div className="mt-12 mb-20" id="comments-section">
            <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2" style={{ fontFamily: "'Outfit', sans-serif" }}>
                <svg className="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                </svg>
                Comments ({comments.length})
            </h2>

            {message && (
                <div className={`mb-6 p-4 rounded-lg font-medium border-l-4 ${message.type === 'error' ? 'bg-red-50 text-red-700 border-red-500' : 'bg-green-50 text-green-700 border-green-500'}`}>
                    {message.text}
                </div>
            )}

            {renderCommentForm(false)}

            <div className="space-y-3">
                {commentTree.length > 0 ? (
                    commentTree.map(node => renderCommentNode(node, false))
                ) : (
                    <div className="text-center py-8 text-gray-500 dark:text-text-secondary bg-white dark:bg-bg-card rounded-lg border border-gray-200 dark:border-border-secondary">
                        No comments yet. Be the first to share your thoughts!
                    </div>
                )}
            </div>
        </div>
    );
};

export default Comments;
