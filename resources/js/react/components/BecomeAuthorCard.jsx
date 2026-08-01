import React, { useState } from 'react';

const BecomeAuthorCard = ({ csrfToken, initialStatus, initialMessage, initialAdminNotes, initialSubmittedDate }) => {
    const [status, setStatus] = useState(initialStatus || 'none');
    const [formData, setFormData] = useState({ message: initialMessage || '' });
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const [successMsg, setSuccessMsg] = useState(null);
    const [adminNotes] = useState(initialAdminNotes);
    const [submittedDate, setSubmittedDate] = useState(initialSubmittedDate);

    const handleChange = (e) => {
        setFormData({ message: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError(null);
        setSuccessMsg(null);

        try {
            const response = await fetch('/dashboard/request-author', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                setStatus('pending');
                setSuccessMsg(data.message);
                setSubmittedDate(new Date().toLocaleString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true }));
            } else {
                if (data.errors && data.errors.message) {
                    setError(data.errors.message[0]);
                } else {
                    setError(data.message || 'Failed to submit request.');
                }
            }
        } catch (err) {
            setError('Network error. Please try again later.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="mb-8">
            <div className="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary shadow-sm overflow-hidden">
                <div className="p-6">
                    <div className="flex items-start gap-4">
                        <div className="p-3 bg-accent/10 dark:!bg-accent/20 rounded-xl">
                            <svg className="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 className="text-xl font-bold text-gray-900 dark:!text-white" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 700 }}>
                                Become an Author
                            </h2>
                            <p className="text-sm text-gray-600 dark:!text-text-secondary mt-1" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 400 }}>
                                Share your knowledge and write articles for our community!
                            </p>
                        </div>
                    </div>

                    {successMsg && (
                        <div className="mt-4 p-4 bg-green-50 dark:!bg-green-900/10 border border-green-200 dark:!border-green-800 rounded-lg">
                            <p className="text-sm text-green-700 dark:!text-green-300 font-semibold">{successMsg}</p>
                        </div>
                    )}

                    {status === 'pending' && (
                        <div className="mt-4 p-4 bg-yellow-50 dark:!bg-yellow-900/10 border border-yellow-200 dark:!border-yellow-800 rounded-lg">
                            <div className="flex items-center gap-2 mb-2">
                                <svg className="w-5 h-5 text-yellow-600 dark:!text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span className="font-semibold text-yellow-800 dark:!text-yellow-400" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 600 }}>
                                    Request Pending
                                </span>
                            </div>
                            <p className="text-sm text-yellow-700 dark:!text-yellow-300" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 400 }}>
                                Your author request is currently under review. We'll notify you once a decision has been made.
                            </p>
                            {formData.message && (
                                <div className="mt-3 p-3 bg-white dark:!bg-bg-card rounded border border-yellow-200 dark:!border-yellow-800">
                                    <p className="text-xs font-semibold text-gray-600 dark:!text-text-secondary mb-1" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 600 }}>Your Message:</p>
                                    <p className="text-sm text-gray-700 dark:!text-text-secondary" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 400 }}>{formData.message}</p>
                                </div>
                            )}
                            {submittedDate && (
                                <p className="text-xs text-yellow-600 dark:!text-yellow-400 mt-2" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 400 }}>
                                    Submitted on {submittedDate}
                                </p>
                            )}
                        </div>
                    )}

                    {status === 'rejected' && (
                        <div className="mt-4 p-4 bg-red-50 dark:!bg-red-900/10 border border-red-200 dark:!border-red-800 rounded-lg">
                            <div className="flex items-center gap-2 mb-2">
                                <svg className="w-5 h-5 text-red-600 dark:!text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span className="font-semibold text-red-800 dark:!text-red-400" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 600 }}>
                                    Request Rejected
                                </span>
                            </div>
                            <p className="text-sm text-red-700 dark:!text-red-300 mb-2" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 400 }}>
                                Your author request was not approved at this time.
                            </p>
                            {adminNotes && (
                                <div className="mt-3 p-3 bg-white dark:!bg-bg-card rounded border border-red-200 dark:!border-red-800">
                                    <p className="text-xs font-semibold text-gray-600 dark:!text-text-secondary mb-1" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 600 }}>Admin Notes:</p>
                                    <p className="text-sm text-gray-700 dark:!text-text-secondary" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 400 }}>{adminNotes}</p>
                                </div>
                            )}
                            <p className="text-xs text-red-600 dark:!text-red-400 mt-2" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 400 }}>
                                You can submit a new request if you'd like to try again.
                            </p>
                            {/* Allow them to submit again if rejected */}
                            <form onSubmit={handleSubmit} className="mt-4">
                                <div className="mb-4">
                                    <label htmlFor="message" className="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 600 }}>
                                        Why do you want to become an author? (Optional)
                                    </label>
                                    <textarea 
                                        id="message" 
                                        name="message" 
                                        rows="4" 
                                        value={formData.message}
                                        onChange={handleChange}
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card dark:!border-border-primary dark:!text-white dark:!placeholder-text-muted" 
                                        placeholder="Tell us about your writing experience, topics you'd like to cover, or any other relevant information..."
                                        style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 400 }}
                                    ></textarea>
                                    {error && (
                                        <p className="mt-1 text-sm text-red-600 dark:!text-red-400" style={{ fontFamily: "'Poppins', sans-serif" }}>{error}</p>
                                    )}
                                </div>
                                <button 
                                    type="submit" 
                                    disabled={loading}
                                    className="px-6 py-3 bg-accent hover:bg-accent-light text-white font-semibold rounded-lg transition-all hover:scale-105 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                    style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 600 }}
                                >
                                    {loading ? 'Submitting...' : 'Submit Author Request'}
                                </button>
                            </form>
                        </div>
                    )}

                    {status === 'none' && (
                        <form onSubmit={handleSubmit} className="mt-4">
                            <div className="mb-4">
                                <label htmlFor="message" className="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 600 }}>
                                    Why do you want to become an author? (Optional)
                                </label>
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    rows="4" 
                                    value={formData.message}
                                    onChange={handleChange}
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card dark:!border-border-primary dark:!text-white dark:!placeholder-text-muted" 
                                    placeholder="Tell us about your writing experience, topics you'd like to cover, or any other relevant information..."
                                    style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 400 }}
                                ></textarea>
                                {error && (
                                    <p className="mt-1 text-sm text-red-600 dark:!text-red-400" style={{ fontFamily: "'Poppins', sans-serif" }}>{error}</p>
                                )}
                            </div>
                            <button 
                                type="submit" 
                                disabled={loading}
                                className="px-6 py-3 bg-accent hover:bg-accent-light text-white font-semibold rounded-lg transition-all hover:scale-105 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                style={{ fontFamily: "'Poppins', sans-serif", fontWeight: 600 }}
                            >
                                {loading ? 'Submitting...' : 'Submit Author Request'}
                            </button>
                        </form>
                    )}
                </div>
            </div>
        </div>
    );
};

export default BecomeAuthorCard;
