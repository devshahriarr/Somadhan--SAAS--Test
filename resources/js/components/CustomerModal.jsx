import { Icon } from "@iconify/react";
import { useState } from "react";

const CustomerModal = ({ isOpen, onClose, onSubmit }) => {
    const [formData, setFormData] = useState({
        name: "",
        phone: "",
        email: "",
        address: "",
        openingBalance: "",
        previousDue: "",
    });

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData((prev) => ({ ...prev, [name]: value }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (!formData.name || !formData.phone) {
            alert("Customer Name and Phone Number are required.");
            return;
        }
        onSubmit(formData);
        setFormData({
            name: "",
            phone: "",
            email: "",
            address: "",
            openingBalance: "",
            previousDue: "",
        });
        onClose();
    };

    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div className="bg-surface-light dark:bg-surface-dark rounded-lg shadow-lg p-6 w-full max-w-2xl transition-colors duration-300 relative">
                <button
                    onClick={onClose}
                    className="absolute top-4 right-4 text-text dark:text-text-dark hover:text-primary dark:hover:text-primary-dark focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark focus:ring-opacity-50 rounded-full p-1 transition-colors duration-200"
                    aria-label="Close modal"
                >
                    <Icon icon="mdi:close" className="w-6 h-6" />
                </button>
                <h2 className="text-xl font-semibold text-text dark:text-text-dark mb-6 border-l-4 border-primary pl-3">
                    Add Customer Info
                </h2>
                <form
                    onSubmit={handleSubmit}
                    className="grid sm:grid-cols-2 gap-4"
                >
                    <div>
                        <label className="block text-sm font-medium text-text dark:text-text-dark mb-1.5">
                            Customer Name *
                        </label>
                        <input
                            type="text"
                            name="name"
                            value={formData.name}
                            onChange={handleChange}
                            className="block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-surface-light dark:bg-surface-dark text-sm text-text dark:text-text-dark focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark focus:border-primary dark:focus:border-primary-dark py-2 px-3 transition-colors duration-200"
                            required
                        />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-text dark:text-text-dark mb-1.5">
                            Phone Number *
                        </label>
                        <input
                            type="tel"
                            name="phone"
                            value={formData.phone}
                            onChange={handleChange}
                            className="block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-surface-light dark:bg-surface-dark text-sm text-text dark:text-text-dark focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark focus:border-primary dark:focus:border-primary-dark py-2 px-3 transition-colors duration-200"
                            required
                        />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-text dark:text-text-dark mb-1.5">
                            Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            value={formData.email}
                            onChange={handleChange}
                            className="block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-surface-light dark:bg-surface-dark text-sm text-text dark:text-text-dark focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark focus:border-primary dark:focus:border-primary-dark py-2 px-3 transition-colors duration-200"
                        />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-text dark:text-text-dark mb-1.5">
                            Address
                        </label>
                        <textarea
                            name="address"
                            value={formData.address}
                            onChange={handleChange}
                            className="block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-surface-light dark:bg-surface-dark text-sm text-text dark:text-text-dark focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark focus:border-primary dark:focus:border-primary-dark py-2 px-3 transition-colors duration-200"
                            rows="3"
                        />
                    </div>
                    <div className="sm:col-span-2">
                        <label className="block text-sm font-medium text-text dark:text-text-dark mb-1.5">
                            Opening Balance (আপনার থেকে কাস্টমার পাবেন)
                        </label>
                        <input
                            type="number"
                            name="openingBalance"
                            value={formData.openingBalance}
                            onChange={handleChange}
                            className="block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-surface-light dark:bg-surface-dark text-sm text-text dark:text-text-dark focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark focus:border-primary dark:focus:border-primary-dark py-2 px-3 transition-colors duration-200"
                            min="0"
                            step="0.01"
                        />
                    </div>
                    <div className="sm:col-span-2">
                        <label className="block text-sm font-medium text-text dark:text-text-dark mb-1.5">
                            Previous Due (আপনি কাস্টমার থেকে পাবেন)
                        </label>
                        <input
                            type="number"
                            name="previousDue"
                            value={formData.previousDue}
                            onChange={handleChange}
                            className="block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-surface-light dark:bg-surface-dark text-sm text-text dark:text-text-dark focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark focus:border-primary dark:focus:border-primary-dark py-2 px-3 transition-colors duration-200"
                            min="0"
                            step="0.01"
                        />
                    </div>
                    <div className="sm:col-span-2 flex justify-end space-x-3">
                        <button
                            type="button"
                            onClick={onClose}
                            className="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-text dark:text-text-dark rounded-md hover:bg-gray-400 dark:hover:bg-gray-700 focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:ring-opacity-50 transition-colors duration-200"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            className="px-4 py-2 bg-primary dark:bg-primary-dark text-white rounded-md hover:bg-primary-dark dark:hover:bg-primary focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark focus:ring-opacity-50 transition-colors duration-200"
                        >
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default CustomerModal;
