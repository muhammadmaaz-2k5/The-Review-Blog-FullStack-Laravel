import React, { useState } from 'react';

const SubmitTipForm = ({ initialCaptchaQuestion, csrfToken }) => {
    const [formData, setFormData] = useState({
        subject: '',
        content: '',
        captcha_answer: ''
    });

    const [loading, setLoading] = useState(false);
    const [success, setSuccess] = useState(null);
    const [errors, setErrors] = useState(null);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setSuccess(null);
        setErrors(null);

        try {
            const response = await fetch('/tips', {
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
                setSuccess(data.message);
                setFormData({
                    subject: '',
                    content: '',
                    captcha_answer: ''
                });
            } else {
                if (data.errors) {
                    setErrors(data.errors);
                } else {
                    setErrors({ general: [data.message || 'An error occurred while submitting the form.'] });
                }
            }
        } catch (error) {
            setErrors({ general: ['Network error. Please try again later.'] });
        } finally {
            setLoading(false);
        }
    };

    return (
        <div>
            {success && (
                <div className="mb-6 p-4 bg-green-500/10 border border-green-500/30 text-green-400 rounded-xl flex items-start gap-3">
                    <svg className="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path></svg>
                    <div>
                        <span className="font-bold block mb-1">Success!</span>
                        {success}
                    </div>
                </div>
            )}

            {errors && (
                <div className="mb-6 p-4 bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl">
                    <ul className="list-disc list-inside space-y-1 text-sm font-medium">
                        {Object.values(errors).flat().map((error, index) => (
                            <li key={index}>{error}</li>
                        ))}
                    </ul>
                </div>
            )}

            <form onSubmit={handleSubmit} className="space-y-6">
                <div className="space-y-2">
                    <label htmlFor="subject" className="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">
                        Subject <span className="text-red-500">*</span>
                    </label>
                    <input type="text" id="subject" name="subject" required
                           value={formData.subject}
                           onChange={handleChange}
                           className="w-full px-5 py-4 bg-black/40 border border-white/10 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent text-white font-medium transition-all placeholder-gray-600 hover:bg-black/60"
                           placeholder="E.g. New smartphone leaks..." />
                </div>

                <div className="space-y-2">
                    <label htmlFor="content" className="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">
                        The Story <span className="text-red-500">*</span>
                    </label>
                    <textarea id="content" name="content" rows="6" required
                              value={formData.content}
                              onChange={handleChange}
                              className="w-full px-5 py-4 bg-black/40 border border-white/10 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent text-white font-medium transition-all placeholder-gray-600 hover:bg-black/60 resize-none"
                              placeholder="Tell us everything... (Min 10 chars)"></textarea>
                    <p className="text-[10px] text-gray-500 text-right uppercase tracking-wide font-bold">
                        Links: Imgur / YouTube supported
                    </p>
                </div>

                <div className="space-y-2">
                    <label htmlFor="captcha_answer" className="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">
                        Security Check <span className="text-red-500">*</span>
                    </label>
                    <div className="relative">
                        <div className="absolute left-5 top-1/2 -translate-y-1/2 text-red-500 font-bold font-mono text-lg z-20">
                            {initialCaptchaQuestion}
                        </div>
                        <input type="number" id="captcha_answer" name="captcha_answer" required
                               value={formData.captcha_answer}
                               onChange={handleChange}
                               className="w-full pl-64 pr-5 py-4 bg-black/40 border border-white/10 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent text-white font-medium transition-all placeholder-gray-600 hover:bg-black/60 relative z-10"
                               placeholder="Answer" />
                    </div>
                </div>

                <button type="submit" disabled={loading} className="w-full group relative px-8 py-4 bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-red-500/40 overflow-hidden">
                    <div className="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
                    <span className="relative z-10 flex items-center justify-center gap-2 uppercase tracking-widest text-sm">
                        {loading ? 'Sending...' : 'Send Tip'}
                        {!loading && <svg className="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>}
                    </span>
                </button>

                <p className="text-center text-xs text-gray-600">
                    By submitting, you agree to our Terms of Service. Your anonymity is priority.
                </p>
            </form>
        </div>
    );
};

export default SubmitTipForm;
